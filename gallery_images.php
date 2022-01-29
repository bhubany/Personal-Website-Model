<?php 
require 'assests/includes/header.php';
// require 'assests/includes/connection.inc.php';
// require 'assests/includes/functions.inc.php';

if (!isset($_GET['gallery_id'])) {
  header("Location:gallery.php");
}else{
  $gall_id=clean_values($con,$_GET['gallery_id']);
}

  $query_select_images=@"SELECT * FROM image_collections WHERE (content_id=$gall_id AND status=1) AND show_in_gallery=1 ORDER BY id DESC";
  $selecting_images_res=mysqli_query($con,$query_select_images) or die(error_reporting(0));
  if (!$selecting_images_res) {
    die($con);
  }
  if (!mysqli_num_rows($selecting_images_res) > 0) {
      header("Location:gallery.php");
  }

// Album Details-----
$query_album=@"SELECT album_details FROM gallery_album WHERE id=$gall_id";
  $album_detail_res=fetch($con,$query_album);


  // Query About Me
$query_about_me=@"SELECT * FROM about_user";
$about_me_res=fetch($con,$query_about_me);

 ?>

<!DOCTYPE html>
<html>

<body><head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lorem-ipsum Gallery</title>
	<link rel="stylesheet" type="text/css" href="assests/css/myStyle.css">
	<link rel="stylesheet" type="text/css" href="assests/css/bootstrap.min.css">
	<script type="text/javascript" src="assests/js/jquery.min.js"></script>
	<script src="assests/js/bootstrap.bundle.min.js"></script>
	<script src="assests/js/bootstrap.min.js"></script>
	<style type="text/css">
		img{
			max-width: 100%;
		}
		.gallery{
			padding: 80px 0;
			border-radius: 50px;
		}
		.gallery img{
			background-color: #ffffff;
			padding: 15px;
			width: 350px;
      height: 350px;
			box-shadow: 0 0 15px rgba(0,0,0,0.3);
			cursor: pointer;
		}
		#gallery-modal .modal-img{
			width: 100%;
		}
	</style>
</head>
<body style="">
<div class="container-lg"><br>
  <nav class="navbar navbar-light">
  	<p class="border-primary text-center" style="padding-top: 10px;">
  		<a href="index.php" style="text-decoration: none;color: white; padding: 15px;">Home</a>
  		<a href="gallery.php" style="text-decoration: none;color: white; padding: 15px;">Gallery</a>
  	</p>
  	<h1 class="text-white" style="font-family: serif; font-style: italic;padding-top: 30px;"><?php echo($about_me_res['name']); ?> Gallery</h1>
  </nav>

  <!-- -------------------Start of gallery Section -->
  <section class="gallery">
       <div class="container-lg">
          <div class="text-center text-secondary card py-2">
              <p><?php echo($album_detail_res['album_details']); ?></p>
          </div><br>
          <div class="row gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
            <?php while ($selecting_images_rows=mysqli_fetch_assoc($selecting_images_res)) {?>
             <div class="col">
                <img src="assests/images/<?php echo($selecting_images_rows['image_name']); ?>" class="gallery-item" alt="<?php echo($selecting_images_rows['img_alt_text']); ?>">
             </div>
           <?php } ?>
          </div>
       </div>
  </section>
</div>

<!-- Modal -->
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
</script>

<?php include 'assests/includes/footer.php' ?>