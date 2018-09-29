<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idVenta = $data->idVenta;

	/*Comprobar si la venta(factura contiene lineas)*/
	$resultado = $misqli->query("SELECT id_lineaVentas FROM linea_ventas WHERE idVenta='".$idVenta."' AND eliminado = 0;");

	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		foreach ($resultado as $valor)
		{
			$idLinea = $valor['id_lineaVentas'];

			$idElemento = getIdElemento($idLinea,$misqli);

			$cantidad = getCantidadLinea($idLinea,$misqli);

			//El elemento es un producto
			if($cantidad!=NULL)
			{
				$misqli->query("UPDATE productos SET cantidadStock = cantidadStock + '".$cantidad."' WHERE idElemento='".$idElemento."';");

				$misqli->query("UPDATE linea_ventas SET eliminado = 1 WHERE id_lineaVentas='".$idLinea."';");
			}
			//El elemento es un servicio
			else
			{
				$misqli->query("UPDATE linea_ventas SET eliminado = 1 WHERE id_lineaVentas='".$idLinea."';");
			}

		} // Fin foreach
		$misqli->query("UPDATE ventas SET eliminado = 1 WHERE id_venta='".$idVenta."';");

		$resultado->close();
	}
	/*Si no tiene lineas se elimina la venta entera*/
	else
	{
		$misqli->query("UPDATE ventas SET eliminado = 1 WHERE id_venta='".$idVenta."';");
	}

	$misqli->close();

	function getIdElemento($idLinea,$misqli)
	{
		$resultado = $misqli->query("SELECT idElemento FROM linea_ventas WHERE id_lineaVentas = '".$idLinea."';");
	    $row = $resultado->fetch_assoc();
		return $row['idElemento'];
	}

	function getCantidadLinea($idLinea,$misqli)
	{
		$resultado = $misqli->query("SELECT cantidad FROM linea_ventas WHERE id_lineaVentas = '".$idLinea."';");
	    $row = $resultado->fetch_assoc();
	    return $row['cantidad'];
	}
 ?>