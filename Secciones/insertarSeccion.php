<?php 
	
	include('../Conexion.php');
	$formData = file_get_contents('php://input');
	$data     = json_decode($formData);

	$insertNombre 	= $data->nombre;
  $idUser         = $data->idUser;

    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $insertNombre   = $misqli->real_escape_string($insertNombre);
    $idUser         = $misqli->real_escape_string($idUser);

    $resultado = $misqli->query("SELECT id_seccion FROM secciones WHERE nombre='".$insertNombre."' AND idUsuario='".$idUser."' AND eliminado = 0");
    $numero_filas = $resultado->num_rows;
    if($numero_filas > 0)
    {
      //Error la seccion al introducir ya existe.
      echo -1;

      $resultado->close();
    }
    else
    {
      $sentencia = "INSERT INTO secciones(nombre,idUsuario)
                  VALUES ('".$insertNombre."',
                          '".$idUser."')";

      $misqli->query($sentencia);
    }

    $misqli->close();

 ?>