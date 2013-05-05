<?php
/**
 * http://stackoverflow.com/questions/9018309/zend-framework-2-how-do-i-access-modules-config-value-from-a-controller
 * http://stackoverflow.com/questions/8957274/access-to-module-config-in-zend-framework-2
 */

namespace TWeb\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AbstractController extends AbstractActionController
{
    /**
     * Add sidebar view to the layout
     * 
     * @param string $template_path Path to viewscript
     */
    protected function setLayoutSidebar($template_path)
    {
        // Create sidebar view
        $sidebar = new ViewModel();
        $sidebar->setTemplate($template_path);
        // Add sidebar view to Layout
        $layout = $this->layout();
        $layout->addChild($sidebar, 'sidebar');
    }

    /**
     * @param  array   $params       Vars to pass into the view script
     * @param  string  $template     The template to use
     * @param  boolean $set_terminal False uses Layout, true does not
     * @return Zend\View
     */
    public function makeView($params, $template, $set_terminal=false)
    {
        $view = new ViewModel($params);
        $view->setTemplate($template);
        if ($set_terminal) {
            $view->setTerminal(true);
        }
        return $view;
    }
}
