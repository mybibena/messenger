<?php

namespace Messenger\Model\Core;

class Session
{
    const SESSION_LIFETIME = 60 * 60 * 24 * 7; //1 week

    public function __construct()
    {
        $this->start();
    }

    // ########################################

    public function start()
    {
        if(!$this->isStarted()) {
            session_set_cookie_params(self::SESSION_LIFETIME);
            session_start();
        }
    }

    public function stop()
    {
        if($this->isStarted()) {
            session_write_close();
        }
    }

    // ----------------------------------------

    /**
     * @return bool
     */
    public function isStarted()
    {
        return (session_id() !== '');
    }

    // ########################################

    /**
     * @return array
     */
    public function getKeys()
    {
        return $_SESSION;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getKey($key)
    {
        return $this->isKeyExists($key) ? $this->getKeys()[$key] : NULL;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isKeyExists($key)
    {
        return array_key_exists($key, $this->getKeys());
    }

    // ----------------------------------------

    /**
     * @param $key
     * @param $value
     */
    public function setKey($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // ----------------------------------------

    /**
     * @param $key
     */
    public function removeKey($key)
    {
        unset($_SESSION[$key]);
    }

    public function clear()
    {
        session_destroy();
    }
}