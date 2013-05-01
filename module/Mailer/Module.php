<?php
namespace Mailer;

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
                'Mailer\Model\MessageTable' => function($sm) {
                    $tableGateway = $sm->get('MessageTableGateway');
                    $table = new MessageTable($tableGateway);
                    return $table;
                },
                'MessageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Message());
                    return new TableGateway('messages', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
