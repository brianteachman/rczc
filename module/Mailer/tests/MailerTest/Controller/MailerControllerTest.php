<?php
/**
 * http://framework.zend.com/manual/2.0/en/user-guide/unit-testing.html
 * http://framework.zend.com/manual/2.1/en/tutorials/unittesting.html
 * http://www.phpunit.de/manual/3.7/en/writing-tests-for-phpunit.html
 */

namespace MailerTest\Controller;

use MailerTest\Bootstrap;
use Mailer\Controller\MailController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use PHPUnit_Framework_TestCase;

class MailControllerTest extends PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new MailController();
        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'index');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMemberActionCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'member');

        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMemberTableIsCorrectlyLoaded()
    {
        $this->assertInstanceOf(
            '\Member\Model\MemberTable',
            $this->controller->getMemberTable()
        );
    }

    public function testMailTableIsCorrectlyLoaded()
    {
        $this->assertInstanceOf(
            '\Mailer\Model\MessageTable',
            $this->controller->getMailTable()
        );
    }

    public function test()
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
    }
}
