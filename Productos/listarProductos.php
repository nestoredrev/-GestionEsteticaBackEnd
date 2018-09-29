<?php 
	
	include('../Conexion.php');
	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);

	$idMarca	= $data->idMarca;
	$idMarca  	= $misqli->real_escape_string($idMarca);

	//Obtener informacion toda la informacion sobre de todos los productos segun una marca seleccionada
	$resultado = $misqli->query("SELECT p.idElemento, p.idMarca, p.urlFoto, p.cantidadStock, ec.nombreElemento AS nombreProducto, ec.precioVenta, lc.precioCompraUnd  
								FROM productos AS p, elementos_comerciales AS ec, compras AS c, linea_compras AS lc
								WHERE p.idMarca = '".$idMarca."' AND p.idElemento = ec.id_elemento AND lc.idProducto = p.idElemento AND c.id_compra = lc.idCompra AND ec.eliminado = 0 GROUP BY p.idElemento;");


	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();

		while($fila = $resultado->fetch_assoc())
		{
			$set[] = $fila;
		}

		echo json_encode($set,JSON_NUMERIC_CHECK);

		$resultado->close();
	}
	else
	{
		//No hay resultados de la consulta
		echo -1;
		$resultado->close();
	}


	$misqli->close();
 ?>