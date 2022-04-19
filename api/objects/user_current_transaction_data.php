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
    public $transactionID;
    public $latitude;
    public $longitude;
    public $client_parking_status;
    public $client_location_id;
    public $client_id;
    public $transaction_id;
    public $client_transaction_id;
    public $transaction_parking_id;
    public $transaction_time_booked;
    public $client_email;
    public $current_server_datetime;
    public $dbData = array();
    public $results;

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create new user record
function transactionDetails(){
    
    date_default_timezone_set('Asia/Manila');
    
    $this->current_server_datetime = date('Y-m-d h:i A');
    
    // query to check if email exists
    $query = "SELECT transaction_time_booked, transaction_id
            FROM " . $this->table_name . "
            WHERE transaction_id = ? ";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->transactionID=htmlspecialchars(strip_tags($this->transactionID));
 
    // bind given email value
    $stmt->bindParam(1, $this->transactionID);
 
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
        $this->transaction_time_booked = $row['transaction_time_booked'];
       
        
 
            
            $results=array('message'=>'Access granted.', 'transaction_time_booked' => $this->transaction_time_booked, 'current_server_datetime' => $this->current_server_datetime);
       
        
                return json_encode($results);  
      
	}


        
    } 

    
    return false;
}
 

 
// update() method will be her
}
