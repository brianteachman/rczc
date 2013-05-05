<?php
/**
 * http://framework.zend.com/manual/2.0/en/user-guide/unit-testing.html
 * http://framework.zend.com/manual/2.1/en/tutorials/unittesting.html
 * http://www.phpunit.de/manual/3.7/en/writing-tests-for-phpunit.html
 */

namespace MailerTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use MailerTest\Bootstrap;
use Mailer\Controller\MailController;

class MailControllerTest extends AbstractHttpControllerTestCase
{
    protected $member_message = array(
        'csrf' => 'SOME_HASH',
        'message_subject' => 'From the Red Cedar Zen Community',
        'message_content' => '<p>New Message</p>',
        'append_address' => 'on',
        'tax_receipt' => 'on',
        'tax_year' => 2012,
        'action' => 'Review Message',
        // Below are only present on group submission
        'send_to' => 'members', /* members, friends, members_and_friends, mailing_list, everyone */ 
        'location' => 'all', /* local, remote, all */
    );
    
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__.'/../../TestConfig.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('application');
        $this->assertControllerName('application\controller\index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testMemberActionCanBeAccessed()
    {
        $this->dispatch('/mail');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('mailer');
        $this->assertControllerName('mailer\controller\mail');
        $this->assertControllerClass('MailController');
        $this->assertMatchedRouteName('mail');
    }

    public function testMemberTableIsCorrectlyLoaded()
    {
        $controllerManager = $this->getApplicationServiceLocator()->get('ControllerLoader');
        $controller        = $controllerManager->get('mailer\controller\mail');

        $this->assertInstanceOf(
            '\Member\Model\MemberTable',
            //$this->controller->getMemberTable()
            $controller->getMemberTable()
        );
    }

    public function testMailTableIsCorrectlyLoaded()
    {
        $controllerManager = $this->getApplicationServiceLocator()->get('ControllerLoader');
        $controller        = $controllerManager->get('mailer\controller\mail');

        $this->assertInstanceOf(
            '\Mailer\Model\MessageTable',
            $controller->getMailTable()
        );
    }

    public function testMailMemberPostToMailReview()
    {
        $post = array(
            'csrf' => 'SOME_HASH',
            'message_subject' => 'From the Red Cedar Zen Community',
            'message_content' => '<p>New Message</p>',
            'append_address' => 'on',
            'tax_receipt' => 'on',
            'tax_year' => 2012,
            'action' => 'Review Message',
            // Below are only present on group submission
            'send_to' => 'members', /* members, friends, members_and_friends, mailing_list, everyone */ 
            'location' => 'all', /* local, remote, all */
        );

        $mockMailTable = $this->getMockBuilder('Mailer\Model\MessageTable')
                            ->disableOriginalConstructor()
                            ->getMock();

        $mockMailTable->expects($this->once())
                        ->method('saveMessage')
                        ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Mailer\Model\MessageTable', $mockMailTable);

        $this->dispatch('/mail/member', 'POST', $post);
        $this->assertResponseStatusCode(302);

        $this->assertRedirectTo('/mail/review');
        }
}
