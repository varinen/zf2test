<?php
namespace Debug;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;


class Module implements AutoloaderProviderInterface
{
    public function init(ModuleManager $moduleManager)
    {
        $eventManager = $moduleManager->getEventManager();
        $eventManager->attach('loadModules.post', array($this, 'loadedModulesInfo'));
    }

    public function loadedModulesInfo(Event $event)
    {
        $moduleManager = $event->getTarget();
        $loadedModules = $moduleManager->getLoadedModules();
        error_log(var_export($loadedModules, true));
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError'));

        $serviceManager = $e->getApplication()->getServiceManager();
        $timer = $serviceManager->get('timer');
        $timer->start('mvc-execution');

        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'getMvcDuration'), 2);

        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'addDebugOverlay'), 100);
    }

    public function getMvcDuration(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $timer = $serviceManager->get('timer');
        $duration = $timer->stop('mvc-execution');
        error_log("MVC Duration: " . $duration . " seconds");
    }

    public function handleError(MvcEvent $event)
    {
        $controller = $event->getController();
        $error = $event->getParam('error');
        $exception = $event->getParam('exception');
        $message = 'Error:' . $error;
        if ($exception instanceof \Exception) {
            $message  .= ', Exception(' . $exception->getMessage().'): '/
                $exception->getTraceAsString();
            error_log($message);
        }
    }

    public function addDebugOverlay(MvcEvent $event)
    {
        $viewModel = $event->getViewModel();

        $sidebarView = new ViewModel();
        $sidebarView->setTemplate('debug/layout/sidebar');
        $sidebarView->addChild($viewModel, 'content');
        $event->setViewModel($sidebarView);
    }
}