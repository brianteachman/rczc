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
            'name' => 'email_subject',
            'attributes' => array(
                'type'  => 'text',
                //'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'First Name',
            ),*/
        ));

        $this->add(array(
            'name' => 'email_message',
            'attributes' => array(
                'type'  => 'textarea',
                //'style' => "width:600px;"
            ),/*
            'options' => array(
                'label' => 'Member Notes',
            ),*/
        ));

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

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'membership_group',
            'options' => array(
                //'label' => 'Send To: ',
                'value_options' => array(
                    'member' => 'Members',
                    'friend' => 'Friends',
                    'members_friends' => 'Members &amp; Friends',
                    'mailing_list' => 'Non-Members (on mailing list only)',
                    'all' => 'All (everyone: members, friends & non-members) ',
                ),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'location',
            'options' => array(
                //'label' => 'Local Person?',
                'value_options' => array(
                    'local' => 'local',
                    'remote' => 'remote',
                    'all' => 'all',
                ),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'checkbox',
            'options' => array(
                //'label' => 'A checkbox',
                'use_hidden_element' => true,
                'checked_value' => 'good',
                'unchecked_value' => 'bad'
            )
        ));

        
        $this->add(new Element\Csrf('security'));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}