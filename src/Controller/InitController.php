<?php

namespace Messenger\Controller;

class InitController extends BaseController
{
    const DEFAULT_DATABASE_TYPE = 'mysql';

    public function indexAction()
    {
        $this->getView()->display($this->fillDefaultParams([
            'content' => $this->getView()->render('base/init'),
            'menu' => '',
            'title' => $this->generateTitle("Initialization"),
        ]));
    }

    public function saveAction()
    {
        if (!$this->isValidSettings()) {
            $this->redirect($this->getUrl()->crete('init'));
        }

        $this->getConfig()->setConfigs($this->getReceivedConfigs());

        $this->initDatabase();

        $this->createAdminUser();

        $this->redirect($this->getUrl()->crete());
    }

    // ----------------------------------------

    /**
     * @return bool
     */
    private function isValidSettings()
    {
        if (!$this->getRequest()->isPost()) {
            return false;
        }

        $expectedKeys = [
            'database_host',
            'database_port',
            'database_name',
            'database_user',
            'database_password',
            'admin_login',
            'admin_password',
            'admin_first_name',
            'admin_last_name',
            'default_user_password',
        ];
        $receivedData = $this->getRequest()->getPostParams();

        if (empty($receivedData)) {
            return false;
        }

        if ($receivedData != array_filter($receivedData)) {
            return false;
        }

        $receivedKeys = array_keys($receivedData);
        sort($expectedKeys);
        sort($receivedKeys);

        if ($expectedKeys != $receivedKeys) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    private function getReceivedConfigs()
    {
        $receivedData = $this->getRequest()->getPostParams();

        unset(
            $receivedData['admin_login'],
            $receivedData['admin_password'],
            $receivedData['admin_first_name'],
            $receivedData['admin_last_name']
        );

        $receivedData['database_type'] = self::DEFAULT_DATABASE_TYPE;

        return $receivedData;
    }

    /**
     * @throws \Exception
     */
    private function initDatabase()
    {
        $this->getDriver()->getInstall()->installDatabase();
    }

    /**
     * @return int|null
     */
    private function createAdminUser()
    {
        $receivedData = $this->getRequest()->getPostParams();

        return $this->getDriver()->getUsers()->create(
            $receivedData['admin_login'],
            $receivedData['admin_password'],
            $receivedData['admin_first_name'],
            $receivedData['admin_last_name'],
            true
        );
    }

    /**
     * @return bool
     */
    public function isProtectedArea()
    {
        return false;
    }
}