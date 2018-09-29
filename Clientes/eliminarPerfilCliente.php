<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idCliente = $data->idCli;

    $misqli->query("UPDATE clientes SET eliminado = 1 WHERE id_cliente='".$idCliente."' ");

    $misqli->close();

 ?>