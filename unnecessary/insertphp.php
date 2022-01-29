<?php 
// initializing details
date_default_timezone_set('Asia/Kathmandu');
$host='localhost';
$user='root';
$password='';
$db="test01";

// Connecting to database
$con=@mysqli_connect($host,$user,$password,$db);

// Checking for connection status
if (mysqli_connect_errno()) {
	echo "Failed to connect to Database\n Try again Later!!!";
	exit();
}else{
	echo("YESS");
}


extract($_POST);

echo($_POST['titlet']);
if (isset($_POST['titlet']) && isset($_POST['bodyt'])) {
	echo("YESS");
	$q="INSERT INTO testtable (title, body) VALUES('$titlet','$bodyt')";
	$query =mysqli_query($con,$q) or die(error_reporting(1));
	header('location:ajaxcurd.php');
}


 ?>