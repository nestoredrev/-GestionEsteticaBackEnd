<?php 

	include('../Conexion.php');

    $formData = file_get_contents('php://input');
    $data = json_decode($formData);

    $insertNombre 	= $data->nombre;
    $insertApelli1 	= $data->apellido1;
    $insertApelli2 	= $data->apellido2;
    $insertTel 		  = $data->telefono;
    $insertEmail    = $data->email;
    $idUser         = $data->idUser;

    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $insertNombre   = $misqli->real_escape_string($insertNombre);
    $insertApelli1  = $misqli->real_escape_string($insertApelli1);
    $insertApelli2  = $misqli->real_escape_string($insertApelli2);
    $insertTel      = $misqli->real_escape_string($insertTel);
    $insertEmail    = $misqli->real_escape_string($insertEmail);
    $idUser         = $misqli->real_escape_string($idUser);

    $sentencia = "INSERT INTO clientes(nombre,apellido1,apellido2,telefono,email,idUsuario)
                  VALUES ('".$insertNombre."',
                          '".$insertApelli1."',
                          '".$insertApelli2."',
                          '".$insertTel."',
                          '".$insertEmail."',
                          '".$idUser."')";

    $misqli->query($sentencia);

    $misqli->close();
 ?>