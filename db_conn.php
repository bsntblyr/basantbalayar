<?php
$servername = "sql109.infinityfree.com";
$username = "if0_36992104";
$password = "M7XUs5oQm7rJq0V";
$dbname = "if0_36992104_imeevent";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 //echo "Connected successfully";
