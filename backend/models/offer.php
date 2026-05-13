<?php

# Include database configuration file to connect to database and offer model to interact with offers table in database
require_once __DIR__ . '/../config/db.php';

class Offer 
{
    private $conn;
    private $table = 'offers';

    public function __construct() 
    {
        # Initialize object from database connection
        $db = new Database();
        # Connect to database using $db object and connect function and assign connection to $conn using $this->conn
        $this->conn = $db->connect();
    }
    # Get all offers from database
    public function getAll() 
    {
        # This function will return all offers from database using SQL query 
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        # Prepare the SQL statement
        $stmt = $this->conn->prepare($query);
        # Execute the statement
        $stmt->execute();

        return $stmt;
    }
}
?>