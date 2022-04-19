<?php
    date_default_timezone_set('Asia/Manila');
    
    $exited_datetime = date('Y-m-d h:i A'); 
    
    $id = "dRMmkdk6QbGzVucRkDumgi:APA91bFPzT5G08lRY6FpO0hm6yp-kAt3DMYd7y_rO8WiTPr9W1mu05dLdqzkaDiR-kZrVZrEEEAI8PpyB_uoKHCMFWZ4z25xgYbtf62ZI5-7Xy_zXhdBdYlIul7Zn_B6cjcIf31EpdKW";
    $message = "Payment Successful.";
    
    $title = "Spark Payment";
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'registration_ids' => array (
                    $id
            ),
            'notification' => array (
                "title" => $title,
            ),
            'data' => array (
                    "message" => $message
            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAA-bJ-bmQ:APA91bFHkcnf2FtwIfW31vEft0BeQYUQ22Q38YzTgl1bNjJ2gpRL3HM7Kpb4grjXiMC8d6vKbsVnx6bmT5iBPCfYEl9U1NX5ehgdQDrEp04DS8J6XaQ0uCN48DjfULASbFGKoB7VxSZ4",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    echo $result;
    curl_close ( $ch );
 $mysqli = new mysqli("localhost", "ameniel_spark", "ameniel_spark", "ameniel_spark");



$sql2=  "UPDATE client_data SET client_parking_status ='Free' WHERE client_id = 24 ";

$sql3=  "UPDATE transaction_data SET transaction_parking_status ='Paid', transaction_time_exited = '$exited_datetime' WHERE transaction_id = 274 ";
$mysqli->query($sql3);
if($mysqli->query($sql2)){
    echo "success";
}
else{
    echo "failed";
}

?>