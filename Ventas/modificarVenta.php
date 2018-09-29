<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');//Recepcion de los datos en formato JSON
    // El segundo parametro si es Tue el json lo decodifica como array php
    // y si el False lo decodifica como stdObject
    $data = json_decode($formData, True);

    $idVenta 			= $data['idVenta'];
    $fecha				= $data['fecha'];
    $observaciones 		= $data['observaciones'];
    $descuento 			= $data['descuento'];
    $precioVentaTotal 	= $data['precioVentaTotal'];
    $idCliente 			= $data['objCliente']['id_cliente'];
    $idEmpleado 		= $data['objEmpleado']['id_empleado'];

    $misqli->query("UPDATE ventas 
    				SET fechaVenta = '".$fecha."',
    					observaciones = '".$observaciones."',
    					descuento = ".$descuento.",
    					precioVentaTotal = ".$precioVentaTotal.",
    					idCliente = ".$idCliente.",
    					idEmpleado = ".$idEmpleado."
    				WHERE id_venta = ".$idVenta.";");

    

    if($data['lineasVentaServicio'] != '')
    {
	   	foreach ($data['lineasVentaServicio'] as $valor)
	    {

	    	$precioVentaServicio = $valor['precioVenta'];
			$idServicio  		 = $valor['categoria']['id_elemento'];

	    	/*La linea existe por lo tanto 
	    	hay que actualizarla*/
	    	if(isset($valor['id_lineaVentas']))
	    	{
	    		$idLineaVenta = $valor['id_lineaVentas'];

			    $misqli->query("UPDATE linea_ventas 
								SET precioVentaUnd = '".$precioVentaServicio."',
									idElemento = '".$idServicio."'
								WHERE id_lineaVentas = ".$idLineaVenta.";");
	    	}
	    	/*la linea no existe por lo tanto 
	    	hay que añadirla como una nueva*/
	    	else
	    	{
				$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,idVenta,idElemento)
				   				 VALUES('".$precioVentaServicio."',
				   				 		'".$idVenta."',
				   				 		'".$idServicio."');";
				$misqli->query($sentenciaSql);
	    	}	
	    }
    }


    if($data['lineasVentasProducto'] != '')
    {
	   	foreach ($data['lineasVentasProducto'] as $valor)
	    {

	    	$precioVentaProducto = $valor['precioVentaProducto'];
	    	$unidades 			 = $valor['unidades'];
			$idProducto  		 = $valor['producto']['idElemento'];

			$idOldProduct 		 = $valor['oldProducto']['idElemento'];
			$oldProductUnts      = $valor['oldProducto']['unidades'];


	    	/*La linea existe por lo tanto 
	    	hay que actualizarla*/
	    	if(isset($valor['id_lineaVentas']))
	    	{
	    		$idLineaVenta = $valor['id_lineaVentas'];

	    		/*La modificacion del producto es sobre el mismo*/
	    		if($idProducto == $idOldProduct)
	    		{
		    		$misqli->query("UPDATE linea_ventas 
									SET precioVentaUnd = '".$precioVentaProducto."',
										cantidad = ".$unidades.",
										idElemento = '".$idProducto."'
									WHERE id_lineaVentas = ".$idLineaVenta.";");

		    		/*Las unidades vendidas en caja se suman al stock y las cantidades que se han modificado se restan al stock actual*/
		    		$misqli->query("UPDATE productos 
					 				SET cantidadStock =  cantidadStock + '".$oldProductUnts."'
					 	 			WHERE idElemento = '".$idProducto."';");

		    		$misqli->query("UPDATE productos 
					 				SET cantidadStock =  cantidadStock - '".$unidades."'
					 	 			WHERE idElemento = '".$idProducto."';");



	    		}
	    		/*Si los productos son diferentes se devuelve la cantidad de stock en el producto que se ha allá cambiado y se resta el stock del producto nuevo
	    		que se ha introducido */
	    		else
	    		{
	    			$misqli->query("UPDATE linea_ventas 
									SET precioVentaUnd = '".$precioVentaProducto."',
										cantidad = ".$unidades.",
										idElemento = '".$idProducto."'
									WHERE id_lineaVentas = ".$idLineaVenta.";");

					$misqli->query("UPDATE productos 
					 				SET cantidadStock =  cantidadStock - '".$unidades."'
					 	 			WHERE idElemento = '".$idProducto."';");

	    			$misqli->query("UPDATE productos 
					 				SET cantidadStock =  cantidadStock + '".$oldProductUnts."'
					 	 			WHERE idElemento = '".$idOldProduct."';");
	    		}
	    	}
	    	/*la linea no existe por lo tanto 
	    	hay que añadirla como una nueva*/
	    	else
	    	{
				$sentenciaSql ="INSERT INTO linea_ventas(precioVentaUnd,idVenta,cantidad,idElemento)
				   				 VALUES('".$precioVentaServicio."',
				   				 		'".$idVenta."',
				   				 		".$unidades.",
				   				 		'".$idProducto."');";
				$misqli->query($sentenciaSql);

				$misqli->query("UPDATE productos 
				 				SET cantidadStock =  cantidadStock - '".$unidades."'
				 	 			WHERE idElemento = '".$idProducto."';");
	    	}	
	    }
    }

    $misqli->close(); 

 ?>