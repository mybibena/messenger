<?php

namespace Messenger\Controller;

class MessageController extends BaseController
{
    public function indexAction()
    {
        $currentUser = $this->getSession()->getKey('user');
        $receiverId = $this->getRequest()->getGetParam('receiver');
        $senderId = $this->getRequest()->getGetParam('sender');

        if (empty($currentUser) ||
            empty($receiverId) ||
            empty($senderId) ||
            $senderId == $receiverId ||
            !in_array($currentUser['id'], [$senderId, $receiverId])) {
            $this->redirect($this->getUrl()->crete());
        }

        $sender = $this->getDriver()->getUsers()->getOneById($senderId);
        $receiver = $this->getDriver()->getUsers()->getOneById($receiverId);

        if (empty($sender) || empty($receiver)) {
            $this->redirect($this->getUrl()->crete());
        }

        $messages = $this->getDriver()->getMessages()->getBySenderAndReceiver($senderId, $receiverId);
        $this->getDriver()->getMessages()->setReadBySenderAndReceiver($receiverId, $senderId);

        $content = $this->getView()->render('message/list', [
            'sender' => $sender,
            'receiver' => $receiver,
            'messages' => $messages,
        ]);

        $this->getView()->display($this->fillDefaultParams([
            'content' => $content,
            'menu' => $this->getView()->render('base/menu'),
        ]));
    }

    public function createMessageAction()
    {
        $currentUser = $this->getSession()->getKey('user');
        $senderId = $this->getRequest()->getPostParam('sender_id');
        $receiverId = $this->getRequest()->getPostParam('receiver_id');
        $text = $this->getRequest()->getPostParam('text');

        if (empty($senderId) || empty($receiverId) || empty($text)) {
            $this->redirect($this->getUrl()->crete());
        }

        if ($senderId != $currentUser['id']) {
            if ($receiverId == $currentUser['id']) {
                $receiverId = $senderId;
                $senderId = $currentUser['id'];
            } else {
                $this->redirect($this->getUrl()->crete());
            }
        }

        $this->getDriver()->getMessages()->create($senderId, $receiverId, $text);

        $this->redirect($this->getUrl()->crete(
            'message',
            'index',
            [
                'sender' => $senderId,
                'receiver' => $receiverId
            ]
        ));
    }

    public function showUnreadAction()
    {
        $currentUser = $this->getSession()->getKey('user');
        $unreadMessages = $this->getDriver()->getMessages()->getUnreadChainsByReceiver($currentUser['id']);

        $content = $this->getView()->render('message/unread', [
            'messages' => $unreadMessages,
        ]);

        $this->getView()->display($this->fillDefaultParams([
            'content' => $content,
            'menu' => $this->getView()->render('base/menu'),
        ]));
    }

    /**
     * @return string
     */
    public function getUnreadMessagesAmountAction()
    {
        $currentUser = $this->getSession()->getKey('user');
        $unreadMessages = $this->getDriver()->getMessages()->getUnreadByReceiver($currentUser['id']);

        echo json_encode([
            'unread' => count($unreadMessages)
        ]);
    }
}