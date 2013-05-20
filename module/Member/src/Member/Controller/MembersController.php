<?php
/**
 * see: http://www.redcedarzen.org/index.php/support/become-a-member-of-red-cedar-zen/
 */

namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Member\Model\Member;
use Member\Model\SanghaRole;
use Member\Form\MemberForm;
use Member\Form\SanghaRolesForm;

/**
 * /members
 * /members/add
 * /members/edit/:id
 * /members/delete/:id
 * /members/roles
 * /members/roles/edit/:id
 * /members/directory
 * /members/directory-view
 * /members/labels
 */
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
        if ( ! isset($_GET['sort_by'])) {
            unset($_SESSION['sort_by']);
        }
        if (isset($_GET['sort_by'])) {
            $letter = $_GET['sort_by'];
            $members = $this->getMemberTable()->getStartsWith($letter);
            $_SESSION['sort_by'] = $letter;

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

                // Redirect to list of members
                return $this->redirect()->toRoute('members');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        // if request from a sorted member search, store it.
        $sort_by = null;
        if (isset($_SESSION['sort_by'])) {
            $sort_by = $_SESSION['sort_by'];
        }

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
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($member->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getMemberTable()->saveMember($form->getData());

                return $this->redirect()->toRoute('members');
            }
        }

        return array(
            'id' => $id,
            'name' => sprintf("%s %s", $member->first_name, $member->last_name),
            'form' => $form,
            'sort_by' => $sort_by,
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

            // Redirect to list of members
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
                // This should return `WHERE list_in_directory = 1`
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
            'type' => $type,
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

    public function rolesAction()
    {
        $members = $this->getMemberTable()->getMemberRoles();
        return array('members' => $members);
    }

    public function rolesEditAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            throw new \Exception("Member ID is nessecary to tie sangha roles to a member!");
        }

        // Get the Member with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $member = $this->getMemberTable()->getMember($id);
        }
        catch (\Exception $ex) {
            // return $this->redirect()->toRoute('members/roles');
            throw new \Exception("No member with an ID of " . $id . " exists.");
        }

        $role = new SanghaRole();
        $role->exchangeArray($member->getArrayCopy());

        $form  = new SanghaRolesForm();
        $form->get('submit')->setAttribute('value', 'Save');
        $form->bind($role);

        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $filter = $form->getInputFilter();
                $member->sangha_jobs = $filter->getRawValue('sangha_jobs');
                $member->volunteer_interests = $filter->getRawValue('volunteer_interests');
                $member->membership_notes = $filter->getRawValue('membership_notes');

                $this->getMemberTable()->saveMember($member);

                return $this->redirect()->toRoute('members/roles');
            } else {
                // Log something
                // return array('dump'=> $form->getMessages(), 'form'=>$form, 'member'=>$member);
            }
        }
        return array('form'=>$form, 'member'=>$member);
    }
}
