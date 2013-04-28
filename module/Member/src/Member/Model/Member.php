<?php
namespace Member\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * MailManager\Model\Member entity
 * 
 * We'll create a MemberTable class that uses the 
 * Zend\Db\TableGateway\TableGateway class in which 
 * each entity is an Member object.
 *
 * Don't put database access code into controller action methods.
 */
class Member implements InputFilterAwareInterface
{
    // Member fields
    public $id;
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
    public $sangha_jobs;
    public $volunteer_interest;
    public $email_optin;
    public $list_in_directory;
    public $membership_notes;

    protected $inputFilter;

    public function getFullName()
    {
        return sprintf("%s %s", ucfirst($this->first_name), ucfirst($this->last_name));
    }

    public function getAddress($line_breaks=false)
    {
        if ($line_breaks) {
            $format = "%s<br>%s, %s %s";
        } else {
            $format = "%s, %s, %s %s";
        }

        if ( ! ($this->street || $this->city || $this->state || $this->zipcode)) {
            return '';
        }
        return sprintf($format,
            $this->street,
            $this->city,
            $this->state,
            $this->zipcode
        );
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
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->first_name = (isset($data['first_name'])) ? $data['first_name'] : null;
        $this->last_name = (isset($data['last_name'])) ? $data['last_name'] : null;
        $this->email  = (isset($data['email'])) ? $data['email'] : null;
        $this->home_phone  = (isset($data['home_phone'])) ? $data['home_phone'] : null;
        $this->work_phone  = (isset($data['work_phone'])) ? $data['work_phone'] : null;
        $this->street  = (isset($data['street'])) ? $data['street'] : null;
        $this->city  = (isset($data['city'])) ? $data['city'] : null;
        $this->state  = (isset($data['state'])) ? $data['state'] : null;
        $this->zipcode  = (isset($data['zipcode'])) ? $data['zipcode'] : null;
        $this->country  = (isset($data['country'])) ? $data['country'] : null;
        $this->member_notes  = (isset($data['member_notes'])) ? $data['member_notes'] : null;
        // Membership fields
        $this->membership_type = (isset($data['membership_type'])) ? $data['membership_type'] : null;
        $this->list_in_directory = (isset($data['list_in_directory'])) ? $data['list_in_directory'] : null;
        $this->is_local = (isset($data['is_local'])) ? $data['is_local'] : null;
        $this->volunteer_interests = (isset($data['volunteer_interests'])) ? $data['volunteer_interests'] : null;
        $this->sangha_jobs = (isset($data['sangha_jobs'])) ? $data['sangha_jobs'] : null;
        $this->email_optin = (isset($data['email_optin'])) ? $data['email_optin'] : null;
        $this->membership_renewal = (isset($data['membership_renewal'])) ? $data['membership_renewal'] : null;
        $this->membership_notes = (isset($data['membership_notes'])) ? $data['membership_notes'] : null;
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
