<?php
	include('imagetoDB.php');
	$name=$_POST['name_image'];
	$id= $_POST['gallery_id'];
	echo "nam:".$name."di:".$id;
	Delete_image_gallery($name,$id);
?>