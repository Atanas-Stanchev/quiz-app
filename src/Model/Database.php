<?php

namespace app\Model;

use Exception;
use PDO;
use PDOException;

class Database
{
    protected string $host = DB_HOST;
    protected string $database_name = DB_DATABASE_NAME;
    protected string $username = DB_USERNAME;
    protected string $password = DB_PASSWORD;
    public PDO $conn;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->getConnection();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getConnection(): ?PDO
    {
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->database_name,
                $this->username,
                $this->password
            );
            $this->conn->exec('set names utf8');
        } catch (PDOException $exception) {
            echo 'Database could not be connected: ' . $exception->getMessage();
        }
        return $this->conn;
    }

    /**
     * @throws Exception
     */
    public function select($query = '', $params = [])
    {
        try {
            return $this->executeStatement($query, $params)->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function executeStatement($query = '', $params = []): \PDOStatement
    {
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception('Unable to do prepared statement: ' . $query);
            }
            if ($params) {
                $stmt->bindParam($params[0], $params[1]);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
