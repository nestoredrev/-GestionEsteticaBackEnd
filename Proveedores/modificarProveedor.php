<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');
	$data = json_decode($formData, TRUE);

	$idProveedor = $data['idProvedor'];
	$nombrePro 	 = $data['nombre'];
	$telefono 	 = $data['telefono'];
	$email 		 = $data['email'];
	$localidad 	 = $data['localidad'];
	$direccion 	 = $data['direccion'];

	//la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $idProveedor     = $misqli->real_escape_string($idProveedor);
    $nombrePro  	= $misqli->real_escape_string($nombrePro);
    $telefono  		= $misqli->real_escape_string($telefono);
    $email  		= $misqli->real_escape_string($email);
    $localidad  	= $misqli->real_escape_string($localidad);
    $direccion  	= $misqli->real_escape_string($direccion);

    $misqli->query("UPDATE proveedores 
					SET nombre 		= '".$nombrePro."',
						telefono 	= '".$telefono."',
						email 		= '".$email."',
						localidad 	= '".$localidad."',
						direccion 	= '".$direccion."' 
					WHERE id_proveedor = '".$idProveedor."';");

	foreach($data['marcasExistentes'] as $valor)
	{
		$idMarca = $valor['id_marca'];
		$nombre  = $valor['nombreMarca'];

		$misqli->query("UPDATE marcas 
						SET nombre = '".$nombre."' 
						WHERE id_marca = '".$idMarca."';");
	}

	// Si no esta vacio la array insertar las nuevas marcas
	if (!empty( $data['nuevasMarcas']) )
	{
		foreach ($data['nuevasMarcas'] as $valor)
		{
			$nombreMarca = $valor['newMarca'];

			$sentenciaMarca = "INSERT INTO marcas(nombre) VALUES('".$nombreMarca."');";

			$result = $misqli->query($sentenciaMarca);

			$lastIdMarca = $misqli->insert_id; // Obtener el ID de la ultima marca insertada

			//Realizar el insert de la relacion N:N de la tabla asignar_marca
			$sentenciaAsignarMarca = "INSERT INTO asignar_marca(idProveedor,idMarca) VALUES('".$idProveedor."','".$lastIdMarca."');";

			$misqli->query($sentenciaAsignarMarca);
		}
	}

	$misqli->close();

 ?>