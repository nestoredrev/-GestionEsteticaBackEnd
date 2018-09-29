<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idMarca 	= $data->idMarca;


	$resultado = $misqli->query("SELECT * FROM productos AS p, elementos_comerciales AS ec 
								 WHERE p.idMarca='".$idMarca."' AND p.idElemento = ec.id_elemento AND ec.eliminado = 0;");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		echo -1;
		$resultado->close();
	}
	else
	{
		$misqli->query("UPDATE marcas SET eliminado = 1 WHERE id_marca='".$idMarca."';");
	}

    $misqli->close();

 ?>