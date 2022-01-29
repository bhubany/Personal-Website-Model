<?php 

include 'assests/includes/header.php';
$Success=''; 
$errors=array();

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
			<div class="row col-sm-12 col-lg-12 col-md-12 col-xs-12 px-0">
				<div class="col-sm-2 col-lg-4 col-md-3 col-xs-4 px-0"></div>
				<div class="col-sm-8 col-lg-4 col-md-6 col-xs-4 px-0">
					<div class ="col-sm text-center alert alert-danger fw-bold">
						<h1>One of the Following Error Occurs.<br> Try Again Later.<br> Return To <a href="index.php">Home</a></h1><br>
						<div class="bg-white rounded py-3">
							<h5>400: Bad Request</h5>
							<h5>401: Unauthorized</h5>
							<h5>403: Forbidden</h5>
							<h5>404: Not Found</h5>
							<h5>503: Service Unavailable</h5>
						</div>		
					</div>
				<div class="col-sm-2 col-lg-4 col-md-3 col-xs-4 px-0"></div>
				</div>
			</div>

<div class="fixed-bottom">
<?php include 'assests/includes/footer.php' ?>	
</div>