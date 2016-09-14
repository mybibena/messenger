<?php

namespace Messenger\Model\Core;

class View
{
    /** @var Url|null */
    private $url = null;

    /** @var Session|null */
    private $session = null;

    /**
     * View constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return Session|null
     */
    private function getSession()
    {
        return $this->session;
    }

    /**
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render($template, array $params = [])
    {
        if (!$this->isValidTemplateFilePath($template)) {
            return '';
        }

        if (!empty($params)) {
            extract($params);
        }


        ob_start();
        include ($this->getTemplateFilePath($template));

        $output = trim(ob_get_contents());
        ob_end_clean();

        return $output;
    }

    public function display($params = [], $layout = 'layout/layout')
    {
        echo $this->render($layout, $params);
    }

    /**
     * @return Url|null
     */
    public function getUrl()
    {
        if (!empty($this->url)) {
            return $this->url;
        }

        return $this->url = new Url();
    }

    /**
     * @return array|null
     */
    public function getUser()
    {
        return $this->getSession()->getKey('user');
    }

    // ########################################

    /**
     * @param string $template
     * @return bool
     */
    private function isValidTemplateFilePath($template)
    {
        return is_file($this->getTemplateFilePath($template));
    }

    /**
     * @param string $template
     * @return string
     */
    private function getTemplateFilePath($template)
    {
        return "{$this->getTemplateDirPath()}$template.phtml";
    }

    /**
     * @return string
     */
    private function getTemplateDirPath()
    {
        return (new Paths())->getTemplatePath();
    }
}