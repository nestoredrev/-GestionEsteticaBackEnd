<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idUser = $data->idUser;
	$idUser = $misqli->real_escape_string($idUser);

    $misqli->query("UPDATE usuarios SET eliminado = 1 WHERE id_usuario='".$idUser."';");

    $misqli->close();

 ?>