<?php 


include('../Conexion.php');
ini_set("upload_max_filesize", "10M");
//$location = $_POST['directory'];
//$uploadfile = $_POST['fileName'];
$idProducto = $_POST['idPro'];
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
        $uploadfile =  'fotoPerfil_'.$idProducto.'_'.$numRandom.'.jpg';
        $uploadfilename = $_FILES['file']['tmp_name'];  
}

//__DIR__ devuelve la ruta absoluto del fichero subirFoto.php
$location = __DIR__.'/imgProductos/';  

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

            $urlFoto = $uri."/appBackEnd/Productos/imgProductos/".$uploadfile."";
            $misqli->query("UPDATE productos SET urlFoto = '".$urlFoto."' WHERE idElemento ='".$idProducto."';");
            //echo 'Foto guardada...';
}
else
{
    echo 'Error al guardar la foto...';
    $misqli->close();
}

$misqli->close();


// if (is_uploaded_file($_FILES['file']['tmp_name'])) {
//    echo "Archivo ". $_FILES['file']['name'] ." subido con éxtio.\n";
// } else {
//    echo "Posible ataque del archivo subido: ";
//    echo "nombre del archivo '". $_FILES['file']['tmp_name'] . "'.";
// }


// if ($_FILES["file"]["error"] > 0){
// echo "Error Code: " . $_FILES["file"]["error"] . "<br />";
// }
// else
// {
// echo "Uploaded file: " . $_FILES["file"]["name"] . "<br />";
// echo "Type: " . $_FILES["file"]["type"] . "<br />";
// echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kilobytes<br />";
// }


// if(move_uploaded_file($uploadfilename, $location.'/'.$uploadfile)){
//         echo 'File successfully uploaded!';
// }
// else
// {
//         echo 'Upload error!';
// }


    // $formData 	= file_get_contents( 'php://input' );
    // $data 		= json_decode( $formData );
    // var_dump($data);
    // exit();
    // $misqpli->close();
// if you want to find the root path of a folder use the line of code below:
//echo $_SERVER['DOCUMENT_ROOT']


// if ($_FILES["file"]["error"] > 0){
// echo "Error Code: " . $_FILES["file"]["error"] . "<br />";
// }
// else
// {
// echo "Uploaded file: " . $_FILES["file"]["name"] . "<br />";
// echo "Type: " . $_FILES["file"]["type"] . "<br />";
// echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kilobytes<br />";

// if (file_exists("/files/".$_FILES["file"]["name"]))
//   {
//   echo $_FILES["file"]["name"] . " already exists. No joke-- this error is almost <i><b>impossible</b></i> to get. Try again, I bet 1 million dollars it won't ever happen again.";
//   }
// else
//   {
//   move_uploaded_file($_FILES["file"]["tmp_name"],"/var/www/vhosts/yourdomain.com/subdomains/domainname/httpdocs/foldername/images/".$_FILES["file"]["name"]);
//   echo "Done";
//   }
// }



 ?>