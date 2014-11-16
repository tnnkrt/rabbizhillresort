<?php
define('DB_NAME', 'mydb');
define('DB_USER', 'root');
define('DB_PASSWORD', '1234');
define('DB_HOST', '192.168.137.98');

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

	function Show_NameIn_gallery($gallery_id){ 
	global $link;
		$value = $gallery_id;	
			//echo $value;
		$sql = "SELECT name_image FROM image WHERE gallery_id='".$value."' ";
		$result = $link->query($sql);
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "name_image: " . $row["name_image"]. "<br>";
				}
			} else {
				echo "0 results";
			}
		$link->close();
	}
?>