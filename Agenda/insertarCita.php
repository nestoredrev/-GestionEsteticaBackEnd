<?php 

	include('../Conexion.php');

	$formData  = file_get_contents( 'php://input' );
    $data 	   = json_decode( $formData );

    $tipoCliente = $data->tipoCliente;
    $tipoCliente = $misqli->real_escape_string($tipoCliente);

    switch ($tipoCliente)
    {
        case 66:

            $title      = $data->title;
            $start      = $data->start;
            $end        = $data->end;
            $idUser     = $data->idUser;
            $idCliente  = $data->idCliente;
            $idTipo     = $data->idTipo;
            
            $title      = $misqli->real_escape_string($title);
            $start      = $misqli->real_escape_string($start);
            $end        = $misqli->real_escape_string($end);
            $idUser     = $misqli->real_escape_string($idUser);
            $idCliente  = $misqli->real_escape_string($idCliente);
            $idTipo     = $misqli->real_escape_string($idTipo);


            $idCalendar = getIdCalendar($idUser,$misqli);

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


            $resultado = $misqli->query("INSERT INTO citas(title,start,end,color,textColor,idCalendario,idCliente)
                                 VALUES('".$title."',
                                        '".$start."',
                                        '".$end."',
                                        '".$color."',
                                        '".$textColor."',
                                        '".$idCalendar."',
                                        '".$idCliente."');");
        break;
        
        case -77:

            $nombre     = $data->nombre;
            $apellido1  = $data->apellido1;
            $start      = $data->start;
            $end        = $data->end;
            $idUser     = $data->idUser;
            
            $nombre     = $misqli->real_escape_string($nombre);
            $apellido1  = $misqli->real_escape_string($apellido1);
            $start      = $misqli->real_escape_string($start);
            $end        = $misqli->real_escape_string($end);
            $idUser     = $misqli->real_escape_string($idUser);

            $nombreCompleto = $nombre.' '.$apellido1;
            $color = '#444';
            $textColor = '#ffffff';

            $res1 = $misqli->query("INSERT INTO clientes(nombre,apellido1,apellido2,idTipo,idUsuario) 
                                    VALUES('".$nombre."','".$apellido1."','',-77,'".$idUser."');");

            $lastIdCliente = $misqli->insert_id;

            $idCalendar = getIdCalendar($idUser,$misqli);

            $resultado = $misqli->query("INSERT INTO citas(title,start,end,color,textColor,idCalendario,idCliente)
                                 VALUES('".$nombreCompleto."',
                                        '".$start."',
                                        '".$end."',
                                        '".$color."',
                                        '".$textColor."',
                                        '".$idCalendar."',
                                        '".$lastIdCliente."');");
        break;
    }



    // $res = $misqli->query("SELECT * FROM clientes 
    //                        WHERE idUsuario = '".$idUser."' 
    //                        AND nombre = '".$title."' 
    //                        AND tipoCliente='66' 
    //                        AND eliminado = 0");
    
    // $numero_filas = $res->num_rows;
    // if($numero_filas == 0)
    // {
    //     
    // }
    

    $misqli->close();

    function getIdCalendar($idUser,$misqli)
    {
    	$res = $misqli->query("SELECT idCalendario FROM pertenecer_calendario WHERE idUsuario = '".$idUser."';");
    	$row = $res->fetch_assoc();
		return $row['idCalendario'];
    }
 ?>