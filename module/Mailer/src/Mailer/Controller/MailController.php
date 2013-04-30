<?php

namespace Mailer\Controller;

use TWeb\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Member\Model\Member;

class MailController extends AbstractController
{
    protected $member_message = array(
        'csrf' => 'SOME_HASH',
        'message_subject' => 'From the Red Cedar Zen Community',
        'message_content' => '<p>New Message</p>',
        'append_address' => 'on',
        'tax_receipt' => 'on',
        'tax_year' => 2012,
        'action' => 'Review Message'
    );

    protected $group_message = array(
        'csrf' => 'SOME_HASH',
        'send_to' => 'members', /* members, friends, members_and_friends, mailing_list, everyone */ 
        'location' => 'all', /* local, remote, all */
        'message_subject' => 'From the Red Cedar Zen Community',
        'message_content' => '<p>New Message</p>',
        'append_address' => 'on',
        'tax_receipt' => 'on',
        'tax_year' => 2012,
        'action' => 'Review Message'
    );

    protected $memberTable;

    public function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            // this is being configured in Module.php
            $this->memberTable = $sm->get('Member\Model\MemberTable');
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
        }

        if (isset($member)) {
            return array('member'=>$member);
        }
        return array();
    }

    public function reviewAction()
    {
        $session = unserialize($_SESSION['review']);

        if (isset($session['member_id'])) {
            $member = $this->getMemberTable()->getMember($session['member_id']);
            $to = $member->email;
        } else {
            $member = null;
            $to = $session['message']['send_to'];
        }

        $email = array(
            'to'=> $to,
            'message'=> $session['message'],
            'member'=> $member,
            'option'=> $session['option']
        );

        if ($_POST['action'] == 'Send') {
            $this->sendMessageToMember($member, $email['message']);
        }

        return array(
            'to'=> $email['to'],
            'message'=> $email['message'],
            'member'=> $email['member'],
            'option'=> $email['option']
        );
    }

    /**
     * Multi-part sendmail method
     *
     * @param  Member   $member  \Member\Model\Member
     * @param  string[] $message Array containing elements of the message.
     */
    protected function sendMessageToMember(Member $member, $message)
    {
        $header_meta = array(
           'from' => 'mailings@sanghasoftware.com',
           'from_name' => 'Red Cedar Zen Community',
           'reply_to' => 'info@redcedarzen.org',
           'reply_to_name' => 'Red Cedar Zen Community',
           //'to' => $member->email,
           'to' => 'mr.teachman@gmail.com',
           'to_name' => $member->getFullName(),
           'subject' => $message['message_subject'],
        );

        $plain_text_message = strip_tags($message['message_content']);

        try {
            // defined in TWeb\Controller\AbstractController
            $this->sendMultiPartMail(
                $header_meta, 
                $message['message_content'], 
                $plain_text_message
            );
        } catch (Exception $e) {
            //log it or something
        }
    }

    /**
     * Multi-part sendmail method
     * 
     * @param  string[] $info Array containing elements of the message.
     */
    protected function sendMessageToGroup($info)
    {
        $meta = array(
           'from' => 'mailings@sanghasoftware.com',
           'from_name' => 'Red Cedar Zen Community',
           'reply_to' => 'info@redcedarzen.org',
           'reply_to_name' => 'Red Cedar Zen Community',
           'to' => 'tia@sacred-energy.com',
           'to_name' => '',
           'subject' => 'Tarot reading request',
        );

        $text_body;

        $html_body;

        try {
            // defined in TWeb\Controller\AbstractController
            $this->sendMultiPartMail($meta, $html_body, $text_body);
        } catch (Exception $e) {
            //log it or something
        }
    }
}
