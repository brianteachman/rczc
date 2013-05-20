<?php

use Member\Form\RolesFilter;

class RolesFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $inputFilter;

    public function setUp()
    {
        $this->inputFilter = new RolesFilter();
    }

    /** @dataProvider validatedDataProvider */
    public function testValidation($data, $valid)
    {
        $this->inputFilter->setData($data);
        $this->assertSame($valid, $this->inputFilter->isValid());
    }

    public function validatedDataProvider()
    {
        return array(
            array(
                array(),
                false
            ),
            array(
                array('id' => '', 'sangha_jobs' => 'Chidan'),
                false
            ),
            array(
                array('id' => '949', 'sangha_jobs' => 'Chidan'),
                true
            ),
            array(
                array('id' => '949', 'sangha_jobs' => ''),
                true
            ),
        );
    }
}