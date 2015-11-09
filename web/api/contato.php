<?php

    
//contato [POST]
if ($_SERVER["REQUEST_METHOD"] === "POST")
{ 
	$json = file_get_contents("php://input");
	$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

 	$str = array();
 	$str[] = "Mensagem enviada pelo site: <br />";
	foreach ($jsonIterator as $key => $val) {
	    if(is_array($val)) {
	        $str[] = "<b>$key:</b> <br />";
	    } else {
	        $str[] =  "<b>$key:</b> $val <br />";
	    }
	}
 	$body = implode($str);
	echo $body;
 
	require("./phpmailer/class.phpmailer.php");

	// Inicia a classe PHPMailer
	$mail = new PHPMailer();
	$sender = "usabitapps@gmail.com"; 
	$para = "contato@usabit.com.br"; 

	// Define os dados do servidor e tipo de conex�o
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP();
	$mail->Host = "smtp.gmail.com"; // Endere�o do servidor SMTP (caso queira utilizar a autentica��o, utilize o host smtp.seudom�nio.com.br)
	$mail->SMTPAuth = true; // Usar autentica��o SMTP (obrigat�rio para smtp.seudom�nio.com.br)
	$mail->Username = $sender; // Usu�rio do servidor SMTP
	$mail->Password = "20usabit15"; // Senha do servidor SMTP
	$mail->Port       = 465;   
	$mail->SMTPSecure = "ssl";
	$mail->SMTPDebug  = 1;


	// Define o remetente
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->From = $para; // Seu e-mail
	$mail->Sender = $sender; // Seu e-mail
	$mail->FromName = "Usabit"; // Seu nome
	 
	// Define os destinat�rio(s)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->AddAddress($para);
	 
	// Define os dados t�cnicos da Mensagem
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
	 
	// Define a mensagem (Texto e Assunto)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->Subject  = "[Usabit] Contato - via web site"; // Assunto da mensagem
	$mail->Body = $body;
	 

	// Envia o e-mail
	$enviado = $mail->Send();
	 
	// Limpa os destinat�rios e os anexos
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	 
	// Exibe uma mensagem de resultado
	if ($enviado) {
		//echo "true";
		http_response_code(200);
	} else {
		echo "Mailer Error: " . $mail->ErrorInfo;
		//echo "false";
		http_response_code(500);
	}
 
}
?>