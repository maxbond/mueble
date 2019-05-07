<?php

namespace Maxbond\Mueble\Database;

interface DBDriverInterface
{
    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @return mixed
     */
    public function connect(string $host, string $username, string $password, string $database);

    /**
     * @param string $sql
     * @param array|null $attributes
     * @return mixed
     */
    public function select(string $sql, ?array $attributes);

    /**
     * @param string $sql
     * @param array|null $attributes
     * @return mixed
     */
    public function query(string $sql, ?array $attributes);
}