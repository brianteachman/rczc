<?php
namespace Member\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\I18n\Validator\Alnum;

/**
 * Member\Form\RolesFilter
 * 
 */
class SanghaRolesFilter extends InputFilter
{
    public function __construct()
    {
        $id = new Input('id');
        $id->setRequired(true);
        //    ->getValidatorChain()
        //    ->addValidator(new Validator\Db\RecordExists(array(
        //         'table'   => 'members',
        //         'field'   => 'id',
        //         'adapter' => $sm->get('Zend\Db\Adapter\Adapter')
        //     )
        // ));
        $this->add($id);

        $jobs = new Input('sangha_jobs');
        $jobs->setRequired(true)
             ->setAllowEmpty(true);
             // ->getValidatorChain()
             // ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($jobs);

        $interest = new Input('volunteer_interests');
        $interest->setRequired(true)
                 ->setAllowEmpty(true);
                 // ->getValidatorChain()
                 // ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($interest);

        $notes = new Input('membership_notes');
        $notes->setRequired(true)
              ->setAllowEmpty(true);
              // ->getValidatorChain()
              // ->addValidator(new Alnum(array('allowWhiteSpace' => true)));
        $this->add($notes);
    }
}
