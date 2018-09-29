<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');//Recepcion de los datos en formato JSON
    // El segundo parametro si es Tue el json lo decodifica como array php
    // y si el False lo decodifica como stdObject
    $data = json_decode($formData, True);
    $idCompra = $data['idCompra'];
    $precioCompraTotal = 0;
    $IVA = 1.21;
    $eliminarCompra = false;

    if($data['lineasCompra']!=null)
    {
	    $idCompra = $data['lineasCompra'][0]['id_compra'];

	    //Actualizar las lineas existentes
	    foreach($data['lineasCompra'] as $valor)
		{
			$id_linea   	= $valor['id_lineaCompras'];
			$cantidad 		= $valor['cantidad'];
			$precioCompra 	= $valor['precioCompraUnd'];
			$nombreProducto = $valor['nombreElemento'];
			$precioVenta 	= $valor['precioVenta'];
			$idElemento 	= $valor['idProducto'];
			$idMarca 		= $valor['objMarca']['id_marca'];
			$nombreMarca  	= $valor['objMarca']['nombre'];

			/*Si el producto pertenece a una venta no se puede borrar o editar*/
			$resultado = $misqli->query("SELECT idElemento FROM linea_ventas WHERE idElemento='".$idElemento."' AND eliminado = 0;");

			$numero_filas = $resultado->num_rows;
			if($numero_filas > 0)
			{
				$eliminarCompra = false;
				echo $nombreMarca.' '.$nombreProducto;
				$resultado->close();
				$misqli->close();
			}
			else
			{
				$eliminarCompra = true;
				//Comprobacion de productos ya existentes de una marca y actualizar su cantidad
				$resultado = $misqli->query("SELECT id_elemento FROM elementos_comerciales AS ec, productos AS p 
											 WHERE ec.nombreElemento = '".$nombreProducto."' AND ec.id_elemento = p.idElemento AND p.idMarca = '".$idMarca."' AND ec.eliminado = 0;");
				$numero_filas = $resultado->num_rows;
				//Si existe el producto de esa marca lo actualizamos
				if($numero_filas > 0)
				{
				    $row = $resultado->fetch_assoc();
					$idElemento = $row['id_elemento'];

					$misqli->query("UPDATE productos 
							 		SET cantidadStock =  cantidadStock - '".$cantidad."'
							 	 	WHERE idElemento = '".$idElemento."';");

					$cantidadExistente 	= getStockProducto($idElemento,$misqli);

			    	// $misqli->query("UPDATE elementos_comerciales SET eliminado = 1 WHERE id_elemento='".$idElemento."' ");

			    	$misqli->query("UPDATE linea_compras SET eliminado = 1 WHERE id_lineaCompras='".$id_linea."' ");
			    	// $misqli->query("UPDATE compras SET precioCompraTotal = '".$totalFactura."' WHERE id_compra='".$idCompra."' ");
					
					$resultado->close();
				}
			} 
	    }// Fin foreach
	    if($eliminarCompra == true)
	    {
	    	$misqli->query("UPDATE compras SET eliminado = 1 WHERE id_compra = '".$idCompra."';");
	    }
	}// Fin Lineas existens
	//Si no tiene lineas las factura se elimina
	else
	{
		$misqli->query("UPDATE compras SET eliminado = 1 WHERE id_compra = '".$idCompra."';");
	}
		
    $misqli->close();

    function getStockProducto($idElemento,$misqli)
	{
		$resultado = $misqli->query("SELECT cantidadStock FROM productos WHERE idElemento = '".$idElemento."'");
	    $row = $resultado->fetch_assoc();
		return $row['cantidadStock'];
	}

 ?>