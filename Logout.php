<?php 
	//header('Access-Control-Allow-Origin: http://192.168.1.216:8100');
	header('Access-Control-Allow-Origin: *');
	session_start();
	session_destroy();
	die();
 ?>