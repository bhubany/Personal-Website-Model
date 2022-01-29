<?php 
require '../includes/connection.inc.php';
require '../includes/functions.inc.php';
$now=date("Y-m-d H:i:s");
$errors=array();
$Success="";
$target_dir = "../images/";

if ((!isset($_GET['blog_id']) or empty($_GET['blog_id']))) {
	  header("Location:myblog.php");
	}else{
	  $blog_id=clean_values($con,$_GET['blog_id']);
	}

	if (isset($_POST['submit_comment'])) {
		$commenter_name=clean_values($con,$_POST['commenter_name']);
		$commenter_email=clean_values($con,$_POST['commenter_email']);
		$commenter_email=filter_var($commenter_email, FILTER_SANITIZE_EMAIL);
		$commenter_comment=clean_values($con,$_POST['commenter_comment']);
		$comment_blog_id=clean_values($con,$_POST['comment_blog_id']);

		// -------Validating data
	 	if (empty($commenter_name)) {
	    	array_push($errors, "Name is required");
	  	} else {
		    $name = $commenter_name;
		    // check if name only contains letters and whitespace
		    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
		      array_push($errors,"Only letters and white space allowed in name");
		    }elseif (strlen($name)>40) {
		    	array_push($errors,"Name length must be below 40 characters");
		    }
		}
	 	if (empty($commenter_email)) {
				array_push($errors, "Email is required");
		} else {
		    $email = $commenter_email;
		    // check if e-mail address is well-formed
		    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		      $emailErr = "Invalid email format";

			}elseif (strlen($email)>40) {
		    	array_push($errors,"Email length must be below 40 characters");
		    }
		}
		if (empty($commenter_comment)) {
				array_push($errors, "Message can't be empty!");
		}

		// If there is no errors---
		if (count($errors) == 0) {

			$query_insert_comment=@"INSERT INTO blog_comments(name,email,comment,blog_id) VALUES('$commenter_name','$commenter_email','$commenter_comment','$comment_blog_id') LIMIT 1";
			$insert_comment_res=insert_update($con,$query_insert_comment);

			if ($insert_comment_res==1) {
				header("Refresh:2");
				$Success="Your Comment has been posted sucessfully";
			}else{
				array_push($errors, "Error occur while posting your replies.<br> Try Again Late!!!");
			}
		}

	}

	//------Replying to comments-----

	if (isset($_POST['reply_to_comment'])) {
		$replier_name=clean_values($con,$_POST['replier_name']);
		$replier_email=clean_values($con,$_POST['replier_email']);
		$replier_email=filter_var($replier_email, FILTER_SANITIZE_EMAIL);
		$replier_comment=clean_values($con,$_POST['replier_comment']);
		$reply_comment_id=clean_values($con,$_POST['reply_comment_id']);
		$reply_blog_id=clean_values($con,$_POST['reply_blog_id']);

		// -------Validating data
	 	if (empty($replier_name)) {
	    	array_push($errors, "Name is required");
	  	} else {
		    $name = $replier_name;
		    // check if name only contains letters and whitespace
		    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
		      array_push($errors,"Only letters and white space allowed in name");
		    }elseif (strlen($name)>40) {
		    	array_push($errors,"Name length must be below 40 characters");
		    }
		}
	 	if (empty($replier_email)) {
				array_push($errors, "Email is required");
		} else {
		    $email = $replier_email;
		    // check if e-mail address is well-formed
		    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		      $emailErr = "Invalid email format";

			}elseif (strlen($email)>40) {
		    	array_push($errors,"Email length must be below 40 characters");
		    }
		}
		if (empty($replier_comment)) {
				array_push($errors, "Message can't be empty!");
		}

		// If there is no errors---
		if (count($errors) == 0) {

			$query_insert_reply=@"INSERT INTO blog_replies(name,email,replies,comment_id,blog_id) VALUES('$replier_name','$replier_email','$replier_comment','$reply_comment_id','$reply_blog_id') LIMIT 1";
			$insert_reply_res=insert_update($con,$query_insert_reply);

			if ($insert_reply_res==1) {
				header("Refresh:2");
				$Success="Your replies has been posted sucessfully";
			}else{
				array_push($errors, "Error occur while posting your replies.<br> Try Again Late!!!");
			}
		}

	}


// ------------Selecting blog contents----------
	$query_select_blog=@"SELECT * FROM blogs WHERE id=$blog_id LIMIT 1";
	$selecting_blog_res=mysqli_query($con,$query_select_blog) or die(error_reporting(0));
	if (!$selecting_blog_res) {
	die($con);
	}elseif (!mysqli_num_rows($selecting_blog_res) > 0) {
	  header("Location:blog.php");
	}else{
		$rows=mysqli_fetch_assoc($selecting_blog_res);
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
	<link rel="stylesheet" type="text/css" href="../css/myStyle.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		.blogImg img{
			padding: 15px;
			max-width: 100%;
			box-shadow: 0 0 15px rgba(0,0,0,0.3);
			cursor: pointer;
			height: 300px;
			width: 400px;
		}
		#gallery-modal .modal-img{
			width: 100%;
		}
		.viewBlogIcons i{
			padding-left: 40px;
			cursor: pointer;
		}
		.tableViews{
			padding: 15px;
			width: 100%;
			box-shadow: 0 0 25px rgba(0,0,0,0.3);
			border-radius: 10px;
		}
	</style>
	
</head>
<body style="">
<!-- -------------------Start of gallery Section -->
<div class="container-fluid">
	<nav class="navbar navbar-dark px-5">
		<p class="border-primary text-center pt-5" style="padding-top: 10px;">
			<a href="index.php" style="text-decoration: none;color: white;">Home</a>
			<a href="myBlog.php" style="text-decoration: none;color: white; padding-left: 30px;">Back</a>
		</p>
		<h1 class="text-white pt-5" style="font-family: serif; font-style: italic;;"><?php echo($about_me_res['name']); ?> Blogs</h1>
	</nav>

	<div class="row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1 pt-5 text-center <?php if($Success=='' and count($errors)==0){
			echo("hideContent");
		} ?>">
		<?php include '../includes/errors.inc.php'; ?>
		<?php if ($Success!="") {?>
		<span class="alert alert-success"><?php echo $Success; ?></span>
		<?php } ?><br><br>
	</div>

	<div class="row px-5">
		<div style="background-color: #ffffff;border-radius: 30px;">
			<div class="pt-3">
				<h1> <?php echo $rows['title']; ?></h1>
				<h6 class="fst-italic pt-2">By <?php echo $rows['blog_by']; ?> | <?php echo $rows["upload_date"]; ?></h6>
			</div><hr style="border: 3px solid blue;">
			<div class="container-lg">
				<div class="row gy-3 row-cols-1 row-cols-sm-2 row-cols-md-4 row-col-lg-4">
					<?php 
						// --Selecting images
					$image_id=$rows['image'];
						$qry_select_imgs=@"SELECT * FROM image_collections WHERE (content_id = '$image_id' AND show_in_blogs=1) AND status=1";
						$selecting_imgs_res=mysqli_query($con,$qry_select_imgs) or die(error_reporting(0));
						while($img_rows=mysqli_fetch_assoc($selecting_imgs_res)){
					?>
					<div class="col blogImg">
		              <img src="../images/<?php echo($img_rows['image_name']); ?>" class="gallery-item" alt="<?php echo($img_rows['img_alt_text']); ?>">
		           </div>
		       <?php } ?>
				</div>
			</div>
			<div>
				<p class="blockquote text-center text-secondary" style="font-style: italic;"><?php echo $rows['short_desc']; ?></p>
			</div>
			<div >
			<div class="text-justify">
			<p><?php echo $rows['full_details']; ?></p>
			</div>
			<div class="viewBlogIcons py-2 tableViews" align="center">
		  		<i class="fa fa-eye"> &nbsp;<?php echo $rows['blog_view']; ?></i>
		  		<i class="fa fa-thumbs-up"> &nbsp;<?php echo $rows['blog_like']; ?></i>
		  		<i class="fa fa-thumbs-down"> &nbsp;<?php echo $rows['blog_dislike']; ?></i>
		  		<i class="fa fa-comments-o"> &nbsp;<?php echo $rows['blog_comment']; ?></i>
		  		<i class="fa fa-share"> &nbsp;<?php echo $rows['blog_share']; ?></i>
		  	</div>
			<div class="row">
				<div class="col-sm-2 col-md-2 col-lg-2"></div>
				<div class="col-sm-8 col-md-8 col-lg-8 col-xs-12 py-3">
					<div class="card" id="commentSection">
					  <div class="card-header">
					    Please! Provide us your essential feedback
					  </div>
					  <div class="card-body">
					    <form action="" method="post">
						<div class="mb-3">
							<input type="hidden" name="comment_blog_id" value="<?php echo($blog_id); ?>">
						  <label for="text" class="form-label">Full Name</label>
						 <input class="form-control form-control-lg" type="text" readonly="" placeholder="lorem ipsum" name="commenter_name" value="<?php echo($about_me_res['name']); ?>">
						</div>
						<div class="mb-3">
						  <label for="email" class="form-label">Email address</label>
						 <input class="form-control form-control-lg" type="text" readonly="" placeholder="lorem.ipsum@gmail.com" name="commenter_email" value="<?php echo($about_me_res['email']); ?>">
						</div>
						<div class="mb-3">
						  <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
						  <textarea class="form-control form-control-lg" id="" rows="4" placeholder="Enter your comments.." name="commenter_comment"></textarea>
						</div>
						<div class="mb-3" align="right">
					    	<button type="submit" class="btn btn-primary" name="submit_comment">Post Your Comment</button>
					    </div>
					</form>
					</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2"></div>
			</div>

			<?php 
				$query_select_blog_comment=@"SELECT * FROM blog_comments WHERE (blog_id=$blog_id AND status=1) ORDER BY id DESC";
				$select_blog_comment_res=mysqli_query($con,$query_select_blog_comment) or die(error_reporting(0));

			 ?>
			<div align="center">
				<h5>Older Comments</h5><hr>
			</div>
			<!-- ---------sELECTING COMMENTS FOR THIS BLOGS------ -->
				<?php while ($select_blog_comment_rows=mysqli_fetch_assoc($select_blog_comment_res)) { ?>
			<div>
				<div class="card py-3 px-2" style="padding-left: 10px;">
				  <div class="card-header">
				    <?php echo $select_blog_comment_rows['name']." | ".$select_blog_comment_rows['upload_date']; ?>
				  </div>
				  <div class="card-body">
				    <blockquote class="blockquote mb-0">
				      <p><?php echo $select_blog_comment_rows['comment']; ?></p>
				    </blockquote>
				  </div>
				  <?php 
				  	$comment_id=$select_blog_comment_rows['id'];
				  	$query_comment_replies=@"SELECT * FROM blog_replies WHERE (blog_id='$blog_id' AND comment_id='$comment_id') AND status=1 ORDER BY id DESC";
				  	$comment_replies_res=mysqli_query($con,$query_comment_replies) or die(error_reporting(0));

				   ?>
				  <div class="card-footer viewBlogIcons" align="center">
				  		<i class="fa fa-thumbs-up" title="total like"> &nbsp;<?php echo $select_blog_comment_rows['comment_like']; ?></i>
				  		<i class="fa fa-thumbs-down" title="total dislike"> &nbsp;<?php echo $select_blog_comment_rows['comment_dislike']; ?></i>
			  			<i class="fa fa-reply" title="Reply" style="padding-left: 50px; cursor: pointer;"  data-bs-toggle="collapse" data-bs-target="#commentReplies<?php echo($comment_id); ?>" aria-expanded="false" aria-controls="collapseExample">&nbsp;Replies (<?php echo (mysqli_num_rows($comment_replies_res)); ?>)</i>
			  		  </div>
			  		  <div class="collapse tableViews" id="commentReplies<?php echo($comment_id); ?>">
			  		  	<div class="row">
				  		  	<div class="col-sm-2 col-md-2 col-lg-2"></div>
							<div class="card col-sm-8 col-md-8 col-lg-8 col-xs-12">
					  			<form class="" method="post" action="">
					  				<input type="hidden" name="reply_blog_id" value="<?php echo($blog_id); ?>">
					  				<input type="hidden" name="reply_comment_id" value="<?php echo($select_blog_comment_rows['id']); ?>">
					  				<div class="mb-3">
					  					<label for="userName" class="form-label">Name:</label>
					  					<input type="text" name="replier_name" placeholder="lorem Ipsum" class="form-control" readonly="" value="<?php echo($about_me_res['name']); ?>">
					  				</div>
					  				<div class="mb-3">
					  					<label for="userEmail" class="form-label">Email:</label>
					  					<input type="email" name="replier_email" placeholder="lorem.Ipsum@gmail.com" class="form-control" readonly="" value="<?php echo($about_me_res['email']); ?>">
					  				</div>
					  				<div class="mb-3">
					  					<label for="userComment" class="form-label">Comment:</label>
					  					<textarea name="replier_comment" placeholder="Enter Reply Message" class="form-control" rows="4"></textarea>
					  				</div>
					  				<div class="mb-3 px-4" align="right">
					  					<button type="submit" class="btn btn-primary" name="reply_to_comment">Reply</button>
					  				</div>
					  			</form>
				  			</div>
				  			<div class="col-sm-2 col-md-2 col-lg-2"></div>
			  			</div><br>
			  			<?php while ($comment_replies_rows=mysqli_fetch_assoc($comment_replies_res)) { ?>
			  			<div class="border border-dark rounded">
			  			  <div class="card-header">
						    <?php echo $comment_replies_rows['name']." | ".$comment_replies_rows['replies_date']; ?>
						  </div>
						  <div class="card-body">
						    <blockquote class="blockquote mb-0">
						      <p><?php echo $comment_replies_rows['replies']; ?></p>
						    </blockquote>
						  </div>
						  <div class="card-footer viewBlogIcons" align="center">
						  	<i class="fa fa-thumbs-up" title="total like"> &nbsp;<?php echo $comment_replies_rows['replies_like']; ?></i>
				  			<i class="fa fa-thumbs-down" title="total dislike"> &nbsp;<?php echo $comment_replies_rows['replies_dislike']; ?></i>
						  </div>
			  		  </div><br>
			  		<?php } ?>
				</div>
			</div><br>
		<?php } ?>
		</div>
	</div>
</div>


<!-- ------For viewing images -->
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
	document.addEventListener("click",function (e){
	   if(e.target.classList.contains("gallery-item")){
	   	  const src = e.target.getAttribute("src");
	   	  document.querySelector(".modal-img").src = src;
	   	  const myModal = new bootstrap.Modal(document.getElementById('gallery-modal'));
	   	  myModal.show();
	   }
	 })

	function copyLink() {
	  var copyText = document.querySelector('input[type=hidden]').value;
	  copyText.select();
	  copyText.setSelectionRange(0, 99999)
	  document.execCommand("copy");
	  alert("Copied to clip board!");
	}
</script>


<?php require '../includes/footer.php'; ?>