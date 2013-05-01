<?php

namespace Mailer\Controller;

use TWeb\Controller\AbstractController;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\View\Model\ViewModel;
use Member\Model\Member;
use Mailer\Model\Message;
use Mailer\Form\MessageForm;

class MailController extends AbstractController
{
    protected $member_message = array(
        'csrf' => 'SOME_HASH',
        'message_subject' => 'From the Red Cedar Zen Community',
        'message_content' => '<p>New Message</p>',
        'append_address' => 'on',
        'tax_receipt' => 'on',
        'tax_year' => 2012,
        'action' => 'Review Message',
        // Below are only present on group submission
        'send_to' => 'members', /* members, friends, members_and_friends, mailing_list, everyone */ 
        'location' => 'all', /* local, remote, all */
    );

    protected $memberTable;
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
        if (isset($_SESSION['edit'])) {
            $session = unserialize($_SESSION['edit']);
        }

        $form = new MessageForm();
        $form->get('submit')->setValue('Review');

        // If GET param `id`, check if member exist;
        // if member exist, pass the entity into the view
        $id = $this->params()->fromRoute('id', null);
        if ($id) {
            try {
                $member = $this->getMemberTable()->getMember($id);
            } catch (\Exception $ex) {
                throw new \InvalidArgumentException('The member your trying to message doesn\'t exists.');
            }
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            //return array('post' => $request->getPost());

            $message = new Message();
            $form->setInputFilter($message->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                //$message->exchangeArray($form->getData());
                //$this->getMailTable()->saveMessage($message);

                //$data = serialize($form->getData());
                $data = $form->getData();

                $return_vars = array();

                if ($data['message_subject'] == '') {
                    $data['message_subject'] = 'From the Red Cedar Zen Community';
                }
                if (isset($data['member_info'])) {
                    $return_vars['proofing'] = true;
                }
                if (isset($data['tax_receipt'])) {
                    $return_vars['tax_receipt'] = true;
                    $return_vars['tax_year'] = $data['tax_year'];
                }

                $email = array(
                    'message'=>$data, 
                    //'member_id'=>$member->id, 
                    'option'=>$return_vars
                );
                if ($member) {
                    $email['member_id'] = $member->id;
                }
                $_SESSION['review'] = serialize($email);

                return $this->redirect()->toRoute('mail', array(
                    'action' => 'review'
                ));
            }/* else {
                return array('post' => $form->getMessages());
            }*/
        }
        if (isset($member)) {
            return array('form' => $form, 'member' => $member);
        }
        return array('form' => $form);
    }

    public function reviewAction()
    {
        if (isset($_SESSION['review'])) {
            $session = unserialize($_SESSION['review']);
        } else {
            return $this->redirect()->toRoute('mail', array(
                'action' => 'member'
            ));
        }

        if (isset($session['member_id'])) {
            $member = $this->getMemberTable()->getMember($session['member_id']);
            $to = $member->email;
            $group = false;
        } else {
            $member = null;
            $group = true;
            $to = array('group' => $session['message']['send_to']);
        }

        $email = array(
            'to'=> $to,
            'message'=> $session['message'],
            'member'=> $member,
            'option'=> $session['option']
        );

        $post = $this->params()->fromPost();
        if (isset($post['action'])) {

            if ($post['action'] == 'Send') {
                if ($group) {
                    //throw new \Exception('Group Mailing to '.$email['to']['group']);
                    $this->sendMessageToGroup($email['to']['group'], $email);
                } else {
                    //throw new \Exception('Shooting message to '.$email['to']);
                    $this->sendMessageToMember($member, $email);
                }

                return $this->redirect()->toRoute('mail', array(
                    'action' => 'sent'
                ));
            } else if ($post['action'] == 'Edit') {

                $_SESSION['edit'] = serialize($post);
                unset($_SESSION['review']);

                return $this->redirect()->toRoute('mail', array(
                    'action' => 'member'
                ));
            }
                
        }

        $view = new ViewModel(array(
            'to'=> $email['to'],
            'message'=> $email['message'],
            'member'=> $email['member'],
            'option'=> $email['option']
        ));
        $view->setTemplate('mailer/mail/email-review');
        $view->setTerminal(true);
        return $view;
    }

    public function sentAction()
    {
        //
    }

    /**
     * Multi-part sendmail method
     *
     * @param  Member   $member  \Member\Model\Member
     * @param  string[] $message Array containing elements of the message.
     */
    protected function sendMessageToMember(Member $member, $email)
    {
        $header_meta = array(
           'from' => 'mailings@sanghasoftware.com',
           'from_name' => 'Red Cedar Zen Community',
           'reply_to' => 'info@redcedarzen.org',
           'reply_to_name' => 'Red Cedar Zen Community',
           //'to' => $member->email,
           'to' => 'mr.teachman@gmail.com',
           'to_name' => $member->getFullName(),
           'subject' => $email['message']['message_subject'],
        );

        $renderer = new PhpRenderer();
        $map = new Resolver\TemplateMapResolver(array(
            'group-email' => __DIR__ . '/../../../view/email/group-email.phtml',
            'text-email'  => __DIR__ . '/../../../view/email/text-email.phtml',
        ));
        $renderer->setResolver($map);

        $html_view = new ViewModel(array(
            'to'=> $email['to'],
            'message'=> $email['message'],
            'member'=> $email['member'],
            'option'=> $email['option']
        ));
        $html_view->setTemplate('group-email');

        $email['message']['message_content'] = strip_tags($email['message']['message_content']);
        $text_view = new ViewModel(array(
            'to'=> $email['to'],
            'message'=> $email['message'],
            'member'=> $email['member'],
            'option'=> $email['option']
        ));
        $text_view->setTemplate('text-email');

        try {
            // defined in TWeb\Controller\AbstractController
            $this->sendMultiPartMail(
                $header_meta, 
                $renderer->render($html_view), 
                $renderer->render($text_view)
            );
        } catch (Exception $e) {
            //log it or something
        }
    }

    /**
     * Multi-part sendmail method
     * 
     * @param  string[] $info Array containing elements of the message.
     * @return object[]       Returns array of Member\Model\Members
     */
    protected function sendMessageToGroup($group_name, $email)
    {
        $mail_list = new \ArrayObject;
        $groups = array(
            'member', 'friends', 
            'members_friends', 
            'mailing_list', 
            'everyone'
        );

        try {
            $members = $this->getMemberTable()->getGroupOfMembers($group_name);
            foreach ($members as $member) {
                if ($member->email) {
                    $mail_list->append($member);
                    $this->sendMessageToMember($member, $email);
                }
            }
        } catch (Exception $e) {
            //log it or something
        }

        return $mail_list;
    }
}
