<?php

namespace Messenger\Model\Core;

class User
{
    const SESSION_KEY = 'user';

    /** @var Session */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    private function getSession()
    {
        return $this->session;
    }

    // ########################################

    /**
     * @return bool
     */
    public function isLogIn()
    {
        return !empty($this->getSession()->getKey(self::SESSION_KEY));
    }
}