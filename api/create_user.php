<?php
// required headers
header("Access-Control-Allow-Origin: https://sparkph.net/Spark-Admin/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);

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
$user->firstname = $data->firstname;
$user->lastname = $data->lastname;
$user->email = $data->email;
$user->password = $data->password;
$user->contact_no = $data->contact_no;
$user->deviceToken = $data->device_token;
// $user->gender = $data->gender;
// $user->birthday = $data->birthday;
// $user->plate_no = $data->plate_no;
// $user->car_model = $data->car_model;
// $user->car_description = $data->car_description;
// $user->car_photo = $data->car_photo;
// $user->driver_license = $data->driver_license;
// !empty($user->firstname) &&
//     !empty($user->lastname) &&
//     !empty($user->contact_no) &&
//     !empty($user->email) &&
//     !empty($user->password) &&
 
// use the create() method here
// create the user
if($user->create()){
     $token = array(
       "iat" => $issued_at,
       "exp" => $expiration_time,
       "iss" => $issuer,
       "data" => array(
           "id" => $user->id,
           "firstname" => $user->firstname,
           "lastname" => $user->lastname,
           "email" => $user->email,
           "contact_no" => $user->contact_no
           
       )
    );
 
 
    // set response code
    http_response_code(200);
    $jwt = JWT::encode($token, $key);
 
    // display message: user was created
    echo json_encode(array("message" => "User was created.", "jwt" => $jwt));
}
 
// message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user.", "error" => $user->create()));
}
?>