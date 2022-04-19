<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "transaction_data";
    private $table_name2 = "client_data";
    private $table_name3 = "owner_parking_data";
 
    // object properties
    public $customerID;
    public $latitude;
    public $longitude;
    public $client_parking_status;
    public $client_location_id;
    public $client_id;
    public $transaction_id;
    public $client_transaction_id;
    public $transaction_parking_id;
    public $client_email;
    public $parking_name;
    public $dbData = array();
    public $results;

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create new user record
function checkStatus(){
    
    // query to check if email exists
    $query = "SELECT client_id, client_parking_status, client_location_id, transaction_id, client_transaction_id, transaction_parking_id,
            parking_ID, latitude, longitude, client_email, parking_name
            FROM " . $this->table_name2 . " 
            LEFT JOIN " . $this->table_name . " ON client_data.client_location_id = transaction_data.transaction_id
            LEFT JOIN " . $this->table_name3 . " ON transaction_data.transaction_parking_id = owner_parking_data.parking_ID
            WHERE client_email = ? ";
 
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
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
        
        // get record details / values
        $result10 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result10 as $row) {
            
        // assign values to object properties
        $this->client_parking_status = $row['client_parking_status'];
        $this->parking_name = $row['parking_name'];
        $this->latitude = (float)$row['latitude'];
        $this->longitude = (float)$row['longitude'];
        $this->transaction_id = (int)$row['transaction_id'];
        
        if($this->client_parking_status == "Free"){
            
            $results=array('message'=>'Access granted.', 'client_parking_status' => $this->client_parking_status);
        }
    
        else{
            $results=array('message'=>'Access granted.', 'client_parking_status' => $this->client_parking_status, 'latitude' => $this->latitude, 'longitude' => $this->longitude,
                            'transaction_id' => $this->transaction_id, 'parking_name' => $this->parking_name);
		
        }
        
                return json_encode($results);  
      
	}


        
    } 

    
    return false;
}
 

 
// update() method will be her
}
