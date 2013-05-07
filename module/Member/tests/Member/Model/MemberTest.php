<?php

namespace MemberTest\Model;

use Member\Model\Member;
use PHPUnit_Framework_TestCase;

class MemberTest extends PHPUnit_Framework_TestCase
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

    public function testMemberInitialState()
    {
        $info = new Member();

        $this->assertNull($info->id, '"id" should initially be null');
        $this->assertNull($info->first_name, '"first_name" should initially be null');
        $this->assertNull($info->last_name, '"last_name" should initially be null');
        $this->assertNull($info->email, '"email" should initially be null');
        $this->assertNull($info->street, '"street" should initially be null');
        $this->assertNull($info->city, '"city" should initially be null');
        $this->assertNull($info->state, '"state" should initially be null');
        $this->assertNull($info->zipcode, '"zipcode" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $info = new Member();
        $data  = $this->test_params;

        $info->exchangeArray($data);

        $this->assertSame($data['id'], $info->id, '"artist" was not set correctly');
        $this->assertSame($data['first_name'], $info->first_name, '"id" was not set correctly');
        $this->assertSame($data['last_name'], $info->last_name, '"title" was not set correctly');
        $this->assertSame($data['email'], $info->email, '"artist" was not set correctly');
        $this->assertSame($data['street'], $info->street, '"id" was not set correctly');
        $this->assertSame($data['city'], $info->city, '"title" was not set correctly');
        $this->assertSame($data['state'], $info->state, '"title" was not set correctly');
        $this->assertSame($data['zipcode'], $info->zipcode, '"title" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $info = new Member();

        $info->exchangeArray($this->test_params);
        $info->exchangeArray(array());

        $this->assertNull($info->id, '"id" should have defaulted to null');
        $this->assertNull($info->first_name, '"first_name" should have defaulted to null');
        $this->assertNull($info->last_name, '"last_name" should have defaulted to null');
        $this->assertNull($info->email, '"email" should have defaulted to null');
        $this->assertNull($info->street, '"street" should have defaulted to null');
        $this->assertNull($info->city, '"city" should have defaulted to null');
        $this->assertNull($info->state, '"state" should have defaulted to null');
        $this->assertNull($info->zipcode, '"zipcode" should have defaulted to null');
    }
}