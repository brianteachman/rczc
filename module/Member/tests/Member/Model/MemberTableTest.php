<?php

namespace MemberTest\Model;

use Member\Model\MemberTable;
use PHPUnit_Framework_TestCase;

class MemberTableTest extends PHPUnit_Framework_TestCase
{
    protected $test_params = array('id' => 2,
                                   'first_name'     => 'Brian ',
                                   'last_name'  => 'Teachman',
                                   'email' => 'you@your.tld',
                                   'home_phone' => '',
                                   'work_phone' => '',
                                   'street' => 'Some St.',
                                   'city' => 'Bellingham',
                                   'state' => 'WA',
                                   'zipcode' => '98229',
                                   'country' => 'USA',
                                   'is_local' => 1,
                                   'member_notes' => '',
                                   'membership_type' => '',
                                   'membership_renewal' => '',
                                   'sangha_jobs' => '',
                                   'volunteer_interest' => '',
                                   'email_optin' => '',
                                   'list_in_directory' => '',
                                   'membership_notes' => '');

    protected $memberTable;

    public function setUp()
    {
        $this->memberTable = new MemberTable();
    }

    public function testMembersList()
    {
        $groups = array(
            'member', 'friends', 
            'members_friends', 
            'mailing_list', 
            'everyone'
        );
        $members = $this->memberTable->getGroup($groups[1]);

        $friends_id_list = new \ArrayObject;
        foreach ($members as $member) {
            if ($member->email) {
                $friends_id_list->append($member->id);
            }
        }

        $test = sprintf("%s: %s", $groups[1], $friends_id_list);
    }
}