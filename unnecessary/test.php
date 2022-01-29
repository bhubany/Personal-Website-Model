<?php 
require 'assests/includes/connection.inc.php';
// Sending Message from user
extract($_POST)
if (isset($_POST['senderName']) && ($_POST['senderEmal']) && ($_POST['senderMessage'])) {
	$query=@"INSERT INTO received_message(name,email,received_message,status) VALUES('$senderName','$senderEmal','$senderMessage',1)";
	$res=mysqli_query($con,$query) or die($con);
}

 ?>