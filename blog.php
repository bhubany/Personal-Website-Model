<?php 
	require 'assests/includes/header.php'; 
	// require 'assests/includes/functions.inc.php'; 
	// require 'assests/includes/connection.inc.php';

	$blog_present='';

	$query_blog=@"SELECT * FROM blogs WHERE status=1 ORDER BY id DESC";
	$selecting_blog_res=mysqli_query($con,$query_blog) or die(error_reporting(0));

	if (!$selecting_blog_res) {
    	die($con);
  	}
  	if (!mysqli_num_rows($selecting_blog_res) > 0) {
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
	<h1 class="text-white" align="right" style="font-family: serif; font-style: italic;padding-top: 30px;"><?php echo($about_me_res['name']); ?> Blogs</h1>
</nav>
<!-- -------------------Start of blog Section -->
<section class="gallery">
     <div class="container-lg">
        <div class="row gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
         <?php 
         if (mysqli_num_rows($selecting_blog_res)>0) {
         	while ($selecting_blog_rows=mysqli_fetch_assoc($selecting_blog_res)) {?>
           <div class="col">
              <div class="card px-2 py-2" style="width: 18rem;">
				  <img class="card-img-top" src="assests/images/<?php echo($selecting_blog_rows['cover_image']); ?>" alt="<?php echo(substr($selecting_blog_rows['title'],20)); ?>">
				  <div class="card-body">
				  	<p class="card-title text-center">
				  		<i class="fa fa-thumbs-up"> &nbsp;<?php echo $selecting_blog_rows['blog_like']; ?></i>
				  		<i class="fa fa-thumbs-down" style="padding-left: 30px;"> &nbsp;<?php echo $selecting_blog_rows['blog_dislike']; ?></i><br>				  		
				  		<i class="fa fa-eye"> &nbsp;<?php echo $selecting_blog_rows['blog_view']; ?></i>
				  		<i class="fa fa-comments-o" style="padding-left: 30px;"> &nbsp;<?php echo $selecting_blog_rows['blog_comment']; ?></i>
				  	</p>
				    <h5 class="card-title"><?php echo substr($selecting_blog_rows['title'],0,50); ?></h5>
				    <p class="card-text"><?php echo substr($selecting_blog_rows['full_details'],0,100); ?></p>
				    <a href="blogReadMode.php?blog_id=<?php echo($selecting_blog_rows['id']); ?>" class="btn btn-primary d-grid">VIEW</a>
				  </div>
				</div>
           </div>
         <?php } }else{ ?>
         	<div align="center" class="container-lg">
	         	<div class="alert alert-danger px-3 py-5">
	         		<p>Curently No available blogs.<br> Try Again later!!!</p>
	         	</div>
         	</div>
         <?php } ?>
        </div>
     </div>
  </section>
</div>
<?php include 'assests/includes/footer.php' ?>