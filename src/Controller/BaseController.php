<?php

namespace Messenger\Controller;

use Messenger\Model\Core\Config;
use Messenger\Model\Core\Request;
use Messenger\Model\Core\Session;
use Messenger\Model\Core\Url;
use Messenger\Model\Core\User;
use Messenger\Model\Core\View;
use Messenger\Model\Database\Base\Driver;
use Messenger\Model\Database\Database;

abstract class BaseController
{
    /** @var Request|null */
    private $request = null;

    /** @var Session|null */
    private $session = null;

    /** @var View|null */
    private $view = null;

    /** @var Config|null */
    private $config = [];

    /** @var User|null */
    private $user = null;

    /** @var Url|null */
    private $url = null;

    /** @var Driver|null */
    private $driver = null;

    /**
     * @param Request|null $request
     */
    private function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Request|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Session|null $session
     */
    private function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return Session|null
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * BaseController constructor.
     * @param Request $request
     * @param Session $session
     */
    public function __construct(Request $request, Session $session)
    {
        $this->setRequest($request);
        $this->setSession($session);
    }

    // ########################################

    /**
     * @return bool
     */
    public function isProtectedArea()
    {
        return true;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (!empty($this->config)) {
            return $this->config;
        }

        return $this->config = new Config();
    }

    /**
     * @return View|null
     */
    public function getView()
    {
        if (!empty($this->view)) {
            return $this->view;
        }

        return $this->view = new View($this->getSession());
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if (!empty($this->user)) {
            return $this->user;
        }

        return $this->user = new User($this->getSession());
    }

    /**
     * @return Url|null
     */
    public function getUrl()
    {
        if (!empty($this->url)) {
            return $this->url;
        }

        return $this->url = new Url();
    }

    /**
     * @return Driver|null
     */
    public function getDriver()
    {
        if (!empty($this->driver)) {
            return $this->driver;
        }

        $configs = $this->getConfig()->getConfigs();
        $database = new Database();

        return $this->driver = $database->getDriver($configs['database_type']);
    }

    // ########################################

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }

    // ########################################

    protected function fillDefaultParams(array $params)
    {
        $defaultParams = [
            'title' => $this->generateTitle(),
            'css' => [],
            'js' => [],
            'menu' => $this->getView()->render('base/menu'),
            'content' => 'There are no data to display',
        ];

        return array_merge($defaultParams, $params);
    }

    protected function generateTitle($title = '')
    {
        return empty($title) ? "Messenger" : "{$title} - Messenger";
    }
}