<?php

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;

    public $connection;

    public function __construct()
    {
        $this->host = getenv('MYSQL_HOST');
        $this->db_name = getenv('MYSQL_DATABASE');
        $this->username = getenv('MYSQL_USER');
        $this->password = getenv('MYSQL_PASSWORD');
    }

    public function connect()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $this->connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

        } catch (PDOException $e) {

            echo json_encode([
                "success" => false,
                "message" => "Connection failed",
                "error" => $e->getMessage()
            ]);

            exit();
        }

        return $this->connection;
    }
}