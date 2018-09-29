<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );

    $idVenta     = $data->idVenta;
    $idVenta     = $misqli->real_escape_string($idVenta);

    // $idCliente 	 = getIdCliente($idVenta,$misqli);
    // $idEmpleado  = getIdEmpleado($idVenta,$misqli);
    // $fechaVenta  = getFechaVenta($idVenta,$misqli);
    // $numVenta 	 = getNumVenta($idVenta,$misqli);
    // $descuento 	 = getDescuentoVenta($idVenta,$misqli);
    // $precioTotal = getPrecioTotal($idVenta,$misqli);

    // $idCli = array();
    // $idEmp = array();
    // $fechaV = array();
    // $numV = array();

    // $idCli['idCliente']   = $idCliente;
    // $idEmp['idEmpleado']  = $idEmpleado;
    // $fechaV['fechaVenta'] = $fechaVenta;
    // $numV['numVenta']	  = $numVenta;

    // $devuelta = array_merge($idCli,$idEmp,$fechaV,$numV);
    
    $resultado = $misqli->query("SELECT idCliente,
										idEmpleado,
										fechaVenta,
										numVenta,
										descuento,
										observaciones,
										precioVentaTotal
										FROM ventas WHERE id_venta = '".$idVenta."';");
	
	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();
		//Obtener una fila de resultado como un array asociativo
		while ($fila = $resultado->fetch_assoc())
		{
		   $set= $fila;
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
    
	//echo json_encode($set,JSON_NUMERIC_CHECK);
    //echo json_encode($devuelta,JSON_NUMERIC_CHECK);

	//$misqli->close();

	// function getIdCliente($id,$misqli)
	// {
	// 	$resultado = $misqli->query("SELECT idCliente FROM ventas WHERE id_venta = '".$id."';");
	//     $row = $resultado->fetch_assoc();
	// 	return $row['idCliente'];
	// }

	// function getIdEmpleado($id,$misqli)
	// {
	// 	$resultado = $misqli->query("SELECT idEmpleado FROM ventas WHERE id_venta = '".$id."';");
	//     $row = $resultado->fetch_assoc();
	// 	return $row['idEmpleado'];
	// }

	// function getFechaVenta($id,$misqli)
	// {
	// 	$resultado = $misqli->query("SELECT fechaVenta FROM ventas WHERE id_venta = '".$id."';");
	//     $row = $resultado->fetch_assoc();
	// 	return $row['fechaVenta'];
	// }

	// function getNumVenta($id,$misqli)
	// {
	// 	$resultado = $misqli->query("SELECT numVenta FROM ventas WHERE id_venta = '".$id."';");
	//     $row = $resultado->fetch_assoc();
	// 	return $row['numVenta'];
	// }

 ?>