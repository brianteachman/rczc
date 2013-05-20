<?php
/**
 * see: http://www.redcedarzen.org/index.php/support/become-a-member-of-red-cedar-zen/
 */

namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Member\Model\Member;
use Member\Form\MemberForm;
use Member\Form\RolesForm;

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

    public function testMembersListAction()
    {
        $groups = array(
            'member', 'friends', 
            'members_friends', 
            'mailing_list', 
            'everyone'
        );
        try {
            //$members = $this->getMemberTable()->getGroupOfMembers('everyone');
            $members = $this->getMemberTable()->getGroupOfMembers($groups[1]);
        } catch (Exception $e) {
            throw new Exception('I don\'t know...');
        }

        $result = new \ArrayObject;
        foreach ($members as $member) {
            if ($member->email) {
                $result->append($member);
            }
        }

        //return array('members' => $members, 'group_list' => $groups[1]);
        return array('members' => $result, 'group_list' => $groups[1]);
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
            throw new \Exception("What the fuck Batman!");
        }

        // Get the Member with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $member = $this->getMemberTable()->getMember($id);
        }
        catch (\Exception $ex) {
            // return $this->redirect()->toRoute('members/roles');
            throw new \Exception("No member looking like that!");
        }

        // return array('dump'=> $id, 'member'=> $member);

        $form  = new RolesForm();
        $form->bind($member);
        $form->get('submit')->setAttribute('value', 'Save');

        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $form->setData($request->getPost());

            if ( ! $form->isValid()) {

                $filter = $form->getInputFilter();
                $member->sangha_jobs = $filter->getRawValue('sangha_jobs');
                $member->volunteer_interest = $filter->getRawValue('volunteer_interests');
                $member->membership_notes = $filter->getRawValue('membership_notes');

                // $test = array(
                //     $member->sangha_jobs,
                //     $member->volunteer_interest,
                //     $member->membership_notes
                // );
                // return array('dump'=> $test, 'form'=>$form, 'member'=>$member);

                $this->getMemberTable()->saveMember($member);

                return $this->redirect()->toRoute('members/roles');
            } else {
                //
                return array('dump'=> $form->getMessages(), 'form'=>$form, 'member'=>$member);
            }
        }
        return array('form'=>$form, 'member'=>$member);
    }
}
