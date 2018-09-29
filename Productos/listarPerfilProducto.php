<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);
	$idProducto = $data->idProducto;

	// $resultado = $misqli->query("SELECT p.cantidadStock, p.urlFoto, ec.nombreElemento, m.nombre AS nombreMarca, pro.nombre AS nombreProveedor, ec.precioVenta, lc.precioCompraUnd, lc.cantidad AS cantidadCompra, c.fechaCompra FROM productos AS p, elementos_comerciales AS ec, marcas AS m, adquerir_producto AS ap, proveedores AS pro, linea_compras AS lc, compras AS c WHERE ec.id_elemento = '".$idProducto."' AND ec.id_elemento = p.idElemento AND p.idMarca = m.id_marca AND ap.idElemento = '".$idProducto."' AND pro.id_proveedor = ap.idProveedor AND lc.idProducto = '".$idProducto."' AND lc.idCompra = c.id_compra;");
	
	$resultado = $misqli->query("SELECT p.cantidadStock, p.urlFoto, ec.nombreElemento, m.nombre AS nombreMarca, pro.nombre AS nombreProveedor, ec.precioVenta FROM productos AS p, elementos_comerciales AS ec, marcas AS m, adquerir_producto AS ap, proveedores AS pro, linea_compras AS lc, compras AS c WHERE ec.id_elemento = '".$idProducto."' AND ec.id_elemento = p.idElemento AND p.idMarca = m.id_marca AND ap.idElemento = '".$idProducto."' AND pro.id_proveedor = ap.idProveedor AND lc.idProducto = '".$idProducto."' AND lc.idCompra = c.id_compra;");

	$info = array();

	//Obtener una fila de resultado como un array asociativo
	while ($fila = $resultado->fetch_assoc())
	{
	   $info['infoProducto'] = $fila;
	}

	$resultado = $misqli->query("SELECT lc.precioCompraUnd, lc.cantidad AS cantidadCompra, c.fechaCompra, c.id_compra, pro.nombre AS nombreProveedor FROM elementos_comerciales AS ec, linea_compras AS lc, compras AS c, proveedores AS pro, adquerir_producto AS ap WHERE ec.id_elemento = '".$idProducto."' AND lc.idProducto = '".$idProducto."' AND lc.idCompra = c.id_compra AND ap.idElemento = '".$idProducto."' AND pro.id_proveedor = ap.idProveedor AND lc.eliminado = 0 ORDER BY c.fechaCompra DESC;");

	$compras = array();

	//Obtener una fila de resultado como un array asociativo
	while ($fila = $resultado->fetch_assoc())
	{
	   $compras['Compras'][] = $fila;
	}

	$resultado = $misqli->query("SELECT lv.precioVentaUnd, lv.cantidad AS cantidadVenta, v.fechaVenta, v.id_venta, pro.nombre AS nombreProveedor FROM elementos_comerciales AS ec, linea_ventas AS lv, ventas AS v, proveedores AS pro, adquerir_producto AS ap WHERE ec.id_elemento = '".$idProducto."' AND lv.idElemento = '".$idProducto."' AND lv.idVenta = v.id_venta AND ap.idElemento = '".$idProducto."' AND pro.id_proveedor = ap.idProveedor AND lv.eliminado = 0 ORDER BY v.fechaVenta DESC;");

	$ventas = array();

	//Obtener una fila de resultado como un array asociativo
	while ($fila = $resultado->fetch_assoc())
	{
	   
	   $ventas['Ventas'][] = $fila;
	}

	$contenidoProducto = array_merge($info,$compras,$ventas);

	echo json_encode($contenidoProducto,JSON_NUMERIC_CHECK);

	/* free result set */
	$resultado->close();

	/* close connection */
	$misqli->close();

 ?>