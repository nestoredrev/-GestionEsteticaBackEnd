<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );
    $idUser     = $data->idUser;
    $parametro  = $data->parametro;
    $idUser     = $misqli->real_escape_string($idUser);
    $parametro  = $misqli->real_escape_string($parametro);

    switch ($parametro)
    {
    	case 'getAllProveedores':
	    	$resultado = $misqli->query("SELECT id_proveedor FROM proveedores WHERE idUsuario = '".$idUser."' AND eliminado = 0");
		
			$numero_filas = $resultado->num_rows;
			if($numero_filas > 0)
			{
				$set = array();
				//Obtener una fila de resultado como un array asociativo
				while ($fila = $resultado->fetch_assoc())
				{
				   
				   	$idPro = $fila["id_proveedor"];

				   	$resultado1 = $misqli->query("SELECT pro.id_proveedor AS id_proveedor,
												pro.nombre AS nombreProveedor,
												pro.telefono AS telefono,
												m.nombre AS marca
												FROM proveedores AS pro, asignar_marca AS am, marcas AS m 
												WHERE pro.id_proveedor = '".$fila["id_proveedor"]."' AND am.idProveedor = '".$fila["id_proveedor"]."' AND am.idMarca = m.id_marca AND m.eliminado = 0 AND pro.eliminado = 0;");

				   while($fila1 = $resultado1->fetch_assoc())
				   {
				   		$set[] = $fila1;
				   }
				}

				echo json_encode($set);

				/* free result set */
				$resultado->close();
				$resultado1->close();
			}
			else
			{
				//No hay resultados de la consulta
				echo -1;
				/* free result set */
				$resultado->close();
				$resultado1->close();
			}		
    	break;
    	case 'getNameProveedores':
    		$resultado2 = $misqli->query("SELECT p.id_proveedor, p.nombre, m.nombre as nombreMarca 
    									  FROM proveedores AS p, marcas AS m, asignar_marca AS am  
    									  WHERE idUsuario = '".$idUser."' AND p.id_proveedor = am.idProveedor AND am.idMarca = m.id_marca AND p.eliminado = 0 AND m.eliminado = 0 ORDER BY p.nombre;");
    		$numero_filas = $resultado2->num_rows;
			if($numero_filas > 0)
			{
				$set1 = array();
				while($fila2 = $resultado2->fetch_assoc())
				{
					$set1[] = $fila2;
				}

				echo json_encode($set1);

				/* free result set */
				$resultado2->close();
			}
			else
			{
				//No hay resultados de la consulta
				echo -1;
				/* free result set */
				$resultado2->close();
			}
    	break;
    	
    	default:
    	echo -1;
    	break;
    }

    

	$misqli->close();

 ?>