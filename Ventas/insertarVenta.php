<?php 	

	include('../Conexion.php');

	$formData = file_get_contents('php://input');//Recepcion de los datos en formato JSON
    // El segundo parametro si es Tue el json lo decodifica como array php
    // y si el False lo decodifica como stdObject
    $data = json_decode($formData, True); 

    $idUser 		= $data['idUser'];
    $idCliente		= $data['idCliente'];
    $fechaVenta		= $data['fecha'];
    $numVenta 		= $data['numVenta'];
    $tipoCliente    = $data['tipoCliente'];
    $descuento 		= $data['descuento'];
    $precioVentaTotal = $data['precioVentaTotal'];
    $observaciones 	= $data['observaciones'];

	$idUser 		= $misqli->real_escape_string($idUser);
	$idCliente 		= $misqli->real_escape_string($idCliente);
	$fechaVenta 	= $misqli->real_escape_string($fechaVenta);
	$numVenta 		= $misqli->real_escape_string($numVenta);
	$tipoCliente	= $misqli->real_escape_string($tipoCliente);
	$descuento		= $misqli->real_escape_string($descuento);
	$precioVentaTotal		= $misqli->real_escape_string($precioVentaTotal);
	
	//$fechaCompraFormatted 	= new DateTime($fechaCompra);
	//$precioCompraTotal = 0;
	//$IVA = 1.21;
	
	if($idCliente!='')
	{
		//La gestion de la caja proviene del calendario
		$idEmpleado = $data['empleado']['id_empleado'];
		$senteciaSql ="INSERT INTO ventas(numVenta,fechaVenta,precioVentaTotal,descuento,observaciones,idUsuario,idCliente,idEmpleado)
		   				 VALUES('".$numVenta."',
		   				 		'".$fechaVenta."',
		   				 		'".$precioVentaTotal."',
		   				 		'".$descuento."',
		   				 		'".$observaciones."',
		   				 		'".$idUser."',
		   				 		'".$idCliente."',
		   				 		'".$idEmpleado."');";
		$misqli->query($senteciaSql);

		$lastIdVenta = $misqli->insert_id;

		if($data['lineasVentasServicios'] != '')
		{
			foreach ($data['lineasVentasServicios'] as $valor)
			{
				$precioVentaServicio = $valor['precioVenta'];
				$idElemento  		 = $valor['categoria']['id_elemento'];

				$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,idVenta,idElemento)
				   				 VALUES('".$precioVentaServicio."',
				   				 		'".$lastIdVenta."',
				   				 		'".$idElemento."')";
				$misqli->query($sentenciaSql);
			}
		}
		if($data['lineasVentasProductos'] != '')
		{
			foreach ($data['lineasVentasProductos'] as $valor)
			{
				$precioVenta = $valor['precioVentaProducto'];
				$unidades 	 = $valor['unidades'];
				$idElemento  = $valor['producto']['idElemento'];

				$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,cantidad,idVenta,idElemento)
				   				 VALUES('".$precioVenta."',
				   				 		'".$unidades."',
				   				 		'".$lastIdVenta."',
				   				 		'".$idElemento."');";
				$misqli->query($sentenciaSql);

			    $misqli->query("UPDATE productos 
		 		SET cantidadStock =  cantidadStock - '".$unidades."'
		 	 	WHERE idElemento = '".$idElemento."';");
			}
		}
	}
	else
	{
		switch ($tipoCliente)
		{
			case 66:
				//Cliente Existente
				$idCliente = $data['objClienteExistente']['id_cliente'];
				$idEmpleado = $data['empleado']['id_empleado'];
				$senteciaSql ="INSERT INTO ventas(numVenta,fechaVenta,precioVentaTotal,descuento,observaciones,idUsuario,idCliente,idEmpleado)
				   				 VALUES('".$numVenta."',
				   				 		'".$fechaVenta."',
				   				 		'".$precioVentaTotal."',
				   				 		'".$descuento."',
				   				 		'".$observaciones."',
				   				 		'".$idUser."',
				   				 		'".$idCliente."',
				   				 		'".$idEmpleado."');";
				$misqli->query($senteciaSql);

				$lastIdVenta = $misqli->insert_id;

				if($data['lineasVentasServicios'] != '')
				{
					foreach ($data['lineasVentasServicios'] as  $valor)
					{
						$precioVentaServicio = $valor['precioVenta'];
						$idElemento  		 = $valor['categoria']['id_elemento'];

						$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,idVenta,idElemento)
						   				 VALUES('".$precioVentaServicio."',
						   				 		'".$lastIdVenta."',
						   				 		'".$idElemento."')";
						$misqli->query($sentenciaSql);
					}
				}
				if($data['lineasVentasProductos'] != '')
				{
					foreach ($data['lineasVentasProductos'] as $valor)
					{
						$precioVenta = $valor['precioVentaProducto'];
						$unidades 	 = $valor['unidades'];
						$idElemento  = $valor['producto']['idElemento'];

						$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,cantidad,idVenta,idElemento)
						   				 VALUES('".$precioVenta."',
						   				 		'".$unidades."',
						   				 		'".$lastIdVenta."',
						   				 		'".$idElemento."');";
						$misqli->query($sentenciaSql);

					    $misqli->query("UPDATE productos 
				 		SET cantidadStock =  cantidadStock - '".$unidades."'
				 	 	WHERE idElemento = '".$idElemento."';");
					}
				}
			break;
			case -77:
				//Cliente genérico
				$idEmpleado = $data['empleado']['id_empleado'];
				$nombre     = $data['nombre'];
	            $apellido1  = $data['apellido1'];
	            
	            $nombre     = $misqli->real_escape_string($nombre);
	            $apellido1  = $misqli->real_escape_string($apellido1);	
				$res1 = $misqli->query("INSERT INTO clientes(nombre,apellido1,apellido2,idTipo,idUsuario) 
	                                    VALUES('".$nombre."','".$apellido1."','',-77,'".$idUser."');");

	            $lastIdCliente = $misqli->insert_id;

	            $senteciaSql ="INSERT INTO ventas(numVenta,fechaVenta,precioVentaTotal,descuento,observaciones,idUsuario,idCliente,idEmpleado)
					   				 VALUES('".$numVenta."',
					   				 		'".$fechaVenta."',
					   				 		'".$precioVentaTotal."',
					   				 		'".$descuento."',
					   				 		'".$observaciones."',
					   				 		'".$idUser."',
					   				 		'".$lastIdCliente."',
					   				 		'".$idEmpleado."');";
				$misqli->query($senteciaSql);

				$lastIdVenta = $misqli->insert_id;

				if($data['lineasVentasServicios'] != '')
				{
					foreach ($data['lineasVentasServicios'] as  $valor)
					{
						$precioVentaServicio = $valor['precioVenta'];
						$idElemento  		 = $valor['categoria']['id_elemento'];

						$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,idVenta,idElemento)
						   				 VALUES('".$precioVentaServicio."',
						   				 		'".$lastIdVenta."',
						   				 		'".$idElemento."')";
						$misqli->query($sentenciaSql);
					}
				}
				if($data['lineasVentasProductos'] != '')
				{
					foreach ($data['lineasVentasProductos'] as $valor)
					{
						$precioVenta = $valor['precioVentaProducto'];
						$unidades 	 = $valor['unidades'];
						$idElemento  = $valor['producto']['idElemento'];

						$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,cantidad,idVenta,idElemento)
						   				 VALUES('".$precioVenta."',
						   				 		'".$unidades."',
						   				 		'".$lastIdVenta."',
						   				 		'".$idElemento."');";
						$misqli->query($sentenciaSql);

					    $misqli->query("UPDATE productos 
				 		SET cantidadStock =  cantidadStock - '".$unidades."'
				 	 	WHERE idElemento = '".$idElemento."';");
					}
				}
			break;
		} // FIN SWITCH
	}

	$misqli->close();
 ?>