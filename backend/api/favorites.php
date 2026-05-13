<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST'); // Allow both GET and POST
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once __DIR__ . '/../models/Favorite.php';

$favoriteModel = new Favorite();
$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'GET') 
{
    if (isset($_GET['user_id'])) 
    {
        $result = $favoriteModel->getUserFavorites($_GET['user_id']);
        $num = $result->rowCount();

        if ($num > 0) 
        {
            $favorites_arr = array();
            $favorites_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
            {
                array_push($favorites_arr['data'], $row);
            }
            echo json_encode($favorites_arr);
        } 
        else 
        {
            echo json_encode(array('message' => 'No favorites found for this user.'));
        }
    } 
    else 
    {
        echo json_encode(array('success' => false, 'message' => 'Missing user_id parameter.'));
    }
}


elseif ($method === 'POST') 
{
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->action) && isset($data->user_id) && isset($data->offer_id)) 
    {
        
        if ($data->action === 'add') 
        {
            if ($favoriteModel->add($data->user_id, $data->offer_id)) 
            {
                echo json_encode(array('success' => true, 'message' => 'Added to favorites.'));
            } 
            else 
            {
                echo json_encode(array('success' => false, 'message' => 'Failed to add. It might already be favorited.'));
            }
        } 
        elseif ($data->action === 'remove') 
        {
            if ($favoriteModel->remove($data->user_id, $data->offer_id)) 
            {
                echo json_encode(array('success' => true, 'message' => 'Removed from favorites.'));
            } 
            else 
            {
                echo json_encode(array('success' => false, 'message' => 'Failed to remove.'));
            }
        } 
        else 
        {
            echo json_encode(array('success' => false, 'message' => 'Invalid action.'));
        }
    } 
    else 
    {
        echo json_encode(array('success' => false, 'message' => 'Missing required fields (action, user_id, offer_id).'));
    }
} 
else 
{
    echo json_encode(array('success' => false, 'message' => 'Method not allowed.'));
}
?>