<?php

declare(strict_types=1);

namespace Framework;

class Database
{
    private ?\PDO $connection = null;

    public function __construct(
        string $driver,
        array $config,
        string $username,
        string $password
    ) {
        $config = http_build_query([
            'host' => $config['host'],
            'port' => $config['port'],
            'dbname' => $config['dbname'],
            'user' => $username,
            'password' => $password
        ], arg_separator: ';');

        $dsn = "{$driver}:{$config}";

        $this->connection = new \PDO($dsn, $username, $password);

        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    public function query(string $query)
    {
        return $this->connection->query($query);
    }
}