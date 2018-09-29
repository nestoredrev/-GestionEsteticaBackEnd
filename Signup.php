<?php 

	include('Conexion.php');
	$formData = file_get_contents( 'php://input' );
	$data = json_decode( $formData );

	$usuario 		= $data->usuario;
	$contrasena 	= $data->contrasena;
	$nombreEmpresa 	= $data->nombreEmpresa;
	$email 			= $data->email;

	//la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
	$usuario 		= $misqli->real_escape_string($usuario);
	$contrasena 	= $misqli->real_escape_string($contrasena);
	$nombreEmpresa 	= $misqli->real_escape_string($nombreEmpresa);
	$email			= $misqli->real_escape_string($email);


	$resultado = $misqli->query("SELECT usuario FROM usuarios WHERE usuario='".$usuario."'");

    /* determinar el número de filas del resultado */
    $numero_filas = $resultado->num_rows;

    if($numero_filas > 0)
    {
    	//Error: El usuario ya existe.
    	echo -1;
    	$misqli->close();
    }
    else
    {
    	$res = $misqli->query("INSERT INTO calendario VALUES()");
    	$lastIdCalendario = getLastId('calendario','id_calendario',$misqli);
    	$opciones = [
		    'cost' => 12,
		];
		$pasCifrado = password_hash($contrasena, PASSWORD_BCRYPT, $opciones);

		$sentencia = "INSERT INTO usuarios(usuario,contrasena,nombreEmpresa,email,tipoUsuario)
	                  VALUES ('".$usuario."',
	                          '".$pasCifrado."',
	                          '".$nombreEmpresa."',
	                          '".$email."',
	                          11)";

	    $misqli->query($sentencia);
	    $lastIdUsuario = getLastId('usuarios','id_usuario',$misqli);

	    $res1 = $misqli->query("INSERT INTO pertenecer_calendario(idUsuario,idCalendario) VALUES ('".$lastIdUsuario."','".$lastIdCalendario."')");
	    $res2 = $misqli->query("INSERT INTO citas(idCalendario) VALUES ('".$lastIdCalendario."')");
	    $misqli->close();
    }

	function getLastId($table,$idRow,$misqli)
    {
    	$res = $misqli->query("SELECT $idRow FROM $table ORDER BY $idRow DESC LIMIT 1");
    	$row = $res->fetch_assoc();
		return $row[$idRow];
    }

 ?>