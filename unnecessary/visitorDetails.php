<?php 
require '../includes/connection.inc.php';
require '../includes/functions.inc.php';
date_default_timezone_set('Asia/Kathmandu');
$now=date("Y-m-d H:i:s");
$errors=array();
$Success="";
$target_dir = "../images/";


// Query About Me
$query_about_me=@"SELECT * FROM about_user";
$about_me_res=fetch($con,$query_about_me);

if (isset($_GET['val'])) {
	$web_visitor_res=$_GET['val'];
}

 ?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" lang="en">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lorem Ipsum</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/myStyle.css">
	<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
	<script type="text/javascript" src="../js/jquery.min.js"></script>
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
		#gallery-modal .modal-img{
			width: 100%;
		}
		.myActionIcons{
			padding-right: 10px;
		}
		.viewBlogIcons i{
			padding-left: 30px;
		}
		.myDetailsBox{
			padding-top: 10px;
			height: 450px;
			overflow: hidden;
			overflow-y: scroll;
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
					<li><a href="myBlog.php" class="nav-link">Blog & gallery</a></li>
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
    		<span class="alert alert-success"><?php echo $Success; ?></span>
    		<?php } ?><br><br>
	    </div>
		<div class="row gy-4 row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1 py-3 px-4">
			<div class="border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-globe text-in">&nbsp;</i>Visitors Info</h1><hr>
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
			     <td>
			      	<?php echo($web_visito_row['visited_date']); ?>
			     </td>
			     <td><a class="btn btn-success" href="https://whatismyipaddress.com/ip/<?php echo($web_visito_row['ip']); ?>" target="blank">Details</a></td>
			    </tr>
			   <?php } ?>
			  </tbody>
			</table>
		</div>
	</div>

<?php require '../includes/footer.php'; ?>