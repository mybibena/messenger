<?php

namespace Messenger\Model\Core\Controller;

use Messenger\Controller\BaseController;
use Messenger\Model\Core\Request;
use Messenger\Model\Core\Session;

class Builder
{
    /**
     * @param string $className
     * @param Request $request
     * @return BaseController|null
     */
    public function getController($className, Request $request)
    {
        try {
            if (!class_exists($className)) {
                return NULL;
            }

            $session = new Session();

            return new $className($request, $session);
        } catch (\Exception $e) {
            return NULL;
        }
    }
}