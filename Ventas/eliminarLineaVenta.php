<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );

	$idVenta 		= $data->idVenta;
	$idLinea 		= $data->idLinea;
	$idElemento 	= $data->idElemento;

	$totalVenta = 0;

	$cantidad = getCantidadLinea($idLinea,$misqli);

	//el idElemento es servicio
	if($cantidad == NULL)
	{
		$precioVentaTotal 	= getValorTotalVenta($idVenta,$misqli); 

		$precioVentaUnd 	= getPrecioUndLinea($idLinea,$misqli);

		$totalVenta 		= $precioVentaTotal - $precioVentaUnd;


		$misqli->query("UPDATE linea_ventas SET eliminado = 1 WHERE id_lineaVentas='".$idLinea."';");

		$misqli->query("UPDATE ventas SET precioVentaTotal = '".$totalVenta."' WHERE id_venta='".$idVenta."';");
	}
	//El idElemento es producto
	else
	{
		$precioVentaTotal 	= getValorTotalVenta($idVenta,$misqli); 

	    $precioVentaUnd 	= getPrecioUndLinea($idLinea,$misqli);

	    $unidadesLinea 		= getCantidadLinea($idLinea,$misqli);

	    $totalVenta = $precioVentaTotal - ($unidadesLinea*$precioVentaUnd);
		 
		$misqli->query("UPDATE productos SET cantidadStock = cantidadStock + '".$unidadesLinea."' WHERE idElemento='".$idElemento."';");


		$misqli->query("UPDATE linea_ventas SET eliminado = 1 WHERE id_lineaVentas='".$idLinea."';");

		$misqli->query("UPDATE ventas SET precioVentaTotal = '".$totalVenta."' WHERE id_venta='".$idVenta."';");
	}

	$misqli->close();

	function getValorTotalVenta($idVenta,$misqli)
	{
		$resultado = $misqli->query("SELECT precioVentaTotal FROM ventas WHERE id_venta = '".$idVenta."';");
	    $row = $resultado->fetch_assoc();
	    return $row['precioVentaTotal'];
	}

	function getPrecioUndLinea($idLinea,$misqli)
	{
		$resultado = $misqli->query("SELECT precioVentaUnd FROM linea_ventas WHERE id_lineaVentas = '".$idLinea."';");
	    $row = $resultado->fetch_assoc();
	    return $row['precioVentaUnd'];
	}

	function getCantidadLinea($idLinea,$misqli)
	{
		$resultado = $misqli->query("SELECT cantidad FROM linea_ventas WHERE id_lineaVentas = '".$idLinea."' AND eliminado = 0;");
	    $row = $resultado->fetch_assoc();
	    return $row['cantidad'];
	}
	


 ?>