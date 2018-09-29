<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');//Recepcion de los datos en formato JSON
    // El segundo parametro si es Tue el json lo decodifica como array php
    // y si el False lo decodifica como stdObject
    $data = json_decode($formData, True); 

	$idUser 	 = $data['idUser'];
	$nombrePro 	 = $data['nombre'];
	$telefono 	 = $data['telefono'];
	$email 		 = $data['email'];
	$localidad 	 = $data['localidad'];
	$direccion 	 = $data['direccion'];

	//la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $idUser     	= $misqli->real_escape_string($idUser);
    $nombrePro  	= $misqli->real_escape_string($nombrePro);
    $telefono  		= $misqli->real_escape_string($telefono);
    $email  		= $misqli->real_escape_string($email);
    $localidad  	= $misqli->real_escape_string($localidad);
    $direccion  	= $misqli->real_escape_string($direccion);

    $sentenciaPro = "INSERT INTO proveedores(nombre,telefono,email,localidad,direccion,idUsuario) 
    							 VALUES('".$nombrePro."',
    							 		'".$telefono."',
    							 		'".$email."',
    							 		'".$localidad."',
    							 		'".$direccion."',
    							 		'".$idUser."');";
    $misqli->query($sentenciaPro);

	$lastIdPro = $misqli->insert_id; // Obtener el ID del ultimo Proveedor insertado

	foreach ($data['nuevasMarcas'] as $valor) {
		$nombreMarca = $valor['newMarca'];

		$sentenciaMarca = "INSERT INTO marcas(nombre) VALUES('".$nombreMarca."');";

		$result = $misqli->query($sentenciaMarca);

		$lastIdMarca = $misqli->insert_id; // Obtener el ID de la ultima marca insertada

		//Realizar el insert de la relacion N:N de la tabla asignar_marca
		$sentenciaAsignarMarca = "INSERT INTO asignar_marca(idProveedor,idMarca) VALUES('".$lastIdPro."','".$lastIdMarca."');";

		$misqli->query($sentenciaAsignarMarca);
	}

	//Este apaño gitano es para eliminar la insercion vacia al realizar una insercion
	//de marca me añade una mas facia y no se porque.
	//$misqli->query("DELETE FROM marcas WHERE nombre=''");

	$misqli->close();

 ?>