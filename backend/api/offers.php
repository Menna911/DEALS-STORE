<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

# Include database and offer model
require_once __DIR__ . '/../models/offer.php';

# Instantiate offer object and get all offers from database
$offer = new Offer();
$result = $offer->getAll();

$num = $result->rowCount();

if ($num > 0) 
{
    $offers_arr = array();
    $offers_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
    {
        array_push($offers_arr['data'], $row);
    }

    echo json_encode($offers_arr);
} 
else 
{
    echo json_encode(array('message' => 'No offers found.'));
}
?>