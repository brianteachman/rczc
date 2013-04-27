<?php
namespace MailManager\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class MemberForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('member');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'first_name',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'First Name',
            ),*/
        ));

        $this->add(array(
            'name' => 'last_name',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'Last Name',
            ),*/
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'attributes' => array(
                'style' => "width:500px;"
            ),/*
            'options' => array(
                'label' => 'Email',
            ),*/
        ));

        $this->add(array(
            'name' => 'home_phone',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'Home Phone',
            ),*/
        ));

        $this->add(array(
            'name' => 'work_phone',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'Work Phone',
            ),*/
        ));

        $this->add(array(
            'name' => 'street',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:594px;"
            ),/*
            'options' => array(
                'label' => 'Street Address',
            ),*/
        ));

        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'City',
            ),*/
        ));

        $this->add(array(
            'name' => 'state',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:40px;"
            ),/*
            'options' => array(
                'label' => 'State',
            ),*/
        ));

        $this->add(array(
            'name' => 'zipcode',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'Zipcode',
            ),*/
        ));

        $this->add(array(
            'name' => 'country',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:200px;"
            ),/*
            'options' => array(
                'label' => 'Country',
            ),*/
        ));

        $this->add(array(
            'name' => 'member_notes',
            'attributes' => array(
                'type'  => 'textarea',
                'style' => "width:600px;"
            ),/*
            'options' => array(
                'label' => 'Member Notes',
            ),*/
        ));

        /* Membership Form */

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
            'name' => 'list_in_directory',
            'options' => array(
                //'label' => 'Include in Sangha Directory?',
                'value_options' => array(
                    'yes' => 'Yes',
                    'no' => 'No',
                ),
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'is_local',
            'options' => array(
                //'label' => 'Local Person?',
                'value_options' => array(
                    'yes' => 'Yes',
                    'no' => 'No',
                ),
            )
        ));

        $this->add(array(
            'name' => 'volunteer_interest',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:600px;"
            ),/*
            'options' => array(
                'label' => 'Volunteer Interests',
            ),*/
        ));

        $this->add(array(
            'name' => 'current_role',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:600px;"
            ),/*
            'options' => array(
                'label' => 'Current Sangha Job(s)',
            ),*/
        ));

        $this->add(array(
            'name' => 'last_renewal',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:360px;"
            ),/*
            'options' => array(
                'label' => 'Membership last renewed',
            ),*/
        ));

        $this->add(array(
            'name' => 'membership_notes',
            'attributes' => array(
                'type'  => 'textarea',
                'style' => "width:360px;"
            ),/*
            'options' => array(
                'label' => 'Membership Notes',
            ),*/
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