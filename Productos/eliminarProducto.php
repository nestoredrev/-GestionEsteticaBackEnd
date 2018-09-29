<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idProducto = $data->idProducto;

	$resultado = $misqli->query("SELECT idElemento FROM linea_ventas WHERE idElemento='".$idProducto."' AND eliminado = 0;");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		echo -1;
		$resultado->close();
	}
	else
	{
		$misqli->query("UPDATE elementos_comerciales SET eliminado = 1 WHERE id_elemento='".$idProducto."';");
	}

    $misqli->close();

 ?>