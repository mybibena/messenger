<?php

namespace Messenger\Model\Database;

use Messenger\Model\Core\Config;

class Connection
{
    /** @var Connection|null */
    static private $instance = NULL;

    private function __clone() {}

    private function __construct() {}

    /**
     * @static
     * @return Connection
     * @return void
     */
    public static function getInstance()
    {
        if (self::$instance === NULL) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    /**
     * @param string $type
     * @return \PDO
     */
    public function getConnection($type)
    {
        $configs = (new Config())->getConfigs();

        if ($type == 'mysql') {
            $dsn = "mysql:host={$configs['database_host']};port={$configs['database_port']};dbname={$configs['database_name']}";
            $dbUser = $configs['database_user'];
            $dbPassword = $configs['database_password'];

            return new \PDO($dsn, $dbUser, $dbPassword);
        }
    }
}