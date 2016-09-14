<?php

namespace Messenger\Controller;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $users = $this->getDriver()->getUsers()->getAll();
        $currentUser = $this->getSession()->getKey('user');

        $unreadMessagesAmount = count($this->getDriver()->getMessages()->getUnreadByReceiver($currentUser['id']));

        $menu = $this->getView()->render('base/menu');
        $content = $this->getView()->render('base/index', [
            'users' => $users,
            'unreadMessagesAmount' => $unreadMessagesAmount,
        ]);

        $this->getView()->display($this->fillDefaultParams([
            'content' => $content,
            'menu' => $menu,
        ]));
    }
}