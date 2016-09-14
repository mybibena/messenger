<?php

namespace Messenger\Model\Core;

class Paths
{
    /**
     * @return string
     */
    public function getBasePath()
    {
        return ROOT_DIRECTORY;
    }

    /**
     * @return string
     */
    public function getConfigFilePath()
    {
        return $this->getBasePath() . "app" . DS . "config" . DS . "config.php";
    }

    /**
     * @param string $databaseType
     * @return string
     */
    public function getInstallFilePath($databaseType)
    {
        return $this->getBasePath() . "app" . DS . "database" . DS . $databaseType . DS . "install.sql";
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->getBasePath() . "src" . DS . "View" . DS;
    }
}