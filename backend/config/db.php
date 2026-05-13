<?php

class Database
{
    // private $host="mysql-db";
    private $host="127.0.0.1";
    private $db_name="deals_store";
    private $username="deals-user";
    private $password="moaazkaff";
    public $connection;

    public function connect()
    {
        $this->connection=null;
        try
        {
            $this->connection=new PDO(
                "mysql:host=".$this->host.";dbname=".$this->db_name,
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
        }
        catch(PDOException $e)
        {
            // echo "Connection Error: ".$e->getMessage();
            echo json_encode([
                "success"=>false,
                "message"=> "Connection failed",
                "error"=>$e->getMessage()
            ]);
            exit();
        }
        return $this->connection;
    }


}