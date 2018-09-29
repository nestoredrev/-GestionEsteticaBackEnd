<?php 

include('Conexion.php');

// Desactivar toda notificación de error
//error_reporting(0);

// Notificar solamente errores de ejecución
//error_reporting(E_ERROR | E_WARNING | E_PARSE);


//header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//header("Content-Type: application/json; charset=UTF-8");
// if (isset($_SERVER['HTTP_ORIGIN'])) {
//         header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//         header('Access-Control-Allow-Credentials: true');
//         header('Access-Control-Max-Age: 86400');    // cache for 1 day
//     }
 
//     // Access-Control headers are received during OPTIONS requests
//     if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
 
//         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
//             header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
 
//         if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
//             header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
 
//         exit(0);
//     }

	    //$objDatos = json_decode(file_get_contents("php://input"));
        //$data               = file_get_contents("php://input");
        //$dataJsonDecode     = json_decode($data);	

    $formData = file_get_contents( 'php://input' );
    $data = json_decode( $formData );

    $usuario = $data->user;
    $contrasena = $data->pass;

    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $usuario = $misqli->real_escape_string($usuario);
    $contrasena = $misqli->real_escape_string($contrasena);


    //var_dump("user ".$usuario." pass ".$contrasena);

    $resultado = $misqli->query("SELECT id_usuario, usuario, contrasena  FROM usuarios WHERE usuario='".$usuario."' AND eliminado = 0;");

    /* determinar el número de filas del resultado */
    $row_cnt = $resultado->num_rows;

    while($fila = $resultado->fetch_assoc())
    {
        $idUser   = $fila['id_usuario'];  
        $userDB   = $fila['usuario'];
        $hashPass = $fila['contrasena'];    
    }

    //strcmp compara cadenas si ambas cadenas coinciden devuelve 0
    if( (strcmp($usuario, $userDB)==0 && password_verify($contrasena, $hashPass)==TRUE) && $row_cnt == 1 )
    {
        session_start();
        $_SESSION["idLogin"]   = $idUser;
        $_SESSION["nameLogin"] = $userDB;
        
        $loginArray = array('id' => $_SESSION["idLogin"], 'userName' => $_SESSION["nameLogin"]);
        echo json_encode($loginArray);
    }
    else
    {
        echo -1;
    }
    
    /* liberar el conjunto de resultados */
    $resultado->free();
    $misqli->close();

?>

