<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );

    $idVenta     = $data->idVenta;
    $idVenta     = $misqli->real_escape_string($idVenta);

    $resultado = $misqli->query("SELECT lv.id_lineaVentas,
										lv.idElemento,
                                        m.id_marca,
                                        m.nombre,
									    ec.nombreElemento as nombreProducto,
									    lv.cantidad,
									    lv.precioVentaUnd
	   FROM linea_ventas AS lv, elementos_comerciales AS ec, productos as pro, marcas as m 
       WHERE lv.idVenta = '".$idVenta."' AND lv.idElemento = ec.id_elemento AND lv.idElemento = pro.idElemento AND pro.idMarca = m.id_marca AND lv.eliminado = 0;");
	
	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();
		//Obtener una fila de resultado como un array asociativo
		while ($fila = $resultado->fetch_assoc())
		{
		   $set[] = $fila;
		}

		echo json_encode($set,JSON_NUMERIC_CHECK);

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

 //    $idCliente 	= getIdCliente($idVenta,$misqli);
 //    $idEmpleado = getIdEmpleado($idVenta,$misqli);
 //    $fechaVenta = getFechaVenta($idVenta,$misqli);
 //    $numVenta 	= getNumVenta($idVenta,$misqli);

 //    $idCli = array();
 //    $idEmp = array();
 //    $fechaV = array();
 //    $numV = array();

 //    $idCli['idCliente']   = $idCliente;
 //    $idEmp['idEmpleado']  = $idEmpleado;
 //    $fechaV['fechaVenta'] = $fechaVenta;
 //    $numV['numVenta']	  = $numVenta;

 //    $devuelta = array_merge($idCli,$idEmp,$fechaV,$numV);

 //    echo json_encode($devuelta,JSON_NUMERIC_CHECK);

	// $misqli->close();

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