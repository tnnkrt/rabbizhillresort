<?php
define('DB_NAME', 'resort');
define('DB_USER', 'developers');
define('DB_PASSWORD', '1234');
define('DB_HOST', '192.168.137.98');
function connectDB(){
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
	if (!$link) {
		throwError("Connection failed: " . mysqli_connect_error());
	}
	return $link;
}
function Up_imageto_gallery($nameoffile,$gallery_id){
	$link = connectDB();
	$value = $nameoffile;
	echo $gallery_id;
	$sql = "INSERT INTO image (name_image,gallery_id) VALUES ('".$nameoffile."',".$gallery_id.")";
	mysqli_query($link, $sql);
	mysqli_close($link);
}

function Edit_imageto_gallery($nameoffile,$detail_th,$detail_en){
	$link = connectDB();
	$sql = "UPDATE image SET datail_th='".$value."' ,datail_en='".$value2."'  WHERE id=".$nameoffile."; " ;

	if (mysqli_query($conn, $sql)) {
		echo "Record updated successfully";
	} else {
		throwError("Error updating record: " . mysqli_error($conn));
	}
	mysqli_query($link, $sql);
	mysqli_close($link);
}

function Delete_image_gallery($name,$gallery_id){
	$link = connectDB();
	$value = $name;
	$value2 = $gallery_id;

		//echo $_SERVER["DOCUMENT_ROOT"]."/rabbiz/php/uploadimage/".$value;
	$sql = "DELETE FROM image WHERE name_image='$value'";
	if (mysqli_query($link, $sql)) {
		echo "Record deleted successfully";
	} else {
		throwError("Error deleting record: " . mysqli_error($link));
	}
	@unlink($_SERVER["DOCUMENT_ROOT"]."/rabbiz/php/uploadimage/".$value);
	mysqli_query($link, $sql);
	mysqli_close($link);
}
	//---------------- Query file name in gallery--------------------//
function Show_NameIn_gallery($gallery_id){ 
	$link = connectDB();
	$value = $gallery_id;	
		//echo "database gallery id:".$gallery_id."\n";
	$sql = "SELECT name_image FROM image WHERE gallery_id='".$gallery_id."' ";
	$result = $link->query($sql);
	if ($result->num_rows > 0) {
				// output data of each row
		$rows = array();
		while($row = $result->fetch_assoc()) {
			//echo $row["name_image"].",";
			$rows[] = $row;
		}
		echo json_encode($rows);
		$link->close();
	} else {
		$link->close();
		throwError("No any image");
	}
}

	//-------------- Create new Gallery -----------------//
function create_gallery($name){ 
	$link = connectDB();
	$value = $name;	
			//echo $value;
	$sql="INSERT INTO gallery (name_gallery) VALUES ('".$value."') ";
	if (mysqli_query($link, $sql)) {
		echo "New record  Gallery created successfully";
	} else {
		throwError("Error: " . $sql . "<br>" . mysqli_error($link));
	}
	mysqli_close($link);
}
?>