<?php
define('DB_NAME', 'resort');
define('DB_USER', 'developers');
define('DB_PASSWORD', '1234');
define('DB_HOST', '192.168.137.98');

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
	function text_to_boxDB($tt_th , $tt_en , $dt_th1 , $dt_en1 , $dt_th2 , $dt_en2 , $com ,$name_gallery){
		global $link;
		 $value1 = $tt_th;
		 $value2 = $tt_en;
		 $value3 = $dt_th1;
		 $value4 = $dt_en1;
		 $value5 = $dt_th2;
		 $value6 = $dt_en2;
		 $value7 = $com;
		 $value8 = $name_gallery;
		 /////////Create Gallery ///////////
		$sql="INSERT INTO gallery (name_gallery) VALUES ('".$value8."') ";
		if (mysqli_query($link, $sql)) {
			echo "New record  Gallery created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($link);
		}
		
		///////////GET Gallery ID///////////
		$sql="SELECT gallery_id FROM gallery WHERE name_gallery='".$value8."' ";
		$result = mysqli_query($link,$sql);
		while ($row = $result->fetch_assoc()) {
        //echo $row['gallery_id']."<br>";
		$value9 = $row['gallery_id'];
		}
		//////////Create text box/////////////
		$sql = "INSERT INTO textbox (title_th , title_en , detail_th1 , detail_en1 , detail_th2 , detail_en2 ,comment ,gallery_id ) VALUES 
			     ('".$value1."', '".$value2."', '".$value3."','".$value4."','".$value5."','".$value6."', '".$value7."' ,'".$value9."') ";
			if ( mysqli_query($link, $sql)) {
				echo "New record Text Box created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($link);
			}
		mysqli_close($link);
	}
	
	function text_update_boxDB($tb_id , $tt_th , $tt_en , $dt_th1 , $dt_en1 , $dt_th2 , $dt_en2 , $com ){
		global $link;
		 $value1 = $tb_id;
		 $value2 = $tt_th;
		 $value3 = $tt_en;
		 $value4 = $dt_th1;
		 $value5 = $dt_en1;
		 $value6 = $dt_th2;
		 $value7 = $dt_en2;
		 $value8 = $com;
		 
		 ////////// Update text box ////////////////
		$sql = "UPDATE textbox SET title_th='".$value2."' , title_en='".$value3."' , detail_th1='".$value4."' , detail_en1='".$value5."' ,
				detail_th2='".$value6."' , detail_en2='".$value7."' , comment='".$value8."' WHERE textbox_id='".$value1."' ";
			if (mysqli_query($link, $sql)) {
				echo "Record updated Text Box successfully";
			} else {
				echo "Error updating Text Box record: " . mysqli_error($link);
			}
		mysqli_close($link);
	}
	
	function text_query_boxDB($tb_id ){
		global $link;
		 $value = $tb_id;
		////////// Query  Text Box ///////////
		$sql = "SELECT * FROM textbox WHERE textbox_id = '".$value."' ";
		$result = $link->query($sql);
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "Textbox_id : " . $row["textbox_id"]. "<br>";
					echo "Title_th   : " . $row["title_th"]. "<br>";
					echo "Title_en   : " . $row["title_en"]. "<br>";
					echo "Detail_th1 : " . $row["detail_th1"]. "<br>";
					echo "Detail_en1 : " . $row["detail_en1"]. "<br>";
					echo "Detail_th2 : " . $row["detail_th2"]. "<br>";
					echo "Detail_en2 : " . $row["detail_en2"]. "<br>";
					echo "Comment    : " . $row["comment"]. "<br>";
					echo "Gallery_id : " . $row["gallery_id"]. "<br>";
				}
			} else {
				echo "0 results";
			}
		$link->close();
	}
?>