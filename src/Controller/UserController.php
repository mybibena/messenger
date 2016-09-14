<?php

namespace Messenger\Controller;

class UserController extends BaseController
{
    public function editAction()
    {
        $currentUser = $this->getSession()->getKey('user');
        $editingUserId = $this->getRequest()->getGetParam('user');

        if (empty($editingUserId)) {
            $this->redirect($this->getUrl()->crete());
        }

        if (!$currentUser['is_admin'] && $currentUser['id'] != $editingUserId) {
            $this->redirect($this->getUrl()->crete());
        }

        $userDetails = $this->getDriver()->getUsers()->getOneById($editingUserId);

        $content = $this->getView()->render('user/edit', [
            'user' => $userDetails,
        ]);

        $this->getView()->display($this->fillDefaultParams([
            'content' => $content,
            'menu' => $this->getView()->render('base/menu'),
        ]));
    }

    public function saveEditAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->redirect($this->getUrl()->crete());
        }

        if (!$this->getRequest()->isPostParam('first_name') ||
            !$this->getRequest()->isPostParam('last_name') ||
            !$this->getRequest()->isPostParam('id')) {
            $this->redirect($this->getUrl()->crete());
        }

        $currentUser = $this->getSession()->getKey('user');
        if (!$currentUser['is_admin'] && $currentUser['id'] != $this->getRequest()->getPostParam('id')) {
            $this->redirect($this->getUrl()->crete());
        }

        $userDetails = $this->getDriver()->getUsers()->getOneById($this->getRequest()->getPostParam('id'));

        $this->getDriver()->getUsers()->edit(
            $this->getRequest()->getPostParam('id'),
            $userDetails['login'],
            $this->getRequest()->getPostParam('first_name'),
            $this->getRequest()->getPostParam('last_name'),
            (bool)$userDetails['is_admin']
        );

        $this->redirect($this->getUrl()->crete());
    }

    // ########################################

    public function createAction()
    {
        $currentUser = $this->getSession()->getKey('user');

        if (!$currentUser['is_admin']) {
            $this->redirect($this->getUrl()->crete());
        }

        $this->getView()->display($this->fillDefaultParams([
            'content' => $this->getView()->render('user/create'),
            'menu' => $this->getView()->render('base/menu'),
        ]));
    }

    public function saveCreateAction()
    {
        if (!$this->getRequest()->isPost()) {
            $this->redirect($this->getUrl()->crete());
        }

        if (!$this->getRequest()->isPostParam('login') ||
            !$this->getRequest()->isPostParam('first_name') ||
            !$this->getRequest()->isPostParam('last_name')) {
            $this->redirect($this->getUrl()->crete());
        }

        $currentUser = $this->getSession()->getKey('user');
        if (!$currentUser['is_admin']) {
            $this->redirect($this->getUrl()->crete());
        }

        $this->getDriver()->getUsers()->create(
            $this->getRequest()->getPostParam('login'),
            $this->getConfig()->getConfigs()['default_user_password'],
            $this->getRequest()->getPostParam('first_name'),
            $this->getRequest()->getPostParam('last_name'),
            $this->getRequest()->isPostParam('is_admin')
        );

        $this->redirect($this->getUrl()->crete());
    }

    // ########################################

    public function deleteAction()
    {
        if (!$this->getRequest()->isGetParam('user')) {
            $this->redirect($this->getUrl()->crete());
        }

        $currentUser = $this->getSession()->getKey('user');
        if (!$currentUser['is_admin']) {
            $this->redirect($this->getUrl()->crete());
        }

        $this->getDriver()->getUsers()->delete($this->getRequest()->getGetParam('user'));

        $this->redirect($this->getUrl()->crete());
    }

    // ########################################

    public function setDefaultPasswordAction()
    {
        if (!$this->getRequest()->isGetParam('user')) {
            $this->redirect($this->getUrl()->crete());
        }

        $currentUser = $this->getSession()->getKey('user');
        if (!$currentUser['is_admin']) {
            $this->redirect($this->getUrl()->crete());
        }

        $this->getDriver()->getUsers()->editPassword(
            $this->getRequest()->getGetParam('user'),
            $this->getConfig()->getConfigs()['default_user_password']
        );

        $this->redirect($this->getUrl()->crete());
    }
}