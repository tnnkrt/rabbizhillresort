<?php
include('global.php');
include('imagetoDB.php');
$DirPath="rabbizhillresort/php/uploadimage/";
$DesPath=$_SERVER["DOCUMENT_ROOT"]."/".$DirPath;

if(strlen($_FILES["upFile"]["name"])<1){
	throwError("No have file");
}
//else echo "pass check name<br/>";
//check directory destination
if(!is_dir($DesPath)){
	unlink($_FILES['upFile']['tmp_name']);
	throwError("No directory destination");
}
//else echo "pass check server directory name<br/>";
if($_FILES['upFile']['type']!="image/gif"and $_FILES['upFile']['type']!="image/jpg" and $_FILES['upFile']['type']!="image/png" and $_FILES['upFile']['type']!="image/jpeg"){
	unlink($_FILES['upFile']['tmp_name']);
	throwError("Not type photo");
}
////////////// TYPE PHOTO ////////////////////////////
if($_FILES['upFile']['type']=="image/gif"){
	$_type = ".GIF";
}
else if(
	$_FILES['upFile']['type']=="image/jpeg"){
	$_type = ".JPG";
}
else if(
	$_FILES['upFile']['type']=="image/png"){
	$_type = ".PNG";
}

$_Date = date("Y-m-d_h-i-s");
$_FileName = $_Date.$_type;
///////////////////// COPY  //////////////////////////

if(@copy($_FILES['upFile']['tmp_name'],$DesPath.$_FileName)){
	echo $_FileName;
	//echo "<hr>";
	$detail_th="thai";
	$detail_en="eng";
	echo "gals:".$_POST['galleryId'];
	Up_imageto_gallery($_FileName,$_POST['galleryId']);	
}else{
// have a problem upload
	throwError("Upload".$_FILES['upFile']['name']."failed");
}

@unlink($_FILES['upFile']['tmp_name']);// delete temp file on sever

//}
?>



