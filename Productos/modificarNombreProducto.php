<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idProducto = $data->idProducto;
	$nombre		= $data->nombreProducto;

	$misqli->query("UPDATE elementos_comerciales SET nombreElemento = '".$nombre."' WHERE id_elemento='".$idProducto."';");

    $misqli->close();

 ?>