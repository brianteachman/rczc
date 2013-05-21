<?php
namespace Member\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Member\Form\SanghaRolesFilter;

class SanghaRolesForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('member');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new SanghaRolesFilter());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'sangha_jobs',
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:600px;"
            ),/*
            'options' => array(
                'label' => 'Current Sangha Job(s)',
            ),*/
        ));

        $this->add(array(
            'name' => 'volunteer_interests', 
            'attributes' => array(
                'type'  => 'text',
                'style' => "width:600px;"
            ),/*
            'options' => array(
                'label' => 'Volunteer Interests',
            ),*/
        ));

        $this->add(array(
            'name' => 'membership_notes',
            'attributes' => array(
                'type'  => 'textarea',
                'style' => "width:600px;"
            ),/*
            'options' => array(
                'label' => 'Member Notes',
            ),*/
        ));
  
        $this->add(new Element\Csrf('security'));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn'
            ),
        ));
    }
}