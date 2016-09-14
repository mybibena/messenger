<?php

namespace Messenger\Model\Database\Mysql;

use Messenger\Model\Database\Mysql;
use Messenger\Model\Database\Base\Messages as BaseMessages;

class Messages extends Mysql implements BaseMessages
{
    /**
     * @param int $senderId
     * @param int $receiverId
     * @param string $text
     * @return null|string
     */
    public function create($senderId, $receiverId, $text)
    {
        if (empty($senderId) ||
            empty($receiverId) ||
            empty($text)) {
            return null;
        }

        if ($senderId == $receiverId) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "INSERT `messages`
             SET `sender` = :senderId,
                 `receiver` = :receiverId,
                 `text` = :text;"
        );

        $sth->execute([
            ':senderId' => $senderId,
            ':receiverId' => $receiverId,
            ':text' => $text,
        ]);

        return $this->getConnection()->lastInsertId();
    }

    // ----------------------------------------

    /**
     * @param int $receiverId
     * @return array|null
     */
    public function getUnreadByReceiver($receiverId)
    {
        if (empty($receiverId)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "SELECT *
             FROM `messages`
             WHERE `receiver` = :receiverId
             AND `is_read` = 0;"
        );

        $sth->execute([
            ':receiverId' => $receiverId,
        ]);

        return $sth->fetchAll();
    }

    /**
     * @param int $receiverId
     * @return array|null
     */
    public function getUnreadChainsByReceiver($receiverId)
    {
        if (empty($receiverId)) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "SELECT COUNT(`messages`.`sender`) as count,
                    CONCAT_WS(' ', `users`.`first_name`, `users`.`last_name`) as name,
                    `messages`.*
             FROM `messages`
             LEFT JOIN `users` ON (`messages`.`sender` = `users`.`id`)
             WHERE `messages`.`receiver` = :receiverId
             AND `messages`.`is_read` = 0
             GROUP BY `messages`.`sender`"
        );

        $sth->execute([
            ':receiverId' => $receiverId,
        ]);

        return $sth->fetchAll();
    }

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return array|null
     */
    public function setReadBySenderAndReceiver($senderId, $receiverId)
    {
        if (empty($senderId) || empty($receiverId)) {
            return null;
        }

        if ($senderId == $receiverId) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "UPDATE `messages`
             SET `is_read` = 1
             WHERE `sender` = :senderId
             AND `receiver` = :receiverId;"
        );

        $sth->execute([
            ':senderId' => $senderId,
            ':receiverId' => $receiverId,
        ]);
    }

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return array|null
     */
    public function getBySenderAndReceiver($senderId, $receiverId)
    {
        if (empty($senderId) || empty($receiverId)) {
            return null;
        }

        if ($senderId == $receiverId) {
            return null;
        }

        $sth = $this->getConnection()->prepare(
            "SELECT *
             FROM `messages`
             WHERE (`sender` = :senderId OR `sender` = :receiverId)
             AND (`receiver` = :receiverId OR `receiver` = :senderId)
             ORDER BY `create_date` DESC;"
        );

        $sth->execute([
            ':senderId' => $senderId,
            ':receiverId' => $receiverId,
        ]);

        return $sth->fetchAll();
    }
}