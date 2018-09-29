<?php 

	echo 'manutepower !!!!<br />';
	$secretKey = 'joker';
	$header = [
		'typ' => 'JWT',
		'alg' => 'HS256'
	];

	$header = json_encode($header);
	$header = base64_encode($header);

	//print_r($header);

	//datos al cifrar
	$payload = [
		'id' => 169,
		'nombre' => 'Nestor',
		'apellidos' => 'Edrev'
	];

	$payload = json_encode($payload);
	$payload = base64_encode($payload);

	//contenido del signature
	//echo($header.'.'.$payload);
	
	$datos = $header.'.'.$payload;
	
	$signature = hash_hmac('sha256', $datos, $secretKey,true);
	$signature = base64_encode($signature);

	$token = $header.'.'.$payload.'.'.$signature;
	echo $token;

 ?>