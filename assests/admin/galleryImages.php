<?php 
require '../includes/connection.inc.php';
require '../includes/functions.inc.php';
$now=date("Y-m-d H:i:s");
$errors=array();
$Success="";
$target_dir = "../images/";

if ((!isset($_GET['album_id']) or empty($_GET['album_id'])) or (!isset($_GET['album_name']) or empty($_GET['album_name']))) {
	  header("Location:myblog.php");
	}else{
	  $album_id=clean_values($con,$_GET['album_id']);
	  $album_name=clean_values($con,$_GET['album_name']);
	}



//----------Removing Image
if (isset($_POST['yesRemoveImage'])) {
	$remove_image_id=clean_values($con,$_POST['remove_image_id']);

	$query=@"DELETE FROM image_collections WHERE id=$remove_image_id LIMIT 1";
	$res=insert_update($con,$query);
	if ($res==1) {
		header("Refresh:3");
		$Success="Your image has been deleted sucessfully !!!";
	}else{
		array_push($errors, "Error occur while Removing image. <br> Try again later!!!");
	}
}


//---Adding Images To album
if (isset($_POST['add_images_to_album'])) {
	$album_id=clean_values($con,$album_id);
	$image_alt_text=clean_values($con,$_POST['image_alt_text']);
	if (isset($_POST['add_yes_show_image_in_gallery'])) {
		$gallery_visibility=1;
	}else{
		$gallery_visibility=0;
	}

	// For images
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
				$random = date("ymdhis");
				$file=$random.$ig.".".$imageFileType;
				move_uploaded_file($_FILES['images_name']['tmp_name'][$key],$target_dir.$file);
				$ig+=1;

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
}

// Editing Images

if (isset($_POST['update_image'])) {
	$image_id=clean_values($con,$_POST['update_image_id']);
	$image_alt_text=clean_values($con,$_POST['update_image_alt_text']);
	$image_gallery_visibility=clean_values($con,$_POST['update_image_gallery_visibility']);
	// $album_details=clean_values($con,$_POST['update_album_details']);

	if (empty($_FILES['update_album_image']['name'])) {
		if (count($errors)==0) {

			$update_image_qry=@"UPDATE image_collections SET img_alt_text='$image_alt_text' ,show_in_gallery=$image_gallery_visibility WHERE id= $image_id LIMIT 1";
			$res=insert_update($con,$update_image_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your Image details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating image details<br> Try again Later!!!");
			}	
		}
	}else{
		$target_file = basename($_FILES["update_album_image"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
		$imageSize = $_FILES["update_album_image"]["size"];

		if($imageSize == false) {
			array_push($errors, "File is not an image.");

		}elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		}elseif (count($errors)==0) {
				$random = date("ymdhis");
				$file=$random.".".$imageFileType;
			move_uploaded_file($_FILES['update_album_image']['tmp_name'],$target_dir.$file);

			$update_album_qry=@"UPDATE image_collections SET image_name='$file', img_alt_text='$image_alt_text' ,show_in_gallery=$image_gallery_visibility WHERE id= $image_id LIMIT 1";
			$res=insert_update($con,$update_album_qry);
			if ($res==1) {
				header("Refresh:3");
				$Success="Your image details has been updated sucessfully!";
			}	else{
				array_push($errors, "Error occurs while updating image details<br> Try again Later!!!");
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
			/*height: 450px;
			overflow: hidden;
			overflow-y: scroll;*/
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
					<li><a href="myCv.php" class="nav-link">Skills & CV</a></li>
					<li><a href="myBlog.php" class="nav-link">Blog & Gallery</a></li>
				</ul>
			</div>

			<a href="index.php" class="navbar-brand"><?php echo $about_me_res['name']; ?></a>
			<div class="dropdown">
			  <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><img class="rounded-circle" src="../images/<?php echo($about_me_res['image'].'?nocache='.time()); ?>" style="width: 40px;height: 40px;">
			    Profile
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			    <li><a class="dropdown-item" href="#">Setting</a></li>
			    <li><a class="dropdown-item" href="#">Edit Details</a></li>
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
			
<!-- -----------For albums Images----- -->
				
			<div class="border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-picture-o text-warning py-2">&nbsp;</i><?php echo($album_name)." Images"; ?></h1><hr>
					<p>
						<span style="padding-left: 100px;">
							<button data-bs-toggle="modal" data-bs-target="#modalForAddImagesToAlbum" class="fa fa-plus btn btn-primary" title="Add Images">&nbsp;Images</button>
						</span>
					</p>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">S.N.</th>
					      <th>Category</th>
					      <th scope="col">Image</th>
					      <th>Img Alt Text</th>
					      <th>Status</th>
					      <th></th>
					      <th scope="col">Action</th>
					      <th></th> 
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					  	<?php 
					// Query Album
					$query_image=@"SELECT * FROM image_collections WHERE content_id=$album_id ORDER BY id DESC";
					$image_res=mysqli_query($con,$query_image) or die(error_reporting());
					if (mysqli_num_rows($image_res)>0) {
					$i=0;
				 ?>
					  	<?php while ($image_row=mysqli_fetch_assoc($image_res)) { $i+=1;?>
					  	<tr>
					      <th scope="row"><?php echo($i); ?></i></th>
					      <td><?php echo($image_row['category']); ?></td>
					      <td class="hideContent" id="imageId"><?php echo($image_row['id']); ?></td>
					      <td class="hideContent" id="imageName"><?php echo($image_row['image_name']); ?></td>
					      <td class="hideContent" id="imageGalleryVisibility"><?php echo($image_row['show_in_gallery']); ?></td>
					      <td>
					      	<img src="../images/<?php echo($image_row['image_name'].'?nocache='.time()); ?>" style="cursor: pointer;height: 100px;width: 100px;" class="gallery-item" alt="<?php echo($image_row['img_alt_text']); ?>">
					      </td>
					      <td id="imageAltText"><?php echo($image_row['img_alt_text']); ?></td>
					      <td><?php if ($image_row['show_in_gallery']==1) { ?><span class="fa fa-eye btn btn-success">&nbsp;Visible</span><?php }else{ ?><span class="fa fa-eye-slash btn btn-danger">&nbsp;Hidden</span><?php } ?></td>					      
					      <td>
					      	<button class="fa fa-pencil btn btn-primary editImageDetails" id="editImageDetails" value="<?php echo($image_row['id']); ?>">&nbsp;Edit</i></button>
						  </td>
						  <td>
						  	<button type="button" class="fa fa-trash btn btn-danger removeImages" id="removeImages" value="<?php echo($image_row['id']); ?>">&nbsp;Remove</i>
						  </td>
					    </tr>
					<?php } }else{ ?>
						<div align="center" class="alert-danger py-3 rounded">
							<h1>This Gallery Does not have any Images</h1>
						</div>
					<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>



<!-- ----------------Different Models----------------- -->

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
					<label>Image Alternative Text: </label>
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

<!-- ------------MODAL FOR Editing Image Details-------- -->
	<div class="modal fade" id="modalForEditImage" tabindex="-1">
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
	        	<input type="hidden" name="update_image_id" id="eImageId">
	        	<div class="text-center py-3">
	        		<img src="img1" alt="Album Image" class="gallery-item" style="height: 100px;width: 100px; cursor: pointer;" id="eAlbumImage">
	        		<p class="p-0 text-info">This image will be shown as Album Image.</p>
	        	</div>
	        	<div class="col-auto mb-3">
					<input class="form-control" type="file" name="update_album_image" accept="image/jpeg" accept="image/jpg" accept="image/png" accept="image/gif">					
				</div>
	        	<div class="mb-3">
	        		<label>Visibility:</label>
				  <input class="form-control" type="text" name="update_image_gallery_visibility" id="eImageGalleryVisibility" placeholder="1" minlength="1" maxlength="1">
				  <label class="text-secondary px-2">" Please Enter '1' If you Want to make visible otherwise '0' "</label>
				</div>
				<div class="mb-3">
				  <!-- <input class="form-control" type="text" name="update_album_name" id="eImageName" placeholder="Image Category"> -->
				</div>
				<div class="mb-3">
					<label>Image Alternative text:</label>
				  <input class="form-control" type="text" name="update_image_alt_text" id="eimageAltText" placeholder="Image Alt Text"></input>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="submit" class="btn btn-primary" name="update_image">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- ----------Remove contents--------- -->
	<div class="modal fade" id="modalForRemoveImage" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <p class="modal-title text-danger">Do you Really want to remove?</p>
	      </div>
	      <div class="modal-body text-center">
	      	<form action="" method="post">
	      		<input type="hidden" name="remove_image_id" id="rImageId">
	         <span class="px-4"><button class="btn btn-success" type="button" data-bs-dismiss="modal" aria-label="Close">NO</button></span>
	         <span class="px-4"><button class="btn btn-danger" type="submit" name="yesRemoveImage">YES</button></span>
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
		// -----------Adding Images TO album-------
		$(document).ready(function(){
			$('.removeImages').on('click',function(){
				var removeImagesId= $(this).closest("tr").find("#removeImages").val();
				$("#modalForRemoveImage").modal('show');
				$("#rImageId").val(removeImagesId);
			});
		});

		// -----------Editing Image Details-------
		$(document).ready(function(){
			$('.editImageDetails').on('click',function(){
				var imageName= $(this).closest("tr").find("#imageName").text();
				var imageAltText= $(this).closest("tr").find("#imageAltText").text();
				var imageGalleryVisibility= $(this).closest("tr").find("#imageGalleryVisibility").text();
				var imageId= $(this).closest("tr").find("#editImageDetails").val();
				albumImage='../images/'+imageName;

				$("#modalForEditImage").modal('show');
				$("#eImageName").val(imageName);
				$("#eimageAltText").val(imageAltText);
				$("#eImageGalleryVisibility").val(imageGalleryVisibility);
				$("#eImageId").val(imageId);
				document.querySelector("#eAlbumImage").src = albumImage;
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