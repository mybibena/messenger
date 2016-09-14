<?php

namespace Messenger\Model\Core;

class Url
{
    /**
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return string
     */
    public function crete($controller = 'index', $action = 'index', $params = [])
    {
        $parsedParams = empty($params) ? '' : '?' . http_build_query($params);

        $url = $this->getProtocol() .
               $this->getDomain() .
               $this->getPortBlock() . '/' .
               (empty($this->getPath()) ? '' : $this->getPath() . '/') .
               $this->getEndpoint() .
               ($controller == 'index' && $action == 'index' ? '' : '/' . $controller) .
               ($action == 'index' ? '' : '/' . $action) .
               $parsedParams;

        return $url;
    }

    /**
     * @param string $cssFile
     * @return string
     */
    public function addCss($cssFile)
    {
        $url = $this->getProtocol() .
               $this->getDomain() .
               $this->getPortBlock() . '/' .
               (empty($this->getPath()) ? '' : $this->getPath() . '/') .
               'css/' .
               $cssFile;

        return $url;
    }

    /**
     * @param string $jsFile
     * @return string
     */
    public function addJs($jsFile)
    {
        $url = $this->getProtocol() .
               $this->getDomain() .
               $this->getPortBlock() . '/' .
               (empty($this->getPath()) ? '' : $this->getPath() . '/') .
               'js/' .
               $jsFile;

        return $url;
    }

    /**
     * @param string $imageFile
     * @return string
     */
    public function addImage($imageFile)
    {
        $url = $this->getProtocol() .
               $this->getDomain() .
               $this->getPortBlock() . '/' .
               (empty($this->getPath()) ? '' : $this->getPath() . '/') .
               'image/' .
               $imageFile;

        return $url;
    }

    // ########################################

    /**
     * @return string
     */
    public function getFullUrl()
    {
        return $this->getProtocol() . $this->getDomain() . $this->getPortBlock() . $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        $temp = explode('?', $this->getFullUrl());

        return array_shift($temp);
    }

    // ----------------------------------------

    /**
     * @return string
     */
    private function getProtocol()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
    }

    /**
     * @return string
     */
    private function getDomain()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * @return string
     */
    private function getPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * @return string
     */
    private function getPath()
    {
        $pathArray = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
        array_pop($pathArray);

        return implode('/', $pathArray);
    }

    /**
     * @return string
     */
    private function getEndpoint()
    {
        $pathArray = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
        return array_pop($pathArray);
    }

    // ----------------------------------------

    /**
     * @return bool
     */
    private function isHttps()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
    }

    /**
     * @return bool
     */
    private function isHttp()
    {
        return !$this->isHttps();
    }

    /**
     * @return bool
     */
    private function isStandardPort()
    {
        return ($this->isHttp() && $this->getPort() == '80') || ($this->isHttps() && $this->getPort() == '443');
    }

    /**
     * @return string
     */
    private function getPortBlock()
    {
        return $this->isStandardPort() ? "" : ":{$this->getPort()}";
    }
}