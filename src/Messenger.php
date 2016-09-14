<?php

namespace Messenger;

use Messenger\Model\Core\Config;
use Messenger\Model\Core\Controller\Builder;
use Messenger\Model\Core\Request;

class Messenger
{
    /** @var Request|null */
    private $request = null;

    const DEFAULT_CONTROLLER = "index";
    const DEFAULT_ACTION = "index";

    const NOT_FOUNT_CONTROLLER = "error";
    const NOT_FOUNT_ACTION = "error404";

    const INIT_CONTROLLER = "init";
    const INIT_ACTION = "index";

    const LOGIN_CONTROLLER = "login";
    const LOGIN_ACTION = "index";

    /**
     * @return Request|null
     */
    private function getRequest()
    {
        if (!is_null($this->request)) {
            return $this->request;
        }

        return $this->request = new Request();
    }

    public function process()
    {
        $builder = new Builder();

        $controllerName = $this->getFullControllerName($this->getRequestedController());
        $actionName = $this->getFullActionName($this->getRequestedAction());

        $controllerObject = $builder->getController($controllerName, $this->getRequest());

        if (empty($controllerObject) || !$this->isValidAction($controllerName, $actionName)) {
            $controllerName = $this->getFullControllerName(self::NOT_FOUNT_CONTROLLER);
            $actionName = $this->getFullActionName(self::NOT_FOUNT_ACTION);

            $controllerObject = $builder->getController($controllerName, $this->getRequest());

            $controllerObject->$actionName();
            return;
        }

        $config = new Config();

        if (empty($config->getConfigs()) && $this->getRequestedController() != self::INIT_CONTROLLER) {
            $controllerName = $this->getFullControllerName(self::INIT_CONTROLLER);
            $actionName = $this->getFullActionName(self::INIT_ACTION);

            $controllerObject = $builder->getController($controllerName, $this->getRequest());

            $controllerObject->$actionName();
            return;
        }

        if ($controllerObject->isProtectedArea() && !$controllerObject->getUser()->isLogIn()) {
            $controllerName = $this->getFullControllerName(self::LOGIN_CONTROLLER);
            $actionName = $this->getFullActionName(self::LOGIN_ACTION);

            $controllerObject = $builder->getController($controllerName, $this->getRequest());

            $controllerObject->$actionName();
            return;
        }

        $controllerObject->$actionName();
    }

    // ########################################

    /**
     * @return string
     */
    private function getRequestedController()
    {
        $controller = $this->getRequest()->getAlias(0);

        return empty($controller) ? self::DEFAULT_CONTROLLER : $controller;
    }

    /**
     * @param string $controller
     * @return string
     */
    private function getFullControllerName($controller)
    {
        $controller = ucfirst($controller);
        $namespace = __NAMESPACE__ . "\\Controller\\";

        return "{$namespace}{$controller}Controller";
    }

    // ----------------------------------------

    /**
     * @return string
     */
    private function getRequestedAction()
    {
        $action = $this->getRequest()->getAlias(1);

        return empty($action) ? self::DEFAULT_ACTION : $action;
    }

    /**
     * @param string $action
     * @return string
     */
    private function getFullActionName($action)
    {
        return "{$action}Action";
    }

    // ----------------------------------------

    /**
     * @param string $controllerName
     * @param string $action
     * @return bool
     */
    private function isValidAction($controllerName, $action)
    {
        $reflectionClass = new \ReflectionClass($controllerName);

        return $reflectionClass->hasMethod($action);
    }
}