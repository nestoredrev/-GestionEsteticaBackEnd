<?php 

    include('../Conexion.php');

    $formData = file_get_contents('php://input');
    $data = json_decode($formData);

    $idEmpleado = $data->id_empleado;
    $editNombre = $data->nombre;
    $editApell1 = $data->apellido1;
    $editApell2 = $data->apellido2;
    $editTel    = $data->telefono;

    //la cual antepone barras invertidas a los siguientes caracteres: \x00, \n, \r, \, ', " y \x1a.
    $idEmpleado  = $misqli->real_escape_string($idEmpleado);
    $editNombre  = $misqli->real_escape_string($editNombre);
    $editApell1  = $misqli->real_escape_string($editApell1);
    $editApell2  = $misqli->real_escape_string($editApell2);
    $editTel     = $misqli->real_escape_string($editTel);

    $misqli->query("UPDATE empleados 
                    SET 
                    nombre    = '".$editNombre."', 
                    apellido1 = '".$editApell1."',
                    apellido2 = '".$editApell2."',
                    telefono  = '".$editTel."'
                    WHERE id_empleado='".$idEmpleado."' ");

    $misqli->close();

 ?>