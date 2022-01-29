<?php 
	require 'assests/includes/header.php'; 
	// require 'assests/includes/functions.inc.php'; 
	// require 'assests/includes/connection.inc.php';

	$blog_present='';

	$query_album=@"SELECT * FROM gallery_album WHERE status=1 ORDER BY id DESC";
	$selecting_album_res=mysqli_query($con,$query_album) or die(error_reporting(0));

	if (!$selecting_album_res) {
    	die($con);
  	}
  	if (!mysqli_num_rows($selecting_album_res) > 0) {
      $blog_present=0;
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
	<title>Lorem-ipsum Gallery</title>
	<link rel="stylesheet" type="text/css" href="assests/css/myStyle.css">
	<link rel="stylesheet" type="text/css" href="assests/css/bootstrap.min.css">
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script type="text/javascript" src="assests/js/jquery.min.js"></script>
	<style type="text/css">
		.cardContents {
			padding: 20px;
		}
		.card .card-body{
			background-color: #ffffff;
			box-shadow: 0 0 15px rgba(0,0,0,0.3);
			cursor: pointer;
		}
		.card img{
			height: 200px;
			width: 16.9rem;
		}
	</style>
	
</head>
<body style="">
<!-- -------------------Start of blog Section -->
<div class="container-lg"><br>
<nav class="navbar navbar-light">
	<p class="border-primary text-center" style="padding-top: 10px;">
		<a href="index.php" style="text-decoration: none;color: white; padding: 15px;">Home</a>
	</p>
	<h1 class="text-white" align="right" style="font-family: serif; font-style: italic;padding-top: 30px;"><?php echo($about_me_res['name']); ?> Gallery</h1>
</nav>
<!-- -------------------Start of blog Section -->
<section class="gallery">
     <div class="container-lg">
        <div class="row gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
         <?php 
         if (mysqli_num_rows($selecting_album_res)>0) {
         while ($selecting_album_rows=mysqli_fetch_assoc($selecting_album_res)) {?>
           <div class="col">
              <div class="card px-2 py-2" style="width: 18rem;">
				  <img class="card-img-top" src="assests/images/<?php echo($selecting_album_rows['cover_image']); ?>" alt="<?php echo(substr($selecting_album_rows['album_name'],20)); ?>">
				  <div class="card-body">
				    <h5 class="card-title"><?php echo($selecting_album_rows['album_name']); ?></h5>
				    <p class="card-text"><?php echo substr($selecting_album_rows['album_details'],0,50); ?></p>
				    <a href="gallery_images.php?gallery_id=<?php echo($selecting_album_rows['id']); ?>" class="btn btn-primary d-grid">VIEW</a>
				  </div>
				</div>
           </div>
         <?php } }else{ ?>
         	<div align="center" class="container-lg">
	         	<div class="alert alert-danger px-3 py-5">
	         		<p>Curently No available Gallery Albums.<br> Try Again later!!!</p>
	         	</div>
         	</div>
         <?php } ?>
        </div>
     </div>
  </section>
</div>
<?php include 'assests/includes/footer.php' ?>