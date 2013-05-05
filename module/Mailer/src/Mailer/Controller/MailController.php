<?php

namespace Mailer\Controller;

use TWeb\Controller\AbstractController;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\View\Model\ViewModel;
use Member\Model\Member;
use Mailer\Model\Message;
use Mailer\Form\MessageForm;

/**
 * Routes:
 * 
 * /mail
 * /mail/member/:id
 * /mail/members
 * /mail/review
 * /mail/edit
 * /mail/sent
 */
class MailController extends AbstractController
{
    /**
     * @var Member\Model\MemberTable
     */
    protected $memberTable;

    /**
     * @var Mailer\Model\MessageTable
     */
    protected $mailTable;

    public function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            // this is being configured in Module.php
            $this->memberTable = $sm->get('Member\Model\MemberTable');
        }
        return $this->memberTable;
    }

    public function getMailTable()
    {
        if (!$this->mailTable) {
            $sm = $this->getServiceLocator();
            // this is being configured in Module.php
            $this->mailTable = $sm->get('Mailer\Model\MessageTable');
        }
        return $this->mailTable;
    }

    public function indexAction()
    {
        return array();
    }

    public function memberAction()
    {
        $id = $this->params()->fromRoute('id', null);
        if ($id) {
            try {
                $member = $this->getMemberTable()->getMember($id);
            } catch (\Exception $ex) {
                throw new \InvalidArgumentException('The member your trying to message doesn\'t exists.');
            }
        }

        $form = new MessageForm();
        $form->get('submit')->setValue('Review');

        $message = new Message();
        //$form->setInputFilter($message->getInputFilter());
        $form->bind($message);

        $request = $this->getRequest();
        if ($request->isPost()) {

            //return array('post' => $request->getPost());
            //
            $form->setValidationGroup(
                //'name', 'email', 
                'message_subject', 
                'message_content',
                'member_info',
                'tax_receipt'
            );

            $form->setData($request->getPost());
            if ($form->isValid()) {

                // Process maill message for storage
                $filter = $form->getInputFilter();
                $data = $filter->getRawValues();

                if ($data['message_subject'] == '') {
                    $data['message_subject'] = 'From the Red Cedar Zen Community';
                }
                if ( ! isset($data['send_to'])) {
                    $data['send_to'] = $member->id;
                }
                if ( ! is_numeric($data['send_to']) && !isset($data['location'])) {
                    $data['location'] = 'all';
                }
                foreach ($data as $key => $value) {
                    if ( ! isset($value) || $key == 'security' || $key == 'submit') {
                        unset($data[$key]);
                    }
                }

                //return array('post' => $data);

                $message->exchangeArray($data);
                $message_id = $this->getMailTable()->saveMessage($message);

                return $this->redirect()->toRoute('mail/review', array(
                    'action' => 'review', 'id' => $message_id
                ));
            } else {
                return array('post' => $form->getMessages());
            }
        }
        if (isset($member)) {
            return array('form' => $form, 'member' => $member);
        }
        return array('form' => $form);
    }

    public function reviewAction()
    {
        $id = $this->params()->fromRoute('id', null);
        if ($id) {
            try {
                $message = $this->getMailTable()->getMessage($id);
            } catch (\Exception $ex) {
                return $this->redirect()->toRoute('mail', array(
                    'action' => 'member'
                ));
            }
        }

        if (is_numeric($message->send_to)) {
            $member = $this->getMemberTable()->getMember($message->send_to);
            $to = $member->email;
            $group = false;
        } else {
            $member = null;
            $group = true;
            $to = array('group' => $message->send_to);
        }

        $email = array(
            'to'=> $to,
            'message'=> $message,
            'member'=> $member,
        );

        $post = $this->params()->fromPost();
        if (isset($post['action'])) {

            if ($post['action'] == 'Send') {
                if ($group) {
                    //throw new \Exception('Group Mailing to '.$email['to']['group']);
                    $group_members = $this->getMemberTable()->getGroup($email['to']['group']);

                    //return array('post' => $group_members);
                    
                    $message->sendMessageToGroup($email, $group_members);
                } else {
                    //throw new \Exception('Shooting message to '.$email['to']);
                    $message->sendMessage($email, $member->email, $member->getFullName());
                }

                return $this->redirect()->toRoute('mail/default', array(
                    'action' => 'sent'
                ));
            } else if ($post['action'] == 'Edit') {

                return $this->redirect()->toRoute('mail/edit', array('id' => $id));
            }
                
        }

        $view = new ViewModel($email);
        $view->setTemplate('mailer/mail/email-review');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', null);
        if (!$id) {
            return $this->redirect()->toRoute('mail', array(
                'action' => 'member'
            ));
        }

        // Get the Member with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $message = $this->getMailTable()->getMessage($id);
            if (is_numeric($message->send_to)) {
                $member = $this->getMemberTable()->getMember($message->send_to);
            }
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('mail', array(
                'action' => 'member'
            ));
        }

        $form  = new MessageForm();
        $form->bind($message);
        $form->get('submit')->setAttribute('value', 'Review');

        $request = $this->getRequest();
        if ($request->isPost()) {

            //$form->setInputFilter($message->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $filter = $form->getInputFilter();
                $data = $filter->getRawValues();
                if ( ! isset($data['send_to'])) {
                    $data['send_to'] = $member->id;
                }
                $message->exchangeArray($data);
                $this->getMailTable()->saveMessage($message);

                return $this->redirect()->toRoute('mail/review', array('action'=>'review', 'id'=>$id));
            } else {
                $view = new ViewModel(array('post' => $form->getMessages()));
                $view->setTemplate('mailer/mail/member');
                return $view;
            }
        }

        if (isset($member)) {
            $params = array('form'=>$form, 'member'=>$member);
        } else {
            $params = array('form'=>$form);
        }
        $view = new ViewModel($params);
        $view->setTemplate('mailer/mail/member');
        return $view;
    }

    public function sentAction()
    {
        //
    }

    public function logsAction()
    {
        $messages = array();

        $results = $this->getMailTable()->fetchAll();
        foreach ($results as $row) {
            $message = array(
                'id' => $row->id,
                'send_to' => $row->send_to,
                'message_subject' => $row->message_subject,
                'sent' => $row->sent,
            );
            if (is_numeric($message['send_to'])) {
                $member = $this->getMemberTable()->getMember($message['send_to']);
                $message['send_to'] = $member->getFullName();
            }
            array_push($messages, $message);
        }
        return array('messages' => $messages);
    }
}
