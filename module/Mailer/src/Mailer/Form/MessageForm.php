<?php
namespace Mailer\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class MessageForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('message');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'message_subject',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'From the Red Cedar Zen Community',
                'style' => "width:100%;"
            ),/*
            'options' => array(
                'label' => 'First Name',
            ),*/
        ));

        $this->add(array(
            'name' => 'message_content',
            'attributes' => array(
                'type'  => 'textarea',
                'style' => "width:100%;"
            ),/*
            'options' => array(
                'label' => 'Member Notes',
            ),*/
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'member_info',
            'options' => array(
                //'label' => 'A checkbox',
                'use_hidden_element' => false, 
                'checked_value' => '1',
                'unchecked_value' => ''
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'tax_receipt',
            'options' => array(
                //'label' => 'A checkbox',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => ''
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'tax_year',
            'options' => array(
                //'label' => 'tax_year',
                'value_options' => array(
                    '2012' => '2012',
                    '2013' => '2013',
                ),
            )
        ));
/*
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'membership_type',
            'options' => array(
                //'label' => 'Membership',
                'value_options' => array(
                    'member' => 'Member',
                    'friend' => 'Friend of Red Cedar',
                    'mailing_list' => 'Mailing List Only',
                    'guest' => 'Dharma Center',
                ),
            )
        ));
*/
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'send_to',
            'options' => array(
                //'label' => 'Send To: ',
                'value_options' => array(
                    'member' => 'Members ',
                    'friends' => 'Friends ',
                    'members_friends' => 'Members & Friends ',
                    'mailing_list' => 'Non-Members (on mailing list only) ',
                    'everyone' => 'Everyone (members, friends & non-members) ',
                ),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'location',
            'options' => array(
                //'label' => 'Local Person?',
                'value_options' => array(
                    'local' => 'Local ',
                    'remote' => 'Remote ',
                    'all' => 'All ',
                ),
            )
        ));
        
        $this->add(new Element\Csrf('security'));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Send',
            ),
        ));
    }
}