<?php
		$tb_id=  $_POST['tt_id'];
			//echo $tb_id;
		include('TextboxtoDB.php');
		text_query_boxDB($tb_id );
?>