<?php
namespace Mailer\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Mailer\Model\Message entity
 * 
 * We'll create a MessageTable class that uses the 
 * Zend\Db\TableGateway\TableGateway class in which 
 * each entity is an Message object.
 *
 * Don't put database access code into controller action methods.
 */
class Message implements InputFilterAwareInterface
{
    // Message fields
    public $id;
    public $message_subject;
    public $message_content;
    public $member_info;
    public $tax_receipt;
    public $tax_year;
    public $send_to;
    public $location;

    protected $inputFilter;

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
        $this->message_subject = (isset($data['message_subject'])) ? $data['message_subject'] : null;
        $this->message_content = (isset($data['message_content'])) ? $data['message_content'] : null;
        $this->member_info  = (isset($data['member_info'])) ? $data['member_info'] : null;
        $this->tax_receipt  = (isset($data['tax_receipt'])) ? $data['tax_receipt'] : null;
        $this->tax_year  = (isset($data['tax_year'])) ? $data['tax_year'] : null;
        $this->membership_type  = (isset($data['membership_type'])) ? $data['membership_type'] : null;
        $this->send_to  = (isset($data['send_to'])) ? $data['send_to'] : null;
        $this->location  = (isset($data['location'])) ? $data['location'] : null;
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
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            // Between 1 and 32 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'message_subject',
                'required' => false,
                //'required' => true,
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

            // Between 1 and 32 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'message_content',
                'required' => true,
                'filters'  => array(
                    //array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ), /*
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 32,
                        ),
                    ),
                ), */
            )));

            // Between 1 and 32 characters
            $inputFilter->add($factory->createInput(array(
                'name'     => 'send_to',
                'required' => false,
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

            $inputFilter->add($factory->createInput(array(
                'name'     => 'location',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'member_info',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'tax_receipt',
                'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
