<?php 

	include('../Conexion.php');

	$formData 	= file_get_contents( 'php://input' );
	$data 		= json_decode( $formData );
	$idProveedor = $data->idProvee;

	$resultado = $misqli->query("SELECT * FROM adquerir_producto AS ap, elementos_comerciales AS ec 
								 WHERE ap.idProveedor = '".$idProveedor."' AND ap.idElemento = ec.id_elemento AND ec.eliminado = 0;");
	$numero_filas = $resultado->num_rows;
	if($numero_filas > 0)
	{
		echo -1;
		$resultado->close();
	}
	else
	{
		$misqli->query("UPDATE proveedores SET eliminado = 1 WHERE id_proveedor='".$idProveedor."' ");	
	}
    
    $misqli->close();

 ?>