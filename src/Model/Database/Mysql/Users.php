<?php

namespace Messenger\Model\Database\Mysql;

use Messenger\Model\Database\Mysql;
use Messenger\Model\Database\Base\Users as BaseUsers;

class Users extends Mysql implements BaseUsers
{
    /**
     * @param string $login
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @param bool $isAdmin
     * @return null|int
     */
    public function create($login, $password, $firstName, $lastName, $isAdmin = false)
    {
        if (empty($login) ||
            empty($password) ||
            empty($firstName) ||
            empty($lastName)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "INSERT `users`
             SET `login` = :login,
                 `password` = :password,
                 `first_name` = :firstName,
                 `last_name` = :lastName,
                 `is_admin` = :isAdmin;"
        );

        $sth->execute([
            ':login' => $login,
            ':password' => $this->cryptPassword($password),
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':isAdmin' => (int)$isAdmin,
        ]);

        return $this->getConnection()->lastInsertId();
    }

    // ----------------------------------------

    /**
     * @param int $id
     * @return array|null
     */
    public function getOneById($id)
    {
        if (empty($id)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "SELECT *
             FROM `users`
             WHERE `id` = :id
             AND `is_deleted` = 0;"
        );

        $sth->execute([
            ':id' => $id,
        ]);

        return $sth->fetch();
    }

    /**
     * @param string $login
     * @return array|null
     */
    public function getOneByLogin($login)
    {
        if (empty($login)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "SELECT *
             FROM `users`
             WHERE `login` = :login
             AND `is_deleted` = 0;"
        );

        $sth->execute([
            ':login' => $login,
        ]);

        return $sth->fetch();
    }

    /**
     * @param string $login
     * @param string $password
     * @return array|null
     */
    public function getOneByLoginAndPassword($login, $password)
    {
        if (empty($login) || empty($password)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "SELECT *
             FROM `users`
             WHERE `login` = :login
             AND `password` = :password
             AND `is_deleted` = 0;"
        );

        $sth->execute([
            ':login' => $login,
            ':password' => $this->cryptPassword($password),
        ]);

        return $sth->fetch();
    }

    /**
     * @return array|null
     */
    public function getAll()
    {
        $sth = $this->getConnection()->prepare(
            "SELECT *
             FROM `users`
             WHERE `is_deleted` = 0;"
        );

        $sth->execute();

        return $sth->fetchAll();
    }

    // ----------------------------------------

    /**
     * @param int $id
     * @param string $login
     * @param string $firstName
     * @param string $lastName
     * @param bool $isAdmin
     * @return int|null
     */
    public function edit($id, $login, $firstName, $lastName, $isAdmin = false)
    {
        if (empty($id) ||
            empty($login) ||
            empty($firstName) ||
            empty($lastName)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "UPDATE `users`
             SET `login` = :login,
                 `first_name` = :firstName,
                 `last_name` = :lastName,
                 `is_admin` = :isAdmin
             WHERE `id` = :id;"
        );

        $sth->execute([
            ':id' => $id,
            ':login' => $login,
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':isAdmin' => (int)$isAdmin,
        ]);

        return $id;
    }

    /**
     * @param int $id
     * @param string $password
     * @return int|null
     */
    public function editPassword($id, $password)
    {
        if (empty($id) ||
            empty($password)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "UPDATE `users`
             SET `password` = :password
             WHERE `id` = :id;"
        );

        $sth->execute([
            ':id' => $id,
            ':password' => $this->cryptPassword($password),
        ]);

        return $id;
    }

    // ----------------------------------------

    /**
     * @param int $id
     * @return int|null
     */
    public function delete($id)
    {
        if (empty($id)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "UPDATE `users`
             SET `is_deleted` = 1
             WHERE `id` = :id;"
        );

        $sth->execute([
            ':id' => $id,
        ]);

        return $id;
    }

    // ########################################

    /**
     * @param string $password
     * @return string
     */
    private function cryptPassword($password)
    {
        return sha1($password);
    }
}