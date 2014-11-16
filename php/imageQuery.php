<?php
	include('global.php');
	include('imagetoDB.php');
	$id= $_POST['galleryId'];
	//echo "request gallery  id:".$id."\n";
	Show_NameIn_gallery($id);
?>