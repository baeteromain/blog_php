<?php

namespace App\core;

use Exception;
use PDO;

abstract class Database
{
    private $connection;

    protected function createQuery($sql, $parameters = null)
    {
        if ($parameters) {
            $result = $this->checkConnection()->prepare($sql);
            $result->execute($parameters);

            return $result;
        }

        return $this->checkConnection()->query($sql);
    }

    private function checkConnection(): PDO
    {
        if (null === $this->connection) {
            return $this->getConnection();
        }

        return $this->connection;
    }

    private function getConnection(): PDO
    {
        try {
            $this->connection = new PDO(DB_HOST, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->connection;
        } catch (Exception $errorConnection) {
            exit('Erreur de connexion: '.$errorConnection->getMessage());
        }
    }
}
