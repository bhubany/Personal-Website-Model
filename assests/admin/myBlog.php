<?php 
require '../includes/connection.inc.php';
require '../includes/functions.inc.php';
date_default_timezone_set('Asia/Kathmandu');
$now=date("Y-m-d H:i:s");
$errors=array();
$Success="";
$target_dir = "../images/";


// changing Password
if (isset($_POST['change_password'])) {
	$old_password=clean_values($con,$_POST['change_old_password']);
	$new_password=clean_values($con,$_POST['change_new_password']);
	$conform_password=clean_values($con,$_POST['change_conform_password']);

	// Password Validation
	$number = preg_match('@[0-9]@', $password);
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);
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


//----------Removing Blog
if (isset($_POST['yesRemoveBlog'])) {
	$remove_blog_id=clean_values($con,$_POST['remove_blog_id']);

	// REmoving Images first of all from gallery
	$qry_img=@"SELECT image FROM blogs WHERE id=$remove_blog_id";
	$img_res=fetch($con,$qry_img);
	$img_id=$img_res['image'];
	if (!empty($img_id) && $img_id!=0) {
		$query_remove_img=@"DELETE FROM image_collections WHERE content_id=$img_id";
		$res=insert_update($con,$query_remove_img);
		if ($res==1) {
			$Success="Your Blog Images has been deleted sucessfully !!!";
		}else{
			array_push($errors, "Error occur while Removing Images. <br> Try again later!!!");
		}
	}
	$query=@"DELETE FROM blogs WHERE id=$remove_blog_id LIMIT 1";
	$res=insert_update($con,$query);
	if ($res==1) {
		header("Refresh:3");
		$Success="Your Blog has been deleted sucessfully !!!";
	}else{
		array_push($errors, "Error occur while Removing blog. <br> Try again later!!!");
	}
}


// Editing Blog

if (isset($_POST['update_blog'])) {
	$blog_id=clean_values($con,$_POST['update_blog_id']);
	$blog_title=clean_values($con,$_POST['update_blog_title']);
	$blog_short_desc=clean_values($con,$_POST['update_blog_short_desc']);
	$blog_content=clean_values($con,$_POST['update_blog_content']);
	$blog_visibility=clean_values($con,$_POST['update_blog_visibility']);

	if (empty($_FILES['update_blog_cover_image']['name'])) {
		if (count($errors)==0) {

			$update_blog_qry=@"UPDATE blogs SET title='$blog_title' , short_desc='$blog_short_desc',full_details='$blog_content',status=$blog_visibility WHERE id= $blog_id LIMIT 1";
			$res=insert_update($con,$update_blog_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your album details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating album details<br> Try again Later!!!");
			}	
		}
	}else{
		$target_file = basename($_FILES["update_blog_cover_image"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
		$imageSize = $_FILES["update_blog_cover_image"]["size"];

		if($imageSize == false) {
			array_push($errors, "File is not an image.");

		}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		}elseif (count($errors)==0) {
			$file=date("Ymdhis").".".$imageFileType;
			move_uploaded_file($_FILES['update_blog_cover_image']['tmp_name'],$target_dir.$file);

			$update_blog_qry=@"UPDATE blogs SET title='$blog_title',cover_image='$file' ,short_desc='$blog_short_desc', full_details='$blog_content',status=$blog_visibility WHERE id= $blog_id LIMIT 1";
			$res=insert_update($con,$update_blog_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your album details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating album details<br> Try again Later!!!");
			}	
		}
	}
}


// ADDING BLOG
if (isset($_POST['upload_blog'])) {
	$blog_title=clean_values($con,$_POST['add_blog_title']);
	$blog_short_desc=clean_values($con,$_POST['add_blog_short_details']);
	$blog_content=clean_values($con,$_POST['add_blog_contents']);
	if (isset($_POST['add_publish_blog'])) {
		$visibility=1;
	}else{
		$visibility=0;
	}
	$blog_by="Sujit Khatri";

//validation
	if (empty($blog_title)) {
		array_push($errors, "Please Enter Appropriate Title");
	}

	if (empty($blog_content)) {
		array_push($errors, "Please Enter Blog Body Content");
	}

	if (empty($_FILES['add_blog_file']['name']['0'])) {
		if (count($errors)==0) {
			$cover_img="blogImage.jpg";

			$add_blog_qry=@"INSERT INTO blogs (title, cover_image,short_desc, full_details, blog_by,status) VALUES('$blog_title','$cover_img','$blog_short_desc','$blog_content','$blog_by',$visibility) LIMIT 1";
			$res=insert_update($con,$add_blog_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your blog has been uploaded sucessfully!";
			}	else{
				array_push($errors, "Error occurs while uploading blog.<br> Try again Later!!!");
			}	
		}
	}else{
		$image=date("Ymdhis");
		// Inserting Images
	$ig=0;
	foreach ($_FILES['add_blog_file']['name'] as $key=>$val){
		$target_file = basename($_FILES["add_blog_file"]["name"][$key]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$imageSize = $_FILES["add_blog_file"]["size"][$key];
		if($imageSize == false) {

			array_push($errors, "File is not an image.");

		}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
			array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		}else{
			if (count($errors)==0) {
				$random = date("Ymdhis");
				$file=$random.$ig.".".$imageFileType;
				$ig+=1;
				move_uploaded_file($_FILES['add_blog_file']['tmp_name'][$key],$target_dir.$file);

				$query=@"INSERT INTO image_collections (image_name, img_alt_text, show_in_blogs, content_id, category) VALUES('$file','blog',$visibility,$image,'blog')";

				$res=insert_update($con,$query);
				if ($res==1) {
					$Success="Your blog Image has been uploaded sucessfully!";
				}	else{
					array_push($errors, "Error occurs while uploading Images<br> Try again Later!!!");
				}	
			}
		}
	}

	// Adding Details in table with cover image	
		if (count($errors)==0) {

			$add_blog_qry=@"INSERT INTO blogs (title, cover_image,image,short_desc, full_details, blog_by,status) VALUES('$blog_title','$file',$image,'$blog_short_desc','$blog_content','$blog_by',$visibility) LIMIT 1";
			$res=insert_update($con,$add_blog_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your blog has been uploaded sucessfully!";
			}	else{
				array_push($errors, "Error occurs while uploading blog.<br> Try again Later!!!");
			}	
		}
	}
}




//----------Removing Album
if (isset($_POST['yesRemoveAlbum'])) {
	$remove_album_id=clean_values($con,$_POST['remove_album_id']);

	$query=@"DELETE FROM gallery_album WHERE id=$remove_album_id LIMIT 1";
	$res=insert_update($con,$query);
	if ($res==1) {
		header("Refresh:3");
		$Success="Your album has been deleted sucessfully !!!";
	}else{
		array_push($errors, "Error occur while Removing Album. <br> Try again later!!!");
	}
}

//---ADDING ALBUM
if (isset($_POST['add_album'])) {
	$album_name=clean_values($con,$_POST['add_album_name']);
	$album_details=clean_values($con,$_POST['add_album_details']);
	if (isset($_POST['add_yes_show_on_gallery'])) {
		$visibility=1;
	}else{
		$visibility=0;
	}

	$target_file = basename($_FILES["add_album_cover_image"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$imageSize = $_FILES["add_album_cover_image"]["size"];
	if($imageSize == false) {

		array_push($errors, "Please Select Images.");

	}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
		array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
	}else{
		if (count($errors)==0) {
				// $random = $album_name;
				$file=$album_name.".".$imageFileType;
				move_uploaded_file($_FILES['add_album_cover_image']['tmp_name'],$target_dir.$file);

				$query=@"INSERT INTO gallery_album (album_name,album_details,cover_image,status) VALUES('$album_name','$album_details','$file',$visibility)";
				$res=insert_update($con,$query);
				if ($res==1) {
					header("Refresh:3");
					$Success="Your album has been Created sucessfully!";
				}	else{
					array_push($errors, "Error occurs while Creating Album.<br> Try again Later!!!");
				}	
		}
	}

}



//---Adding Images To album
if (isset($_POST['add_images_to_album'])) {
	$album_id=clean_values($con,$_POST['add_image_to_album_id']);
	$image_alt_text=clean_values($con,$_POST['image_alt_text']);
	if (isset($_POST['add_yes_show_image_in_gallery'])) {
		$gallery_visibility=1;
	}else{
		$gallery_visibility=0;
	}
		$ig=0;
		foreach ($_FILES['images_name']['name'] as $key=>$val){
			$target_file = basename($_FILES["images_name"]["name"][$key]);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$imageSize = $_FILES["images_name"]["size"][$key];
			if($imageSize == false) {

				array_push($errors, "File is not an image.");

			}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
			}else{
				if (count($errors)==0) {
					$random = date("Ymdhis");
					$file=$random.$ig.".".$imageFileType;
					$ig+=1;
					move_uploaded_file($_FILES['images_name']['tmp_name'][$key],$target_dir.$file);

					$query=@"INSERT INTO image_collections (image_name, img_alt_text, show_in_gallery, content_id, category) VALUES('$file','$image_alt_text',$gallery_visibility,$album_id,'gallery')";
					$res=insert_update($con,$query);
					if ($res==1) {
						header("Refresh:3");
						$Success="Your Image has been uploaded sucessfully!";
					}	else{
						array_push($errors, "Error occurs while uploading Images<br> Try again Later!!!");
					}	
				}
			}
		}
	// }
}

// Editing Album

if (isset($_POST['update_album'])) {
	$album_id=clean_values($con,$_POST['update_album_id']);
	$album_name=clean_values($con,$_POST['update_album_name']);
	$album_visibility=clean_values($con,$_POST['update_album_visibility']);
	$album_details=clean_values($con,$_POST['update_album_details']);

	if (empty($_FILES['update_album_cover_image']['name'])) {
		if (count($errors)==0) {

			$update_album_qry=@"UPDATE gallery_album SET album_name='$album_name' ,album_details='$album_details', status=$album_visibility WHERE id= $album_id LIMIT 1";
			$res=insert_update($con,$update_album_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your album details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating album details<br> Try again Later!!!");
			}	
		}
	}else{
		$target_file = basename($_FILES["update_album_cover_image"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
		$imageSize = $_FILES["update_album_cover_image"]["size"];

		if($imageSize == false) {
			array_push($errors, "File is not an image.");

		}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		}elseif (count($errors)==0) {
			$file=$album_name.".".$imageFileType;
			move_uploaded_file($_FILES['update_album_cover_image']['tmp_name'],$target_dir.$file);

			$update_album_qry=@"UPDATE gallery_album SET album_name='$album_name' ,album_details='$album_details',cover_image='$file', status=$album_visibility WHERE id= $album_id LIMIT 1";
			$res=insert_update($con,$update_album_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your album details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating album details<br> Try again Later!!!");
			}	
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
			<!-- //--My blog -->
					<?php 
			// Query blog
				$query_blog=@"SELECT * FROM blogs ORDER BY id DESC";
				$blog_res=mysqli_query($con,$query_blog) or die(error_reporting());
				$i=0;
		 ?>
			<div class="border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-id-card text-in">&nbsp;</i>My Blogs</h1><hr>
					<p>
						<span style="padding-left: 100px;">
							<button data-bs-toggle="modal" data-bs-target="#modalForAddBlog" class="fa fa-plus btn btn-primary" title="Add Blog">&nbsp;Blog </button>
						</span>
						<span style="padding-left: 100px;">
							<button data-bs-toggle="modal" data-bs-target="#modalForViewBlogNotification" class="fa fa-envelope btn btn-danger" title="Notifications">&nbsp;Notification</Button>
						</span>
					</p>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">S.N.</th>
					      <th scope="col">Title</th>
					      <th scope="col">Image/ Short Desc</th>
					      <th>Blog Contents</th>
					      <th>Cover Image</th>
					      <th>Status</th>
					      <th></th>
					      <th scope="col">Action</th>
					      <th></th>     
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					  	<?php while ($blog_row=mysqli_fetch_assoc($blog_res)) {  $i+=1;?>
					  	<tr>
					      <th scope="row"><?php echo($i); ?></i></th>
					      <td id="blogTitle"><?php echo($blog_row['title']); ?></td>
					      <td id="blogShortDesc"><?php echo($blog_row['short_desc']); ?></td>					      
					      <td id="BlogContent"><?php echo($blog_row['full_details']); ?></td>
					      <td class="hideContent" id="BlogCoverImage"><?php echo($blog_row['cover_image']); ?></td>
					      <td class="hideContent" id="blogVisibility"><?php echo($blog_row['status']); ?></td>
					      <td><img src="../images/<?php echo($blog_row['cover_image'].'?nocache='.time()); ?>" style="cursor: pointer;height: 100px;width: 100px;" class="gallery-item" alt="blog cover image"></td>
					      <td><?php if ($blog_row['status']==1) { ?><span class="fa fa-eye btn btn-success">&nbsp;Visible</span><?php }else{ ?><span class="fa fa-eye-slash btn btn-danger">&nbsp;Hidden</span><?php } ?></td>
						  <td>
					      	<a href="viewBlog.php?blog_id=0<?php echo($blog_row['id']); ?>"><button class="fa fa-eye btn btn-secondary" title="View Blog" id="editBlogDetails">&nbsp;Blog</button></a>
						  </td>
					      <td>
					      	<button class="fa fa-pencil btn btn-primary editBlogDetails" title="Edit" id="editBlogDetails" value="<?php echo($blog_row['id']) ?>">&nbsp;Edit</button>
						  </td>
						  <td>
					      	<a href="galleryImages.php?album_id=<?php echo($blog_row['image']."&& album_name=".$blog_row['title']); ?>"><button class="fa fa-eye btn btn-success" title="view Images">&nbsp;Images</button></a>
					      </td>
						  <td>
						  	<button class="fa fa-trash btn btn-danger removeBlog" value="<?php echo($blog_row['id']); ?>" id="removeBlog">&nbsp;Remove</button>
						  </td>
					    </tr>
					<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>

<!-- -----------For albums----- -->
<?php 
	// Query Album
	$query_album=@"SELECT * FROM gallery_album ORDER BY id DESC";
	$album_res=mysqli_query($con,$query_album) or die(error_reporting());
	$i=0;

 ?>

			<div class="border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-picture-o">&nbsp;</i>My Gallery</h1><hr>
					<p>
						<span style="padding-left: 100px;">
							<button data-bs-toggle="modal" data-bs-target="#modalForAddAlbum" class="fa fa-plus btn btn-primary" title="Add Albums">&nbsp;Album</button>
						</span>
					</p>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">S.N.</th>
					      <th scope="col">Album Name</th>
					      <th scope="col">Cover Image</th>
					      <th>Album Details</th>
					      <th></th>
					      <th scope="col">Action</th>
					      <th></th>
					      <th></th> 
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					  	<?php while ($album_row=mysqli_fetch_assoc($album_res)) {  $i+=1;?>
					  	<tr>
					      <th scope="row"><?php echo($i); ?></i></th>
					      <td id="albumName"><?php echo($album_row['album_name']); ?></td>
					      <td>
					      	<img src="../images/<?php echo($album_row['cover_image'].'?nocache='.time()); ?>" style="cursor: pointer;height: 100px;width: 100px;" class="gallery-item" alt="<?php echo($album_row['album_name']); ?>">
					      </td>
					      <td class="hideContent" id="albumVisibility"><?php echo($album_row['status']); ?></td>
					      <td class="hideContent" id="albumCoverImage"><?php echo($album_row['cover_image']); ?></td>
					      <td class="px-1" id="albumDetails"><?php echo($album_row['album_details']); ?>
					      </td>
					      <td>
					      	<button class="fa fa-plus btn btn-secondary addImagesToAlbum" id="addImagesToAlbum" title="Add Images to Album" value="<?php echo($album_row['id']); ?>">&nbsp;Image</button>
					      </td>
					      <td>
					      	<a href="galleryImages.php?album_id=<?php echo($album_row['id']."&& album_name=".$album_row['album_name']); ?>">
					      		<button class="fa fa-eye btn btn-success" id="">&nbsp;View</button></a>
					      </td>
					      <td>
					      	<button class="fa fa-pencil btn btn-primary editAlbumDetails" id="editAlbumDetails" value="<?php echo($album_row['id']); ?>">&nbsp;Edit</i></button>
						  </td>
						  <td>
						  	<button type="button" class="fa fa-trash btn btn-danger removeAlbumContent" id="removeAlbumContent"  value="<?php echo($album_row['id']); ?>">&nbsp;Remove</i>
						  </td>
					    </tr>
					<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>



<!-- ----------------Different Models----------------- -->
	<!-- ------------MODAL FOR Add Blogs-------- -->
	<div class="modal fade" id="modalForAddBlog" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Blog Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form" method="post" enctype="multipart/form-data">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" checked="on" value="1" name="add_publish_blog">
				  <label class="form-check-label" for="forCheckBox">
				    Publish My Blog
				  </label>
				</div>
	        	<div class="mb-3">
	        		<input class="form-control" name="add_blog_file[]" type="file" id="" multiple="on/">
	        	</div>
	        	<div class="mb-3">
				  <textarea class="form-control" name="add_blog_title" type="text" id="formFile" placeholder="Enter Topic" rows="2"></textarea>
				</div>
				<div class="mb-3">
				  <textarea class="form-control" name="add_blog_short_details" type="text" id="formFile" placeholder="Image / Short Description" rows="3"></textarea>
				</div>
				<div class="mb-3">
				  <textarea class="form-control" name="add_blog_contents" type="text" id="formFile" placeholder="Enter details.." rows="10"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" name="upload_blog" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- ------------MODAL FOR Editing Blog Contents-------- -->
	<div class="modal fade" id="modalForEditBlog" tabindex="-1">
	  <div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<div class="modal-title">
	        	<h5>Enter Your Blog Details</h5>
	        </div>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form" method="post">
	        	<input type="text" name="update_blog_id" id="eBlogId">
	        	<div class="text-center py-3">
	        		<img src="img1" alt="defaut" class="gallery-item" style="height: 100px;width: 100px; cursor: pointer;" id="eBlogCoverImage">
	        		<p class="p-0 text-info">This image will be shown as blog Cover Image.</p>
	        	</div>
	        	<div class="col-auto mb-3">
					<input class="form-control" type="file" name="update_blog_cover_image" accept="image/jpeg" accept="image/jpg" accept="image/png" accept="image/gif">					
				</div>
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="eBlogVisibility" name="update_blog_visibility" placeholder="1" minlength="1" maxlength="1">
				  <label class="text-secondary px-2">" Please Enter '1' If you Want to make visible otherwise '0' "</label>
				</div>
	        	<div class="mb-3">
				  <textarea class="form-control" name="update_blog_title" type="text" id="eBlogTitle" placeholder="Enter Topic" rows="2"></textarea>
				</div>
				<div class="mb-3">
				  <textarea class="form-control" name="update_blog_short_desc" type="text" id="eBlogShortDesc" placeholder="Image / Short Description" rows="3"></textarea>
				</div>
				<div class="mb-3">
				  <textarea class="form-control" name="update_blog_content" type="text" id="eBlogContent" placeholder="Enter details.." rows="10"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" name="update_blog" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- ------------MODAL FOR Adding Albums-------- -->
	<div class="modal fade" id="modalForAddAlbum" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Album Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form" method="post" enctype="multipart/form-data">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked="on" value="1" name="add_yes_show_on_gallery">
				  <label class="form-check-label" for="flexCheckDefault">
				    Show in Gallery
				  </label>
				</div>
				<div class="col-auto mb-3">
					<input class="form-control" type="file" id="formFile" name="add_album_cover_image">					
				</div>
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" name="add_album_name" placeholder="Album Name">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" name="add_album_details" placeholder="Album Details" rows="3"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" class="btn btn-primary" name="add_album">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>
<!-- ------------MODAL FOR Adding Images to Albums-------- -->
	<div class="modal fade" id="modalForAddImagesToAlbum" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Image Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form" method="post" enctype="multipart/form-data">	
	        	<input type="hidden" id="addImageAlbumId" name="add_image_to_album_id">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked="on" name="add_yes_show_image_in_gallery">
				  <label class="form-check-label" for="flexCheckDefault">
				    Show in Gallery
				  </label>
				</div>
				<div class="col-auto mb-3">
					<input class="form-control" type="file" name="images_name[]" accept="image/jpeg" accept="image/jpg" accept="image/png" accept="image/gif" multiple=""/>					
				</div>
				<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Image Alternative Text" name="image_alt_text">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" class="btn btn-primary" name="add_images_to_album">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>
<!-- ------------MODAL FOR Editing Album Details-------- -->
	<div class="modal fade" id="modalForEditAlbum" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	      	<div class="modal-title">
	      		<h5>Edit Your Album Details</h5>
	        </div>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form" method="post" enctype="multipart/form-data">
	        	<input type="hidden" name="update_album_id" id="eAlbumId">
	        	<div class="text-center py-3">
	        		<img src="img1" alt="defaut" class="gallery-item" style="height: 100px;width: 100px; cursor: pointer;" id="eAlbumCoverImage">
	        		<p class="p-0 text-info">This image will be shown as Album Cover Image.</p>
	        	</div>
	        	<div class="col-auto mb-3">
					<input class="form-control" type="file" name="update_album_cover_image" accept="image/jpeg" accept="image/jpg" accept="image/png" accept="image/gif">					
				</div>
	        	<div class="mb-3">
				  <input class="form-control" type="text" name="update_album_visibility" id="eAlbumVisibility" placeholder="1" minlength="1" maxlength="1">
				  <label class="text-secondary px-2">" Please Enter '1' If you Want to make visible otherwise '0' "</label>
				</div>
				<div class="mb-3">
				  <input class="form-control" type="text" name="update_album_name" id="eAlbumName" placeholder="Album Name">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" name="update_album_details" id="eAlbumDetails" placeholder="Album Details Details..." rows="3"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" class="btn btn-primary" name="update_album">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- ----------Remove Blog--------- -->
	<div class="modal fade" id="modalForRemoveBlog" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Do you Really want to remove?</p>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<input type="hidden" name="remove_blog_id" id="removeBlogId">
	         <span class="px-4"><button class="btn btn-success" type="button" data-bs-dismiss="modal" aria-label="Close">NO</button></span>
	         <span class="px-4"><button class="btn btn-danger" type="submit" name="yesRemoveBlog">YES</button></span>
	         </form>
	      </div>
	    </div>
	  </div>
	</div>
	</div>

<!-- ----------Remove Album--------- -->
	<div class="modal fade" id="modalForRemoveAlbum" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Do you Really want to remove?</p>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<input type="hidden" name="remove_album_id" id="removeAlbumId">
	         <span class="px-4"><button class="btn btn-success" type="button" data-bs-dismiss="modal" aria-label="Close">NO</button></span>
	         <span class="px-4"><button class="btn btn-danger" type="submit" name="yesRemoveAlbum">YES</button></span>
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

		// -- ----------Removing Blog--------------
		$(document).ready(function(){
			$('.removeBlog').on('click',function(){
				var contentId= $(this).closest("tr").find("#removeBlog").val();

				$("#modalForRemoveBlog").modal('show');
				$("#removeBlogId").val(contentId);
			});
		});


		// -----------Editing Blog Details-------
		$(document).ready(function(){
			$('.editBlogDetails').on('click',function(){
				var blogTitle= $(this).closest("tr").find("#blogTitle").text();
				var blogShortDesc= $(this).closest("tr").find("#blogShortDesc").text();
				var BlogContent= $(this).closest("tr").find("#BlogContent").text();
				var blogVisibility= $(this).closest("tr").find("#blogVisibility").text();
				var blogId= $(this).closest("tr").find("#editBlogDetails").val();
				var BlogCoverImage= $(this).closest("tr").find("#BlogCoverImage").text();
				BlogCoverImage='../images/'+BlogCoverImage;

				$("#modalForEditBlog").modal('show');
				$("#eBlogTitle").val(blogTitle);
				$("#eBlogShortDesc").val(blogShortDesc);
				$("#eBlogContent").val(BlogContent);
				$("#eBlogVisibility").val(blogVisibility);
				$("#eBlogId").val(blogId);
				document.querySelector("#eBlogCoverImage").src = BlogCoverImage;
			});
		});

		// -----------Adding Images TO album-------
		$(document).ready(function(){
			$('.addImagesToAlbum').on('click',function(){
				var addImagesId= $(this).closest("tr").find("#addImagesToAlbum").val();
				$("#modalForAddImagesToAlbum").modal('show');
				$("#addImageAlbumId").val(addImagesId);
			});
		});

		// -----------Editing Album Details-------
		$(document).ready(function(){
			$('.editAlbumDetails').on('click',function(){
				var albumName= $(this).closest("tr").find("#albumName").text();
				var albumDetails= $(this).closest("tr").find("#albumDetails").text();
				var albumVisibility= $(this).closest("tr").find("#albumVisibility").text();
				var albumId= $(this).closest("tr").find("#editAlbumDetails").val();
				var albumImage= $(this).closest("tr").find("#albumCoverImage").text();
				albumImage='../images/'+albumImage;

				$("#modalForEditAlbum").modal('show');
				$("#eAlbumName").val(albumName);
				$("#eAlbumDetails").val(albumDetails);
				$("#eAlbumVisibility").val(albumVisibility);
				$("#eAlbumId").val(albumId);
				document.querySelector("#eAlbumCoverImage").src = albumImage;
			});
		});

		// -- ----------Removing Album--------------
		$(document).ready(function(){
			$('.removeAlbumContent').on('click',function(){
				var contentId= $(this).closest("tr").find("#removeAlbumContent").val();

				$("#modalForRemoveAlbum").modal('show');
				$("#removeAlbumId").val(contentId);
			});
		});

		// <!-- ----------Viewing Image-------------- -->
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