<?php
namespace Member\Model;

/**
 * MailManager\Model\SanghaRole entity
 * 
 * We'll use MemberTable class that uses the 
 * Zend\Db\TableGateway\TableGateway in which 
 * each entity is an Member object.
 *
 */
class SanghaRole
{
    // Member fields
    public $id;
    public $sangha_jobs;
    public $volunteer_interests;
    public $membership_notes;

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
        if ( ! isset($data['id'])) {
            throw new \InvalidArgumentException("Sangha roles must have a valid ID.");
        }
        $this->id = $data['id'];
        $this->sangha_jobs = (isset($data['sangha_jobs'])) ? $data['sangha_jobs'] : null;
        $this->volunteer_interests = (isset($data['volunteer_interests'])) ? $data['volunteer_interests'] : null;
        $this->membership_notes = (isset($data['membership_notes'])) ? $data['membership_notes'] : null;
    }

    /**
     * Zend\Stdlib\Hydrator\ArraySerializable contract method
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
