<?php 	

	include('../Conexion.php');

	$formData = file_get_contents('php://input');//Recepcion de los datos en formato JSON
    // El segundo parametro si es Tue el json lo decodifica como array php
    // y si el False lo decodifica como stdObject
    $data = json_decode($formData, True);

    $idProveedor 	  		= $data['proveedorModal']['id_proveedor'];
    $nombreProveedor 		= $data['proveedorModal']['nombre'];
    $numFactura 			= $data['numFactura'];
    $fechaCompra			= $data['fechaCompra'];
	$numFactura 			= $misqli->real_escape_string($numFactura);
	$fechaCompra 			= $misqli->real_escape_string($fechaCompra);
	//$fechaCompraFormatted 	= new DateTime($fechaCompra);
	$precioCompraTotal = 0;
	$IVA = 1.21;

		$sentencia5 ="INSERT INTO compras(numFactura,fechaCompra,idProveedor)
		   				 VALUES('".$numFactura."',
		   				 		'".$fechaCompra."',
		   				 		'".$idProveedor."')";
		$misqli->query($sentencia5);

		$lastIdCompra = $misqli->insert_id;


			foreach ($data['Lineas'] as  $valor)
			{
				$objProducto    = $valor['producto'];
				$nombreElemento = $valor['nombreProducto'];
				$precioVenta 	= $valor['precioVentaUnd'];
				$precioCompra 	= $valor['precioCompraUnd'];
				$unidades 		= $valor['unidades'];
				$idMarca		= $valor['marca']['id_marca'];

				$precioCompraTotal = $precioCompraTotal + ($unidades*$precioCompra);

				//EL PRODUCTO EXISTE
				if($objProducto!='')
				{
					$idElemento = $valor['producto']['idElemento'];

					$sentencia4 ="INSERT INTO linea_compras(precioCompraUnd,cantidad,idCompra,idProducto)
				   				 VALUES('".$precioCompra."',
				   				 		'".$unidades."',
				   				 		'".$lastIdCompra."',
				   				 		'".$idElemento."');";
				   	$misqli->query($sentencia4);

				    $misqli->query("UPDATE productos 
							 		SET cantidadStock =  cantidadStock + '".$unidades."'
							 	 	WHERE idElemento = '".$idElemento."';");

					$misqli->query("UPDATE elementos_comerciales 
							 		SET precioVenta = '".$precioVenta."'
							 	 	WHERE id_elemento = '".$idElemento."';");	
				}
				else 
				{
					//Comprobacion de productos ya existentes de una marca y actualizar su cantidad en caso de que se
					//insertan dos productos iguales en varias lineas de compras
					$resultado = $misqli->query("SELECT id_elemento FROM elementos_comerciales AS ec, productos AS p 
												 WHERE ec.nombreElemento = '".$nombreElemento."' AND ec.id_elemento = p.idElemento AND p.idMarca = '".$idMarca."';");
					$numero_filas = $resultado->num_rows;
					//Si existe el producto de esa marca lo actualizamos
					if($numero_filas > 0)
					{
						while($fila = $resultado->fetch_assoc())
					    {
					        $idElementoExistente = $fila['id_elemento']; 
					    }

					    $sentencia4 ="INSERT INTO linea_compras(precioCompraUnd,cantidad,idCompra,idProducto)
					   				 VALUES('".$precioCompra."',
					   				 		'".$unidades."',
					   				 		'".$lastIdCompra."',
					   				 		'".$idElementoExistente."');";
					   	$misqli->query($sentencia4);

					    $misqli->query("UPDATE productos 
								 		SET cantidadStock =  cantidadStock + '".$unidades."'
								 	 	WHERE idElemento = '".$idElementoExistente."';");

						$misqli->query("UPDATE elementos_comerciales 
								 		SET precioVenta = '".$precioVenta."'
								 	 	WHERE id_elemento = '".$idElementoExistente."';");

					}
					else
					{
						$sentencia1 = "INSERT INTO elementos_comerciales(nombreElemento,precioVenta)
					              	    VALUES ('".$nombreElemento."',
					                      	   '".$precioVenta."');";
						$misqli->query($sentencia1);

					   $lastIdLinea = $misqli->insert_id;

					   $sentencia2 ="INSERT INTO productos(idElemento,idMarca,cantidadStock)
					   				  VALUES('".$lastIdLinea."',
					   				 		'".$idMarca."',
					   				 		'".$unidades."');";
					   	$misqli->query($sentencia2);

					   	$sentencia3 ="INSERT INTO adquerir_producto(idElemento,idProveedor)
					   				  VALUES('".$lastIdLinea."',
					   				 		'".$idProveedor."');";
					   	$misqli->query($sentencia3);

					   	$sentencia4 ="INSERT INTO linea_compras(precioCompraUnd,cantidad,idCompra,idProducto)
					   				  VALUES('".$precioCompra."',
					   				 		'".$unidades."',
					   				 		'".$lastIdCompra."',
					   				 		'".$lastIdLinea."');";
					   	$misqli->query($sentencia4);
					}
				}
			} // Fin foreach

			// Aplicar IVA a la compra TOTAL
			$precioCompraTotal = $precioCompraTotal * $IVA;

			$misqli->query("UPDATE compras 
							 SET precioCompraTotal = '".$precioCompraTotal."'
							 	 WHERE id_compra = '".$lastIdCompra."';");

	$misqli->close();
 ?>