<?php
			$tb_id=  $_POST['tt_id'];
			$tt_th=  $_POST['edtt_th'];
			$tt_en=  $_POST['edtt_en'];
			$dt_th1= $_POST['eddt_th1'];
			$dt_en1= $_POST['eddt_en1'];
			$dt_th2= $_POST['eddt_th2'];
			$dt_en2= $_POST['eddt_en2'];
			$com   = $_POST['comment'];
			//echo $tb_id.$tt_th.$tt_en.$dt_th1.$dt_en1.$dt_th2.$dt_en2.$com;
		include('TextboxtoDB.php');
		text_update_boxDB($tb_id , $tt_th , $tt_en , $dt_th1 , $dt_en1 , $dt_th2 , $dt_en2 ,$com );
		
		 
?>
