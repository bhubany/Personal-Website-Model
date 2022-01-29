<?php
 
require 'connection.inc.php';

function clean_values($con,$value){
	$value=stripslashes($value);
	$value=stripcslashes($value);
	$value=mysqli_escape_string($con,$value);
	$value=mysqli_real_escape_string($con,$value);
	return $value;

}

// for inserting/Updating into database
function insert_update($con,$query){
	$res=mysqli_query($con,$query) or die(error_reporting());
	if ($res==1) {
		return 1;
	}else{
		return 0;
	}
}

// for fetching Data From Tables

function fetch($con,$query){
	$res=mysqli_query($con,$query) or die(error_reporting());
	$rows=mysqli_fetch_assoc($res);
	if (!empty($rows)) {
		return $rows;
	}else{
		return 0;
	}
}

// IP address DETAILS
function get_ip_details($ip){
	$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
	if($query && $query['status'] == 'success')
    {
    	return $query;
    }else{
    	return false;
    }
}

// Website Visitor
function website_visited($con,$user_ip){
	$ip_res=get_ip_details($user_ip);
	if ($ip_res!=false) {
		$isp=$ip_res['isp'];
		$country=$ip_res['country'];
		$city=$ip_res['city'];
		$region=$ip_res['region'];
		$zipcode=$ip_res['zip'];
		$latitude=$ip_res['lat'];
		$longitude=$ip_res['lon'];
	}else{
		$city=$country=$isp=$region=$zipcode=$latitude=$longitude="unknown";
	}
	$res=@"INSERT INTO page_visitor (ip,city,country,isp,region,zipcode,latitude,longitude) VALUES('$user_ip','$city','$country','$isp','$region','$zipcode','$latitude','$longitude') LIMIT 1";
	insert_update($con,$res);
}

// Website Visitors
function page_visited($con,$con_id){
	$res=@"SELECT blog_view FROM blogs WHERE id =$con_id";
	$rows=fetch($con,$res);
	$new_view=intval($rows['blog_view'])+1;
	$update_view=@"UPDATE blogs SET blog_view=$new_view WHERE id='$con_id' LIMIT 1";
	insert_update($con,$update_view);
}

// like/
extract($_POST);
if (isset($_POST['userId']) && isset($_POST['blogId']) && isset($_POST['like'])) {
	$blogId=clean_values($con,$_POST['blogId']);
	$userId=clean_values($con,$_POST['userId']);
	$like=clean_values($con,$_POST['like']);
	//--Check if already licked
	$query_check=@"SELECT * FROM like_dislike WHERE (ip='$userId' and blog_id='$blogId') LIMIT 1";
	$res=mysqli_query($con,$query_check) or die(error_reporting());
	if (mysqli_num_rows($res)==0) {
		// increasing like on blog table
		$query_blog=@"SELECT blog_like FROM blogs WHERE id='$blogId' LIMIT 1";
		$res=fetch($con,$query_blog);
		if ($res!=false) {
			$like_count=intval($res['blog_like'])+1;
			// Updating Like
			$update_like=@"UPDATE blogs SET blog_like=$like_count WHERE id=$blogId LIMIT 1";
			$update_res=insert_update($con,$update_like);
			if ($update_res==1) {
				$q="INSERT INTO like_dislike (ip, post_like, blog_id) VALUES('$userId','$like','$blogId')";
				$query =mysqli_query($con,$q) or die(error_reporting(1));
			}
		}
	}
}

//dislike
if (isset($_POST['userId']) && isset($_POST['blogId']) && isset($_POST['dislike'])) {
	$blogId=clean_values($con,$_POST['blogId']);
	$userId=clean_values($con,$_POST['userId']);
	$dislike=clean_values($con,$_POST['dislike']);
	//--Check if already licked
	$query_check=@"SELECT * FROM like_dislike WHERE ((ip='$userId' and blog_id='$blogId') AND post_dislike=1)  LIMIT 1";
	$res=mysqli_query($con,$query_check) or die(error_reporting());
	if (mysqli_num_rows($res)==0) {
		// increasing like on blog table
		$query_blog=@"SELECT blog_dislike FROM blogs WHERE id='$blogId' LIMIT 1";
		$res=fetch($con,$query_blog);
		if ($res!=false) {
			$dislike_count=intval($res['blog_dislike'])+1;
			// Updating Like
			$update_like=@"UPDATE blogs SET blog_dislike=$dislike_count WHERE id=$blogId LIMIT 1";
			$update_res=insert_update($con,$update_like);
			if ($update_res==1) {
				$q="INSERT INTO like_dislike (ip, post_dislike, blog_id) VALUES('$userId','$dislike','$blogId')";
				$query =mysqli_query($con,$q) or die(error_reporting(1));
			}
		}
	}
}

 ?>