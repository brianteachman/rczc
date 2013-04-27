<?php
namespace MailManager;

use MailManager\Model\Member;
use MailManager\Model\MemberTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\ModuleManager;
use Zend\View\Model\ViewModel;

class Module
{
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            // This event will only be fired when an ActionController under the MyModule namespace is dispatched.
            $controller = $e->getTarget();
            $layout = $controller->layout();

            $nav = new ViewModel();
            $nav->setTemplate('layout/nav');
            $layout->addChild($nav, 'nav');

            //$sidebar = new ViewModel();
            //$sidebar->setTemplate('layout/sidebar');
            //$layout->addChild($sidebar, 'sidebar');
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * I'm using the ServiceManager to always use the same instance
     * of MemberTable.
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'MailManager\Model\MemberTable' => function($sm) {
                    $tableGateway = $sm->get('MemberTableGateway');
                    $table = new MemberTable($tableGateway);
                    return $table;
                },
                'MemberTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Member());
                    return new TableGateway('members', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
