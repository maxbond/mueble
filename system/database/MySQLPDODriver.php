<?php

namespace Maxbond\Mueble\Database;

use PDO;
use PDOException;
use PDOStatement;
use Maxbond\Mueble\Exceptions\DBException;
use Maxbond\Mueble\Exceptions\UsageException;

/**
 * MySQL driver
 * @package Maxbond\mueble\system\database
 */
class MySQLPDODriver implements DBDriverInterface
{
    const BIND_PREFIX = ':';

    /**
     * @var PDO|null
     */
    protected $connection = null;

    /**
     * MysqlPDODriver constructor.
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @throws DBException
     */
    public function __construct(string $host = '', string $username = '', string $password = '', string $database = '')
    {
        if ($host && $username && $password && $database) {
            $this->connect($host, $username, $password, $database);
        }
    }

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @return mixed|void
     * @throws DBException
     */
    public function connect(string $host, string $username, string $password, string $database)
    {
        try {
            $this->connection = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
        } catch (PDOException $exception) {
            throw new DBException($exception->getMessage());
        }

    }

    /**
     * @param string $sql
     * @param array|null $attributes
     * @return false|mixed|\PDOStatement
     * @throws UsageException|DBException
     */
    public function select(string $sql, ?array $attributes = null)
    {
        $this->checkConnection();

        try {
            $statement = $this->connection->prepare($sql);
            $this->bindValues($statement, $attributes);
            $statement->execute();
        } catch (PDOException $exception) {
            throw new DBException($exception->getMessage());
        }

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $sql
     * @param array|null $attributes
     * @return mixed|void
     * @throws DBException
     * @throws UsageException
     */
    public function query(string $sql, ?array $attributes)
    {
        $this->checkConnection();
        $statement = $this->connection->prepare($sql);
        $this->bindValues($statement, $attributes);

        try {
            $statement->execute();
        } catch (PDOException $exception) {
            throw new DBException($exception->getMessage());
        }
    }

    /**
     * @throws UsageException
     */
    protected function checkConnection()
    {
        if (!$this->connection) {
            throw new UsageException("First connect to database");
        }

    }

    /**
     * @param PDOStatement $statement
     * @param array|null $attributes
     */
    protected function bindValues(PDOStatement $statement, ?array $attributes)
    {
        if (!$attributes || empty($attributes)) {
            return;
        }

        foreach ($attributes as $name => $value) {
            $statement->bindValue(static::BIND_PREFIX . $name, $value);
        }
    }


}