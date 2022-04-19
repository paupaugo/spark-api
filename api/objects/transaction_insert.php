<?php



// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "transaction_data";
    private $table_name2 = "client_data";
    private $table_name3 = "owner_parking_data";
 
    // object properties
    public $parkID;
    public $customerID;
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
    public $client_transaction_id;
    public $transaction_amount;
    public $transaction_duration;
    public $transaction_parking_status;
    public $transaction_parking_id;
    public $client_parking_status;
    public $client_location_id;
    public $client_id;
    public $client_email;
    public $transaction_time_booked;
    public $dbData = array();
    public $results;
    public $booked_datetime;
    
    

 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create new user record
function createTransaction(){
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
    // Setting up the time zone
    date_default_timezone_set('Asia/Manila');
    
    $this->booked_datetime = date('Y-m-d h:i A');
    
    // query to check if email exists
    $query = "SELECT parking_ID, owner_parking_id, parking_name, parking_flatrate, parking_duration
            FROM owner_parking_data
            WHERE parking_ID = ? ";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->parkID=htmlspecialchars(strip_tags($this->parkID));
 
    // bind given email value
    $stmt->bindParam(1, $this->parkID);
 
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
        $this->transaction_parking_status = "Booked";
 
        
        // insert query
        $query2 = "INSERT INTO transaction_data
            SET
                client_transaction_id = :client_transaction_id,
                owner_transaction_id = :owner_transaction_id,
                transaction_amount = :transaction_amount,
                transaction_duration = :transaction_duration,
                transaction_parking_status = :transaction_parking_status,
                transaction_parking_id = :transaction_parking_id,
                transaction_time_booked = :transaction_time_booked";
                
    
 
    // prepare the query
    $stmt2 = $this->conn->prepare($query2);
 
    // sanitize
    $this->client_transaction_id=htmlspecialchars(strip_tags($this->customerID));
    $this->owner_transaction_id=htmlspecialchars(strip_tags($this->owner_parking_id));
    $this->transaction_amount=htmlspecialchars(strip_tags($this->parking_flatrate));
    $this->transaction_duration=htmlspecialchars(strip_tags($this->parking_duration));
    $this->transaction_parking_status=htmlspecialchars(strip_tags($this->transaction_parking_status));
    $this->transaction_parking_id=htmlspecialchars(strip_tags($this->parking_ID));

 
    // bind the values
    $stmt2->bindParam(':client_transaction_id', $this->client_transaction_id);
    $stmt2->bindParam(':owner_transaction_id', $this->owner_transaction_id);
    $stmt2->bindParam(':transaction_amount', $this->transaction_amount);
    $stmt2->bindParam(':transaction_duration', $this->transaction_duration);
    $stmt2->bindParam(':transaction_parking_status', $this->transaction_parking_status);
    $stmt2->bindParam(':transaction_parking_id', $this->transaction_parking_id);
    $stmt2->bindParam(':transaction_time_booked', $this->booked_datetime);
      if($stmt2->execute()){
         $last_insert_transation_id = (int)$this->conn->lastInsertId();
         
         
         // if no posted password, do not update the password
	$query3 = "UPDATE client_data
			SET
				client_parking_status = :client_parking_status,
				client_location_id = :client_location_id
			WHERE client_email = :client_email";

	// prepare the query
	$stmt3 = $this->conn->prepare($query3);

	// sanitize
	$this->client_parking_status=htmlspecialchars(strip_tags($this->transaction_parking_status));
	$this->client_location_id=htmlspecialchars(strip_tags($last_insert_transation_id));
	// bind the values from the form
	$stmt3->bindParam(':client_parking_status', $this->client_parking_status);
	$stmt3->bindParam(':client_location_id', $this->client_location_id);

	// unique ID of record to be edited
	$stmt3->bindParam(':client_email', $this->customerID);

	// execute the query
	if($stmt3->execute()){
		 $results=array('message'=>'Transaction created.', 'transaction_id' => $last_insert_transation_id);
							    
		
		
        
        return json_encode($results);
	}

        
        
    } else {
        $results=array('message'=>'hindi naginsert.', 'error' => $stmt2->errorInfo()); 
        
        return json_encode($results);
        
    }

    }
    }
    else {
        $results=array('message'=>'none parking.'); 
        return json_encode($results);
    }
    
    return false;
}
 

 
// update() method will be her
}
