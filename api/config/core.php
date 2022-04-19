<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Manila');
 
// variables used for jwt
$key = "spark_app";
$issued_at = time();
$expiration_time = $issued_at + (600 * 600); // valid for 1 hour
$issuer = "http://localhost/Spark-Admin/";
?>