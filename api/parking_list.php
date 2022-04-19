<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
// required headers
header("Access-Control-Allow-Origin: http://sparkph.net/Spark-Admin/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// required to decode jwt
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/parking_data.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$parking_list = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
$parking_list->customerID = $data->customerID;


       
 
// get jwt
$jwt=isset($_SERVER['HTTP_JWT']) ? $_SERVER['HTTP_JWT'] : "";
 
//if jwt is not empty
if(!empty($parking_list->customerID) && $jwt){
 
    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        // set response code
        http_response_code(200);
 
        // show user details
        echo $parking_list->parkingList();
 
    }
 
    // if decode fails, it means jwt is invalid
catch (Exception $e){
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied  & show error message
    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}
}
 
// show error message if jwt is empty
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}
?>