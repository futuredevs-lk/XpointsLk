<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "xpointssl_database";

// $servername = "localhost";
// $username = "xpointsl_root";
// $password = "db@Xpointslk0411";
// $dbname = "xpointsl_database";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}
session_start();




// $db_host        = '162.214.201.218';
// $db_user        = 'xpointsl_root';
// $db_pass        = 'db@Xpointslk0411';
// $db_database    = 'xpointsl_database'; 
// $db_port        = '3306';

// $db = mysqli_connect($db_host,$db_user,$db_pass,$db_database,$db_port);
// session_start();
?>