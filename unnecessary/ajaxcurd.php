<?php 
require '../assests/includes/connection.inc.php';


    // $ip = '168.192.0.1'; // your ip address here
    // $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
    // if($query && $query['status'] == 'success')
    // {
    // 	echo 'Your ountry is ' . $query['isp'];
    // 	 echo '<br />';
    //     echo 'Your City is ' . $query['city'];
    //     echo '<br />';
    //     echo 'Your State is ' . $query['region'];
    //     echo '<br />';
    //     echo 'Your Zipcode is ' . $query['zip'];
    //     echo '<br />';
    //     echo 'Your Coordinates are ' . $query['lat'] . ', ' . $query['lon'];
    // }
    ?>



    <!DOCTYPE html>
    <html>
    <head>
    	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lorem Ipsum</title>
	<link rel="stylesheet" type="text/css" href="../assests/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="../assests/css/myStyle.css"> -->
    <script type="text/javascript" src="../assests/js/bootstrap.bundle.js"></script>
	<script type="text/javascript" src="../assests/js/jquery.min.js"></script>
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
    	<div class="container-fluid bg-secondary text-white">

    		<div class="col-5">
	    		<!-- <form action="" method="post"> -->
	    			<div class="mb-3">
	    				<label>Title</label>
	    				<input type="text" class="form-control"  id="titleContent">
	    			</div>
	    			<div class="mb-3">
	    				<label>Body</label>
	    				<input type="text" class="form-control" id="bodyContent">
	    			</div>
	    			<div class="mb-3">
	    				<input type="button" class="btn btn-success form-control" id="submitbtn" value="submit" onclick="addRecord()" value="1">
	    			</div><br><br>
	    		<!-- </form> -->
	    	</div>
    	</div>
    	<script type="text/javascript">
    		// $(document).ready(function(){
    		// 	// var form=$('#myform');
    		// 	var title= $("#titlet").val();
    		// 	var body= $("#bodyt").val();
    		// 	$('#submit').click(function()
    		// var albumId= $(this).closest("tr").find("#editAlbumDetails").val();{

    		// 	alert(titlet);
    		// 		$.ajax({
    		// 			url: form.attr("action"),
    		// 			type: 'post',
    		// 			data: {	title:title,    						body:body,
    		// 			},

    		// 			success: function(data){
    		// 				console.log(data);
    		// 			}
    		// 		});
    		// 	});
    		// });

	function addRecord(){
        var title=$("#titleContent").val();
        var body=$("#bodyContent").val();

		$.ajax({
			url: "insertphp.php",
			type: 'post',
			data: {	
				titlet: title,
				bodyt: body
			},

			success:function(data,status){
				console.log(data);
			}
		});
	}
    	</script>

    </body>
    </html>