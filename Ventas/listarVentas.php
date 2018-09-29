<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );

    $idUser     = $data->idUser;
    $fechaIni   = $data->ini;
    $fechaFin   = $data->fin;

    $idUser     = $misqli->real_escape_string($idUser);
    $fechaIni   = $misqli->real_escape_string($fechaIni);
    $fechaFin   = $misqli->real_escape_string($fechaFin);

	$resultado = $misqli->query("SELECT id_venta,
										numVenta,
										fechaVenta,
										precioVentaTotal
										FROM ventas WHERE (fechaVenta BETWEEN '".$fechaIni."' AND '".$fechaFin."') AND idUsuario = '".$idUser."' AND eliminado = 0 ORDER BY fechaVenta DESC;");
	
	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();
		//Obtener una fila de resultado como un array asociativo
		while ($fila = $resultado->fetch_assoc())
		{
		   $set[] = $fila;
		}

		echo json_encode($set);

		/* free result set */
		$resultado->close();
	}
	else
	{
		//No hay resultados de la consulta
		echo -1;
		/* free result set */
		$resultado->close();
	}

	$misqli->close();

 ?>