<?php 

    include('../Conexion.php');

    $formData = file_get_contents('php://input');
    $data = json_decode($formData);

    $idUser        = $data->idUser;
    $usuario       = $data->usuario;
    $email         = $data->email;
    $nombreEmpresa = $data->nombreEmpresa;
    $contrasenaOld = $data->contrasenaOld;
    $contrasenaNew = $data->contrasenaNew;

    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $idUser         = $misqli->real_escape_string($idUser);
    $usuario        = $misqli->real_escape_string($usuario);
    $email          = $misqli->real_escape_string($email);
    $nombreEmpresa  = $misqli->real_escape_string($nombreEmpresa);
    $contrasenaOld  = $misqli->real_escape_string($contrasenaOld);
    $contrasenaNew   = $misqli->real_escape_string($contrasenaNew);


    if($contrasenaOld==null || $contrasenaNew==null)
    {
        $misqli->query("UPDATE usuarios 
                            SET nombreEmpresa = '".$nombreEmpresa."',
                                email = '".$email."'
                             WHERE id_usuario='".$idUser."';");
        echo -2;
        $misqli->close();
    }
    else
    {
        $resultado = $misqli->query("SELECT usuario, contrasena  FROM usuarios WHERE id_usuario='".$idUser."' AND eliminado = 0;");

        /* determinar el número de filas del resultado */
        $row_cnt = $resultado->num_rows;

        while($fila = $resultado->fetch_assoc())
        {
            $userDB   = $fila['usuario'];
            $hashPass = $fila['contrasena'];    
        }

        //strcmp compara cadenas si ambas cadenas coinciden devuelve 0
        if( (strcmp($usuario, $userDB)==0 && password_verify($contrasenaOld, $hashPass)==TRUE) && $row_cnt == 1 )
        {
            $opciones = [
                'cost' => 12,
            ];
            $pasCifrado = password_hash($contrasenaNew, PASSWORD_BCRYPT, $opciones);

            $misqli->query("UPDATE usuarios 
                            SET contrasena = '".$pasCifrado."',
                                nombreEmpresa = '".$nombreEmpresa."',
                                email = '".$email."'
                             WHERE id_usuario='".$idUser."';");
        }
        else
        {
            echo -1;
            $misqli->close();
        }
    }



    $misqli->close();

 ?>