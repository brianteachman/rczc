<?php
namespace Member\Model;

/**
 * MailManager\Model\Member entity
 * 
 * We'll create a MemberTable class that uses the 
 * Zend\Db\TableGateway\TableGateway class in which 
 * each entity is an Member object.
 *
 * Don't put database access code into controller action methods.
 */
class Member
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
    public $volunteer_interests;
    public $email_optin;
    public $list_in_directory;
    public $membership_notes;

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

    public function getMemberType()
    {
        switch ($this->membership_type) {
            case '1':
                $type = 'Mailing List Only';
                break;

            case '2':
                $type = 'Friend of Red Cedar';
                break;

            case '3':
                $type = 'Member';
                break;
            
            default:
                $type = 'Dharma Center';
                break;
        }
        return $type;
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
        $this->country  = (isset($data['country'])) ? $data['country'] : 'USA';
        $this->member_notes  = (isset($data['member_notes'])) ? $data['member_notes'] : null;
        // Membership fields
        $this->membership_type = (isset($data['membership_type'])) ? $data['membership_type'] : null;
        $this->list_in_directory = (isset($data['list_in_directory'])) ? $data['list_in_directory'] : 0;
        $this->is_local = (isset($data['is_local'])) ? $data['is_local'] : null;
        $this->volunteer_interests = (isset($data['volunteer_interests'])) ? $data['volunteer_interests'] : null;
        $this->sangha_jobs = (isset($data['sangha_jobs'])) ? $data['sangha_jobs'] : null;
        $this->email_optin = (isset($data['email_optin'])) ? $data['email_optin'] : 0;
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
     * Used to update members role in the sangha
     * 
     * @param  array $data
     */
    public function updateRoles($data)
    {
        $this->volunteer_interests = (isset($data['volunteer_interests'])) ? $data['volunteer_interests'] : null;
        $this->sangha_jobs = (isset($data['sangha_jobs'])) ? $data['sangha_jobs'] : null;
        $this->membership_notes = (isset($data['membership_notes'])) ? $data['membership_notes'] : null;
    }
}
