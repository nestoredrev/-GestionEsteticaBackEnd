<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );

    $idRol     = $data->idRol;
    $getBy     = $data->getBy;
    $idRol     = $misqli->real_escape_string($idRol);
    $getBy     = $misqli->real_escape_string($getBy);


    switch ($getBy) {
    	case 'proveedor':
		    $resultado = $misqli->query("SELECT id_compra,
												numFactura,
												fechaCompra,
												precioCompraTotal
												FROM compras WHERE idProveedor = '".$idRol."' AND eliminado = 0 ORDER BY fechaCompra DESC;");
			
			$numero_filas = $resultado->num_rows;
			if($numero_filas > 0)
			{
				$set = array();
				//Obtener una fila de resultado como un array asociativo
				while ($fila = $resultado->fetch_assoc())
				{
				   $set[] = $fila;
				}

				echo json_encode($set);

				/* free result set */
				$resultado->close();
			}
			else
			{
				//No hay resultados de la consulta
				echo -1;
				/* free result set */
				$resultado->close();
			}
    	break;
    	//Para obtener la informacion de la cabecera de la factura
    	case 'factura':
    		$resultado = $misqli->query("SELECT id_compra,
    											numFactura,
												fechaCompra,
												precioCompraTotal,
												idProveedor
												FROM compras WHERE id_compra = '".$idRol."' AND eliminado = 0;");
			
			$numero_filas = $resultado->num_rows;
			if($numero_filas > 0)
			{
				$set = array();
				//Obtener una fila de resultado como un array asociativo
				while ($fila = $resultado->fetch_assoc())
				{
				   $set[] = $fila;
				}

				echo json_encode($set);

				/* free result set */
				$resultado->close();
			}
			else
			{
				//No hay resultados de la consulta
				echo -1;
				/* free result set */
				$resultado->close();
			}
    	break;
    }




	$misqli->close();

 ?>