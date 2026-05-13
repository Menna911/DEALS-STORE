<?php
// Bring in the database connection
require_once __DIR__ . '/config/db.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Database connection failed.");
}

// Array of fake offers to insert
$offers = [
    [
        'title' => 'Wireless Noise-Canceling Headphones',
        'description' => 'Premium over-ear headphones with 30-hour battery life.',
        'category' => 'Electronics',
        'old_price' => 299.99,
        'discount_percentage' => 20.00, // 20% off
        'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e',
        'expiry_date' => '2026-12-31'
    ],
    [
        'title' => 'Men\'s Running Shoes',
        'description' => 'Lightweight and breathable sneakers for daily runs.',
        'category' => 'Fashion',
        'old_price' => 120.00,
        'discount_percentage' => 50.00, // 50% off!
        'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff',
        'expiry_date' => '2026-10-15'
    ],
    [
        'title' => 'Smart Home Coffee Maker',
        'description' => 'Brew your morning coffee using your smartphone.',
        'category' => 'Home Appliances',
        'old_price' => 150.00,
        'discount_percentage' => 15.00, // 15% off
        'image_url' => 'https://images.unsplash.com/photo-1517502884422-41eaead166d4',
        'expiry_date' => '2026-11-20'
    ]
];

// SQL Query (Notice we skip final_price because your database calculates it automatically!)
$query = "INSERT INTO offers (title, description, category, old_price, discount_percentage, image_url, expiry_date) 
          VALUES (:title, :description, :category, :old_price, :discount_percentage, :image_url, :expiry_date)";

$stmt = $conn->prepare($query);

$count = 0;
foreach ($offers as $offer) {
    try {
        $stmt->execute($offer);
        $count++;
    } catch (PDOException $e) {
        echo "Error inserting " . $offer['title'] . ": " . $e->getMessage() . "<br>";
    }
}

echo "Successfully inserted $count fake offers into the database!";
?>