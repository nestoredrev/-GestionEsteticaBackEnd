<?php 

    include('../Conexion.php');

    $formData = file_get_contents('php://input');
    $data = json_decode($formData);

    $idUsuario     = $data->id_usuario;
    $usuario       = $data->usuario;
    $contrasena    = $data->nuevaContrasena;
    $nombreEmpresa = $data->nombreEmpresa;
    $email         = $data->email;
    $eliminado     = $data->eliminado;

    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $idUsuario      = $misqli->real_escape_string($idUsuario);
    $usuario        = $misqli->real_escape_string($usuario);
    $contrasena     = $misqli->real_escape_string($contrasena);
    $nombreEmpresa  = $misqli->real_escape_string($nombreEmpresa);
    $email          = $misqli->real_escape_string($email);
    $eliminado      = $misqli->real_escape_string($eliminado);

    if($contrasena!='')
    {

        $resultado = $misqli->query("SELECT usuario FROM usuarios WHERE usuario='".$usuario."' AND id_usuario != '".$idUsuario."';");

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
            $opciones = [
                'cost' => 12,
            ];
            $pasCifrado = password_hash($contrasena, PASSWORD_BCRYPT, $opciones);

            $misqli->query("UPDATE usuarios 
                    SET 
                    usuario       = '".$usuario."',
                    contrasena    =  '".$pasCifrado."',
                    nombreEmpresa = '".$nombreEmpresa."',
                    email         = '".$email."',
                    eliminado     = '".$eliminado."'
                    WHERE id_usuario='".$idUsuario."';");
        }
    }
    else
    {
        $resultado = $misqli->query("SELECT usuario FROM usuarios WHERE usuario='".$usuario."' AND id_usuario != '".$idUsuario."';");

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
            $misqli->query("UPDATE usuarios 
                    SET 
                    usuario       = '".$usuario."',
                    nombreEmpresa = '".$nombreEmpresa."',
                    email         = '".$email."',
                    eliminado     = '".$eliminado."'
                    WHERE id_usuario='".$idUsuario."';");
        }
    }

    $misqli->close();

 ?>