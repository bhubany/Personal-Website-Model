<?php
require 'assests/includes/connection.inc.php';
require 'assests/includes/functions.inc.php';

// Getting Ip address
function getIPAddress() {  
    //whether ip is from the share internet  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
//whether ip is from the remote address  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  
$ip = getIPAddress();  

website_visited($con,$ip);


$qry_bg_image=@"SELECT background_image FROM extra_details";
$res=mysqli_query($con,$qry_bg_image) or die(error_reporting());
	$rows=mysqli_fetch_assoc($res);
$image="assests/images/".$rows['background_image']."?nocache".time(); 


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lorem Ipsum</title>
	<link rel="stylesheet" type="text/css" href="../css/myStyle.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.bundle.min.js"></script><!-- 
	<script src="assests/js/bootstrap.min.js"></script> -->

	<style type="text/css">
		#gallery-modal .modal-img{
			width: 100%;
		}
	</style>
	
</head>


<body style="background-image:url('<?php echo $image; ?>');">