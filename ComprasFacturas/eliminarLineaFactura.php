<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );

	$idCompra 		= $data->idCompra;
	$idLinea 		= $data->idLinea;
	$idProducto 	= $data->idProducto;
	$unidadesLinea  = $data->unidades;

	
	$diferenciaUnidades = 0;
	$totalFactura = 0;
	$IVA = 0.21;

	/*Comprobar si el producto pertenece a una venta, si pertenece a una venta
	no podra ser eliminado*/
	$resultado = $misqli->query("SELECT idElemento FROM linea_ventas WHERE idElemento='".$idProducto."' AND eliminado = 0;");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		echo -1;
		$resultado->close();
	}
	else
	{
	    $cantidadExistente 	= getStockProducto($idProducto,$misqli);

	    $precioCompraTotal 	= getValorTotalCompra($idCompra,$misqli); 

	    $precioCompraUnd 	= getPrecioUndLinea($idLinea,$misqli);
	    

	    $totalFactura = $precioCompraTotal - ($unidadesLinea*$precioCompraUnd + ($unidadesLinea*$precioCompraUnd*$IVA));

	    $diferenciaUnidades = $cantidadExistente - $unidadesLinea;

    	 
    	$misqli->query("UPDATE productos SET cantidadStock = cantidadStock - '".$unidadesLinea."' WHERE idElemento='".$idProducto."' ");

    	// $misqli->query("UPDATE elementos_comerciales SET eliminado = 1 WHERE id_elemento='".$idElemento."' ");

    	$misqli->query("UPDATE linea_compras SET eliminado = 1 WHERE id_lineaCompras='".$idLinea."' ");
    	$misqli->query("UPDATE compras SET precioCompraTotal = '".$totalFactura."' WHERE id_compra='".$idCompra."' ");

    	$resultado->close();	
	}

	$misqli->close();

	function getStockProducto($idElemento,$misqli)
	{
		$resultado = $misqli->query("SELECT cantidadStock FROM productos WHERE idElemento = '".$idElemento."'");
	    $row = $resultado->fetch_assoc();
		return $row['cantidadStock'];
	}

	function getValorTotalCompra($idCompra,$misqli)
	{
		$resultado = $misqli->query("SELECT precioCompraTotal FROM compras WHERE id_compra = '".$idCompra."'");
	    $row = $resultado->fetch_assoc();
	    return $row['precioCompraTotal'];
	}

	function getPrecioUndLinea($idLinea,$misqli)
	{
		$resultado = $misqli->query("SELECT precioCompraUnd FROM linea_compras WHERE id_lineaCompras = '".$idLinea."'");
	    $row = $resultado->fetch_assoc();
	    return $row['precioCompraUnd'];
	}
	


 ?>