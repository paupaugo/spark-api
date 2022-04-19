<?php

// required headers
header("Access-Control-Allow-Origin: http://sparkph.net/Spark-Admin/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/transaction_insert.php';

 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$transaction = new User($db);
 
// submitted data will be here
// get posted data






$json_data = file_get_contents("php://input");
$array = json_decode($json_data, TRUE);
// print_r($array);




 
// set product property values
$transaction->parkID = $array['parkID'];
$transaction->customerID = $array['customerID'];


$jwt=isset($_SERVER['HTTP_JWT']) ? $_SERVER['HTTP_JWT'] : "";
 
// use the create() method here
// create the user
if(
    !empty($transaction->parkID) &&
    !empty($transaction->customerID) &&
    $jwt
){
     
 
 
    // set response code
    http_response_code(200);
    
 
    // display message: user was created
    echo $transaction->createTransaction();
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create transaction."));
}
?>