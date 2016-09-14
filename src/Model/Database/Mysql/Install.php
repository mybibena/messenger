<?php

namespace Messenger\Model\Database\Mysql;

use Messenger\Model\Core\Paths;
use Messenger\Model\Database\Mysql;
use Messenger\Model\Database\Base\Install as BaseInstall;

class Install extends Mysql implements BaseInstall
{
    public function installDatabase()
    {
        $paths = new Paths();

        $installFilePath = $paths->getInstallFilePath(self::DATABASE_TYPE);

        if (!is_file($installFilePath)) {
            throw new \Exception('Install file is missing for database type ' . self::DATABASE_TYPE);
        }

        $installSql = file_get_contents($installFilePath);

        $this->queryWithDelimiter($installSql);
    }
}