<?php
namespace MailManager\Model;

use Zend\Db\TableGateway\TableGateway;

class MemberTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        // returns Zend\Db\ResultSet\ResultSet
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getMember($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveMember(Member $member)
    {
        $data = array(
            'first_name' => $member->first_name,
            'last_name' => $member->last_name,
            'email' => $member->email,
            'home_phone' => $member->home_phone,
            'work_phone' => $member->work_phone,
            'street' => $member->street,
            'city' => $member->city,
            'state' => $member->state,
            'zipcode' => $member->zipcode,
            'country' => $member->country,
            'member_notes' => $member->member_notes,
            'membership_type' => $member->membership_type,
            'email_optin' => $member->email_optin,
            'list_in_directory' => $member->list_in_directory,
            'is_local' => $member->is_local,
            'volunteer_interests' => $member->volunteer_interests,
            'sangha_jobs' => $member->sangha_jobs,
            'membership_renewal' => $member->membership_renewal,
            'membership_notes' => $member->membership_notes,
        );

        $id = (int)$member->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getMember($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteMember($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}