<?php
namespace Member\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\I18n\Validator\Alnum;

/**
 * MailManager\Model\Member entity
 * 
 * We'll create a MemberTable class that uses the 
 * Zend\Db\TableGateway\TableGateway class in which 
 * each entity is an Member object.
 *
 * Don't put database access code into controller action methods.
 */
class MemberFilter extends InputFilter
{
    // Member fields
    // public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $home_phone;
    public $work_phone;
    public $street;
    public $city;
    public $state;
    public $zipcode;
    public $country;
    public $is_local;
    public $member_notes;
    // Membership fields
    public $membership_type;
    public $membership_renewal;
    // public $sangha_jobs;
    // public $volunteer_interest;
    public $email_optin;
    public $list_in_directory;
    // public $membership_notes;

    protected $inputFilter;

    public function __construct()
    {
        $id = new Input('id');
        $id->setRequired(true)
           ->setAllowEmpty(false);
        //    ->getValidatorChain()
        //    ->addValidator(new Validator\Db\RecordExists(array(
        //         'table'   => 'members',
        //         'field'   => 'id',
        //         'adapter' => $sm->get('Zend\Db\Adapter\Adapter')
        //     )
        // ));
        $this->add($id);

        $jobs = new Input('sangha_jobs');
        $jobs->setRequired(true)
             ->setAllowEmpty(true)
             ->getValidatorChain()
             ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($jobs);

        $interest = new Input('volunteer_interest');
        $interest->setRequired(true)
                 ->setAllowEmpty(true)
                 ->getValidatorChain()
                 ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($interest);

        $notes = new Input('membership_notes');
        $notes->setRequired(true)
              ->setAllowEmpty(true)
              ->getValidatorChain()
              ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($notes);
    }

    /**
     * Zend\InputFilter\InputFilterAwareInterface contract method
     */
    public function __construct()
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

            /**
             * Between 1 and 32 characters
             */
            $inputFilter->add($factory->createInput(array(
                'name'     => 'first_name',
                'required' => true,
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
                            'max'      => 32,
                        ),
                    ),
                ),
            )));

            /**
             * Between 1 and 32 characters
             */
            $inputFilter->add($factory->createInput(array(
                'name'     => 'last_name',
                'required' => true,
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
                            'max'      => 32,
                        ),
                    ),
                ),
            )));

            /**
             * Between 1 and 70 characters
             */
            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
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

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
