<?php
			$tt_th=  $_POST['tt_th'];
			$tt_en=  $_POST['tt_en'];
			$dt_th1= $_POST['dt_th1'];
			$dt_en1= $_POST['dt_en1'];
			$dt_th2= $_POST['dt_th2'];
			$dt_en2= $_POST['dt_en2'];
			$com= $_POST['comment'];
			$nm_gr=  $_POST['nm_gr'];
		//echo $tt_th.$tt_en.$dt_th1.$dt_en1.$dt_th2.$dt_en2.$com.$nm_gr; 
		include('TextboxtoDB.php');
		text_to_boxDB($tt_th , $tt_en , $dt_th1 , $dt_en1 , $dt_th2 , $dt_en2 , $com , $nm_gr );
		
		 
?>


