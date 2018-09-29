<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$id = $data->idCita;

    $misqli->query("DELETE FROM citas WHERE id='".$id."' ");

    $misqli->close();
 ?>