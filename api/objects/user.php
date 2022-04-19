<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    // 'user' object
    class User{
     
        // database connection and table name
        private $conn;
        private $table_name = "client_data";
        private $table_name2 = "client_car_data";
        private $table_name3 = "client_car_data";
     
        // object properties
        public $id;
        public $firstname;
        public $lastname;
        public $email;
        public $password;
        public $contact_no;
        public $gender;
        public $birthday;
        public $plate_no;
        public $car_model;
        public $car_description;
        public $car_photo;
        public $driver_license;
        public $last_insert_client_id;
        public $last_insert_car_id;
        public $deviceToken;
        public $client_token_device;
        public $device_token;
        public $client_parking_status = "Free";
     
        // constructor
        public function __construct($db){
            $this->conn = $db;
        }
     
        // create new user record
        function create(){
            
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            
           
            // insert query
            $query = "INSERT INTO client_data
                    SET
                        client_token_device = :client_token_device,
                        client_Fname = :firstname,
                        client_Lname = :lastname,
                        client_email = :email,
                        client_password = :password,
                        client_mobile = :contact_no,
                        client_parking_status = :client_parking_status";
                        
            // prepare the query
            $stmt = $this->conn->prepare($query);
         
            // sanitize
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->contact_no=htmlspecialchars(strip_tags($this->contact_no));
            $this->client_parking_status=htmlspecialchars(strip_tags($this->client_parking_status));
            // $this->gender=htmlspecialchars(strip_tags($this->gender));
            // $this->birthday=htmlspecialchars(strip_tags($this->birthday));
        
            // bind the values
            $stmt->bindParam(':client_token_device', $this->deviceToken);
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':email', $this->email);
            // hash the password before saving to database
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':contact_no', $this->contact_no);
            $stmt->bindParam(':client_parking_status', $this->client_parking_status);
            // $stmt->bindParam(':gender', $this->gender);
            // $stmt->bindParam(':birthday', $this->birthday);
            
            if($stmt->execute()){
                $last_insert_client_id = $this->conn->lastInsertId();
                
                // insert query
                // $query2 = "INSERT INTO " . $this->table_name2 . "
                //     SET
                //         client_car_id = :last_insert_client_id,
                //         car_brand = :car_model,
                //         car_details = :car_description,
                //         car_plate = :plate_no";
            
                // // prepare the query
                // $stmt2 = $this->conn->prepare($query2);
            
                // // sanitize
                // $this->car_model=htmlspecialchars(strip_tags($this->car_model));
                // $this->car_description=htmlspecialchars(strip_tags($this->car_description));
                // $this->plate_no=htmlspecialchars(strip_tags($this->plate_no));
                
                // // bind the values
                // $stmt2->bindParam(':last_insert_client_id', $last_insert_client_id);
                // $stmt2->bindParam(':car_model', $this->car_model);
                // $stmt2->bindParam(':car_description', $this->car_description);
                // $stmt2->bindParam(':plate_no', $this->plate_no);
               
                // // execute the query, also check if query was successful
                // if($stmt2->execute()) {
                
                //     $last_insert_car_id = $this->conn->lastInsertId();
               
                //     foreach ($_FILES['car_photo']['name'] as $name => $value)  {  
                   
                //         $file_name = explode(".", $_FILES['car_photo']['name'][$name]);  
                //         $allowed_ext = array("jpg", "jpeg", "png", "gif");  
                   
                //         if(in_array($file_name[1], $allowed_ext))  {  
                //             $new_name = md5(rand()) . '.' . $file_name[1];  
                //             $sourcePath = $_FILES['car_photo']['tmp_name'][$name];  
                //             $targetPath = "../car_photos/".$new_name;
                        
                //             $statement4 = $pdo->prepare("INSERT INTO client_car_photos (
        								// 	                car_owner_id,
        								// 	                car_data_id,
        								// 	                car_photo) VALUES (?,?,?)");
                //             $statement4->execute(array(
        								// 	$owner_parking_id,
        								// 	$slot_parking_id,									
        								// 	$targetPath));
        
                //             if(move_uploaded_file($sourcePath, $targetPath))  {
                         
                //                 return true;
                //             }                 
                //         }            
                //     }
                // }
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
