<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');//Recepcion de los datos en formato JSON
    // El segundo parametro si es Tue el json lo decodifica como array php
    // y si el False lo decodifica como stdObject
    $data = json_decode($formData, True);
    $idCompra       = $data['id_compra'];
	$idProveedor 	= $data['idProveedor'];
	$numFactura 	= $data['numFactura'];
	$fecha			= $data['fechaCompra'];

    $precioCompraTotal = 0;
    $IVA = 1.21;

    if($data['lineasCompra']!=null)
    {

	    //Actualizacion cabecera factura
	    $misqli->query("UPDATE compras 
						SET numFactura = '".$numFactura."',
							fechaCompra = '".$fecha."'
							WHERE id_compra = '".$idCompra."';");

	    
	    foreach($data['lineasCompra'] as $valor)
		{
			$id_linea   	= $valor['id_lineaCompras'];
			$producto 		= $valor['producto'];
			$cantidad 		= $valor['cantidad'];
			$precioCompra 	= $valor['precioCompraUnd'];
			$precioVenta 	= $valor['precioVenta'];
			$idMarca 		= $valor['objMarca']['id_marca'];
			/*Son las cantidades anteriores de los productos que se van a modificar*/
			$cantidadesExistentes = $valor['cantidadesAnteriores'];

			$precioCompraTotal = $precioCompraTotal + ($cantidad*$precioCompra);

			//Actualizar las lineas existentes
			if(isset($id_linea))
			{

				/*id del producto de la linea existente*/
				$idProductoLinea = $valor['idProducto'];

				if($producto!='') // Producto nuevo existente
				{
					$idNuevoProductoExistente = $valor['producto']['idElemento'];

					/*En una linea existente se modifica el producto actual con  uno nuevo existente*/
					if(isset($idNuevoProductoExistente))
					{
						/*Se elimina la linea existente y se sustituye por una nueva con los datos nuevos del produco nuevo existente*/
						$misqli->query("UPDATE linea_compras 
										SET eliminado = 1
										WHERE id_lineaCompras = '".$id_linea."';");

						/*Restar las cantidades del producto existente que fue modificado con el nuevo producto en la linea de compra eliminada*/			
						$misqli->query("UPDATE productos 
								 		SET cantidadStock =  cantidadStock - '".$cantidadesExistentes."'
								 	 	WHERE idElemento = '".$idProductoLinea."';");


						/*Insercion del producto nuevo existente en una nueva linea de compra*/
						$sentenciaSQL ="INSERT INTO linea_compras(precioCompraUnd,cantidad,idCompra,idProducto)
					   				 VALUES('".$precioCompra."',
					   				 		'".$cantidad."',
					   				 		'".$idCompra."',
					   				 		'".$idNuevoProductoExistente."');";
					   	$misqli->query($sentenciaSQL);

					   	/*Actualizacion del producto nuevo existente de su precio de venta*/
						$misqli->query("UPDATE elementos_comerciales 
								 SET precioVenta = '".$precioVenta."'
								WHERE id_elemento = '".$idNuevoProductoExistente."';");

						/*Actualizar el stock del producto nuevo que fue actualizado en la linea de la compra*/
						$stockTotalCompras = getTotalStock($idNuevoProductoExistente,$misqli);

						$misqli->query("UPDATE productos 
								 		SET cantidadStock = '".$stockTotalCompras."'
								 	 	WHERE idElemento = '".$idNuevoProductoExistente."';	");
					}
					/*Actualizacion de la linea existente sin modificar el producto en su linea, solo actualizar la cantidad precio de compra y venta y el stock*/
					else
					{
						$misqli->query("UPDATE linea_compras 
										SET cantidad = '".$cantidad."',
											precioCompraUnd = '".$precioCompra."'
										WHERE id_lineaCompras = '".$id_linea."';");

						$misqli->query("UPDATE elementos_comerciales 
								 SET precioVenta = '".$precioVenta."'
								WHERE id_elemento = '".$idProductoLinea."';");

						$stockTotalCompras = getTotalStock($idProductoLinea,$misqli);

						$misqli->query("UPDATE productos 
								 		SET cantidadStock = '".$stockTotalCompras."'
								 	 	WHERE idElemento = '".$idProductoLinea."';	");
					}
				}
				/*En una linea existente se modifica el producto actual con  uno nuevo sin existir por lo tanto hay que darlo de alta como elemento comercial*/
				else
				{
					$nuevoProductoNombre = $valor['nombreProducto'];

					/*Se elimina la linea existente y se sustituye por una nueva con los datos nuevos del produco nuevo existente*/
					$misqli->query("UPDATE linea_compras 
									SET eliminado = 1
									WHERE id_lineaCompras = '".$id_linea."';");

					/*Restar las cantidades del producto existente que fue modificado con el nuevo producto en la linea de compra eliminada*/			
					$misqli->query("UPDATE productos 
							 		SET cantidadStock =  cantidadStock - '".$cantidadesExistentes."'
							 	 	WHERE idElemento = '".$idProductoLinea."';");

					$sentencia1 = "INSERT INTO elementos_comerciales(nombreElemento,precioVenta)
				              	    VALUES ('".$nuevoProductoNombre."',
				                      	   '".$precioVenta."');";
					$misqli->query($sentencia1);

				   	$idNuevoProducto = $misqli->insert_id;

				   	$sentencia2 ="INSERT INTO productos(idElemento,idMarca,cantidadStock)
				   				  VALUES('".$idNuevoProducto."',
				   				 		'".$idMarca."',
				   				 		'".$cantidad."');";
				   	$misqli->query($sentencia2);

				   	$sentencia3 ="INSERT INTO adquerir_producto(idElemento,idProveedor)
				   				  VALUES('".$idNuevoProducto."',
				   				 		'".$idProveedor."');";
				   	$misqli->query($sentencia3);

				   	$sentencia4 ="INSERT INTO linea_compras(precioCompraUnd,cantidad,idCompra,idProducto)
				   				  VALUES('".$precioCompra."',
				   				 		'".$cantidad."',
				   				 		'".$idCompra."',
				   				 		'".$idNuevoProducto."');";
				   	$misqli->query($sentencia4);
				}
			}
			//Añadir lineas nuevas
			else
			{
				if($producto!='') // Producto nuevo existente
				{
					$idNuevoProductoExistente = $valor['producto']['idElemento'];

					/*Insercion del producto nuevo existente en una nueva linea de compra*/
					$sentenciaSQL ="INSERT INTO linea_compras(precioCompraUnd,cantidad,idCompra,idProducto)
				   				 VALUES('".$precioCompra."',
				   				 		'".$cantidad."',
				   				 		'".$idCompra."',
				   				 		'".$idNuevoProductoExistente."');";
				   	$misqli->query($sentenciaSQL);

				   	/*Actualizacion del producto nuevo existente de su precio de venta*/
					$misqli->query("UPDATE elementos_comerciales 
							 SET precioVenta = '".$precioVenta."'
							WHERE id_elemento = '".$idNuevoProductoExistente."';");

					/*Actualizar el stock del producto nuevo que fue actualizado en la linea de la compra*/
					$stockTotalCompras = getTotalStock($idNuevoProductoExistente,$misqli);

					$misqli->query("UPDATE productos 
							 		SET cantidadStock = '".$stockTotalCompras."'
							 	 	WHERE idElemento = '".$idNuevoProductoExistente."';	");
				}
				else // Producto nuevo nuevo
				{
					$nuevoProductoNombre = $valor['nombreProducto'];

					$sentencia1 = "INSERT INTO elementos_comerciales(nombreElemento,precioVenta)
				              	    VALUES ('".$nuevoProductoNombre."',
				                      	   '".$precioVenta."');";
					$misqli->query($sentencia1);

				   	$idNuevoProducto = $misqli->insert_id;

				   	$sentencia2 ="INSERT INTO productos(idElemento,idMarca,cantidadStock)
				   				  VALUES('".$idNuevoProducto."',
				   				 		'".$idMarca."',
				   				 		'".$cantidad."');";
				   	$misqli->query($sentencia2);

				   	$sentencia3 ="INSERT INTO adquerir_producto(idElemento,idProveedor)
				   				  VALUES('".$idNuevoProducto."',
				   				 		'".$idProveedor."');";
				   	$misqli->query($sentencia3);

				   	$sentencia4 ="INSERT INTO linea_compras(precioCompraUnd,cantidad,idCompra,idProducto)
				   				  VALUES('".$precioCompra."',
				   				 		'".$cantidad."',
				   				 		'".$idCompra."',
				   				 		'".$idNuevoProducto."');";
				   	$misqli->query($sentencia4);
				}
			}

		}
    }
   	else
	{
		echo -1;
		$misqli->close(); 
	}


	$precioCompraTotal = $precioCompraTotal * $IVA;

	$misqli->query("UPDATE compras 
					SET precioCompraTotal = '".$precioCompraTotal."'
					WHERE id_compra = '".$idCompra."';");

    $misqli->close(); 

    function getTotalStock($idProducto,$misqli)
	{
		$resultado = $misqli->query("SELECT SUM(cantidad) AS StockTotalCompras FROM linea_compras WHERE idProducto = '".$idProducto."';");
	    $row = $resultado->fetch_assoc();
	    return $row['StockTotalCompras'];
	}

 ?>