<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    // 'user' object
    class Partner {
     
        // database connection and table name
        private $conn;
        private $table_name = "tbl_owner_data";
    
     
        // object properties
        public $firstname;
        public $middlename;
        public $lastname;
        public $email;
        public $password;
     
        // constructor
        public function __construct($db){
            $this->conn = $db;
        }
     
        // create new user record
        function create(){
            
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            
           
            // insert query
            $query = "INSERT INTO tbl_owner_data
                    SET
                        
                        owner_firstname = :firstname,  
                        owner_middlename = :middlename,
                        owner_lastname = :lastname,
                        email = :email,
                        password = :password";
                        
            // prepare the query
            $stmt = $this->conn->prepare($query);
         
            // sanitize
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->middlename=htmlspecialchars(strip_tags($this->middlename));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->password=htmlspecialchars(strip_tags($this->password));
        
            // $this->gender=htmlspecialchars(strip_tags($this->gender));
            // $this->birthday=htmlspecialchars(strip_tags($this->birthday));
        
            // bind the values
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':middlename', $this->middlename);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':email', $this->email);
            // hash the password before saving to database
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
            
            if($stmt->execute()){
                $last_insert_partner_id = $this->conn->lastInsertId();
                
                
                return true;
            }
            else {
                echo $stmt->errorInfo();
            }
            return false;
        }
     
    //check if given email exist in the database
    function emailExists(){
     
        // query to check if email exists
        $query = "SELECT client_id, client_Fname, client_Lname, client_password, client_mobile, client_gender, client_Birthday, client_car_id, car_brand, car_details, car_plate, car_id
                FROM " . $this->table_name . " LEFT JOIN " . $this->table_name2 . " ON " . $this->table_name .".client_id = " . $this->table_name2 .".client_car_id
                WHERE client_email = ?
                LIMIT 0,1";
     
        // prepare the query
        $stmt = $this->conn->prepare( $query );
     
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
     
        // bind given email value
        $stmt->bindParam(1, $this->email);
     
        // execute the query
        $stmt->execute();
     
        // get number of rows
        $num = $stmt->rowCount();
     
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
     
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
            // assign values to object properties
            $this->id = $row['client_id'];
            $this->firstname = $row['client_Fname'];
            $this->lastname = $row['client_Lname'];
            $this->password = $row['client_password'];
            $this->contact_no = $row['client_mobile'];
            $this->gender = $row['client_gender'];
            $this->birthday = $row['client_Birthday'];
            $this->car_brand = $row['car_brand'];
            $this->car_details = $row['car_details'];
            $this->car_plate = $row['car_plate'];
            $this->last_insert_car_id = $row['car_id'];
            
            $query3 = "UPDATE " . $this->table_name . "
    			SET
    				client_token_device = :client_token_device
    			WHERE client_email = :client_email";
    
    	// prepare the query
    	$stmt3 = $this->conn->prepare($query3);
    
    
    	$stmt3->bindParam(':client_token_device', $this->deviceToken);
    
    	// unique ID of record to be edited
    	$stmt3->bindParam(':client_email', $this->email);
    
    	// execute the query
    	if($stmt3->execute()){
     
            // return true because email exists in the database
            return true;
            
    	}
        }
     
        // return false if email does not exist in the database
        return false;
    }
     
    // update() method will be her
    }
