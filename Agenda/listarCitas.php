<?php 

	include('../Conexion.php');

    $formData 	= file_get_contents( 'php://input' );
    $data 		= json_decode( $formData );
    $idUser     = $data->idUser;
    $idUser     = $misqli->real_escape_string($idUser);

    $idCalendar = getIdCalendar($idUser,$misqli);

	$resultado = $misqli->query("SELECT id,title,start,end,url,color,textColor,idCliente FROM citas WHERE idCalendario = '".$idCalendar."' AND title IS NOT NULL");
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

		/* close connection */
		$misqli->close();
	}
	else
	{
		echo -1;
		$misqli->close();
	}


	function getIdCalendar($idUser,$misqli)
    {
    	$res = $misqli->query("SELECT idCalendario FROM pertenecer_calendario WHERE idUsuario = '".$idUser."'");
    	$row = $res->fetch_assoc();
		return $row['idCalendario'];
    }
 ?>