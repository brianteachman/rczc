<?php

namespace MailManager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MailManager\Model\Member;

class MailController extends AbstractActionController
{
    protected $test_message = array(
        'csrf' => 'SOME_HASH',
        'send_to' => 'members',
        'location' => 'all',
        'message_subject' => 'From the Red Cedar Zen Community',
        'message_content' => '<p>New Message</p>',
        'append_address' => 'on',
        'tax_receipt' => 'on',
        'tax_year' => 2012,
        'save_message' => 'Prepare the Message'
    );

    protected $memberTable;

    public function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            // this is being configured in Module.php
            $this->memberTable = $sm->get('MailManager\Model\MemberTable');
        }
        return $this->memberTable;
    }

    public function indexAction()
    {
        return array();
    }

    public function newAction()
    {
        $return_vars = array();

        // If GET param `id`, check if member exist;
        // if member exist, pass the entity into the view
        $id = $this->params()->fromRoute('id', null);
        try {
            $member = $this->getMemberTable()->getMember($id);
        }
        catch (\Exception $ex) {
            /*
            return $this->redirect()->toRoute('members', array(
                'action' => 'index'
            ));
            */
        }

        // If the form was submitted
        $post = $this->params()->fromPost();
        if ($post) {
            if ($post['message_subject'] == '') {
                $post['message_subject'] = 'From the Red Cedar Zen Community';
            }
            if (isset($post['member_info'])) {
                $return_vars['proofing'] = true;
            }
            if (isset($post['tax_receipt'])) {
                $return_vars['tax_receipt'] = true;
                $return_vars['tax_year'] = $post['tax_year'];
            }

            $email = array(
                'message'=>$post, 
                'member_id'=>$member->id, 
                'option'=>$return_vars
            );
            $_SESSION['review'] = serialize($email);

            return $this->redirect()->toRoute('mail', array(
                'action' => 'review'
            ));
        }

        if (isset($member)) {
            return array('member'=>$member);
        }
        return array();
    }

    public function reviewAction()
    {
        $session = unserialize($_SESSION['review']);

        return array(
            'message'=> $session['message'],
            'member'=> $this->getMemberTable()->getMember($session['member_id']),
            'option'=> $session['option']
        );
    }
}
