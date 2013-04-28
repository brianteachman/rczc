<?php

namespace MemberTest\Model;

use Member\Model\Member;
use PHPUnit_Framework_TestCase;

class MemberTest extends PHPUnit_Framework_TestCase
{
    protected $test_params = array('id' => 2,
                                   'name'     => 'Brian Teachman',
                                   'birthdate'  => '01/01/1900',
                                   'email' => 'you@your.tld',
                                   'question' => 'When to pee?',
                                   'deck' => 'Rider Waite',
                                   'card_spread' => '5 card');

    public function testMemberInitialState()
    {
        $info = new Member();

        $this->assertNull($info->id, '"id" should initially be null');
        $this->assertNull($info->name, '"name" should initially be null');
        $this->assertNull($info->birthdate, '"birthdate" should initially be null');
        $this->assertNull($info->email, '"email" should initially be null');
        $this->assertNull($info->question, '"question" should initially be null');
        $this->assertNull($info->deck, '"deck" should initially be null');
        $this->assertNull($info->card_spread, '"card_spread" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $info = new Member();
        $data  = $this->test_params;

        $info->exchangeArray($data);

        $this->assertSame($data['id'], $info->id, '"artist" was not set correctly');
        $this->assertSame($data['name'], $info->name, '"id" was not set correctly');
        $this->assertSame($data['birthdate'], $info->birthdate, '"title" was not set correctly');
        $this->assertSame($data['email'], $info->email, '"artist" was not set correctly');
        $this->assertSame($data['question'], $info->question, '"id" was not set correctly');
        $this->assertSame($data['deck'], $info->deck, '"title" was not set correctly');
        $this->assertSame($data['card_spread'], $info->card_spread, '"title" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $info = new Member();

        $info->exchangeArray($this->test_params);
        $info->exchangeArray(array());

        $this->assertNull($info->id, '"id" should have defaulted to null');
        $this->assertNull($info->name, '"name" should have defaulted to null');
        $this->assertNull($info->birthdate, '"birthdate" should have defaulted to null');
        $this->assertNull($info->email, '"email" should have defaulted to null');
        $this->assertNull($info->question, '"question" should have defaulted to null');
        $this->assertNull($info->deck, '"deck" should have defaulted to null');
        $this->assertNull($info->card_spread, '"card_spread" should have defaulted to null');
    }
}