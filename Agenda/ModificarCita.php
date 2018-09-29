<?php 

    include('../Conexion.php');

    $formData = file_get_contents( 'php://input' );
    $data     = json_decode( $formData );

    $tipoCliente = $data->tipoCliente;
    $tipoCliente = $misqli->real_escape_string($tipoCliente);

    $id     = $data->idCita;
    $titulo = $data->title;
    $inicio = $data->start;
    $fin    = $data->end;
    $idTipo = $data->idTipo;
    $idCliente  = $data->idCliente;


    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $id     = $misqli->real_escape_string($id);
    $titulo = $misqli->real_escape_string($titulo);
    $inicio = $misqli->real_escape_string($inicio);
    $fin    = $misqli->real_escape_string($fin);
    $idTipo = $misqli->real_escape_string($idTipo);
    $idCliente  = $misqli->real_escape_string($idCliente);


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
                    title   = '".$titulo."', 
                    start   = '".$inicio."',
                    end     = '".$fin."',
                    color   = '".$color."',
                    textColor = '".$textColor."',
                    idCliente = '".$idCliente."'
                    WHERE   id='".$id."';");

    $misqli->close();
 ?>