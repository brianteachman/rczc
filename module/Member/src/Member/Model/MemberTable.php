<?php
namespace Member\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

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

    /**
     * @todo Update table so email without value is NULL
     * 
     * @param  string $group_name Membership group
     * @return Zend\Db\ResultSet
     */
    public function getGroupOfMembers($group_name)
    {
        $groups = array(
            'member', 'friends', 
            'members_friends', 
            'mailing_list', 
            'everyone'
        );

        if (in_array($group_name, $groups)) {
            switch ($group_name) {
                case 'member':
                    return $this->tableGateway->select(
                        array(
                            'membership_type' => 3,
                            new \Zend\Db\Sql\Predicate\IsNotNull('email')
                        )
                    );
                    break;
                
                case 'friends':
                    return $this->tableGateway->select(
                        array(
                            'membership_type' => 2,
                            new \Zend\Db\Sql\Predicate\IsNotNull('email')
                        )
                    );
                    break;
                
                case 'members_friends':
                    $sel = '`membership_type` = 2 OR `membership_type` = 3';
                    return $this->tableGateway->select($sel);
                    break;
                
                case 'mailing_list':
                    return $this->tableGateway->select(
                        array(
                            'membership_type' => 1,
                            new \Zend\Db\Sql\Predicate\IsNotNull('email')
                        )
                    );
                    break;

                case 'everyone':
                //default: /* everyone */
                    $sel = array(new \Zend\Db\Sql\Predicate\IsNotNull('email'));
                    return $this->tableGateway->select($sel);
                    break;
            }
        } else {
            throw new \Exception("Query is not in the groups array.");
        }
    }

    /**
     * Return ResultSet of all of opted-in or members.
     * 
     * @param  string $type Either 'sangha' or 'members'
     * @return Zend\Db\ResultSet   
     */
    public function getDirectoryMembers($type)
    {
        // "SELECT * FROM `members` WHERE {$sel};"
        if ($type == 'sangha') {
            $sel = array('list_in_directory' => 1);
        } elseif ($type == 'members') {
            $sel = array('membership_type' => 3);
        } else {
            //
        }
        $rowset = $this->tableGateway->select(function (Select $select) use ($sel) {
             $select->where($sel);
             $select->order('last_name ASC');
        });
        if (!$rowset) {
            throw new \Exception("Could not run the query {$q}.");
        }
        return $rowset;
    }

    public function getStartsWith($letter)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($letter) {
             $select->where->like('last_name', $letter.'%');
             $select->order('last_name ASC');
        });
        if (!$rowset) {
            throw new \Exception("Could not find members that start with {$letter}.");
        }
        return $rowset;
    }

    /**
     * http://framework.zend.com/manual/2.1/en/modules/zend.db.sql.html
     * @param  [type] $q Term to query for
     * @return [type]    [description]
     */
    public function search($q)
    {
        /* SELECT * FROM `members` WHERE `first_name` LIKE '%ad%' OR `last_name` LIKE '%ad%' */
        $rowset = $this->tableGateway->select(function (Select $select) use ($q) {
             //$select->where->like('last_name', '%'.$q.'%');
             $select->where("`first_name` LIKE '%".$q."%' OR `last_name` LIKE '%".$q."%'");
             $select->order('last_name ASC');
        });
        if (!$rowset) {
            throw new \Exception("Could not find members that start with {$letter}.");
        }
        return $rowset;
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