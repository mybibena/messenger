<?php

namespace Messenger\Model\Database\Base;

interface Users
{
    /**
     * @param string $login
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @param bool $isAdmin
     * @return null|int
     */
    public function create($login, $password, $firstName, $lastName, $isAdmin);

    // ----------------------------------------

    /**
     * @param int $id
     * @return array|null
     */
    public function getOneById($id);

    /**
     * @param string $login
     * @return array|null
     */
    public function getOneByLogin($login);

    /**
     * @param string $login
     * @param string $password
     * @return array|null
     */
    public function getOneByLoginAndPassword($login, $password);

    /**
     * @return array|null
     */
    public function getAll();

    // ----------------------------------------

    /**
     * @param int $id
     * @param string $login
     * @param string $firstName
     * @param string $lastName
     * @param bool $isAdmin
     * @return int|null
     */
    public function edit($id, $login, $firstName, $lastName, $isAdmin);

    /**
     * @param int $id
     * @param string $password
     * @return int|null
     */
    public function editPassword($id, $password);

    // ----------------------------------------

    /**
     * @param int $id
     * @return int|null
     */
    public function delete($id);
}