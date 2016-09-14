<?php

namespace Messenger\Controller;

class ErrorController extends BaseController
{
    public function error404Action()
    {
        $this->getView()->display($this->fillDefaultParams([
            "content" => [
                "message" => 'Error 404...',
            ],
        ]));
    }
}