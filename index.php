<?php 

include 'assests/includes/header.php';
// include 'assests/includes/functions.inc.php';
// include 'assests/includes/connection.inc.php';
$Success=''; 
$errors=array();

// $send_message_res="x";
// --------Sending Message---------
 if (isset($_POST['sendMessage'])) {
 	$senderName=clean_values($con,$_POST['senderName']);
 	$senderEmail=clean_values($con,$_POST['senderEmail']);
 	$senderEmail=filter_var($senderEmail, FILTER_SANITIZE_EMAIL);
 	$senderMessage=clean_values($con,$_POST['senderMessage']);

 	// -------Validating data
 	if (empty($senderName)) {
    	array_push($errors, "Name is required");
  	} else {
	    $name = $senderName;
	    // check if name only contains letters and whitespace
	    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
	      array_push($errors,"Only letters and white space allowed in name");
	    }elseif (strlen($name)>40) {
	    	array_push($errors,"Name length must be below 40 characters");
	    }
	}
 	if (empty($senderEmail)) {
			array_push($errors, "Email is required");
	} else {
	    $email = $senderEmail;
	    // check if e-mail address is well-formed
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	      $emailErr = "Invalid email format";

		}elseif (strlen($email)>40) {
	    	array_push($errors,"Email length must be below 40 characters");
	    }
	}
	if (empty($senderMessage)) {
			array_push($errors, "Message is required");
	}

	//-----Inserting into data
	if (count($errors)==0) {
	 	$query_send_message=@"INSERT INTO received_message (name, email, received_message,status) VALUES('$senderName','$senderEmail','$senderMessage',0) LIMIT 1";
		$send_message_res=insert_update($con,$query_send_message);
		if ($send_message_res==1) {
			header("Refresh:3");
			$Success="Your message has been sent sucessfully!";
		}
		else{
			array_push($errors, "Error Occurs while sending your message");
		}
	}

 }


$query_social_media=@"SELECT * FROM social_media WHERE status=1";
$social_media_res=mysqli_query($con,$query_social_media) or die($con);

$query_about_user=@"SELECT * FROM about_user";
$about_user_res=fetch($con,$query_about_user);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo($about_user_res['name']); ?></title>
	<link rel="stylesheet" type="text/css" href="assests/css/myStyle.css">
	<link rel="stylesheet" type="text/css" href="assests/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<script type="text/javascript" src="assests/js/jquery.min.js"></script>
	<script src="assests/js/bootstrap.bundle.min.js"></script><!-- 
	<script src="assests/js/bootstrap.min.js"></script> -->

	<style type="text/css">
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


	<div class="container-fluid px-0" style="max-width: 100%;">
		<div class="row">
			<div class="col-sm-8 col-lg-8 col-md-8 col-xs-8">
				<div class="row">
					<div class="col-sm-3 col-lg-4 col-md-4 col-xs-1"></div>
		    		<div class="col-sm-9 col-lg-8 col-md87 col-xs-10 text-center pt-5">
		    		<?php include 'assests/includes/errors.inc.php'; ?>
		    		<?php if ($Success!="") {?>
		    		<span class="alert alert-success"><?php echo $Success; ?></span>
		    		<?php } ?><br><br>
			    	</div>
			    </div>
			</div>
			<div class="col-sm-4 col-lg-4 col-md-4 col-xs-4 px-0">
				<div align="right" class="p-5">
					<ul style="list-style-type: none;">
						<?php while ($social_media_rows=mysqli_fetch_assoc($social_media_res)) { ?>
						<li class="pb-3">
							<a href="<?php echo($social_media_rows['link']); ?>" target="blank">
								<img class="myIcon" src="assests/icons/<?php echo($social_media_rows['icon']); ?>" alt="<?php echo($social_media_rows['alt_text']); ?>" title="<?php echo($social_media_rows['alt_text']); ?>">
							</a>
						</li>
					<?php } ?>
					</ul>
				</div>
			</div>
			<div class="col-sm-12 col-lg-12 col-md-12 col-xs-12 px-0">
				<div class ="text-center text-white fw-bold">
					<h1><?php echo $about_user_res['greeting_message']." ".$about_user_res['name']; ?></h1>
					<p style="font-style: italic;" class="overflow-wrap px-5">
						<?php echo $about_user_res['quotes']; ?>
					</p>
					
				</div>
			</div>

				<!-- --------Different icons for different Pages------ -->
			<div class="col-sm-12 col-lg-12 col-md-12 col-xs-12 px-0">
				<div class="myMenue" align="center">
					<p>
						<a class="hideContent disabled" id="menu-about" data-bs-toggle="modal" data-bs-target="#aboutMe">
							<img title="About me" class="myIcon text-white" src="assests/icons/about.png?nocache=<?php echo(time());?>">
						</a>
					
						<a id="menu-message" class="hideContent disabled" data-bs-toggle="modal" data-bs-target="#messageMe">
							<img title="Message me" class="myIcon text-white" src="assests/icons/message.png?nocache=<?php echo(time());?>">
						</a>
						<a href="" id="menu-cv" class="hideContent disabled" data-bs-toggle="modal" data-bs-target="#myCV">
							<img title="My CV" class="myIcon text-white" src="assests/icons/cv.png?nocache=<?php echo(time());?>">
						</a>
					</p><br>
					<p> 
						<a href="gallery.php" id="menu-gallery" class="hideContent">
							<img title="My Gallery" class="myIcon text-white" src="assests/icons/gallery.png?nocache=<?php echo(time());?>">
						</a>
						<a class="disabled hideContent" id="hideMenu">
							<img title="Hide Menue" class="myIcon text-white" src="assests/icons/shrink.png?nocache=<?php echo(time());?>">
						</a>
						<a class="disabled" id="showMenu">
							<img title="Show Menue" class="myIcon text-white" src="assests/icons/expand.png?nocache=<?php echo(time());?>">
						</a>
						<a href="" id="menu-skills" class="hideContent disabled" data-bs-toggle="modal" data-bs-target="#mySkills">
							<img title="My Skills" class="myIcon text-white" src="assests/icons/skills.png?nocache=<?php echo(time());?>">
						</a>
					</p><br>
					<p>
						<a href="blog.php" id="menu-blog" class="hideContent">
							<img  title="My Blog" class="myIcon text-white" src="assests/icons/blog.png?nocache=<?php echo(time());?>">			
						</a>
						<a href="" id="menu-work" class="hideContent disabled" data-bs-toggle="modal" data-bs-target="#myWorkDetails">
							<img title="My Work" class="myIcon text-white" src="assests/icons/work.png?nocache=<?php echo(time());?>">
						</a>

						<a href="" id="menu-contacts" class="hideContent disabled" data-bs-toggle="modal" data-bs-target="#myContactDetails">
							<img title="Contact Details" class="myIcon text-white" src="assests/icons/contacts.png?nocache=<?php echo(time());?>">
						</a>						
					</p>		
				</div>
			</div>			
		</div>
	</div>


	<script type="text/javascript" src="assests/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		function showMenuContent(){
				document.getElementById('showMenu').style.display='none';
				document.getElementById('hideMenu').style.display='inline';
				document.getElementById('menu-about').style.display='inline';
				document.getElementById('menu-message').style.display='inline';
				document.getElementById('menu-cv').style.display='inline';
				document.getElementById('menu-gallery').style.display='inline';
				document.getElementById('menu-contacts').style.display='inline';
				document.getElementById('menu-skills').style.display='inline';
				document.getElementById('menu-blog').style.display='inline';
				document.getElementById('menu-work').style.display='inline';
		}
		document.getElementById('showMenu').addEventListener('click',showMenuContent);

		function hideMenuContent(){
				document.getElementById('hideMenu').style.display='none';
				document.getElementById('menu-about').style.display='none';
				document.getElementById('menu-message').style.display='none';
				document.getElementById('menu-cv').style.display='none';
				document.getElementById('menu-gallery').style.display='none';
				document.getElementById('menu-contacts').style.display='none';
				document.getElementById('menu-skills').style.display='none';
				document.getElementById('menu-blog').style.display='none';
				document.getElementById('menu-work').style.display='none';
				
				document.getElementById('showMenu').style.display='inline';
		}
		document.getElementById('hideMenu').addEventListener('click',hideMenuContent);
	</script>

<!-- ---------------About Me Modal------------ -->
<div class="modal fade" id="aboutMe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="background-color: #1b5693;">
      <div class="modal-header">
        <h5 class="modal-title text-white">About Me!</h5><hr>
        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-secondary">
      	<!-- ----------Cards-- -->
      	<div class="card">
			<div class="text-center p-2">
				<img class="gallery" src="assests/images/<?php echo($about_user_res['image']); ?>" alt="Image03">
				<h1><?php echo($about_user_res['name']); ?></h1>
				<h6><?php echo($about_user_res['name_title']); ?></h6><hr>
			</div>
			<div class="card-body">
		    <?php 
		    	$query_about_me=@"SELECT * FROM other_details WHERE about=1";
				$about_me_res=mysqli_query($con,$query_about_me) or die($con);
				while ($about_me_rows=mysqli_fetch_assoc($about_me_res)) {
				$image_id=$about_me_rows['images'];
		     ?>
			    <div class="bg-light">
			    	<div class="bg-success text-white">
			    		<h3 class="card-title text-center"><?php echo(strtoupper($about_me_rows['header'])); ?></h3>
			    	</div>
			    	<!-- //Selecting Images present on this Category -->
			    	<?php 
			    		$query_image_about=@"SELECT * FROM image_collections WHERE ((show_in_skills=1 and status=1) AND content_id='$image_id') ORDER BY id DESC";
			    		$image_about_res=mysqli_query($con,$query_image_about) or die($con);
			    		if (mysqli_num_rows($image_about_res)>0) {
							while ($image_about_row=mysqli_fetch_assoc($image_about_res)) {
			    	 ?>
			    	<img class="gallery gallery-item" src="assests/images/<?php echo($image_about_row['image_name']); ?>" alt="<?php echo($image_about_row['img_alt_text']); ?>">
			    	<?php } ?>
			    	<?php } ?>
			    	<p class="card-text text-justify px-2">
			    		<?php echo($about_me_rows['content']); ?>
			    	</p>
			    </div><hr>
				<?php } ?>
			</div>
		</div>
	   </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Print</button> -->
      </div>
    </div>
  </div>
</div>

<!-- -------------------Message Me--------------- -->
<div class="modal fade" id="messageMe" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="exampleModalLabel">Message Me</h5>
        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-white" style="background-color: #1b5693;">
        <form method="post" action="">
        	<div class="form-group">
		    <label for="text">Enter Name</label>
		    <input type="text" class="form-control" name="senderName" placeholder="lorem ipsum" required="on">
		  </div>
		  <div class="form-group">
		    <label for="email">Email address</label>
		    <input type="email" class="form-control" name="senderEmail" placeholder="name@example.com" required="on">
		  </div>
		  <div class="mb-3">
		    <label for="text">Message</label>
		    <textarea class="form-control" name="senderMessage" placeholder="Enter Message Here..." rows="3" required="on"></textarea>
		  </div>
		  <div class="card-footer">
	        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary" name="sendMessage" onclick="validateContent()">Send</button>
	      </div>
		</form>
      </div>
    </div>
  </div>
</div>

<!-- -------------MY CV---------- -->
<div class="modal fade" id="myCV" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content" style="background-color: #1b5693;">
      <div class="modal-header text-white"">
        <h5 class="modal-title id="exampleModalLabel">My CV</h5>
        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-0">
      	<!-- ----------Cards-- -->
      	<div class="card">
      		<div class="card-header text-center rounded p-3">
		 		 <img src="assests/images/<?php echo($about_user_res['image']); ?>" alt="sujit khatri image" class="gallery card-img-top gallery-item" alt="Sujit Khatri"><br><br>
		 		 <h5 class="card-title text-center"><?php echo($about_user_res['greeting_message']); ?></h5>
		        <h3 class="card-title text-center">I'M <?php echo($about_user_res['name']); ?></h3>
		        <h6 class="card-title text-center"><?php echo($about_user_res['name_title']); ?></h6>
		 	</div>
		  <div class="card-body">
		  	<div>
		    	<p class="card-text text-justify px-2">
		        	<span>DOB: <?php echo($about_user_res['dob']); ?></span><br>
		        	<span>Addess: <?php echo($about_user_res['address']); ?></span><br>
		        	<span>Email: <?php echo($about_user_res['email']); ?></span><br>
		        	<span>Phone: <?php echo($about_user_res['primary_phone']); ?></span><br>
		        	<span>Website: <?php echo($about_user_res['website']); ?></span><br>
		        </p>
		    </div><hr>
		    <?php 
		    	$query_my_cv=@"SELECT * FROM other_details WHERE cv=1";
				$my_cv_res=mysqli_query($con,$query_my_cv) or die($con);
				while ($my_cv_rows=mysqli_fetch_assoc($my_cv_res)) {
				$image_id=$my_cv_rows['images'];
		     ?>
		    <div class="bg-light">
		    	<div class="bg-success text-white">
		    		<h3 class="card-title text-center"><?php echo(strtoupper($my_cv_rows['header'])); ?></h3>
		    	</div>
		    	<!-- //Selecting Images present on this Category -->
		    	<?php 
		    		$query_image_cv=@"SELECT * FROM image_collections WHERE (show_in_cv=1 and status=1) AND content_id='$image_id' ORDER BY id DESC";
		    		$image_cv_res=mysqli_query($con,$query_image_cv) or die($con);
		    		if (mysqli_num_rows($image_cv_res)>0) {
						while ($image_cv_row=mysqli_fetch_assoc($image_cv_res)) {
		    	 ?>
		    	<img class="gallery gallery-item" src="assests/images/<?php echo($image_cv_row['image_name']); ?>" alt="<?php echo($image_cv_row['img_alt_text']); ?>">
		    	<?php } ?>
		    <?php } ?>
		    	<p class="card-text text-justify px-2">
		    		<?php echo($my_cv_rows['content']); ?>
		    	</p>
		    </div><hr>
		<?php } ?>
		  </div>
		  <?php 
		  		$query_social_media=@"SELECT * FROM social_media";
				$social_media_res=mysqli_query($con,$query_social_media) or die($con);
		   ?>
		  <div class="card-footer myCvMenue bg-light rounded">
		  	<?php while ($social_media_rows=mysqli_fetch_assoc($social_media_res)) { ?>
		  	<a href="<?php echo($social_media_rows['link']); ?>" target="blank"><img alt="<?php echo($social_media_rows['link']); ?>" title="<?php echo($social_media_rows['name']); ?>" src="assests/icons/<?php echo($social_media_rows['icon']); ?>"></a>
		  <?php } ?>
		  		
		  	</ul>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Print</button> -->
      </div>
    </div>
  </div>
</div>

<!-- -------------MY SKILLS------------ -->
<div class="modal fade" id="mySkills" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="background-color: #1b5693;">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="exampleModalLabel">MY SKILLS</h5>
        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-0">
      	<!-- ----------Cards-- -->
      	<div class="card">
      		<div class="card-body">
		    <?php 
		    	$query_my_skills=@"SELECT * FROM other_details WHERE skills=1";
				$my_skills_res=mysqli_query($con,$query_my_skills) or die($con);
				while ($my_skills_rows=mysqli_fetch_assoc($my_skills_res)) {
				$image_id=$my_skills_rows['images'];
		     ?>
			    <div class="bg-light">
			    	<div class="bg-success text-white">
			    		<h3 class="card-title text-center"><?php echo(strtoupper($my_skills_rows['header'])); ?></h3>
			    	</div>
			    	<!-- //Selecting Images present on this Category -->
			    	<?php 
			    		$query_image_skills=@"SELECT * FROM image_collections WHERE ((show_in_skills=1 and status=1) AND content_id='$image_id') ORDER BY id DESC";
			    		$image_skills_res=mysqli_query($con,$query_image_skills) or die($con);
			    		if (mysqli_num_rows($image_skills_res)>0) {
							while ($image_skills_row=mysqli_fetch_assoc($image_skills_res)) {
			    	 ?>
			    	<img class="gallery gallery-item" src="assests/images/<?php echo($image_skills_row['image_name']); ?>" alt="<?php echo($image_skills_row['img_alt_text']); ?>">
			    	<?php } ?>
			    	<?php } ?>
			    	<p class="card-text text-justify px-2">
			    		<?php echo($my_skills_rows['content']); ?>
			    	</p>
			    </div><hr>
				<?php } ?>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Print</button> -->
      </div>
    </div>
  </div>
</div>

<!-- ---------------MY CONTACT DETAILS------------------ -->
<div class="modal fade" id="myContactDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="background-color: #1b5693;">
      <div class="modal-header">
        <h5 class="modal-title text-white">Contact Details</h5><hr>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-success">
      	<!-- ----------Cards-- -->
      	<div class="card mb-3 border-0 px-2" style="max-width: 540px;">
		  <div class="row g-0">
		    <div class="col-md-4 py-3">
		      <img class="gallery-item" src="assests/images/<?php echo($about_user_res['image']); ?>" alt="Image02" style="height:150px;width: 150px; border-radius: 50%;cursor: pointer;"><br>
		    </div>
		    <div class="col-md-8">
		      <div class="card-body">
		        <p class="card-text p-0 m-0">
		        	<span>Name: <?php echo($about_user_res['name']); ?></span><br>
		        	<span>Phone: <label><?php echo($about_user_res['primary_phone']); ?>,</label><label style="padding-left: 55px;"> <?php echo($about_user_res['secondary_phone']); ?></label></span><br>
		        	<span>Address: <?php echo($about_user_res['address']); ?></span><br>
		        	<span>Email: <?php echo($about_user_res['email']); ?></span><br>
		        	<span>Website: <?php echo($about_user_res['website']); ?></span>
		        </p>
		      </div>
		    </div>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Print</button> -->
      </div>
    </div>
  </div>
</div>
<!-- ---------------My WORK DETAILS---------------- -->
<div class="modal fade" id="myWorkDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="background-color: #1b5693;">
      <div class="modal-header">
        <h5 class="modal-title text-white">Work Details</h5><hr>
        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<!-- ----------Cards-- -->
      	<div class="card">
      		<div class="card-body">
		    	<?php 
		    		$query_my_work=@"SELECT * FROM other_details WHERE work=1";
					$my_work_res=mysqli_query($con,$query_my_work) or die($con);
					while ($my_work_rows=mysqli_fetch_assoc($my_work_res)) {
					$image_id=$my_work_rows['images'];
		     	?>
			    <div class="bg-light">
			    	<div class="bg-success text-white">
			    		<h3 class="card-title text-center"><?php echo(strtoupper($my_work_rows['header'])); ?></h3>
			    	</div>
			    	<!-- //Selecting Images present on this Category -->
			    	<?php 
			    		$query_image_work=@"SELECT * FROM image_collections WHERE ((show_in_skills=1 and status=1) AND content_id='$image_id') ORDER BY id DESC";
			    		$image_work_res=mysqli_query($con,$query_image_work) or die($con);
			    		if (mysqli_num_rows($image_work_res)>0) {
							while ($image_work_row=mysqli_fetch_assoc($image_work_res)) {
			    	 ?>
			    	<img class="gallery gallery-item" src="assests/images/<?php echo($image_work_row['image_name']); ?>" alt="<?php echo($image_work_row['img_alt_text']); ?>">
			    	<?php } ?>
			    	<?php } ?>
			    	<p class="card-text text-justify px-2">
			    		<?php echo($my_work_rows['content']); ?>
			    	</p>
			    </div><hr>
				<?php } ?>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Print</button> -->
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

<!-- ----------Viewing Image-------------- -->
<script type="text/javascript">
	document.addEventListener("click",function (e){
   if(e.target.classList.contains("gallery-item")){
   	  const src = e.target.getAttribute("src");
   	  document.querySelector(".modal-img").src = src;
   	  const myModal = new bootstrap.Modal(document.getElementById('gallery-modal'));
   	  myModal.show();
   }
 })

function sendMessage(){
	var name=$('#senderName').val();
	var email=$('#senderEmail').val();
	var message=$('#senderMessage').val();

	$.ajax({
		url:"test.php",
		type: 'post',
		data: {senderName:name,senderEmail:email,senderMessage:message},

		success:function(data,status){
			sendMessage();
		}
	});


}

</script>


<?php include 'assests/includes/footer.php' ?>	