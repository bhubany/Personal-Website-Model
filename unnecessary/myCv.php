<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lorem Ipsum</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/myStyle.css">
	<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
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
		
	</style>
</head>
<body style="background-image: none;">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<button class="navbar-toggler type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
			<div class="collapse navbar-collapse" id="navbarMenu">
				<ul class="navbar-nav mr-auto">
					<li><a href="" class="nav-link">Messages</a></li>
					<li><a href="myCv.html" class="nav-link">Skills & CV</a></li>
					<li><a href="" class="nav-link">Work</a></li>
					<li><a href="" class="nav-link">Blog</a></li>
					<li><a href="" class="nav-link">Gallery</a></li>
				</ul>
			</div>

			<a href="dashboard.php" class="navbar-brand">Lorem Ipsum</a>
			<div class="dropdown">
			  <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><img class="rounded-circle" src="images/image01.jpg" style="width: 30px;height: 30px;">
			    Profile
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
			    <li><a class="dropdown-item" href="#">Setting</a></li>
			    <li><a class="dropdown-item" href="#">Edit Details</a></li>
			    <li><a class="dropdown-item" href="#">Logout</a></li>
			  </ul>
			</div>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row gy-4 row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-2 py-3 px-4">
			<div class="col border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-id-card">&nbsp;</i>My CV</h1><hr>
					<p>
						<span style="padding-left: 100px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForAddEducation"> <i class="fa fa-plus" title="Add skills"></i></span>
						<span style="padding-left: 100px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewCv"> <i class="fa fa-eye" title="view CV"></i></span>
					</p>
					<div class="text-center">
						<img src="images/image01.jpg" style="height: 150px;width: 150px;cursor: pointer;" class="gallery-item rounded-circle">
					</div>
					<form class="row  py-4 px-5">
						<div class="col-auto">
						  <input class="form-control" type="file" id="formFile">
						</div>
						<div class="col-auto">
						  <button class="btn btn-primary mb-3" type="submit" id="formFile">Update</button>
						</div>
					</form>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info-circle icon"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>
					      <th></th>
					      <th scope="col">Action</th>	
					      <th></th>	      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					  	<tr>
					      <th scope="row"><i class="fa fa-check icon"></i></th>
					      <td>Greetings</td>
					      <td>Hello & Welcome</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForGreet">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-user icon"></i></th>
					      <td>Name</td>
					      <td>Lorem Ipsum</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForName">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-graduation-cap"></i></th>
					      <td>Education Title</td>
					      <td>Software Engineer</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEducationTitle">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-calendar"></i></th>
					      <td>DOB </td>
					      <td>18 Apr 1997 (23 y)</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForDob">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-male"></i></th>
					      <td>SEX </td>
					      <td>Male</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForSex">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-phone-square icon"></i></th>
					      <td>Phone (Primary)</td>
					      <td>+977 98xxxxxxxx</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForPrimaryPhone">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-phone-square icon"></i></th>
					      <td>Phone (Secondary)</td>
					      <td>+977 98xxxxxxxx</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForSecondaryPhone">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-globe"></i></th>
					      <td>Website</td>
					      <td>www.loremipsum.com</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForWebsite">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><i class="fa fa-facebook-square"></i></th>
					      <td>Social Media</td>
					      <td>Show in footer</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForSocialMedia">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    
					    <tr>
					      <th scope="row"><i class="fa fa-cogs"></i></th>
					      <td>Skills</td>
					      <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					      consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
					      cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					      proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</td>
					      <td><button class="btn btn-danger">Hide</button></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditEducation">
							  Edit
							</button></td>
							<td><button class="btn btn-danger">Remove</button></td>
					    </tr>
					  </tbody>
					</table>
				</div>
			</div>
			<!-- ----------------FOR Skills----------- -->
			<div class="col border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-cogs">&nbsp;</i>My Skills</h1><hr>
					<div>						
						<a type="btn" data-bs-toggle="modal" data-bs-target="#modalForAddingSkillls" style="padding-left: 120px; cursor: pointer;" title="create message"><i class="fa fa-plus"></i></a>
						<a type="btn" data-bs-toggle="modal" data-bs-target="#modalForViewSkills"  style="padding-left: 100px; cursor: pointer;" title="delete"><i class="fa fa-eye"></i></a>
					</div>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>
					      <th colspan="2">Action</th>	
					      <th></th>		      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					    <tr>
					      <th scope="row"><img src="images/Python.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Python</a></td>
					      <td >Lorem ipsum dolor sit amet</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditSkillsCategory">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><img src="images/Python.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Python</a></td>
					      <td >Lorem ipsum dolor sit amet</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditSkillsCategory">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><img src="images/Python.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Python</a></td>
					      <td >Lorem ipsum dolor sit amet</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditSkillsCategory">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					  </tbody>
					</table>
				</div>
			</div>

			<!-- ----------------FOR Working Details----------- -->
			<div class="col border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-briefcase">&nbsp;</i>My Working Details</h1><hr>
					<div>						
						<a type="btn" data-bs-toggle="modal" data-bs-target="#modalForAddWorkDetails" style="padding-left: 120px; cursor: pointer;" title="create message"><i class="fa fa-plus"></i></a>
						<a type="btn" data-bs-toggle="modal" data-bs-target="#modelForViewWorkDetails"  style="padding-left: 100px; cursor: pointer;" title="delete"><i class="fa fa-eye"></i></a>
					</div>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>
					      <th colspan="2">Action</th>	
					      <th></th>		      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					    <tr>
					      <th scope="row"><img src="images/google.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Google</a></td>
					      <td >I work on google since ....</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditWorkDetails">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><img src="images/google.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Google</a></td>
					      <td >I work on google since ....</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditWorkDetails">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><img src="images/google.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Google</a></td>
					      <td >I work on google since ....</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditWorkDetails">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					  </tbody>
					</table>
				</div>
			</div>

	<!-- ----------------FOR Gallery----------- -->
			<div class="col border-primary myDetailsBox">
				<div class="table-responsive-md tableViews">
					<h1 align="center"><i class="fa fa-file-picture-o">&nbsp;</i>My Gallery</h1><hr>
					<div>						
						<a type="btn" data-bs-toggle="modal" data-bs-target="#modalForAddingSkillls" style="padding-left: 120px; cursor: pointer;" title="create message"><i class="fa fa-plus"></i></a>
						<a type="btn" data-bs-toggle="modal" data-bs-target="#modalForViewSkills"  style="padding-left: 100px; cursor: pointer;" title="delete"><i class="fa fa-eye"></i></a>
					</div>
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col"><i class="fa fa-info"></i></th>
					      <th scope="col">Parameter</th>
					      <th scope="col">Value</th>
					      <th colspan="2">Action</th>	
					      <th></th>		      
					    </tr>
					  </thead>
					  <tbody class="overflow-scroll">
					    <tr>
					      <th scope="row"><img src="images/Python.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Pokhra Tour</a></td>
					      <td ><span ><img style="height: 50px; width: 50px; cursor: pointer;" src="images/image01.jpg" class="gallery-item"></span></td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditSkillsCategory">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><img src="images/Python.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Python</a></td>
					      <td >Lorem ipsum dolor sit amet</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditSkillsCategory">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					    <tr>
					      <th scope="row"><img src="images/Python.png" style="height: 50px; width: 50px;cursor: pointer;" class="gallery-item"></th>
					      <td class="text-info"><a type="btn" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalForViewSkillsCategory">Python</a></td>
					      <td >Lorem ipsum dolor sit amet</td>
					      <td><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForEditSkillsCategory">
							  EDIT
							</button></td>
					      <td ><button class="btn btn-danger">Remove</button></td>
					    </tr>
					  </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


	<!-- ------------MODAL FOR Add Education-------- -->
	<div class="modal fade" id="modalForAddEducation" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Education Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Enter Topic">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" placeholder="Enter details" rows="4"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Greetings-------- -->
	<div class="modal fade" id="modalForGreet" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Greetings!</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" placeholder="Hello and Welcome" rows="2"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Editing Name-------- -->
	<div class="modal fade" id="modalForName" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Full Name</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="lorem ipsum">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Education Title-------- -->
	<div class="modal fade" id="modalForEducationTitle" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Education Title</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Software Engineer">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR DOB-------- -->
	<div class="modal fade" id="modalForDob" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your DOB</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="date" id="formFile" placeholder="1997/APR/18">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------ MOdal For Select Your Sex-------- -->
	<div class="modal fade" id="modalForSex" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Select Your Sex</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="form-check">
				  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
				  <label class="form-check-label" for="flexRadioDefault1">
				    Male
				  </label>
				</div>
				<div class="form-check">
				  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
				  <label class="form-check-label" for="flexRadioDefault2">
				    Female
				  </label>
				</div>
				<div class="form-check">
				  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
				  <label class="form-check-label" for="flexRadioDefault2">
				    Other
				  </label>
				</div>
				<div class="form-check">
				  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
				  <label class="form-check-label" for="flexRadioDefault2">
				    Dont want to show.
				  </label>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL Phone(Primary)-------- -->
	<div class="modal fade" id="modalForPrimaryPhone" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter your Phone(Primary)</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="tel" id="formFile" placeholder="+977 98XXXXXXXX">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Phone(Secondary)-------- -->
	<div class="modal fade" id="modalForDob" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Phone(Secondary)</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="date" id="formFile" placeholder="1997/APR/18">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Website-------- -->
	<div class="modal fade" id="modalForWebsite" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Website</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="url" id="formFile" placeholder="www.loremipsum.com">
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Social Media-------- -->
	<div class="modal fade" id="modalForSocialMedia" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Social Media Links</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <div>
	        	<h4 class="text-danger">You Can't edit details here. <br>Go to <a href="./dashboard.html#socialMediaDetails" style="text-decoration: none;">dashboard</a> and edit it.</h4>
	        </div>
			  <div class="mb-3">
			  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- ------------MODAL FOR Editing Education Details-------- -->
	<div class="modal fade" id="modalForEditEducation" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Edit Your Education Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Education">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" placeholder="Education Details..." rows="5"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal fade" id="modalForViewCv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">My CV</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-0">
      	<!-- ----------Cards-- -->
      	<div class="card">
      		<div class="card-header text-center rounded p-3">
		 		 <img src="images/image01.jpg" alt="Lorem Ipsum Image" style="height: 200px;width:200px; cursor: pointer;" class="card-img-top gallery-item"><br><br>
		 		 <h5 class="card-title text-center">Hello & Welcome</h5>
		        <h3 class="card-title text-center">I'M LOREM IPSUM</h3>
		        <h6 class="card-title text-center">Computer Engineer</h6>
		 	</div>
		  <div class="card-body">
		  	<div>
		    	<p class="card-text text-justify px-2">
		        	<span>Age: 23</span><br>
		        	<span>Addess: Dhapakhel, Nepal</span><br>
		        	<span>Email: lorem@gmail.com</span><br>
		        	<span>Phone: +977 98xxxxxxxx</span><br>
		        	<span>Website: www.loremipsum.com</span><br>
		        </p>
		    </div>
		    <div class="bg-light">
		    	<h3 class="card-title text-center">SKILLS</h3><hr>
		    	<p class="card-text text-justify px-2">
		    		I have different skills
		    	</p>
		    </div>
		    <div class="bg-light">
		    	<h3 class="card-title text-center">EDUCATIONS</h3><hr>
		    	<p class="card-text text-justify px-2">
		    		I have completed my Education In
		    	</p>
		    </div>
		  </div>
		  <div class="card-footer myCvMenue bg-light rounded">
		  	<a href=""><img src="assests/icons/facebook.png"></a>
		  	<a href=""><img src="assests/icons/instagram.png"></a>
		  	<a href=""><img src="assests/icons/twitter.png"></a>
		  	<a href=""><img src="assests/icons/message.png"></a>
		  	<a href=""><img src="assests/icons/linkedin.png"></a>
		  		
		  	</ul>
		  </div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Print</button>
      </div>
    </div>
  </div>
</div>

<!-- ------------MODAL FOR Adding Skills Category-------- -->
	<div class="modal fade" id="modalForAddingSkillls" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Skills with Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
				  <label class="form-check-label" for="flexCheckDefault">
				    Show in My CV
				  </label>
				</div>
				<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
				  <label class="form-check-label" for="flexCheckChecked">
				    Show in Skills
				  </label>
				</div>
				<div class="col-auto mb-3">
					<input class="form-control" type="file" id="formFile" multiple="on">					
				</div>
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Skill Header">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" placeholder="Skills Details..." rows="5"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- -------------Model For Viewing MY SKILLS------------ -->
<div class="modal fade" id="modalForViewSkills" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">MY SKILLS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-0">
      	<!-- ----------Cards-- -->
      	<div class="card">
      		<div class="text-center">
		  		<img align="center" src="images/python.png" alt="Python Image" style="height:150px;width: 150px; border-radius: 50%;" alt="Sujit Khatri"><br>
		  		<h5 class="card-title text-center">Lorem Ipsum</h5>
		  		<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		  		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		  		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		  		consequat</p>
		  	</div>

		  	<div class="text-center">
		  		<img align="center" src="images/python.png" alt="Python Image" style="height:150px;width: 150px; border-radius: 50%;" alt="Sujit Khatri"><br><br>
		  		<h5 class="card-title text-center">Lorem Ipsum</h5>
		  		<p class="card-text"> Duis aute irure dolor in reprehenderit in voluptate velit esse
		  		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		  		proident, sunt in culpa qui o</p>
		  	</div>
		  	<div class="text-center">
		  		<img align="center" src="images/python.png" alt="Python Image" style="height:150px;width: 150px; border-radius: 50%;" alt="Sujit Khatri"><br><br>
		  		<h5 class="card-title text-center">Lorem Ipsum</h5>
		  		<p class="card-text">.fficia deserunt mollit anim id est laborum.</p>
		  	</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Print</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------MODAL FOR Editing Skills Category-------- -->
	<div class="modal fade" id="modalForEditSkillsCategory" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Skills with Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
				  <label class="form-check-label" for="flexCheckDefault">
				    Show in My CV
				  </label>
				</div>
				<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
				  <label class="form-check-label" for="flexCheckChecked">
				    Show in Skills
				  </label>
				</div>
				<div class="col-auto mb-3">
					<input class="form-control" type="file" id="formFile" multiple="on">					
				</div>
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Skill Header">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" placeholder="Skills Details..." rows="5"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- ------------MODAL FOR Adding Working Details-------- -->
	<div class="modal fade" id="modalForAddWorkDetails" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Working Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
				  <label class="form-check-label" for="flexCheckDefault">
				    Show in My CV
				  </label>
				</div>
				<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
				  <label class="form-check-label" for="flexCheckChecked">
				    Show in Working Details
				  </label>
				</div>
				<div class="col-auto mb-3">
					<input class="form-control" type="file" id="formFile" multiple="on">					
				</div>
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Work Header">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" placeholder="Working Details..." rows="5"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
	      </div>
	    </div>
	  </div>
	</div>

<!-- -------------Model For Viewing Working Details------------ -->
<div class="modal fade" id="modelForViewWorkDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" style="background-color: #9507ed;">
      <div class="modal-header">
        <h5 class="modal-title">Work Details</h5><hr>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<!-- ----------Cards-- -->
      	<div class="card">
      		<div class="p-0 m-0">
			  <div class="card-body">
			    <h5 class="card-title text-center">Google</h5><hr>
			    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.	</p>
			  </div>
			  <ul class="mylistImage">
			  	<li>
				  <img src="assests/images/image02.jpg" alt="Image02" style="height:120px;width: 120px;">
				</li>
				<li>
				  <img src="assests/images/image02.jpg" alt="Image02" style="height:120px;width: 120px;">
				</li>
				<li>
				  <img src="assests/images/image02.jpg" alt="Image02" style="height:120px;width: 120px;">
				</li>
			  </ul>
			</div><br>
			<div class="p-0 m-0">
			  <div class="card-body">
			    <h5 class="card-title text-center">Amazon</h5><hr>
			    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.	</p>
			  </div>
			  <ul class="mylistImage">
			  	<li>
				  <img src="assests/images/image04.jpg" alt="Image02" style="height:120px;width: 120px;">
				</li>
				<li>
				  <img src="assests/images/image04.jpg" alt="Image02" style="height:120px;width: 120px;">
				</li>
				<li>
				  <img src="assests/images/image04.jpg" alt="Image02" style="height:120px;width: 120px;">
				</li>
			  </ul>
			</div><br>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Print</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------MODAL FOR Editing Work Details-------- -->
	<div class="modal fade" id="modalForEditWorkDetails" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Work Details</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body tableViews">
	        <form class="form">
	        	<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
				  <label class="form-check-label" for="flexCheckDefault">
				    Show in My CV
				  </label>
				</div>
				<div class="form-check mb-3">
				  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
				  <label class="form-check-label" for="flexCheckChecked">
				    Show in Skills
				  </label>
				</div>
				<div class="col-auto mb-3">
					<input class="form-control" type="file" id="formFile" multiple="on">					
				</div>
	        	<div class="mb-3">
				  <input class="form-control" type="text" id="formFile" placeholder="Skill Header">
				</div>
				<div class="mb-3">
				  <textarea class="form-control" type="text" id="formFile" placeholder="Skills Details..." rows="5"></textarea>
				</div>
				  <div class="mb-3">
				  	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        		<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
			</form>
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
	</script>
</body>
</html>