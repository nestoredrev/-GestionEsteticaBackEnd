<?php 


include('../Conexion.php');
ini_set("upload_max_filesize", "10M");
//$location = $_POST['directory'];
//$uploadfile = $_POST['fileName'];
$idUser = $_POST['idUser'];
//$uploadfilename = $_FILES['file']['tmp_name'];
//echo $_FILES["file"]["error"].' --> '.$location.' '.$uploadfile.' '.$uploadfilename;



if(!isset($_FILES['file']) || ($_FILES['file']['tmp_name'] == ''))
{
        echo "No hay foto existente.";
}
else
{
        // generamos un numero aleatorio para ser concatenado en la url
        // para que cada foto tenga su url y despues de la actualizacion
        // se visualize.
        $numRandom = rand(1,100);
        $uploadfile =  'fotoPerfil_'.$idUser.'_'.$numRandom.'.jpg';
        $uploadfilename = $_FILES['file']['tmp_name'];  
}

//__DIR__ devuelve la ruta absoluto del fichero subirFoto.php
$location = __DIR__.'/imgUsuarios/';  

if(move_uploaded_file($uploadfilename, $location.$uploadfile))
{
            //unlink($location.$uploadfile); // BORRAR UN FICHERO
            
            if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']))
            {
                $uri = 'https://';
            }
            else
            {
                $uri = 'http://';
            }
            $uri .= $_SERVER['HTTP_HOST'];

            $urlFoto = $uri."/appBackEnd/Usuarios/imgUsuarios/".$uploadfile."";
            $misqli->query("UPDATE usuarios SET urlFoto = '".$urlFoto."' WHERE id_usuario ='".$idUser."';");
            //echo 'Foto guardada...';
}
else
{
    echo 'Error al guardar la foto...';
    $misqli->close();
}

$misqli->close();

 ?>