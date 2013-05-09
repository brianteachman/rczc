<?php
namespace Mailer\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\View\Model\ViewModel;
use TWeb\Model\Email;

/**
 * Mailer\Model\Message entity
 * 
 * We'll create a MessageTable class that uses the 
 * Zend\Db\TableGateway\TableGateway class in which 
 * each entity is an Message object.
 *
 * Don't put database access code into controller action methods.
 */
class Message extends Email implements InputFilterAwareInterface
{
    // Message fields
    public $id;
    public $from;
    public $send_to;
    public $location;
    public $message_subject;
    public $message_content;
    public $member_info;
    public $tax_receipt;
    public $tax_year;
    public $sent;

    private $inputFilter;

    public function getSnippet($length)
    {
        $clean_content = strip_tags($this->message_content);
        return substr($clean_content, 0, $length);
    }

    /**
     * Zend\Stdlib\Hydrator\ArraySerializable contract method
     * 
     * The exchangeArray() method simply copies the data from the
     * passed in array to our entity’s properties.
     * 
     * @param string $data
     */
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->from = (isset($data['from'])) ? $data['from'] : null;
        $this->send_to  = (isset($data['send_to'])) ? $data['send_to'] : null;
        $this->location  = (isset($data['location'])) ? $data['location'] : null;
        $this->message_subject = (isset($data['message_subject'])) ? $data['message_subject'] : null;
        $this->message_content = (isset($data['message_content'])) ? $data['message_content'] : null;
        //$this->message_content = (isset($data['message_content'])) ? htmlspecialchars($data['message_content']) : null;
        $this->member_info  = (isset($data['member_info'])) ? $data['member_info'] : null;
        $this->tax_receipt  = (isset($data['tax_receipt'])) ? $data['tax_receipt'] : null;
        $this->tax_year  = (isset($data['tax_year'])) ? $data['tax_year'] : null;
        $this->sent  = (isset($data['sent'])) ? $data['sent'] : null;
    }

    /**
     * Zend\Stdlib\Hydrator\ArraySerializable contract method
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Zend\InputFilter\InputFilterAwareInterface contract method
     * 
     * @param InputFilterInterface $inputFilter
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * Zend\InputFilter\InputFilterAwareInterface contract method
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            // Between 1 and 32 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'from',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 70,
                        ),
                    ),
                ),
            )));

            // Between 1 and 32 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'send_to',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 70,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'location',
                'required' => false,
            )));

            // Between 1 and 32 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'message_subject',
                'required' => false,
                //'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 70,
                        ),
                    ),
                ),
            )));

            // Between 1 and 32 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'message_content',
                'required' => true,
                'filters'  => array(
                    //array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ), /*
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 32,
                        ),
                    ),
                ), */
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'member_info',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'tax_receipt',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'tax_year',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'sent',
                'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * Multi-part sendmail method
     *
     * @param  string   $member_email
     * @param  string   $member_name
     * @param  string[] $content      Array containing elements of the message.
     */
    public function sendMessage($content, $member_email, $member_name=null)
    {
        $message = $content['message'];

        $header_meta = array(
           'from' => 'mailings@sanghasoftware.com',
           'from_name' => 'Red Cedar Zen Community',
           'reply_to' => 'info@redcedarzen.org',
           'reply_to_name' => 'Red Cedar Zen Community',
           'to' => $member_email,
           //'to' => 'mr.teachman@gmail.com',
           'to_name' => $member_name,
           'subject' => $message->message_subject,
        );

        $renderer = new PhpRenderer();
        $map = new Resolver\TemplateMapResolver(array(
            'group-email' => __DIR__ . '/../../../view/email/group-email.phtml',
            'text-email'  => __DIR__ . '/../../../view/email/text-email.phtml',
        ));
        $renderer->setResolver($map);
        //$renderer->plugin('basePath')->setBasePath('public');

        $html_view = new ViewModel(array(
            'to'=> $content['to'],
            'message'=> $message,
            'member'=> $content['member'],
        ));
        $html_view->setTemplate('group-email');

        $message->message_content = strip_tags($message->message_content);
        $text_view = new ViewModel(array(
            'to'=> $content['to'],
            'message'=> $message,
            'member'=> $content['member'],
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
     * Set $group_members from MemberTable::getGroupMembers($group_name)
     *
     * @param object[] $group_members Array or ResultSet of Member\Model\Members
     * @param string[] $message       Array containing elements of the message.
     */
    public function sendMessageToGroup($message, $group_members)
    {
        $mail_list = new \ArrayObject;
        foreach ($group_members as $member) {
            if ($member->email) {
                $mail_list->append($member);
                $this->sendMessage($message, $member->email, $member->getFullName());
            }
        }
        return $mail_list;
    }
}
