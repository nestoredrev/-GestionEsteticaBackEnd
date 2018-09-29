<?php 

include('../Conexion.php');

$formData 	= file_get_contents('php://input');
$data 		= json_decode($formData);
$idCliente = $data->idCli;


    $resultado = $misqli->query("SELECT id_venta FROM ventas WHERE idCliente = '".$idCliente."';");
    $numero_filas = $resultado->num_rows;
    if($numero_filas > 0)
    {
        $resultado = $misqli->query("SELECT v.id_venta AS idVenta, v.numVenta, v.fechaVenta,v.observaciones, sec.nombre AS nombreSeccion, ser.nombreServicio, ec.nombreElemento, lv.precioVentaUnd
                                     FROM ventas AS v, linea_ventas AS lv, elementos_comerciales AS ec, servicios AS ser, secciones AS sec 
                                     WHERE id_venta = lv.idVenta AND lv.idElemento = ec.id_elemento AND ser.idElemento = ec.id_elemento AND ser.idSeccion = sec.id_seccion AND v.idCliente = '".$idCliente."' ORDER BY idVenta DESC;");

        $serviciosUtilizados = array();

        //Obtener una fila de resultado como un array asociativo
        while ($fila = $resultado->fetch_assoc())
        {

            $serviciosUtilizados[$fila['idVenta']][] =  $fila;
        }


        $resultado = $misqli->query("SELECT v.id_venta AS idVenta,m.nombre AS nombreMarca, ec.nombreElemento AS nombreProducto, lv.precioVentaUnd AS precioVentaUndProducto, lv.cantidad, v.numVenta, v.fechaVenta,v.observaciones 
                                     FROM ventas AS v, linea_ventas AS lv, elementos_comerciales AS ec, productos AS pro, marcas AS m 
                                     WHERE id_venta = lv.idVenta AND lv.idElemento = ec.id_elemento AND pro.idElemento = ec.id_elemento AND pro.idMarca = m.id_marca AND v.idCliente = '".$idCliente."' AND lv.eliminado = 0 ORDER BY idVenta DESC;");

        $elementosComerciales = array();

        //Obtener una fila de resultado como un array asociativo
        while ($fila = $resultado->fetch_assoc())
        {
            //$idVenta = $fila['idVenta'];
            //$serviciosUtilizados['ServiciosUtilizados'][] = $fila;
            $serviciosUtilizados[$fila['idVenta']][] =  $fila;
        }

        foreach ($serviciosUtilizados as $key => $value)
        {
            array_push($elementosComerciales, $value);
        }

        //$aux = array();
        //$contenidoPerfilCliente = array_merge($aux1,$aux2);

        //echo json_encode($contenidoPerfilCliente,JSON_NUMERIC_CHECK);
        echo json_encode($elementosComerciales,JSON_NUMERIC_CHECK);

        /* free result set */
        $resultado->close();

        /* close connection */
        $misqli->close();
    }
    else
    {
        //El cliente no tiene ventas asignadas.
        echo -1;
        $misqli->close();
    }

 ?>