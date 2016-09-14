<?php

namespace Messenger\Model\Database;

class Mysql
{
    const DATABASE_TYPE = 'mysql';

    /** @var \PDO|null */
    private $connection = NULL;

    /**
     * @return \PDO|null
     */
    protected function getConnection()
    {
        if (!empty($this->connection)) {
            return $this->connection;
        }

        return $this->connection = Connection::getInstance()->getConnection(self::DATABASE_TYPE);
    }

    /**
     * @param string $statement
     * @return \PDOStatement
     * @throws \Exception
     */
    public function query($statement)
    {
        $result = $this->getConnection()->query($statement);

        if ($result === false) {
            throw new \Exception('Mysql query was not executed');
        }

        return $result;
    }

    /**
     * @param string $query
     * @return bool|\PDOStatement
     * @throws \Exception
     */
    public function queryWithDelimiter($query)
    {
        $queries = $this->splitQueryWithDelimiter($query);
        $result = true;

        foreach ($queries as $statement) {
            if (!$result) {
                return false;
            }

            $result = $this->query($statement);
        }

        return $result;
    }

    /**
     * @param string $query
     * @return array
     */
    private function splitQueryWithDelimiter($query)
    {
        preg_match('#.*DELIMITER (.{1,2}).*#', $query, $match);

        if (empty($match[1])) {
            return array($query);
        }

        $query = str_replace('DELIMITER ', '', $query);

        $queries = array_filter(explode($match[1], $query), function($node) {
            if (trim($node) == '') {
                return false;
            }

            return true;
        });

        array_walk($queries, function(&$value) {
            $value = trim($value, "\r\n; ") . ';';
        });

        return $queries;
    }
}