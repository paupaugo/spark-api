<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "owner_parking_data";
    private $table_name2 = "owner_parking_photos";
    private $table_name3 = "client_car_data";
 
    // object properties
    public $parking_ID;
    public $owner_parking_id;
    public $parking_name;
    public $parking_flatrate;
    public $parking_duration;
    public $parking_exceeding;
    public $parking_street;
    public $parking_barangay;
    public $parking_municipal;
    public $parking_province;
    public $parking_country;
    public $latitude;
    public $longitude;
    public $dbData = array();
    public $results;

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }


 
// retrieve 
function parkingList(){
 
    // query to check if email exists
    $query = "SELECT parking_ID, owner_parking_id, parking_name, parking_flatrate, parking_duration, parking_exceeding,
            parking_street, parking_barangay, parking_municipal, parking_province, parking_country, latitude, longitude,
            owner_parking_photos_id, owner_parking_id, parking_photos
            FROM " . $this->table_name . " LEFT JOIN  " . $this->table_name2 . " ON  owner_parking_data.parking_ID = owner_parking_photos.owner_parking_id";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
    
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $result10 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result10 as $row) {
 
        // assign values to object properties
        $this->parking_ID = (int)$row['parking_ID'];
        $this->owner_parking_id = (int)$row['owner_parking_id'];
        $this->parking_name = $row['parking_name'];
        $this->parking_flatrate = (float)$row['parking_flatrate'];
        $this->parking_duration = (float)$row['parking_duration'];
        $this->parking_exceeding = (float)$row['parking_exceeding'];
        $this->parking_street = $row['parking_street'];
        $this->parking_barangay = $row['parking_barangay'];
        $this->parking_municipal = $row['parking_municipal'];
        $this->parking_province = $row['parking_province'];
        $this->parking_country = $row['parking_country'];
        $this->latitude = (float)$row['latitude'];
        $this->longitude = (float)$row['longitude'];
        $this->owner_parking_photos_id = (int)$row['owner_parking_photos_id'];
        $this->parking_photos = $row['parking_photos'];
        
        $results=array('message'=>'Access granted.', 'parking_id' => $this->parking_ID, 'owner_parking_id' => $this->owner_parking_id, 'parking_name' => $this->parking_name,
                        'parking_flatrate' => $this->parking_flatrate, 'parking_duration' => $this->parking_duration, 'parking_exceeding' => $this->parking_exceeding,
                        'parking_street' => $this->parking_street, 'parking_barangay' => $this->parking_barangay, 'parking_municipal' => $this->parking_municipal,
                        'parking_province' => $this->parking_province, 'parking_country' => $this->parking_country, 'latitude' => $this->latitude, 'longitude' => $this->longitude,
                        array('owner_parking_photos_id' => $this->owner_parking_photos_id, 'parking_photos' => $this->parking_photos));
							    
		$dbData[] = $results;
		
        }
        
        return json_encode($dbData);
        
        $results =  json_encode($dbData);
 
        // return true because email exists in the database
    
    }
      
    // return false if email does not exist in the database
    return false;
}


 
// update() method will be her
}
 echo parkingList();
