<?php

namespace Messenger\Model\Database\Base;

interface Driver
{
    /**
     * @return Install
     */
    public function getInstall();

    /**
     * @return Users
     */
    public function getUsers();

    /**
     * @return Messages
     */
    public function getMessages();
}