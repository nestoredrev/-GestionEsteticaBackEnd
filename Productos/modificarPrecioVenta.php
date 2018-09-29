<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idProducto = $data->idProducto;
	$precio		= $data->precioVenta;

	$misqli->query("UPDATE elementos_comerciales SET precioVenta = '".$precio."' WHERE id_elemento='".$idProducto."';");

    $misqli->close();

 ?>