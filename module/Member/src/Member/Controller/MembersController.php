<?php
/**
 * see: http://www.redcedarzen.org/index.php/support/become-a-member-of-red-cedar-zen/
 */

namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Member\Model\Member;
use Member\Form\MemberForm;

class MembersController extends AbstractActionController
{
    protected $memberTable;

    public function getMemberTable()
    {
        if (!$this->memberTable) {
            $sm = $this->getServiceLocator();
            // this is being configured in Module.php
            $this->memberTable = $sm->get('Member\Model\MemberTable');
        }
        return $this->memberTable;
    }

    public function indexAction()
    {
        if (isset($_GET['picker'])) {
            $letter = $_GET['picker'];
            $members = $this->getMemberTable()->getStartsWith($letter);

        } elseif (isset($_GET['search'])) {
            $q = $_GET['search'];
            $members = $this->getMemberTable()->search($q);

        } else {
            $members = $this->getMemberTable()->fetchAll();
        }

        return new ViewModel(array(
            'members' => $members,
        ));
    }

    public function addAction()
    {
        $form = new MemberForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $member = new Member();
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $member->exchangeArray($form->getData());
                $this->getMemberTable()->saveMember($member);

                // Redirect to list of albums
                return $this->redirect()->toRoute('members');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('members', array(
                'action' => 'add'
            ));
        }

        // Get the Member with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $member = $this->getMemberTable()->getMember($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('members', array(
                'action' => 'index'
            ));
        }

        $form  = new MemberForm();
        $form->bind($member);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getMemberTable()->saveMember($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('members');
            }
        }

        return array(
            'id' => $id,
            'name' => sprintf("%s %s", $member->first_name, $member->last_name),
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('members');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getMemberTable()->deleteMember($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('members');
        }

        return array(
            'id'    => $id,
            'member' => $this->getMemberTable()->getMember($id)
        );
    }

    public function directoryAction()
    {
        //
    }

    public function directoryViewAction()
    {
        if (isset($_POST['list_type'])) {
            $type = $_POST['list_type'];
            if ($type == 'sangha') {
                $members = $this->getMemberTable()->getDirectoryMembers($type);
                $title = "Sangha Directory - " . count($members) . ' members';
            } elseif ($type == 'members') {
                $members = $this->getMemberTable()->getDirectoryMembers($type);
                $title = "Membership List (Board use only) - " . count($members) . ' members';
            } else {
                $members = $this->getMemberTable()->fetchAll();
                $title = "Full Mailing List (Board use only) - " . count($members) . ' members';
            }
        }

        return new ViewModel(array(
            'members' => $members,
            'title' => $title,
        ));
    }

    public function labelsAction()
    {
        if (isset($_POST['prepare'])) {
            $type = $_POST['mailing_type'];
            unset($_POST);
            return new ViewModel(array(
                'address_list' => $type,
            ));
        }
    }
}
