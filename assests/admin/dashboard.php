<?php
if(!session_id()){
  session_start();
}
if (!isset($_SESSION['admin_login_username'])) {
		header("Location:index.php");
}

require '../includes/connection.inc.php';
require '../includes/functions.inc.php';
$now=date("Y-m-d H:i:s");
$errors=array();
$Success='';
$target_dir_icons = "../icons/";
$target_dir_images = "../images/";
$visibility='';
$upload_by=$_SESSION['admin_login_username'];

// updating background Image ---------.
if(isset($_POST['change_background_image'])){
	if (empty($_FILES['edit_bg_img']['name'])) {
		array_push($errors, "Please select image");
	}else{
		$target_file = basename($_FILES["edit_bg_img"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	}
		// Check if image file is a actual image or fake image
	$imageSize = $_FILES["edit_bg_img"]["size"];

	if($imageSize == false) {
		array_push($errors, "File is not an image.");

	}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		
		array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");

	}elseif (count($errors)==0) {
		$file="backgroundImage.".$imageFileType;
		move_uploaded_file($_FILES['edit_bg_img']['tmp_name'],$target_dir_images.$file);

		$update_bg_img_qry=@"UPDATE extra_details SET background_image='$file' LIMIT 1";
		$res=insert_update($con,$update_bg_img_qry);
		if ($res==1) {
			header("Refresh:3");
			$Success="Your Background Image has been changed sucessfully!";
		}	else{
			array_push($errors, "Error occurs while changing background images.<br> Try again Later!!!");
		}
	}
}


// changing Password
if (isset($_POST['change_password'])) {
	$old_password=clean_values($con,$_POST['change_old_password']);
	$new_password=clean_values($con,$_POST['change_new_password']);
	$conform_password=clean_values($con,$_POST['change_conform_password']);

	
	$old_password=md5($old_password);

	$check_old_pwd_qry=@"SELECT password FROM admin_info WHERE password='$old_password'";
	$check_res=fetch($con,$check_old_pwd_qry);
	if ($check_res==0) {
		array_push($errors, "Old Password Did not matched.");
	}

	if (empty($old_password)) {
		array_push($errors, "Old Password Is Required");
	}
	if (empty($new_password)) {
		array_push($errors, "New Password Is Required");
	}
	if (empty($conform_password)) {
		array_push($errors, "Conform Password Is Required");
	}elseif ($new_password!=$conform_password) {
		array_push($errors, "Both password did not Matched.");
	}else{
		$pass=$conform_password;
		// Password Validation
		$number = preg_match('@[0-9]@', $pass);
		$uppercase = preg_match('@[A-Z]@', $pass);
		$lowercase = preg_match('@[a-z]@', $pass);
		$specialChars = preg_match('@[^\w]@', $pass);
		if(strlen($pass) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
 		array_push($errors, "Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character.");
		} else {
			$new_pass=md5($pass);
			$user=$_SESSION['admin_login_username'];
			if (count($errors)==0) {
				$update_password=@"UPDATE admin_info SET password='$new_pass' WHERE username= '$user' LIMIT 1";
				$res=insert_update($con,$update_password);
				if ($res==1) {
					header("Refresh:3");
					$Success="Your Login Credintals has been updated sucessfully!";
				}	else{
					array_push($errors, "Error occurs while updating Login Credintals.<br> Try again Later!!!");
				}	
			}
		}
	}
}


//----------Removing CWAS Details
if (isset($_POST['yesRemoveCWASDetails'])) {
	$remove_cwas_id=clean_values($con,$_POST['remove_cwas_id']);

	$query=@"DELETE FROM other_details WHERE id=$remove_cwas_id LIMIT 1";
	$res=insert_update($con,$query);
	if ($res==1) {
		header("Refresh:3");
		$Success="Your CWAS details has been deleted sucessfully !!!";
	}else{
		array_push($errors, "Error occur while Removing CWAS details. <br> Try again later!!!");
	}
}

//----------Removing Received Message
if (isset($_POST['yesRemoveReceivedMessage'])) {
	$remove_message_id=clean_values($con,$_POST['remove_message_id']);

	$query=@"DELETE FROM received_message WHERE id=$remove_message_id LIMIT 1";
	$res=insert_update($con,$query);
	if ($res==1) {
		header("Refresh:3");
		$Success="Your received message has been deleted sucessfully !!!";
	}else{
		array_push($errors, "Error occur while Removing Message. <br> Try again later!!!");
	}
}


//---------Sending Message/Reply-----
if (isset($_POST['Send_message'])) {
	$receiver_email=clean_values($con,$_POST['receiver_email']);
	$receiver_Subject=clean_values($con,$_POST['receiver_subject']);
	$receiver_message=clean_values($con,$_POST['receiver_message']);

//-----Validation
	if (empty($receiver_Subject)) {
    	array_push($errors, "Subject is required");
  	}

 	if (empty($receiver_email)) {
			array_push($errors, "Email is required");
	} else {
	    $email = $receiver_email;
	    // check if e-mail address is well-formed
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	      $emailErr = "Invalid email format";

		}elseif (strlen($email)>40) {
	    	array_push($errors,"Email length must be below 40 characters");
	    }
	}
	if (empty($receiver_message)) {
			array_push($errors, "Message is required");
	}

	if (count($errors)==0) {
		$query_Send_message=@"INSERT INTO sent_message (subject,email,sent_message) VALUES('$receiver_subject','$receiver_email','$receiver_message') LIMIT 1";
		$Send_message_res=insert_update($con,$query_Send_message);
		if ($Send_message_res==1) {
			header("Refresh:3");
			$Success="Your Message has been sent sucessfully";
		}else{
			array_push($errors, "Error ocuurs try again later");
		}
	}

}


// Updating Contact Details----
if (isset($_POST['update_contact_details'])) {
	$edit_contact_details_col=clean_values($con,$_POST['edit_contact_details_col']);
	$edit_contact_details_content=clean_values($con,$_POST['edit_contact_details_content']);
	if (count($errors)==0) {
		$update_qry=@"UPDATE about_user SET $edit_contact_details_col='$edit_contact_details_content' LIMIT 1";
		$update_res=insert_update($con,$update_qry);
		if ($update_res==1) {
			header("Refresh:3");
			$Success="Your Details has been updated sucessfully";
		}else{
			array_push($errors, "Error ocuurs try again later");
		}
	}

}



// updating User Images---------.
if(isset($_POST['update_user_image'])){
	$target_file = basename($_FILES["user_image"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
	$imageSize = $_FILES["user_image"]["size"];

	if($imageSize == false) {
		array_push($errors, "File is not an image.");

	}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		
		array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");

	}elseif (count($errors)==0) {
		$file="about_user_image.".$imageFileType;
		move_uploaded_file($_FILES['user_image']['tmp_name'],$target_dir_images.$file);

		$update_about_user_qry=@"UPDATE about_user SET image='$file' LIMIT 1";
		$res=insert_update($con,$update_about_user_qry);
		if ($res==1) {
			header("Refresh:3");
			$Success="Your About Image has been changed sucessfully!";
		}	else{
			array_push($errors, "Error occurs while changing images.<br> Try again Later!!!");
		}
	}
}

// -----------Updating Other Details------------
if (isset($_POST['update_other_details'])) {
	$other_details_id=clean_values($con,$_POST['other_details_col']);
	$other_details_header=clean_values($con,$_POST['other_details_header']);
	$other_details_content=clean_values($con,$_POST['other_details_content']);
	$other_details_visibility=clean_values($con,$_POST['other_details_visibility']);
	if (strlen($other_details_visibility)!=4) {
		array_push($errors, "Visibility must be of 4 characters");
	}else{
		$cv_visibility=number_format($other_details_visibility['0']);
		$work_visibility=number_format($other_details_visibility['1']);
		$about_visibility=number_format($other_details_visibility['2']);
		$skills_visibility=number_format($other_details_visibility['3']);
	}

	if (count($errors)==0) {

		$update_other_details_qry=@"UPDATE other_details SET header='$other_details_header' ,content='$other_details_content',cv=$cv_visibility,work=$work_visibility,about=$about_visibility,skills=$skills_visibility WHERE id= $other_details_id LIMIT 1";
		$res=insert_update($con,$update_other_details_qry);
		if ($res==1) {
			header("Refresh:3");
			$Success="Your details has been updated sucessfully!";
		}	else{
			array_push($errors, "Error occurs while updating details<br> Try again Later!!!");
		}	
	}
}

//--------Adding Other Details---------

if (isset($_POST['add_other_details'])) {
	$add_other_details_header=clean_values($con,$_POST['add_other_details_header']);
	$add_other_details_content=clean_values($con,$_POST['add_other_details_content']);
	if (isset($_POST['add_show_in_cv'])) {
		$cv_visibility=1;
	}else{
		$cv_visibility=0;
	}
	if (isset($_POST['add_show_in_work'])) {
		$work_visibility=1;
	}else{
		$work_visibility=0;
	}
	if (isset($_POST['add_show_in_about'])) {
		$about_visibility=1;
	}else{
		$about_visibility=0;
	}
	if (isset($_POST['add_show_in_skills'])) {
		$skills_visibility=1;
	}else{
		$skills_visibility=0;
	}


	if (empty($_FILES['add_other_details_file']['name']['0'])) {
		if (count($errors)==0) {

			$add_other_details_qry=@"INSERT INTO other_details (header ,content,cv,work,about,skills,upload_by) VALUES('$add_other_details_header','$add_other_details_content',$cv_visibility,$work_visibility,$about_visibility,$skills_visibility,'$upload_by') LIMIT 1";
			$res=insert_update($con,$add_other_details_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your details has been Added sucessfully!";
			}	else{
				array_push($errors, "Error occurs while adding details<br> Try again Later!!!");
			}	
		}
	}else{
		$content_id=date("Ymdhis");
		$ig=0;
		foreach ($_FILES['add_other_details_file']['name'] as $key=>$val){
			$target_file = basename($_FILES["add_other_details_file"]["name"][$key]);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				// Check if image file is a actual image or fake image
			$imageSize = $_FILES["add_other_details_file"]["size"][$key];

			if($imageSize == false) {
				array_push($errors, "File is not an image.");

			}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
					array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");

			}elseif (count($errors)==0) {
				$random = date("Ymdhis");
				$file=$random.$ig.".".$imageFileType;
				$ig+=1;
				move_uploaded_file($_FILES['add_other_details_file']['tmp_name'][$key],$target_dir_images.$file);

				$query=@"INSERT INTO image_collections (image_name,category,content_id) VALUES('$file','other',$content_id)";
				$res=insert_update($con,$query);
			}
		}
		$add_other_details_qry=@"INSERT INTO other_details (header ,content,images,cv,work,about,skills,upload_by) VALUES('$add_other_details_header','$add_other_details_content','$category',$cv_visibility,$work_visibility,$about_visibility,$skills_visibility,'$upload_by') LIMIT 1";
				$res=insert_update($con,$add_other_details_qry);
				if ($res==1) {
					header("Refresh:3");
					$Success="Your details has been Added sucessfully!";
				}	else{
					array_push($errors, "Error occurs while Adding details<br> Try again Later!!!");
				}	
	}
}


// -------Removing Social Media Contents---------
if (isset($_POST['yesRemoveSocialMediaContent'])) {
	$content_id=clean_values($con,$_POST['remove_content_id']);
	$query=@"DELETE FROM social_media WHERE id=$content_id";
	$res=insert_update($con,$query);
	if ($res==1) {
		header("Refresh:3");
		$Success="Your social media content has been deleted sucessfully !!!";
	}else{
		array_push($errors, "Error occur while Removing Content. <br> Try again later!!!");
	}

}

//--------Updating Social Media Details---------

if (isset($_POST['update_social_media'])) {
	$social_media_name=clean_values($con,$_POST['social_media_name']);
	$social_media_link=clean_values($con,$_POST['social_media_link']);
	$social_media_id=clean_values($con,$_POST['social_media_id']);

	if (empty($_FILES['social_media_icon']['name'])) {
		if (count($errors)==0) {

			$update_social_media_qry=@"UPDATE social_media SET name='$social_media_name' ,link='$social_media_link' WHERE id= $social_media_id LIMIT 1";
			$res=insert_update($con,$update_social_media_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating details<br> Try again Later!!!");
			}	
		}
	}else{
		$target_file = basename($_FILES["social_media_icon"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
		$imageSize = $_FILES["social_media_icon"]["size"];

		if($imageSize == false) {
			array_push($errors, "File is not an image.");

		}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		}elseif (count($errors)==0) {
			$random = strtolower($social_media_name);
			$file=$random.".".$imageFileType;
			move_uploaded_file($_FILES['social_media_icon']['tmp_name'],$target_dir_icons.$file);

			$update_social_media_qry=@"UPDATE social_media SET name='$social_media_name',link='$social_media_link',icon='$file' WHERE id=$social_media_id LIMIT 1";
			$res=insert_update($con,$update_social_media_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating details<br> Try again Later!!!");
			}	
		}
	}
}



//--------Adding Social Media Details---------

if (isset($_POST['add_social_media'])) {
	$add_social_media_name=clean_values($con,$_POST['add_social_media_name']);
	$add_social_media_link=clean_values($con,$_POST['add_social_media_link']);

	if (empty($_FILES['add_social_media_icon']['name'])) {
		if (count($errors)==0) {

			$add_social_media_qry=@"INSERT INTO social_media (name ,link) VALUES('$add_social_media_name','$add_social_media_link') LIMIT 1";
			$res=insert_update($con,$add_social_media_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your details has been Added sucessfully!";
			}	else{
				array_push($errors, "Error occurs while adding details<br> Try again Later!!!");
			}	
		}
	}else{
		$target_file = basename($_FILES["add_social_media_icon"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
		$imageSize = $_FILES["add_social_media_icon"]["size"];

		if($imageSize == false) {
			array_push($errors, "File is not an image.");

		}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		}elseif (count($errors)==0) {
			$random = strtolower($social_media_name);
			$file=$random.".".$imageFileType;
			move_uploaded_file($_FILES['add_social_media_icon']['tmp_name'],$target_dir_icons.$file);

			$add_social_media_qry=@"INSERT INTO social_media (name ,link,icon) VALUES('$add_social_media_name','$add_social_media_link','$file') LIMIT 1";
			$res=insert_update($con,$add_social_media_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your details has been Added sucessfully!";
			}	else{
				array_push($errors, "Error occurs while Adding details<br> Try again Later!!!");
			}	
		}
	}
}

// Website Visitor
// Current date and time
$datetime = date("Y-m-d H:i:s");

// Convert datetime to Unix timestamp
$timestamp = strtotime($datetime);

// Subtract time from datetime

$monthly_check = $timestamp - (30*7*24 * 60 * 60);
$weekly_check = $timestamp - (7*24 * 60 * 60);
$daily_check = $timestamp - (1*24 * 60 * 60);

// Date and time after subtraction
$datetime_monthly = date("Y-m-d H:i:s", $monthly_check);
$datetime_weekly = date("Y-m-d H:i:s", $weekly_check);
$datetime_daily = date("Y-m-d H:i:s", $daily_check);

// initializing all counts
$total_count=$monthly_count=$weekly_count=$today_count=0;

// For Total
$query_website_visitor=@"SELECT * FROM page_visitor ORDER BY id DESC";
$web_visitor_res=mysqli_query($con,$query_website_visitor) or die(error_reporting());
$count=mysqli_num_rows($web_visitor_res);


// MOnthly
$query_monthly_website_visitor=@"SELECT * FROM page_visitor WHERE visited_date >'$datetime_monthly' ORDER BY id DESC";
$web_visitor_monthly_res=mysqli_query($con,$query_monthly_website_visitor) or die(error_reporting());
$monthly_count=mysqli_num_rows($web_visitor_monthly_res);

// Weekly
$query_weekly_website_visitor=@"SELECT * FROM page_visitor WHERE visited_date> '$datetime_weekly' ORDER BY id DESC";
$web_visitor_weekly_res=mysqli_query($con,$query_weekly_website_visitor) or die(error_reporting());
$weekly_count=mysqli_num_rows($web_visitor_weekly_res);

// Daily
$query_daily_website_visitor=@"SELECT * FROM page_visitor WHERE visited_date> '$datetime_daily' ORDER BY id DESC";
$web_visitor_daily_res=mysqli_query($con,$query_daily_website_visitor) or die(error_reporting());
$daily_count=mysqli_num_rows($web_visitor_daily_res);

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
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/myStyle.css">
	<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		.webViews div{
			padding: 15px;
			width: 100%;
			box-shadow: 0 0 25px rgba(0,0,0,0.3);
			border-radius: 10px;
		}
		.tableViews{
			padding: 15px;
			width: 100%;
			box-shadow: 0 0 25px rgba(0,0,0,0.3);
			border-radius: 10px;
		}
		.myDetailsBox{
			padding-top: 10px;
			height: 450px;
			overflow: hidden;
			overflow-y: scroll;
		}
		#gallery-modal .modal-img{
			width: 100%;
		}
		.gallery{
			background-color: #ffffff;
			padding: 15px;
			width: 200px;
      		height: 200px;
			box-shadow: 0 0 15px rgba(0,0,0,0.3);
			cursor: pointer;
		}
		
	</style>
</head>
<body style="background-image: none;">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<button class="navbar-toggler type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
			<div class="collapse navbar-collapse" id="navbarMenu">
				<ul class="navbar-nav mr-auto">		
					<li><a href="index.php" class="nav-link">Dashboard</a></li>
					<li><a href="myBlog.php" class="nav-link">Blog & Gallery</a></li>
				</ul>
			</div>

			<a href="index.php" class="navbar-brand"><?php echo $about_me_res['name']; ?></a>
			<div class="dropdown">
			  <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><img class="rounded-circle" src="../images/<?php echo($about_me_res['image'].'?nocache='.time()); ?>" style="width: 40px;height: 40px;">
			    Profile
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			    <li><a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#modalForChangePassword">Change Password</a></li>
			    <li><a class="dropdown-item" href="../includes/logout.php">Logout</a></li>
			  </ul>
			</div>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1 pt-5 text-center <?php if($Success=='' and count($errors)==0){
			echo("hideContent");
		} ?>">
    		<?php include '../includes/errors.inc.php'; ?>
    		<?php if ($Success!="") {?>
    		<div class="alert alert-success text-center" role="alert"><?php echo $Success; ?></div>
    		<?php } ?><br><br>
	    </div>
		<div class="row gy-3 row-cols-1 row-cols-sm-2 row-cols-md-4 py-4 px-4">
			<div class="col webViews text-white">
				<div align="center" class="bg-success">
					<h1><i class="fa fa-eye icon"></i><br>Latest Visitors</h1>
					<h6><?php echo($daily_count); ?></h6>
					<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForDailyVisitor">View full</button>
				</div>
			</div>
			<div class="col webViews text-white">
				<div align="center" class="bg-info">
					<h1><i class="fa fa-eye icon"></i><br>This Week</h1>
					<h6><?php echo($weekly_count); ?></h6>
					<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForWeeklyVisitor">View full</button>
				</div>
			</div>
			<div class="col webViews">
				<div align="center" class="bg-secondary text-white">
					<h1><i class="fa fa-eye icon"></i><br>This Month</h1>
					<h6 class="text-white"><?php echo($monthly_count); ?></h6>
					<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForMonthlyVisitor">View full</button>
				</div>
			</div>
			<div class="col webViews">
				<div align="center" class="bg-danger">
					<h1 class="text-white"><i class="fa fa-eye icon"></i><br>Total Views</h1>
					<h6 class="text-white"><?php echo($count); ?></h6>
					<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForTotalVisitor">View full</button>
				</div>
			</div>
		</div>
<!-- -------ABOUT ME---------- -->
		<div class="row gy-4 row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 py-3 px-4">
			<div class="col border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-address-card">&nbsp;</i>Contact Details</h1>
					<div class="text-center">
						<img src="../images/about_user_image.jpg?nocache=<?php echo(time());?>" style="height: 150px;width: 150px;cursor: pointer;" class="gallery-item rounded-circle" alt="About User Image">
					</div>
					<form class="row  py-4 px-5" method="post" enctype="multipart/form-data">
						<div class="col-auto">
						  <input class="form-control" type="file" id="" name="user_image">
						</div>
						<div class="col-auto">
						  <button class="btn btn-primary mb-3" type="submit" id="" name="update_user_image">Update</button>
						</div>
					</form>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info-circle icon"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>
					      <th scope="col">Action</th>		      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					    <tr>
					      <th scope="row"><i class="fa fa-user icon"></i></th>
					      <td id="contactDetailsHeader">Name</td>
					      <td id="contactDetailsContent"><?php echo($about_me_res['name']); ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("name"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-user icon"></i></th>
					      <td id="contactDetailsHeader">Name Title</td>
					      <td id="contactDetailsContent"><?php echo($about_me_res['name_title']); ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("name_title"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-user icon"></i></th>
					      <td id="contactDetailsHeader">Greet</td>
					    <td id="contactDetailsContent"><?php echo($about_me_res['greeting_message']); ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("greeting_message"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-user icon"></i></th>
					      <td id="contactDetailsHeader">Quotes</td>
					    <td id="contactDetailsContent"><?php echo($about_me_res['quotes']); ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("quotes"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-user icon"></i></th>
					      <td id="contactDetailsHeader">DOB</td>
					   	  <td id="contactDetailsContent"><?php echo($about_me_res['dob']); ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("dob"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-phone-square icon"></i></th>
					      <td id="contactDetailsHeader">Phone (Primary)</td>
					      <td id="contactDetailsContent"><?php echo($about_me_res['primary_phone']); ?></td>
					     <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("primary_phone"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-phone-square icon"></i></th>
					      <td id="contactDetailsHeader">Phone (Secondary)</td>
					      <td id="contactDetailsContent"><?php echo($about_me_res['secondary_phone']); ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("secondary_phone"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-map-marker icon"></i></th>
					      <td id="contactDetailsHeader">Address</td>
					      <td id="contactDetailsContent"><?php echo($about_me_res['address']); ?></td>
					     <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("address"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-envelope icon"></i></th>
					      <td id="contactDetailsHeader">Email </td>
					      <td id="contactDetailsContent"><?php echo($about_me_res['email']); ?></td>
					     <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("email"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-globe icon"></i></th>
					      <td id="contactDetailsHeader">Website</td>
					      <td id="contactDetailsContent"><?php echo($about_me_res['website']); ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editContactDetails" id="eContactDetails" value="<?php echo("website"); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					  </tbody>
					</table>
				</div>
			</div>


			<!-- ----------------FOR Message----------- -->
			<?php 	
				$query_received_message=@"SELECT * FROM received_message";
				$received_message_res=mysqli_query($con,$query_received_message) or die(error_reporting(0));
				$total_msg_count=mysqli_num_rows($received_message_res);

				$query_unread_message=@"SELECT * FROM received_message WHERE status='0' ";
				$unread_message_res=mysqli_query($con,$query_unread_message) or die(error_reporting(0));
				$unread_msg_count=mysqli_num_rows($unread_message_res);
			 ?>
			<div class="col border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-envelope">&nbsp;</i>Message</h1>
					<div align="center">						
						<a type="btn" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalForCreatingMsg" style="padding-right: 25px; cursor: pointer;" title="create message"><i class="fa fa-plus"></i></a>
						<span class="btn btn-outline-secondary">Total (<?php echo($total_msg_count);?>)</span>
						<span class="btn btn-outline-info">unread (<?php echo($unread_msg_count); ?>)</span>
						<a style="cursor: pointer;" class="btn btn-outline-danger" title="delete"><i class="fa fa-trash"></i></a>
					</div>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-check-square-o"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>

					      <th></th>
					      <th colspan="2">Action</th>			      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					  	<?php while ($received_message_row=mysqli_fetch_assoc($received_message_res)) { ?>
					    <tr>
					      <th scope="row"><input type="checkbox" name=""></th>
					      <td  id="viewSenderName"><?php echo($received_message_row['name']); ?></td>
					      <td class="hideContent"  id="viewSenderEmail"><?php echo($received_message_row['email']); ?></td>
					      <td class="hideContent"  id="viewSenderDate"><?php echo($received_message_row['received_date']); ?></td>
					      <td class="text-secondary" id="viewSenderMessage"><?php echo($received_message_row['received_message']); ?>
					      </td>
					      <td><button type="button" class="btn btn-outline-success viewUserMessage" id="viewUserMessage">
							  VIEW
							</button></td>
					      <td ><button class="btn btn-outline-danger removeMessage" value="<?php echo($received_message_row['id']); ?>" id="ForRemoveMessage">Remove</button></td>
					    </tr>
							<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>

			<!-- ----FOR Different Social Media Links----------- -->

			<?php 	
				$query_social_media=@"SELECT * FROM social_media WHERE status=1";
				$social_media_res=mysqli_query($con,$query_social_media) or die(error_reporting(0));
			 ?>

			<div class="col border-primary myDetailsBox" id="socialMediaDetails">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-globe text-info">&nbsp;</i>Social Media</h1>
					<div align="center">						
						<a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForAddSocialMedia" style="padding-right: 25px; cursor: pointer;" title="Add social media"><i class="fa fa-plus">&nbsp; Social Media</i></a>
					</div>
					<table class="table table-hover overflow-scroll">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info-circle"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>
					      <th></th>
					      <th scope="col">Action</th>			      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					  	<?php while ($social_media_rows=mysqli_fetch_assoc($social_media_res)) {?>
					    <tr>
					      <th scope="row"><i class="fa fa-<?php echo(strtolower($social_media_rows['name'])); ?>"></i></th>
					      <td ><span id="socialMediaName"><?php echo $social_media_rows['name']; ?></span></td>
					      <td><span id="socialmediaLink"><?php echo $social_media_rows['link']; ?></span></td>
					      <td class="hideContent"><span id="socialMediaICon"><?php echo$social_media_rows['icon']; ; ?></span></td>
					      <td>
					      	<button type="button" class="btn btn-success editSocialMedia" id="eSocialMedia" value="<?php echo($social_media_rows['id']); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>

					      <td >
					      	<button type="button" class="btn btn-danger removeContent" id="ForRemoveContent" value="<?php echo($social_media_rows['id']); ?>"><span class="glyphicon glyphicon-edit">Remove</span></button>
					      </td>
					    </tr>
					<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>

			<!-- -----FOR OTHER EXTRA DETAILS---------- -->
			<?php 	
				$query_extra_details=@"SELECT * FROM extra_details";
				$extra_details_res=mysqli_query($con,$query_extra_details) or die(error_reporting());
			 ?>
			<div class="col border-primary myDetailsBox" id="socialMediaDetails">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-cogs text-info">&nbsp;</i>OTHER SETTINGS</h1>
					<table class="table table-hover overflow-scroll">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info-circle">&nbsp;SN</i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>
					      <th scope="col">Action</th>			      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					  	<?php $i=1; while ($extra_details_rows=mysqli_fetch_assoc($extra_details_res)) {?>
					    <tr>
					      <th scope="row"><?php echo($i); $i+=1; ?></i></th>
					      <td ><span id="socialMediaName"><?php echo "Background Image" ?></span></td>
					      <td><img class="gallery gallery-item" src="../images/<?php echo($extra_details_rows['background_image']."?nocache=".time());?>"></td>
					      <td id="backgroundImageName" class="hideContent"><?php echo $extra_details_rows['background_image']; ?></td>
					      <td>
					      	<button type="button" class="btn btn-success editBackgroundImage" id="ebackgroundImage" value="<?php echo($extra_details_rows['id']); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					    </tr>
					<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- -----------For CWAS DEtails--------------- -->
			<?php 	
				$query_other_details=@"SELECT * FROM other_details";
				$other_details_res=mysqli_query($con,$query_other_details) or die(error_reporting(0));
			 ?>


			<div class="col border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-info-circle">&nbsp;</i>CV/WORK/ABOUT/SKILLS Details</h1>
					<div align="center">						
						<a type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForAddOtherDetails" style="padding-right: 25px; cursor: pointer;" title="Add other details"><i class="fa fa-plus">&nbsp;CWAS Details</i></a>
					</div><br>
					<div align="center" class="alert-danger py-3 rounded">
						<h5> Note: C=CV; W=work; A=about; S=skills; 1: Active And 0: Disable/Inactive </h5>
					</div>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info-circle"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>

					      <th>CWAS</th>
					      <th></th>
					      <th colspan="2">Action</th>			      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					    <tr>
					      <th scope="row"><i class="fa fa-user"></i></th>
					      <td>Name</td>
					      <td><?php echo $about_me_res['name']; ?></td>
					      <td></td>
					      <td></td>
					      <td></td>
					    </tr>
					    <?php while ($other_details_row=mysqli_fetch_assoc($other_details_res)) { ?>
					    <tr>
					      <th scope="row"><i class="fa fa-info"></i></th>
					      <td id="otherDetailsHeader"><?php echo $other_details_row['header']; ?>	      	
					      </td>
					      <td id="otherDetailsContent"><?php echo $other_details_row['content']; ?>			      	
					      </td>
							
							<?php $visibility= $other_details_row['cv'].$other_details_row['work'].$other_details_row['about'].$other_details_row['skills']; ?>

					      <td id="otherDetailsVisibility"><?php echo $visibility; ?></td>
					     <td>
					      	<button type="button" class="btn btn-primary editOtherDetails" id="eOtherDetails" value="<?php echo($other_details_row['id']); ?>"><span class="glyphicon glyphicon-edit">EDIT</span></button>
					      </td>
					      <td >
					      	<a href="galleryImages.php?album_id=<?php echo($other_details_row['images']."&& album_name=".$other_details_row['header']); ?>"><button class="fa fa-eye btn btn-success" title="view Images">&nbsp;Images</button></a>
					      </td>
					      <td >
					      	<button class="btn btn-danger removeCWASDetails" id="removeCWASDetails" value="<?php echo($other_details_row['id']); ?>">Remove</button>
					      </td>
					    </tr>
					<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>
	</div>







	<!-- --------STARTING OF MODALS---------- -->

<!-- --------------MODAL FOR VIEWING WEBSITE VISITOR----- -->
<!-- ------------Daily-------- -->
	<div class="modal fade" id="modalForDailyVisitor" tabindex="-1">
	  <div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Today Visitors(<?php echo($today_count); ?>) <span id="editContactDetailsHeader"></span></h5>
	        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <table class="table table-hover">
			  <thead>
			    <tr>
			      <th scope="col">SN</th>
			      <th scope="col">IP Address</th>
			      <th>Country</th>
			      <th scope="col">City</th>
			      <th scope="col">Region</th>
			      <th scope="col">Zipcode</th>
			      <th scope="col">latitude</th>
			      <th scope="col">longitude</th>
			      <th>ISP Provider</th>
			      <th>Content</th>
			      <th>Visited At</th>
			      <th>Action</th>	      
			    </tr>
			  </thead>
			  <tbody class="overflow-scroll">
			    <?php $i=1; while ($web_visitor_daily_row=mysqli_fetch_assoc($web_visitor_daily_res)) { ?>
			    <tr>
			      <th scope="row"><?php echo($i); $i+=1; ?></th>
			      <td><?php echo $web_visitor_daily_row['ip']; ?>  	
			      </td>
			     <td ><?php echo($web_visitor_daily_row['country']); ?>
			      </td>
			      <td ><?php echo($web_visitor_daily_row['city']); ?>
			      </td>
			      <td ><?php echo($web_visitor_daily_row['region']); ?>
			      </td>
			      <td ><?php echo($web_visitor_daily_row['zipcode']); ?>
			      </td>
			      <td ><?php echo($web_visitor_daily_row['latitude']); ?>
			      </td>
			      <td ><?php echo($web_visitor_daily_row['longitude']); ?></td>
			      <td><?php echo($web_visitor_daily_row['isp']); ?></td>
			      <td><?php if($web_visitor_daily_row['content']=="blog"){ ?><a class="btn btn-success" href="viewBlog.php?blog_id=<?php echo($web_visitor_daily_row['content_id']); ?>"><span>Blog</span></a><?php }else{ ?><span><?php echo($web_visitor_daily_row['content']); ?></span><?php } ?></td>
			     <td>
			      	<?php echo($web_visitor_daily_row['visited_date']); ?>
			     </td>
			     <td><a class="btn btn-success" href="https://whatismyipaddress.com/ip/<?php echo($web_visitor_daily_row['ip']); ?>" target="blank">Details</a></td>
			    </tr>
			   <?php } ?>
			  </tbody>
			</table>
	      </div>
	      <div class="modal-footer">
		  	<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
		  </div>
	    </div>
	  </div>
	</div>

	<!-- ------------Weekly Visitor-------- -->
	<div class="modal fade" id="modalForWeeklyVisitor" tabindex="-1">
	  <div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">This Week Visitors(<?php echo($weekly_count); ?>) <span id="editContactDetailsHeader"></span></h5>
	        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <table class="table table-hover">
			  <thead>
			    <tr>
			      <th scope="col">SN</th>
			      <th scope="col">IP Address</th>
			      <th>Country</th>
			      <th scope="col">City</th>
			      <th scope="col">Region</th>
			      <th scope="col">Zipcode</th>
			      <th scope="col">latitude</th>
			      <th scope="col">longitude</th>
			      <th>ISP Provider</th>
			      <th>Content</th>
			      <th>Visited At</th>
			      <th>Action</th>	      
			    </tr>
			  </thead>
			  <tbody class="overflow-scroll">
			    <?php $i=1; while ($web_visitor_weekly_row=mysqli_fetch_assoc($web_visitor_weekly_res)) { ?>
			    <tr>
			      <th scope="row"><?php echo($i); $i+=1; ?></th>
			      <td><?php echo $web_visitor_weekly_row['ip']; ?>
			      </td>
			      <td ><?php echo($web_visitor_weekly_row['country']); ?>
			      </td>
			      <td ><?php echo($web_visitor_weekly_row['city']); ?>
			      </td>
			      <td ><?php echo($web_visitor_weekly_row['region']); ?>
			      </td>
			      <td ><?php echo($web_visitor_weekly_row['zipcode']); ?>
			      </td>
			      <td ><?php echo($web_visitor_weekly_row['latitude']); ?>
			      </td>
			      <td ><?php echo($web_visitor_weekly_row['longitude']); ?></td>
			      <td><?php echo($web_visitor_weekly_row['isp']); ?></td>
			      <td><?php if($web_visitor_weekly_row['content']=="blog"){ ?><a class="btn btn-success" href="viewBlog.php?blog_id=<?php echo($web_visitor_weekly_row['content_id']); ?>"><span>Blog</span></a><?php }else{ ?><span><?php echo($web_visitor_weekly_row['content']); ?></span><?php } ?></td>
			     <td>
			      	<?php echo($web_visitor_weekly_row['visited_date']); ?>
			     </td>
			     <td><a class="btn btn-success" href="https://whatismyipaddress.com/ip/<?php echo($web_visitor_weekly_row['ip']); ?>" target="blank">Details</a></td>
			    </tr>
			   <?php } ?>
			  </tbody>
			</table>
	      </div>
	      <div class="modal-footer">
		  	<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
		  </div>
	    </div>
	  </div>
	</div>

		<!-- ------------Monthly Visitor-------- -->
	<div class="modal fade" id="modalForMonthlyVisitor" tabindex="-1">
	  <div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">This Month Visitors(<?php echo($weekly_count); ?>) <span id="editContactDetailsHeader"></span></h5>
	        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <table class="table table-hover">
			  <thead>
			    <tr>
			      <th scope="col">SN</th>
			      <th scope="col">IP Address</th>
			      <th>Country</th>
			      <th scope="col">City</th>
			      <th scope="col">Region</th>
			      <th scope="col">Zipcode</th>
			      <th scope="col">latitude</th>
			      <th scope="col">longitude</th>
			      <th>ISP Provider</th>
			      <th>Content</th>
			      <th>Visited At</th>
			      <th>Action</th>		      
			    </tr>
			  </thead>
			  <tbody class="overflow-scroll">
			    <?php $i=1; while ($web_visitor_monthly_row=mysqli_fetch_assoc($web_visitor_monthly_res)) { ?>
			    <tr>
			      <th scope="row"><?php echo($i); $i+=1; ?></th>
			      <td><?php echo $web_visitor_monthly_row['ip']; ?>
			      </td>
			      <td ><?php echo($web_visitor_monthly_row['country']); ?>
			      </td>
			      <td ><?php echo($web_visitor_monthly_row['city']); ?>
			      </td>
			      <td ><?php echo($web_visitor_monthly_row['region']); ?>
			      </td>
			      <td ><?php echo($web_visitor_monthly_row['zipcode']); ?>
			      </td>
			      <td ><?php echo($web_visitor_monthly_row['latitude']); ?>
			      </td>
			      <td ><?php echo($web_visitor_monthly_row['longitude']); ?></td>
			      <td><?php echo($web_visitor_monthly_row['isp']); ?></td>
			      <td><?php if($web_visitor_monthly_row['content']=="blog"){ ?><a class="btn btn-success" href="viewBlog.php?blog_id=<?php echo($web_visitor_monthly_row['content_id']); ?>"><span>Blog</span></a><?php }else{ ?><span><?php echo($web_visitor_monthly_row['content']); ?></span><?php } ?></td>
			     <td>
			      	<?php echo($web_visitor_monthly_row['visited_date']); ?>
			     </td>
			     <td><a class="btn btn-success" href="https://whatismyipaddress.com/ip/<?php echo($web_visitor_monthly_row['ip']); ?>" target="blank">Details</a></td>
			    </tr>
			   <?php } ?>
			  </tbody>
			</table>
	      </div>
	      <div class="modal-footer">
		  	<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
		  </div>
	    </div>
	  </div>
	</div>

		<!-- ------------Total Visitor-------- -->
	<div class="modal fade" id="modalForTotalVisitor" tabindex="-1">
	  <div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Total Visitors(<?php echo($total_count); ?>) <span id="editContactDetailsHeader"></span></h5>
	        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <table class="table table-hover">
			  <thead>
			    <tr>
			      <th scope="col">SN</th>
			      <th scope="col">IP Address</th>
			      <th>Country</th>
			      <th scope="col">City</th>
			      <th scope="col">Region</th>
			      <th scope="col">Zipcode</th>
			      <th scope="col">latitude</th>
			      <th scope="col">longitude</th>
			      <th>ISP Provider</th>
			      <th>Content</th>
			      <th>Visited At</th>
			      <th>Action</th>	      
			    </tr>
			  </thead>
			  <tbody class="overflow-scroll">
			    <?php $i=1; while ($web_visito_row=mysqli_fetch_assoc($web_visitor_res)) { ?>
			    <tr>
			      <th scope="row"><?php echo($i); $i+=1; ?></th>
			      <td><?php echo $web_visito_row['ip']; ?>	      	
			      </td>
			      <td ><?php echo($web_visito_row['country']); ?>
			      </td>
			      <td ><?php echo($web_visito_row['city']); ?>
			      </td>
			      <td ><?php echo($web_visito_row['region']); ?>
			      </td>
			      <td ><?php echo($web_visito_row['zipcode']); ?>
			      </td>
			      <td ><?php echo($web_visito_row['latitude']); ?>
			      </td>
			      <td ><?php echo($web_visito_row['longitude']); ?></td>
			      <td><?php echo($web_visito_row['isp']); ?></td>
			      <td><?php if($web_visito_row['content']=="blog"){ ?><a class="btn btn-success" href="viewBlog.php?blog_id=<?php echo($web_visito_row['content_id']); ?>"><span>Blog</span></a><?php }else{ ?><span><?php echo($web_visito_row['content']); ?></span><?php } ?></td>
			      <td>
			      	<?php echo($web_visito_row['visited_date']); ?>
			     </td>
			     <td><a class="btn btn-success" href="https://whatismyipaddress.com/ip/<?php echo($web_visito_row['ip']); ?>" target="blank">Details</a></td>
			    </tr>
			   <?php } ?>
			  </tbody>
			</table>
	      </div>
	      <div class="modal-footer">
		  	<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
		  </div>
	    </div>
	  </div>
	</div>

<!-- ------------MODAL FOR Editing Contact Details-------- -->
	<div class="modal fade" id="modalForEditContact" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your <span id="editContactDetailsHeader"></span></h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <form class="form" method="post">
	        	<input type="hidden" id="editContactDetailsCol" name="edit_contact_details_col">
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="editContactDetailsContent" placeholder="Enter details" name="edit_contact_details_content">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" class="btn btn-primary" name="update_contact_details">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>
	
	<!-- ------------MODAL FOR Adding Other Details-------- -->
	<div class="modal fade" id="modalForAddOtherDetails" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        	<h5 class="modal-title">Enter Your Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <!-- <p class="text-info">Enter Visibility in CWAS (i.e 1101) pattern.</p> -->
	        <form class="form" method="post" enctype="multipart/form-data">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" checked="on" value="1" name="add_show_in_skills">
				  <label class="form-check-label" for="forCheckBox">
				    Show in Skills
				  </label>
				</div>
				<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" checked="on" value="1" name="add_show_in_about">
				  <label class="form-check-label" for="forCheckBox">
				    Show in about
				  </label>
				</div>
				<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" checked="on" value="1" name="add_show_in_work">
				  <label class="form-check-label" for="forCheckBox">
				    Show in Work
				  </label>
				</div>
				<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" checked="on" value="1" name="add_show_in_cv">
				  <label class="form-check-label" for="forCheckBox">
				    Show in CV
				  </label>
				</div>
	        	<div class="col-auto" align="center">
				  <input class="form-control" type="file" id="" name="add_other_details_file[]" multiple="/">
				</div>
				<div class="mb-3">
	        		<label class="">Title:</label>
				  <input class="form-control" type="text" placeholder="Header" name="add_other_details_header">
				</div>
	        	<div class="mb-3">
	        		<label>Details:</label>
				  <textarea class="form-control" type="text" placeholder="contents" name="add_other_details_content" rows="6"></textarea>
				</div>
				<div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		    		<button type="submit" class="btn btn-primary" name="add_other_details">Save changes</button>
				</div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Editing Other Details-------- -->
	<div class="modal fade" id="modalForEditOtherDetails" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <form class="form" method="post">
				<input class="" type="hidden" id="eOtherDetailsCol" name="other_details_col">
				<div class="mb-3">					
				  <label class="form-check-label" for="">
				    Visibility(CWAS):
				  </label>
				  <input class="form-control" type="text" id="eOtherDetailsVisibility" name="other_details_visibility" maxlength="4" minlength="4">
				  <label class="text-warning">Enter Visibility In appropriate patterns as (C=>CV, W=>WORK, A=>ABOUT, S=> SKills)</label>
				</div>
				<div class="mb-3">
	        		<label class="">Title:</label>
				  <input class="form-control" type="text" id="eOtherDetailsHeader" placeholder="Header" name="other_details_header">
				</div>
	        	<div class="mb-3">
	        		<label>Details:</label>
				  <textarea class="form-control" type="text" id="eOtherDetailsContent" placeholder="contents" name="other_details_content" rows="6"></textarea>
				</div>
				<div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		    		<button type="submit" class="btn btn-primary" name="update_other_details">Save changes</button>
				</div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Creating Message-------- -->
	<div class="modal fade" id="modalForCreatingMsg" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header bg-dark">
	        <div>
	        	 <h5 class="modal-title text-white">Create New Message</h5>
	        </div>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div class="tableViews">
	        	<form class="form" method="post">
	        		<div class="mb-3">
	        			<input type="email" name="receiver_email" class="form-control" placeholder="lorem.ipsum@gmail.com">
	        		</div>
	        		<div class="mb-3">
	        			<input type="text" name="receiver_subject" class="form-control" placeholder="Subject">
	        		</div>
	        		<div class="mb-3">
	        			<textarea class="form-control" rows="3" name="receiver_message" placeholder="Reply Message..."></textarea>	
	        		</div> 
	        		<div class="mb-3">
	        			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        			<button type="submit" class="btn btn-primary" name="Send_message">Send</button>
	        		</div>       		
	        	</form>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR view message-------- -->
	<div class="modal fade" id="modalForViewMsg" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        	<h3 class="card-title" id="vSenderName">Sender Name</h3>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div>
	        	<h6> <span id="vSenderEmail" class="text-dark">lorem.ipsum@gmail.com | </span> <span class="fst-italic text-secondary" id="vSenderDate">22 Apr 2020 12:05</span></h6>
	        	<p id="vSenderMessage">Message</p>
	        </div>
	        <div class="tableViews">
	        	<h6 align="center" class="text-danger">Send Reply</h6>
	        	<form class="form" method="post"">
	        		<input type="hidden" id="vReceiverEmail" name="receiver_email">
	        		<div class="mb-3">
	        			<input type="text" name="receiver_subject" class="form-control" placeholder="Subject">
	        		</div>
	        		<div class="mb-3">
	        			<textarea class="form-control" rows="3" name="receiver_message" placeholder="Reply Message..."></textarea>	
	        		</div>
	        		<div class="modal-footer">
				  		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        			<button type="submit" class="btn btn-primary" name="Send_message">Send</button>
				  </div>        		
	        	</form>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR ADD Social Media-------- -->
	<div class="modal fade" id="modalForAddSocialMedia" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form action="" method="post" enctype="multipart/form-data">
	        	<div class="mb-3">
	        		<p class="text-info">This Icon will be shown in user page.</p>
	        	</div>
	        	<div class="col-auto">
				  <input class="form-control" type="file" id="" name="add_social_media_icon">
				</div>
	        	<div class="mb-3">
	        		<label class="">Name:</label>
				  <input class="form-control" type="text" id="" placeholder="Facebook" name="add_social_media_name">
				</div>
	        	<div class="mb-3">
	        		<label>Link:</label>
				  <input class="form-control" type="url" id="" placeholder="www.facebook.com/loremipsum" name="add_social_media_link">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" class="btn btn-primary" name="add_social_media">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Edit Social Media-------- -->
	<div class="modal fade" id="modalForEditSocialMedia" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form action="" method="post" enctype="multipart/form-data">
	        	<input type="hidden" name="social_media_id" id="eSocialMediaId">
	        	<div class="text-center py-3">
	        		<img src="img1" alt="defaut" class="gallery-item" style="height: 100px;width: 100px; cursor: pointer;" id="eSocialMediaICon">
	        		<p class="p-0 text-info">This Icon will be shown in user page.</p>
	        	</div>
	        	<div class="col-auto">
				  <input class="form-control" type="file" id="" name="social_media_icon" accept="image/jpeg" accept="image/jpg" accept="image/png" accept="image/gif">
				</div>
	        	<div class="mb-3">
	        		<label class="">Name:</label>
				  <input class="form-control" type="text" id="eSocialMediaName" placeholder="Facebook" name="social_media_name">
				</div>
	        	<div class="mb-3">
	        		<label>Link:</label>
				  <input class="form-control" type="url" id="eSocialmediaLink" placeholder="www.facebook.com/loremipsum" name="social_media_link">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" class="btn btn-primary" name="update_social_media">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- ----------Remove CWAS--------- -->
	<div class="modal fade" id="modalForRemoveCWAS" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Do you Really want to remove?</p>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<input type="hidden" name="remove_cwas_id" id="removeCWASId">
	         <span class="px-4"><button class="btn btn-success" type="button" data-bs-dismiss="modal" aria-label="Close">NO</button></span>
	         <span class="px-4"><button class="btn btn-danger" type="submit" name="yesRemoveCWASDetails">YES</button></span>
	         </form>
	      </div>
	    </div>
	  </div>
	</div>
	</div>


<!-- ----------Remove Message--------- -->
	<div class="modal fade" id="modalForRemoveMessage" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Do you Really want to remove?</p>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<input type="hidden" name="remove_message_id" id="removeMessageId">
	         <span class="px-4"><button class="btn btn-success" type="button" data-bs-dismiss="modal" aria-label="Close">NO</button></span>
	         <span class="px-4"><button class="btn btn-danger" type="submit" name="yesRemoveReceivedMessage">YES</button></span>
	         </form>
	      </div>
	    </div>
	  </div>
	</div>
	</div>

	<!-- ----------Remove contents--------- -->
	<div class="modal fade" id="modalForRemoveContent" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Do you Really want to remove?</p>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<input type="hidden" name="remove_content_id" id="removeContentId">
	         <span class="px-4"><button class="btn btn-success" type="button" data-bs-dismiss="modal" aria-label="Close">NO</button></span>
	         <span class="px-4"><button class="btn btn-danger" type="submit" name="yesRemoveSocialMediaContent">YES</button></span>
	         </form>
	      </div>
	    </div>
	  </div>
	</div>
	</div>

	<!-- ----------Modal For Change Password--------- -->
	<div class="modal fade" id="modalForChangePassword" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-md">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Keep login Credintals Secerately</p>
	        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<div class="alert-info px-2 py-2 rounded">
	      			<p>Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character.</p>
	      		</div>
	      		<div class="mb-3">
	      			<label>Old Password:</label>
	      			<input type="password" class="form-control" name="change_old_password" placeholder="Old Password">
	      		</div>
	      		<div class="mb-3">
	      			<label>New Password:</label>
	      			<input type="password" class="form-control" name="change_new_password" placeholder="New Password">
	      		</div>
	      		<div class="mb-3">
	      			<label>Conform Password:</label>
	      			<input type="password" class="form-control" name="change_conform_password" placeholder="conform password">
	      		</div>
	         <div class="mb-3">
	         	<button class="btn btn-danger" type="button" data-bs-dismiss="modal" aria-label="Close">Close</button>
	         	<button class="btn btn-success" type="submit" name="change_password">Update</button>
	         </div>
	         </form>
	      </div>
	    </div>
	  </div>
	</div>
	</div>

		<!-- ----------Remove Background Image--------- -->
	<div class="modal fade" id="modalForRemoveBackgroundImage" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Do you Really want to remove?</p>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<input type="hidden" name="remove_bg_img_id" id="removeBgImgId">
	         <span class="px-4"><button class="btn btn-success" type="button" data-bs-dismiss="modal" aria-label="Close">NO</button></span>
	         <span class="px-4"><button class="btn btn-danger" type="submit" name="yesRemoveBgImg">YES</button></span>
	         </form>
	      </div>
	    </div>
	  </div>
	</div>
	</div>

	<!-- ----------Modal For Change Background Image--------- -->
	<div class="modal fade" id="modalForEditBackgroundImage" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-md">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Chose appropriate background images</p>
	        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post" enctype="multipart/form-data">
	      		<div class="alert-info px-2 py-2 rounded">
	      			<img src="img1" class="gallery-item gallery" id="eBackgroundImg">
	      			<p>This image will be shown as display image in homepage.</p>
	      			<input type="hidden" name="edit_bg_img_id" id="eBgImgId">
	      		</div><br>
	      		<div class="col-auto">
	      			<input class="form-control" name="edit_bg_img" type="file" id="" accept="image/jpeg" accept="image/jpg" accept="image/png" accept="image/gif">
	      		</div><br><br>
	         <div class="mb-3">
	         	<button class="btn btn-danger" type="button" data-bs-dismiss="modal" aria-label="Close">Close</button>
	         	<button class="btn btn-success" type="submit" name="change_background_image">Update</button>
	         </div>
	         </form>
	      </div>
	    </div>
	  </div>
	</div>
	</div>

	<!-- ----------View Images--------- -->
	<div class="modal fade" id="gallery-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <!-- <p class="modal-title" id="exampleModalLabel">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p> -->
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	         <img src="img/1.jpg" class="modal-img" alt="modal img" style="width: 100%;height: auto;">
	      </div>
	    </div>
	  </div>
	</div>
	</div>



	<script type="text/javascript">
		// -- ----------Removing CWAS--------------
		$(document).ready(function(){
			$('.removeCWASDetails').on('click',function(){
				var contentId= $(this).closest("tr").find("#removeCWASDetails").val();

				$("#modalForRemoveCWAS").modal('show');
				$("#removeCWASId").val(contentId);
			});
		});

		// ---remove message-------
		$(document).ready(function(){
			$('.removeMessage').on('click',function(){
				var messageId= $(this).closest("tr").find("#ForRemoveMessage").val();

				$("#modalForRemoveMessage").modal('show');
				$("#removeMessageId").val(messageId);
			});
		});

	// -- ----------Removing Contents--------------
		$(document).ready(function(){
			$('.removeContent').on('click',function(){
				var contentId= $(this).closest("tr").find("#ForRemoveContent").val();

				$("#modalForRemoveContent").modal('show');
				$("#removeContentId").val(contentId);
			});
		});
//----------Editing contact Details-----
		$(document).ready(function(){
			$('.editContactDetails').on('click',function(){
				var contactDetailsHeader= $(this).closest("tr").find("#contactDetailsHeader").text();
				var contactDetailsContent= $(this).closest("tr").find("#contactDetailsContent").text();
				var contactDetailsCol= $(this).closest("tr").find("#eContactDetails").val();
				$("#modalForEditContact").modal('show');
				$("#editContactDetailsHeader").text(contactDetailsHeader);
				$("#editContactDetailsContent").val(contactDetailsContent);
				$("#editContactDetailsCol").val(contactDetailsCol);
			});
		});

//----------Editing Other Details-----
		$(document).ready(function(){
			$('.editOtherDetails').on('click',function(){
				var otherDetailsHeader= $(this).closest("tr").find("#otherDetailsHeader").text();
				var otherDetailsContent= $(this).closest("tr").find("#otherDetailsContent").text();
				var otherDetailsVisibility= $(this).closest("tr").find("#otherDetailsVisibility").text();
				var otherDetailsCol= $(this).closest("tr").find("#eOtherDetails").val();
				$("#modalForEditOtherDetails").modal('show');
				$("#eOtherDetailsCol").val(otherDetailsCol);
				$("#eOtherDetailsHeader").val(otherDetailsHeader);
				$("#eOtherDetailsContent").val(otherDetailsContent);
				$("#eOtherDetailsVisibility").val(otherDetailsVisibility);
			});
		});


		//----------Viewing Message-----
		$(document).ready(function(){
			$('.viewUserMessage').on('click',function(){
				var senderName= $(this).closest("tr").find("#viewSenderName").text();
				var senderEmail= $(this).closest("tr").find("#viewSenderEmail").text();
				var senderMessage= $(this).closest("tr").find("#viewSenderMessage").text();
				var senderDate= $(this).closest("tr").find("#viewSenderDate").text();
				$("#modalForViewMsg").modal('show');
				$("#vSenderName").text(senderName);
				$("#vSenderEmail").text(senderEmail);
				$("#vSenderMessage").text(senderMessage);
				$("#vSenderDate").text(senderDate);
				$("#vReceiverEmail").val(senderEmail);
			});
		});

// -----------Editing Social Media-------
		$(document).ready(function(){
			$('.editSocialMedia').on('click',function(){
				var socialMediaName= $(this).closest("tr").find("#socialMediaName").text();
				var socialmediaLink= $(this).closest("tr").find("#socialmediaLink").text();
				var socialMediaICon= $(this).closest("tr").find("#socialMediaICon").text();
				var socialmediaId= $(this).closest("tr").find("#eSocialMedia").val();
				socialMediaICon='../icons/'+socialMediaICon;

				$("#modalForEditSocialMedia").modal('show');
				$("#eSocialMediaName").val(socialMediaName);
				$("#eSocialmediaLink").val(socialmediaLink);
				$("#eSocialMediaId").val(socialmediaId);
				document.querySelector("#eSocialMediaICon").src = socialMediaICon;
			});
		});

		// -----------Editing Background Image-------
		$(document).ready(function(){
			$('.editBackgroundImage').on('click',function(){
				var imageName= $(this).closest("tr").find("#backgroundImageName").text();
				var imageId= $(this).closest("tr").find("#ebackgroundImage").val();
				image='../images/'+imageName+"?nocache=1";

				$("#modalForEditBackgroundImage").modal('show');
				$("#eBgImgId").val(imageId);
				document.querySelector("#eBackgroundImg").src = image;
			});
		});


		document.addEventListener("click",function (e){
		   if(e.target.classList.contains("gallery-item")){
		   	  const src = e.target.getAttribute("src");
		   	  document.querySelector(".modal-img").src = src;
		   	  const myModal = new bootstrap.Modal(document.getElementById('gallery-modal'));
		   	  myModal.show();
		   }
		 });



	</script>
	
<?php require '../includes/footer.php'; ?>