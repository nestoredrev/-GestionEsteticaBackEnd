<?php 

	include('../Conexion.php');

	$formData = file_get_contents('php://input');
	$data = json_decode($formData);
	
	$idCategoria	= $data->idCategoria;
	$idCategoria  = $misqli->real_escape_string($idCategoria);

	$misqli->query("UPDATE elementos_comerciales  SET eliminado = 1 WHERE id_elemento = '".$idCategoria."';");
	

	$misqli->close();

 ?>