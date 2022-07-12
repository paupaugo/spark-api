<?php
// required headers
header("Access-Control-Allow-Origin: https://sparkph.tech/Spark-Admin/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/partner.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$partner = new Partner($db);

// generate json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// submitted data will be here
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$partner->firstname = $data->form_fields[firstname];
$partner->middlename = $data->form_fields[middlename];
$partner->lastname = $data->form_fields[lastname];
$partner->email = $data->form_fields[email];
$partner->password = $data->form_fields[password];


 
// use the create() method here
// create the user
if($partner->create()){
     
 
    // set response code
    http_response_code(200);

 
    // display message: user was created
    echo json_encode(array("message" => "Partner was created."));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user.", "error" => $user->create()));
}
?>