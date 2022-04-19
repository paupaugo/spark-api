<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/user_status_data.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$status = new User($db);
 
// submitted data will be here
// get posted data
// $data = json_decode(file_get_contents("php://input"));
 
// set product property values
$status->customerID = $_GET['customerID'];

$jwt=isset($_SERVER['HTTP_JWT']) ? $_SERVER['HTTP_JWT'] : "";
 
// use the create() method here
// create the user
if(
    !empty($status->customerID) &&
    $jwt
){
     
 
 
    // set response code
    http_response_code(200);
    
 
    // display message: user was created
    echo $status->checkStatus();
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Access denied."));
}
?>