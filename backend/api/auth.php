<?php


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST'); // We only accept POST requests for security
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


require_once __DIR__ . '/../models/User.php';

$userModel = new User();


$data = json_decode(file_get_contents("php://input"));


if(isset($data->action)) 
{
    if($data->action === 'register') 
    {
        if(!empty($data->name) && !empty($data->email) && !empty($data->password) && !empty($data->age)) 
        {    
            if($userModel->register($data->name, $data->email, $data->password, $data->age)) 
            {
                echo json_encode(['success' => true, 'message' => 'User Registered Successfully.']);
            } 
            else
            {
                echo json_encode(['success' => false, 'message' => 'Registration Failed. Email might already exist.']);
            }
        } 
        else 
        {
            echo json_encode(['success' => false, 'message' => 'Missing required fields for registration.']);
        }
    }

    
    else if($data->action === 'login') 
    {
        
        if(!empty($data->email) && !empty($data->password)) 
        {
            
            
            $loggedInUser = $userModel->login($data->email, $data->password);
            
            if($loggedInUser) 
            {
                echo json_encode(['success' => true, 'message' => 'Login Successful.', 'user' => $loggedInUser]);
            } 
            else 
            {
                echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
            }
        } 
        else 
        {
            echo json_encode(['success' => false, 'message' => 'Missing email or password.']);
        }
    }

    else 
    {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} 
else
{
    echo json_encode(['success' => false, 'message' => 'No action specified.']);
}
?>