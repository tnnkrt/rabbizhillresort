<?php
function throwError($msg){
	$data = array('type' => 'error', 'message' => $msg);
	header('HTTP/1.1 400 Bad Request');
	header('Content-Type: application/json; charset=UTF-8');
	json_encode($data);
	die($msg);

}
?>