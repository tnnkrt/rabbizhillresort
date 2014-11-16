<?php
		$name=  $_POST['nm_gr'];
			//echo $tb_id;
		include('imagetoDB.php');
		create_gallery($name);
?>