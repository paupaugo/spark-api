<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "client_favorite_data";
    private $table_name2 = "client_data";
    private $table_name3 = "owner_parking_data";
 
    // object properties
    public $parkID;
    public $customerID;
    public $status = 1;
    public $dbData = array();
    public $dbData3 = array();
    public $results;
    public $results3;
    public $favorite_customer_id;
    public $favorite_parking_id;
    public $favorite_status;
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
    public $parking_type;
    public $latitude;
    public $longitude;
    public $isFavorite;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    // create new user record
    function createFavorite(){
        
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        
        // Setting up the time zone
        date_default_timezone_set('Asia/Manila');
        
            // insert favorite query
        $query2 = "INSERT INTO " . $this->table_name . "
                    SET 
                        favorite_customer_id = :favorite_customer_id,
                        favorite_parking_id = :favorite_parking_id,
                        favorite_status = :favorite_status";
                    
        // prepare the query
        $stmt2 = $this->conn->prepare($query2);
     
        // sanitize
        $this->favorite_customer_id=htmlspecialchars(strip_tags($this->customerID));
        $this->favorite_parking_id=htmlspecialchars(strip_tags($this->parkID));
        $this->favorite_status=htmlspecialchars(strip_tags($this->status));
    
        // bind the values
        $stmt2->bindParam(':favorite_customer_id', $this->favorite_customer_id);
        $stmt2->bindParam(':favorite_parking_id', $this->favorite_parking_id);
        $stmt2->bindParam(':favorite_status', $this->favorite_status);
        
        if($stmt2->execute()){
            
            $last_insert_favorite_id = (int)$this->conn->lastInsertId();
            
            // query to get updated favorite list
            $query = "SELECT parking_ID, owner_parking_id, parking_name, parking_flatrate, 
                parking_duration, parking_exceeding, parking_street, parking_barangay, 
                parking_municipal, parking_province, parking_country, latitude, longitude, parking_type, favorite_status, 
                favorite_customer_id, favorite_parking_id FROM owner_parking_data 
                LEFT JOIN client_favorite_data ON owner_parking_data.parking_ID = client_favorite_data.favorite_parking_id AND 
                client_favorite_data.favorite_customer_id = ?  GROUP BY favorite_parking_id";
 
            // prepare the query
            $stmt = $this->conn->prepare( $query );
    
            // sanitize
            $this->customerID=htmlspecialchars(strip_tags($this->customerID));
 
            // bind given email value
            $stmt->bindParam(1, $this->customerID);
 
            // execute the query
            $stmt->execute();
 
            // get number of rows
            $num = $stmt->rowCount();
             
            // if favorite exist
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
                    $this->parking_type = $row['parking_type'];
                        
                    if($row['favorite_status'] == 1){
                        $this->isFavorite = true;
                    }
                    else {
                        $this->isFavorite = false;
                    }
     
                    $results=array('message'=>'Favorite created.', 'parking_id' => $this->parking_ID, 'owner_parking_id' => $this->owner_parking_id, 'parking_name' => $this->parking_name,
                        'parking_flatrate' => $this->parking_flatrate, 'parking_duration' => $this->parking_duration, 'parking_exceeding' => $this->parking_exceeding,
                        'parking_street' => $this->parking_street, 'parking_barangay' => $this->parking_barangay, 'parking_municipal' => $this->parking_municipal,
                        'parking_province' => $this->parking_province, 'parking_country' => $this->parking_country, 'latitude' => $this->latitude, 'longitude' => $this->longitude,
                        'parking_type' => $this->parking_type, 'isFavorite' => $this->isFavorite);
    							    
    		        $this->dbData[] = $results;
    		    }
            
                return json_encode($this->dbData);
            }
        }
    
        return false;
    }

    // create new user record
    function deleteFavorite(){
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
            // insert query
            $query3 = "DELETE FROM " . $this->table_name . "
                    WHERE favorite_customer_id = :favorite_customer_id AND 
                    favorite_parking_id = :favorite_parking_id";
                    
        
     
        // prepare the query
        $stmt3 = $this->conn->prepare($query3);
     
        // sanitize
        $this->favorite_customer_id=htmlspecialchars(strip_tags($this->customerID));
        $this->favorite_parking_id=htmlspecialchars(strip_tags($this->parkID));
    
     
        // bind the values
        $stmt3->bindParam(':favorite_customer_id', $this->favorite_customer_id);
        $stmt3->bindParam(':favorite_parking_id', $this->favorite_parking_id);
        if($stmt3->execute()){
         
             
               $query4 = "SELECT parking_ID, owner_parking_id, parking_name, parking_flatrate, 
                parking_duration, parking_exceeding, parking_street, parking_barangay, 
                parking_municipal, parking_province, parking_country, latitude, longitude, parking_type, favorite_status, 
                favorite_customer_id, favorite_parking_id FROM owner_parking_data 
                LEFT JOIN client_favorite_data ON owner_parking_data.parking_ID = client_favorite_data.favorite_parking_id AND 
                client_favorite_data.favorite_customer_id = ? ";
 
            // prepare the query
            $stmt4 = $this->conn->prepare( $query4 );
    
            // sanitize
            $this->customerID=htmlspecialchars(strip_tags($this->customerID));
 
            // bind given email value
            $stmt4->bindParam(1, $this->customerID);
 
            // execute the query
            $stmt4->execute();
 
            // get number of rows
            $num = $stmt4->rowCount();
             
            // if favorite exist
            if($num>0){
             
            // get record details / values
            $result10 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
                    
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
                    $this->parking_type = $row['parking_type'];
                        
                    if($row['favorite_status'] == 1){
                        $this->isFavorite = true;
                    }
                    else {
                        $this->isFavorite = false;
                    }
     
                    $results=array('message'=>'Favorite deleted.', 'parking_id' => $this->parking_ID, 'owner_parking_id' => $this->owner_parking_id, 'parking_name' => $this->parking_name,
                        'parking_flatrate' => $this->parking_flatrate, 'parking_duration' => $this->parking_duration, 'parking_exceeding' => $this->parking_exceeding,
                        'parking_street' => $this->parking_street, 'parking_barangay' => $this->parking_barangay, 'parking_municipal' => $this->parking_municipal,
                        'parking_province' => $this->parking_province, 'parking_country' => $this->parking_country, 'latitude' => $this->latitude, 'longitude' => $this->longitude,
                        'parking_type' => $this->parking_type, 'isFavorite' => $this->isFavorite);
    							    
    		        $this->dbData[] = $results;
    		    }
            
                return json_encode($this->dbData);
            }
        
    	}
    
            
            
        
    
        
        
        
        return false;
    }
 

 
// update() method will be her
}
