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
    public function __construct()
    {
        $id = new Input('id');
        $id->setRequired(true)
           ->setAllowEmpty(true);
        $this->add($id);

        $fname = new Input('first_name');
        $fname->setRequired(true)
              ->setAllowEmpty(true);
        $this->add($fname);

        $lname = new Input('last_name');
        $lname->setRequired(true)
              ->setAllowEmpty(true);
        $this->add($lname);

        $email = new Input('email');
        $email->setRequired(true)
              ->setAllowEmpty(true);
        $this->add($email);

        $phone_1 = new Input('home_phone');
        $phone_1->setRequired(true)
                ->setAllowEmpty(true);
        $this->add($phone_1);

        $phone_2 = new Input('work_phone');
        $phone_2->setRequired(true)
                ->setAllowEmpty(true);
        $this->add($phone_2);

        $street = new Input('street');
        $street->setRequired(true)
               ->setAllowEmpty(true);
        $this->add($street);

        $city = new Input('city');
        $city->setRequired(true)
             ->setAllowEmpty(true);
        $this->add($city);

        $state = new Input('state');
        $state->setRequired(true)
              ->setAllowEmpty(true);
        $this->add($state);

        $zip = new Input('zipcode');
        $zip->setRequired(true)
            ->setAllowEmpty(true);
        $this->add($zip);

        $country = new Input('country');
        $country->setRequired(true)
                ->setAllowEmpty(true);
        $this->add($country);

        $is_local = new Input('is_local');
        $is_local->setRequired(true)
                 ->setAllowEmpty(true);
        $this->add($is_local);

        $member_notes = new Input('member_notes');
        $member_notes->setRequired(true)
                     ->setAllowEmpty(true);
        $this->add($member_notes);

        $membership_type = new Input('membership_type');
        $membership_type->setRequired(true)
                        ->setAllowEmpty(true);
        $this->add($membership_type);

        $membership_renewal = new Input('membership_renewal');
        $membership_renewal->setRequired(true)
                           ->setAllowEmpty(true);
        $this->add($membership_renewal);

        $jobs = new Input('sangha_jobs');
        $jobs->setRequired(true)
             ->setAllowEmpty(true);
             // ->getValidatorChain()
             // ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($jobs);

        $interest = new Input('volunteer_interest');
        $interest->setRequired(true)
                 ->setAllowEmpty(true);
                 // ->getValidatorChain()
                 // ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($interest);
        
        // $email_optin = new Input('email_optin');
        // $email_optin->setRequired(true)
        //             ->setAllowEmpty(true);
        // $this->add($email_optin);

        $d_list = new Input('list_in_directory');
        $d_list->setRequired(true)
            ->setAllowEmpty(true);
        $this->add($d_list);

        $notes = new Input('membership_notes');
        $notes->setRequired(true)
              ->setAllowEmpty(true);
              // ->getValidatorChain()
              // ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($notes);
    }

    /**
     * Zend\InputFilter\InputFilterAwareInterface contract method
     */
    public function pconstruct()
    {
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
    }
}
