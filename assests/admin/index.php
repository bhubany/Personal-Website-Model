<?php
if(!session_id()){
  session_start();
}

require '../includes/connection.inc.php';
require '../includes/functions.inc.php';
$now=date("Y-m-d H:i:s");
$errors=array();

//if already login
if (isset($_SESSION['admin_login_username'])) {
		header("Location:dashboard.php");
}	else{

if (isset($_POST['adminLogin'])) {
	$username=clean_values($con,$_POST['adminUsername']);
	$password=clean_values($con,$_POST['adminPassword']);

	// validation
	if (empty($username)) 
		 {
    	array_push($errors, "Username is required");
  		 }
  	if (empty($password))
  		{
    	array_push($errors, "Password is required");
		}

	// If there is no errors---
	if (count($errors) == 0) {
		$login_password = md5($password);
		$query="SELECT * FROM admin_info WHERE(email='$username' or username='$username') and password='$login_password' LIMIT 1";
		$rows=fetch($con,$query);
		// $count+=1;
		if ($rows!=False && $rows['status']==1) {
			$_SESSION['admin_login_username']=$username;
			header("Location:dashboard.php");
		}else{
			array_push($errors, "Username or password is incorrect");
		}
	}

}

// Query About Me
$query_about_me=@"SELECT * FROM about_user";
$about_me_res=fetch($con,$query_about_me);
 ?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lorem Ipsum</title>
	<link rel="stylesheet" type="text/css" href="../css/myStyle.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.bundle.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .input-icons i {
            position: absolute;
        }
          
        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }
          
        .icon {
            padding: 15px;
            min-width: 40px;
        }
          
        .input-field {
            width: 100%;
            padding: 10px;
            text-align: center;
        }
		.myForm {
			padding: 80px 0;
			border-radius: 20px;
			background-color: #fff;
			padding: 15px;
			box-shadow: 0 0 15px rgba(0,0,0,0.3);
			cursor: pointer;
		}
    </style>
	
</head>
<body style="background-image: none;">
	<div class="container-fluid">
		<div class="row" style="padding-top: 15vh;">
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-4"></div>
			<div class="col-xs-8 col-sm-8 col-md-6 col-lg-4">
				<div class="myForm">
				<div align="center">
					<img class="rounded-circle" src="../images/<?php echo($about_me_res['image']); ?>" style="height: 150px;width: 150px;">
					<h3>Lorem Ipsum</h3>
				</div><br><br>
				<div>
					<form action="" method="post">
						<?php include '../includes/errors.inc.php'; ?>
						<div class="mb-3 input-icons">
						  <i class="fa fa-user icon"></i>
						  <input type="text" class="form-control input-field" id="adminUsername" name="adminUsername" placeholder="Username">
						</div>
						<div class="mb-3 input-icons">
						  <i class="fa fa-key icon"></i>
						  <input type="password" class="form-control input-field" id="adminPassword" name="adminPassword" placeholder="Password">
						</div>
						<div class="mb-3" style="padding-top: 1vh;">
						  <input type="submit" class="form-control btn-primary" id="adminPassword" value="Login" name="adminLogin">
						</div>
						<span style="padding-left: 50px;">Developed by :<a href="https://www.facebook.com/bhuban.yadav.79"target="blank" style="text-decoration: none;color: blueviolet;"> Bhuban Yadav</a></span>
					</form>
				</div>
				</div>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-3 col-lg-4"></div>
		</div>
	</div>
<?php //require '../includes/footer.php'; ?>
<?php } ?>