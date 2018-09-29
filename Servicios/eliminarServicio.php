<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');
	$data = json_decode($formData, TRUE);

	foreach($data as $valor)
	{
		$idElemento = $valor['id_elemento'];

		$misqli->query("UPDATE elementos_comerciales  SET eliminado = 1 WHERE id_elemento = '".$idElemento."';");
	}

	$misqli->close();

 ?>