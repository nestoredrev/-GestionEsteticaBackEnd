<?php 

    include('../Conexion.php');

    $formData = file_get_contents('php://input');
    $data = json_decode($formData);

    $idCliente  = $data->id_cliente;
    $editNombre = $data->nombre;
    $editApell1 = $data->apellido1;
    $editApell2 = $data->apellido2;
    $editTel    = $data->telefono;
    $editEmail  = $data->email;
    $idTipo     = $data->idTipo;

    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $idCliente   = $misqli->real_escape_string($idCliente);
    $editNombre  = $misqli->real_escape_string($editNombre);
    $editApell1  = $misqli->real_escape_string($editApell1);
    $editApell2  = $misqli->real_escape_string($editApell2);
    $editTel     = $misqli->real_escape_string($editTel);
    $editEmail   = $misqli->real_escape_string($editEmail);
    $idTipo      = $misqli->real_escape_string($idTipo);

    $misqli->query("UPDATE clientes 
                    SET 
                    nombre    = '".$editNombre."', 
                    apellido1 = '".$editApell1."',
                    apellido2 = '".$editApell2."',
                    telefono  = '".$editTel."',
                    email     = '".$editEmail."',
                    idTipo    = '".$idTipo."'
                    WHERE id_cliente='".$idCliente."' ");

    //Modificacion de color al cambiar el tipo de cliente
    if($idTipo == 66)
    {
        $color = '#886aea';
        $textColor = '#ffffff';
    }
    else
    {
        $color = '#444';
        $textColor = '#ffffff';
    }

    $misqli->query("UPDATE citas 
                    SET 
                    color   = '".$color."',
                    textColor = '".$textColor."'
                    WHERE   idCliente = '".$idCliente."';");

    $misqli->close();

 ?>