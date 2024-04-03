<?php

require_once(__DIR__ . "/src/Framework/Database.php");

use Framework\Database;

$driver = 'pgsql';
$config = [
    "host" => 'localhost',
    "port" => 5432,
    "dbname" => 'phpiggy',
];
$username = 'admin';
$password = 'password';

try {
    /**
     * @var Database $db
     */
    $db = new Database(
        $driver,
        $config,
        $username,
        $password
    );

    $sqlFile = file_get_contents("./users.sql");

    $db->query($sqlFile);

    $sqlFile = file_get_contents("./transactions.sql");

    $db->query($sqlFile);

    $sqlFile = file_get_contents("./receipts.sql");

    $db->query($sqlFile);

    // try {
    //     $db->connection->beginTransaction();
    //     $db->connection->query("INSERT INTO products (name) VALUES('Gloves');");
    //     $stmt = $db->connection->prepare($query);
    //     $stmt->bindValue('name', 'Shirt', \PDO::PARAM_STR);
    //     $stmt->execute();
    //     $db->connection->commit();
    //     print_r($results);
    // } catch (\Exception $e) {
    //     if ($db->connection->inTransaction()) {
    //         $db->connection->rollBack();
    //     }
    //     echo "Transaction failed!\r\n";
    // }
} catch (\PDOException $e) {
    echo "Connection failed: {$e->getMessage()}\r\n";
}