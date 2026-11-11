<?php
/*session_start*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*database connection */
$host = "localhost";
$username = "root";
$password = "";
$database = "hospital_ms";
$connection = new mysqli($host, $username, $password, $database);
if ($connection->connect_error){
    die("Database connection failed" .$connection->connect_error);
}
$connection->set_charset("utf8mb4");

/*send email */
$sender_email = "sumitra29mahato@gmail.com";
$sender_name = "Hospital Management System";

$base_url = "http://localhost/hospital_management_system";

?>