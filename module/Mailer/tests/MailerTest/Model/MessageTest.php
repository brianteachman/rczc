<?php

namespace MailerTest\Model;

use Mailer\Model\Message;
use PHPUnit_Framework_TestCase;

class MessageTest extends PHPUnit_Framework_TestCase
{
    protected $test_params = array('id' => 2,
                                   'message_subject'     => 'Test Email',
                                   'message_content'  => '<b>Test Email</b><p>Hello.</p>',
                                   'member_info' => '1',
                                   'tax_receipt' => '0',
                                   'tax_year' => '2013',
                                   'send_to' => 'members',
                                   'location' => 'all');

    public function testMessageInitialState()
    {
        $info = new Message();

        $this->assertNull($info->id, '"id" should initially be null');
        $this->assertNull($info->message_subject, '"message_subject" should initially be null');
        $this->assertNull($info->message_content, '"message_content" should initially be null');
        $this->assertNull($info->member_info, '"member_info" should initially be null');
        $this->assertNull($info->tax_receipt, '"tax_receipt" should initially be null');
        $this->assertNull($info->tax_year, '"tax_year" should initially be null');
        $this->assertNull($info->send_to, '"send_to" should initially be null');
        $this->assertNull($info->location, '"location" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $info = new Message();
        $data  = $this->test_params;

        $info->exchangeArray($data);

        $this->assertSame($data['id'], $info->id, '"artist" was not set correctly');
        $this->assertSame($data['message_subject'], $info->message_subject, '"id" was not set correctly');
        $this->assertSame($data['message_content'], $info->message_content, '"title" was not set correctly');
        $this->assertSame($data['member_info'], $info->member_info, '"artist" was not set correctly');
        $this->assertSame($data['tax_receipt'], $info->tax_receipt, '"id" was not set correctly');
        $this->assertSame($data['tax_year'], $info->tax_year, '"title" was not set correctly');
        $this->assertSame($data['send_to'], $info->send_to, '"title" was not set correctly');
        $this->assertSame($data['location'], $info->location, '"title" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $info = new Message();

        $info->exchangeArray($this->test_params);
        $info->exchangeArray(array());

        $this->assertNull($info->id, '"id" should have defaulted to null');
        $this->assertNull($info->message_subject, '"message_subject" should have defaulted to null');
        $this->assertNull($info->message_content, '"message_content" should have defaulted to null');
        $this->assertNull($info->member_info, '"member_info" should have defaulted to null');
        $this->assertNull($info->tax_receipt, '"tax_receipt" should have defaulted to null');
        $this->assertNull($info->tax_year, '"tax_year" should have defaulted to null');
        $this->assertNull($info->send_to, '"send_to" should have defaulted to null');
        $this->assertNull($info->location, '"location" should have defaulted to null');
    }
}