<?php

namespace Messenger\Model\Database;

use Messenger\Model\Database\Base\Driver;

class Database
{
    const MYSQL_DRIVER = 'mysql';

    /**
     * @param string $databaseType
     * @return Driver|null
     */
    public function getDriver($databaseType)
    {
        if ($databaseType == self::MYSQL_DRIVER) {
            $className = __NAMESPACE__ . "\\" . ucfirst(self::MYSQL_DRIVER) . "\\Driver";
        }

        if (empty($className) || !class_exists($className)) {
            return null;
        }

        return new $className;
    }
}