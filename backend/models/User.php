<?php

# Includes the database connection class
require_once __DIR__ . '/../config/db.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($name, $email, $password, $age) 
    {
        $query = 'INSERT INTO ' . $this->table . ' (name, email, password_hash, age) 
                  VALUES (:name, :email, :password_hash, :age)';
        
        $stmt = $this->conn->prepare($query);

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':age', $age);

        try 
        {
            if($stmt->execute()) 
            {
                return true;
            }
        } catch(PDOException $e) 
        {
            return false;
        }
        return false;
    }

    public function login($email, $password) 
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email LIMIT 1';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password_hash'])) 
        {
            unset($user['password_hash']);
            return $user;
        }
        
        return false; 
    }
}
?>