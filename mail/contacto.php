<?php
error_reporting(E_ALL);

/* INICIO CONFIGURACION */
// A quien se le enviá la notificación
//$de = array('pablo.amenabar@grazcapital.com', 'Pablo Amenabar'); // array('correo','nombre'); 
$de = array('matiassw@gmail.com', 'Matias Scardia'); // array('correo','nombre'); 
// Asunto del correo
$asunto = "Solicitud de Contacto Web";

/*	Mensaje para el ejecutivo (el que recibes tu)
	Todas las lineas deben terminar con "." excepto la ultima que termina con ";" (Sin comillas)
	El texto contiene las siguientes variables que puedes utilizar
	%1$s: Nombre del cliente
	%2$s: Teléfono
	%3$s: Correo del cliente
	%4$s: Mensaje del cliente
*/
$mensaje_personal = 'Nombre: %1$s'. PHP_EOL .
					'Correo: %3$s'. PHP_EOL .
					'Teléfono: %2$s'. PHP_EOL .
					'Mensaje:%4$s';

//Agregar identificador único? (Evita que los correos de agrupen y que así mismo se pierden :P) - (1 Si, 0 No)
$unique = 1;
//Enviar Correo de Notificación al cliente?
$notif = 1;

/*	Mensaje para el cliente
	Todas las lineas deben terminar con "." excepto la ultima que termina con ";" (Sin comillas)
	El texto contiene las siguientes variables que puedes utilizar
	%1$s: Nombre del cliente
	%2$s: Teléfono
	%3$s: Correo del cliente
	%4$s: Mensaje del cliente
	%5$s: Nombre de quien envía el correo (El mismo de la configuración $de que esta arriba
	Si quieres poner la firma en imagen que tienes, tendrias que reemplazar el %5$s al final con un <img src="url" />
*/

$mensaje_cliente = 'Estimado %1$s,'. PHP_EOL .
					PHP_EOL .
					'Hemos recibido una solicitud de contacto a través de nuestro sitio web y nos pondremos en contacto con usted a la brevedad.'. PHP_EOL .
					PHP_EOL .
					'Saludos,'.PHP_EOL .
					'%5$s';
					
/* FIN DE LA CONFIGURACIÓN */
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['phone']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	
$nombre = $_POST['name'];
$email = $_POST['email'];
$fono = $_POST['phone'];
$mensaje = $_POST['message'];

require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$mail->isSendmail();

$mail->setFrom($email, $nombre);
$mail->addAddress($de[0], $de[1]);
$mail->Subject = $unique?'[#'.substr(md5(rand(0, 1000000)+time()), 0, 7).'] ':'' . $asunto;
$mail->WordWrap = 50; 
$mail->isHTML(true);

$mail->Body    = sprintf($mensaje_personal, $nombre, $fono, $email, nl2br($mensaje));

if(!$mail->send()) {
	die ("0");
} else {
    $ret = 1;
}

if ($notif) {
	$mail = null;
	$mail = new PHPMailer;
	
	$mail->isSendmail();
	
	$mail->addAddress($email, $nombre);
	$mail->setFrom($de[0], $de[1]);
	$mail->Subject = $unique?'[#'.substr(md5(rand(0, 1000000)+time()), 0, 7).'] ':'' . $asunto;
	$mail->WordWrap = 50; 
	$mail->isHTML(true);
	
	$mail->Body    = sprintf($mensaje_cliente, $nombre, $fono, $email, nl2br($mensaje), $de[1]);
	
	if(!$mail->send()) {
		die ("0");
	} else {
		$ret = 2;
	}
}
echo $ret;
?>