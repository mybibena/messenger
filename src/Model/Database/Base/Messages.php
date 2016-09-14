<?php

namespace Messenger\Model\Database\Base;

interface Messages
{
    /**
     * @param int $senderId
     * @param int $receiverId
     * @param string $text
     * @return null|string
     */
    public function create($senderId, $receiverId, $text);

    // ----------------------------------------

    /**
     * @param int $receiverId
     * @return array|null
     */
    public function getUnreadByReceiver($receiverId);

    /**
     * @param int $receiverId
     * @return array|null
     */
    public function getUnreadChainsByReceiver($receiverId);

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return array|null
     */
    public function setReadBySenderAndReceiver($senderId, $receiverId);

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return array|null
     */
    public function getBySenderAndReceiver($senderId, $receiverId);
}