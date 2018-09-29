<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents('php://input');
	$data 		= json_decode($formData);

	$idSeccion 		= $data->idSeccion;
	$nombreSeccion 	= $data->nombre;
	$idUser			= $data->idUser;

	//la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $idSeccion   	= $misqli->real_escape_string($idSeccion);
    $nombreSeccion  = $misqli->real_escape_string($nombreSeccion);
    $idUser  		= $misqli->real_escape_string($idUser);

    $misqli->query("UPDATE secciones 
                    SET 
                    nombre    = '".$nombreSeccion."'
                    WHERE id_seccion='".$idSeccion."' AND idUsuario = '".$idUser."' ");

    $misqli->close();

 ?>