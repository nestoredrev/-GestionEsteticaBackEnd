<?php 
	
	include('../Conexion.php');
	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);
	
	$id 	= $data->idCompra;
	$id 	= $misqli->real_escape_string($id);

	$resultado = $misqli->query("SELECT c.id_compra, c.idProveedor, lc.id_lineaCompras,  lc.precioCompraUnd, lc.cantidad, lc.idProducto, ec.nombreElemento, ec.precioVenta, p.cantidadStock, m.id_marca, m.nombre  
								FROM compras as c, linea_compras as lc, elementos_comerciales as ec, productos as p, marcas as m 
								WHERE id_compra = '".$id."' AND idCompra = '".$id."' AND lc.idProducto = ec.id_elemento AND p.idElemento = lc.idProducto AND p.idMarca = m.id_marca AND lc.eliminado = 0");
	//Comprobar si la factura tiene lineas de compras
	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		$set = array();

		while($fila = $resultado->fetch_assoc())
		{
			$set[] = $fila;
		}
		$resultado->close();
		//JSON_NUMERIC_CHECK es necesario a la hora de codificar los numeros
		//si no los devuelve en el cliente como undefined
		echo json_encode($set,JSON_NUMERIC_CHECK);
	}
	else
	{
		echo -1;
		$resultado->close();
	}

	$misqli->close();

 ?>