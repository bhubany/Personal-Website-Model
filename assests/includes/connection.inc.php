<?php 
// initializing details
date_default_timezone_set('Asia/Kathmandu');
$host='localhost';
$user='root';
$password='';
$db="khatriSujit";

// Connecting to database
$con=@mysqli_connect($host,$user,$password,$db);

// Checking for connection status
if (mysqli_connect_errno()) {
	echo "Failed to connect to Database\n Try again Later!!!";
	exit();
}


 ?>