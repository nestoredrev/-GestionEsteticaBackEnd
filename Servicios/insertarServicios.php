<?php 	

	include('../Conexion.php');

	$formData = file_get_contents('php://input');//Recepcion de los datos en formato JSON
    // El segundo parametro si es Tue el json lo decodifica como array php
    // y si el False lo decodifica como stdObject
    $data = json_decode($formData, True); 

    $idSeccion 	  		= $data['seccionModal']['id_seccion'];
    $nombreSeccion 		= $data['seccionModal']['nombre'];
    $nombreServicio 	= $data['nombreServicio'];
	$nombreServicio 	= $misqli->real_escape_string($nombreServicio);

	//Comprobacion de servicios ya existentes
	$resultado = $misqli->query("SELECT idElemento FROM servicios WHERE nombreServicio = '".$nombreServicio."' AND idSeccion = '".$idSeccion."'");
	$numero_filas = $resultado->num_rows;

	if($numero_filas > 0)
	{
		echo -1;

		$resultado->close();
	}
	else
	{
		$nombreElemento = '';
		$precioElemento = '';
		foreach ($data['Elementos'] as  $valor) {
	    	
			$nombreElemento = $valor['categoria'];
			$precioElemento = $valor['precio'];

			$sentencia1 = "INSERT INTO elementos_comerciales(nombreElemento,precioVenta)
		              	   VALUES ('".$nombreElemento."',
		                      	   '".$precioElemento."')";
			$misqli->query($sentencia1);

		   $lastIdElemento = $misqli->insert_id;

		   $sentencia2 ="INSERT INTO servicios(idElemento,nombreServicio,idSeccion)
		   				 VALUES('".$lastIdElemento."',
		   				 		'".$nombreServicio."',
		   				 		'".$idSeccion."')";
		   	$misqli->query($sentencia2);
		}
	}

	$misqli->close();

 ?>