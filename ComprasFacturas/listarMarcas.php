<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );
    $idRol     = $data->idRol;
    $getBy     = $data->getBy;
    $idRol     = $misqli->real_escape_string($idRol);
    $getBy     = $misqli->real_escape_string($getBy);

    switch ($getBy)
    {
    	case 'proveedor':
    		//Listar todas las marcas de un proveedor
			$resultado = $misqli->query("SELECT m.id_marca,
												m.nombre
												FROM marcas AS m, asignar_marca as am
												WHERE m.id_marca = am.idMarca AND am.idProveedor = '".$idRol."' 
												AND m.eliminado = 0 ORDER BY m.nombre");
			
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

   		case 'usuario':
   			//Listar todas las marcas de usuario
   			$resultado = $misqli->query("SELECT m.id_marca,
												m.nombre,
												p.nombre as nombreProveedor
										 FROM proveedores as p, asignar_marca as am, marcas as m 
										 WHERE p.idUsuario = '".$idRol."' AND p.id_proveedor = am.idProveedor AND m.id_marca = am.idMarca AND p.eliminado = 0 AND m.eliminado = 0 ORDER BY m.nombre");
			
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
    	
    	// default:
    	// echo -1;
    	// break;
    }

	$misqli->close();

 ?>