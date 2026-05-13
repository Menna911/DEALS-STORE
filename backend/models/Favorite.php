<?php
require_once __DIR__ . '/../config/db.php';

class Favorite {
    private $conn;
    private $table = 'favorites';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function add($user_id, $offer_id) 
    {
        $query = 'INSERT INTO ' . $this->table . ' (user_id, offer_id) VALUES (:user_id, :offer_id)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':offer_id', $offer_id);

        try 
        {
            if($stmt->execute()) 
            {
                return true;
            }
        } 
        catch(PDOException $e) 
        {
            return false;
        }
        return false;
    }

    public function remove($user_id, $offer_id) 
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE user_id = :user_id AND offer_id = :offer_id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':offer_id', $offer_id);

        if($stmt->execute()) 
        {
            return true;
        }
        return false;
    }

    public function getUserFavorites($user_id) 
    {
        $query = 'SELECT o.* FROM offers o 
                  INNER JOIN ' . $this->table . ' f ON o.id = f.offer_id 
                  WHERE f.user_id = :user_id 
                  ORDER BY f.created_at DESC';
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt;
    }
}
?>