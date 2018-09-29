<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');
	$data = json_decode($formData, TRUE);

	$nombreServicio = $data['nombreServicio'];
	$idSeccion 	  	= $data['idSeccion'];
	$nombreServicio = $misqli->real_escape_string($nombreServicio);
	$idSeccion 		= $misqli->real_escape_string($idSeccion);

	foreach($data['categoriasExistentes'] as $valor)
	{
		$idElemento = $valor['id_elemento'];
		$nombre 	= $valor['nombreElemento'];
		$precio 	= $valor['precioVenta'];

		$misqli->query("UPDATE servicios 
						SET nombreServicio = '".$nombreServicio."' 
						WHERE idElemento = '".$idElemento."';");
		
		$misqli->query("UPDATE elementos_comerciales 
						 SET nombreElemento = '".$nombre."', 
						 	 precioVenta='".$precio."'
						 	 WHERE id_elemento = '".$idElemento."';");
	}

	if($data['categoriasNuevas'] != null)
	{

		foreach ($data['categoriasNuevas'] as $valor)
		{
			$nombreElemento = $valor['newCategoria'];
			$precioElemento = $valor['precioVenta'];

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