<?php

namespace Messenger\Model\Database\Mysql;

use Messenger\Model\Database\Base\Driver as BaseDriver;

class Driver implements BaseDriver
{
    /** @var Install|null */
    private $install = null;

    /** @var Users|null */
    private $users = null;

    /** @var Messages|null */
    private $messages = null;

    /**
     * @return Users
     */
    public function getInstall()
    {
        if (!empty($this->install)) {
            return $this->install;
        }

        return $this->install = new Install();
    }

    /**
     * @return Users
     */
    public function getUsers()
    {
        if (!empty($this->users)) {
            return $this->users;
        }

        return $this->users = new Users();
    }

    /**
     * @return Messages
     */
    public function getMessages()
    {
        if (!empty($this->messages)) {
            return $this->messages;
        }

        return $this->messages = new Messages();
    }
}