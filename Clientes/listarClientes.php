<?php 

include('../Conexion.php');


    $formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );
    $parametro 	= $data->q;
    $idUser     = $data->idUser;
    $idUser     = $misqli->real_escape_string($idUser);

    switch ($parametro)
    {
    	case 'citasClientes':
			$resultado = $misqli->query("SELECT c.id_cliente,
								c.nombre,
	            				c.apellido1,
	            				c.apellido2,
	            				tc.nombreTipo,
	  							tc.id_tipo
	            				FROM clientes AS c, tipo_cliente AS tc WHERE c.idUsuario = '".$idUser."' AND tc.id_tipo = c.idTipo AND c.eliminado = 0 ORDER BY nombre");
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

    	case 'listaClientes':
			$resultado = $misqli->query("SELECT id_cliente,
												nombre,
			                    				apellido1,
			                    				apellido2,
			                    				telefono,
			                    				urlFoto,
			                    				idTipo 
			                    			    FROM clientes WHERE idUsuario = '".$idUser."' AND eliminado = 0 ORDER BY nombre");

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
    	
    	default:
    		# code...
    	break;
    }

/* close connection */
$misqli->close();

 ?>