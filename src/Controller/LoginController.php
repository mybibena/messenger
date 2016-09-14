<?php

namespace Messenger\Controller;

class LoginController extends BaseController
{
    public function indexAction()
    {
        $this->getView()->display($this->fillDefaultParams([
            'content' => $this->getView()->render('base/login'),
        ]));
    }

    public function checkAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->redirect($this->getUrl()->crete('login'));
        }

        if (empty($this->getRequest()->getPostParam('login')) ||
            empty($this->getRequest()->getPostParam('password'))) {
            $this->redirect($this->getUrl()->crete('login'));
        }

        $user = $this->getDriver()->getUsers()->getOneByLoginAndPassword(
            $this->getRequest()->getPostParam('login'),
            $this->getRequest()->getPostParam('password')
        );

        if (empty($user)) {
            $this->redirect($this->getUrl()->crete('login'));
        }

        $this->getSession()->setKey('user', $user);

        $this->redirect($this->getUrl()->crete());
    }

    public function logoutAction()
    {
        $this->getSession()->clear();

        $this->redirect($this->getUrl()->crete());
    }

    /**
     * @return bool
     */
    public function isProtectedArea()
    {
        return false;
    }
}