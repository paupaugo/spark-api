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
    public $client_parking_status;
    public $client_location_id;
    public $transaction_id;
    public $client_transaction_id;
    public $transaction_parking_id;
    public $client_email;
    public $transaction_parking_status;
    public $transaction_time_parked;
    public $dbData = array();
    public $results;
    
    public $parked_datetime;

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create new user record
function statusParked(){


    // Setting up the time zone
    date_default_timezone_set('Asia/Manila');
    
    $this->parked_datetime = date('Y-m-d h:i A'); 
    
    // if no posted password, do not update the password
	$query3 = "UPDATE " . $this->table_name2 . "
			SET
				client_parking_status = :client_parking_status
			WHERE client_email = :client_email";

	// prepare the query
	$stmt3 = $this->conn->prepare($query3);
	
	$this->transaction_parking_status = "Parked";

	// sanitize
	$this->client_parking_status=htmlspecialchars(strip_tags($this->transaction_parking_status));
	$this->client_location_id=htmlspecialchars(strip_tags($last_insert_transation_id));
	
	// bind the values from the form
	$stmt3->bindParam(':client_parking_status', $this->client_parking_status);

	// unique ID of record to be edited
	$stmt3->bindParam(':client_email', $this->customerID);

	// execute the query
	if($stmt3->execute()){
	    
	    
	    
	     // if no posted password, do not update the password
	$query4 = "UPDATE " . $this->table_name . "
			SET
				transaction_parking_status = :transaction_parking_status,
				transaction_time_parked = :transaction_time_parked
			WHERE transaction_id = :transactionID";

	// prepare the query
	$stmt4 = $this->conn->prepare($query4);
	
	$this->transaction_parking_status = "Parked";

	// sanitize
	$this->transaction_parking_status=htmlspecialchars(strip_tags($this->transaction_parking_status));

	
	// bind the values from the form
	$stmt4->bindParam(':transaction_parking_status', $this->transaction_parking_status);
	$stmt4->bindParam(':transaction_time_parked', $this->parked_datetime);

	// unique ID of record to be edited
	$stmt4->bindParam(':transactionID', $this->transactionID);

	// execute the query
	if($stmt4->execute()){
	    
	    
	    
		 $results=array('message'=>'Access granted.');
							    
		
		
        
        return json_encode($results);
	}

}
    
    return false;
}
 

 
// update() method will be her
}
