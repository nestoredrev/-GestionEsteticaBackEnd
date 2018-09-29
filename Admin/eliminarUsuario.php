<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);
	$id 		= $data->idUser;

    $misqli->query("UPDATE usuarios SET eliminado = 1 WHERE id_usuario='".$id."';");

	$misqli->close();

 ?>