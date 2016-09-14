<?php

namespace Messenger\Model\Core;

class Request
{
    /**
     * @return string
     */
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    // ----------------------------------------

    /**
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() == 'get';
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() == 'post';
    }

    // ----------------------------------------

    /**
     * @return bool
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    // ########################################

    /**
     * @return array
     */
    public function getAliases()
    {
        preg_match('#^.*index.php/(\w*)/?(\w*)?/?$#', $_SERVER['PHP_SELF'], $match);
        return array_slice($match, 1);
    }

    /**
     * @param int $key
     * @return string
     */
    public function getAlias($key)
    {
        $aliases = $this->getAliases();
        return isset($aliases[$key]) ? $aliases[$key] : NULL;
    }

    // ########################################

    /**
     * @return array
     */
    public function getGetParams()
    {
        return $_GET;
    }

    /**
     * @param $key
     * @return string|null
     */
    public function getGetParam($key)
    {
        return $this->isGetParam($key) ? $this->getGetParams()[$key] : NULL;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isGetParam($key)
    {
        return array_key_exists($key, $this->getGetParams());
    }

    // ----------------------------------------

    /**
     * @return array
     */
    public function getPostParams()
    {
        return $_POST;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getPostParam($key)
    {
        $params = $this->getPostParams();
        return $this->isPostParam($key) ? $params[$key] : NULL;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isPostParam($key)
    {
        $params = $this->getPostParams();
        return isset($params[$key]);
    }

    // ----------------------------------------

    /**
     * @return array
     */
    public function getParams()
    {
        return array_merge($this->getGetParams(), $this->getPostParams());
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getParam($key)
    {
        $params = $this->getParams();
        return $this->isParam($key) ? $params[$key] : NULL;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isParam($key)
    {
        $params = $this->getParams();
        return isset($params[$key]);
    }
}