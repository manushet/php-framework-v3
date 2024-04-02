<?php

require_once(__DIR__ . "/src/Framework/Database.php");

use Framework\Database;

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

    $sqlFile = file_get_contents("./database.sql");

    $db->query($sqlFile);

    // try {
    //     $db->connection->beginTransaction();

    //     $db->connection->query("INSERT INTO products (name) VALUES('Gloves');");

    //     $query = "SELECT * FROM products WHERE name=:name ORDER BY name;";

    //     $stmt = $db->connection->prepare($query);
    //     $stmt->bindValue('name', 'Shirt', \PDO::PARAM_STR);
    //     $stmt->execute();

    //     $results = $stmt->fetchAll();

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