<?

define("SECRET_KEY", "6Le7E7YZAAAAAK0GUaftnbwWqLQ3uZVBnbpKoTFT");

if(isset($_POST["contato"])){
		
	$emailsender = "contato@papelegancia.com.br";

	/* Verifica qual éo sistema operacional do servidor para ajustar o cabeçalho de forma correta.  */
	if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
	else $quebra_linha = "\n"; //Se "nÃ£o for Windows"

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	$emaildestinatario = "contato@papelegancia.com.br";
	$name = test_input($_POST["inputName"]);
	$email = test_input($_POST["inputEmail"]);
	$cel = test_input($_POST["inputCel"]);
	$msg = test_input($_POST["inputMessage"]);
	$cep = test_input($_POST["inputCep"]);
	$rolls = test_input($_POST["inputRolos"]);
	$token = $_POST["g-recaptcha-response"];

	$assunto = 'Formulário de Contato Papelegância';

	/* Montando a mensagem a ser enviada no corpo do e-mail. */
	$mensagemHTML = '<html><body>';
	$mensagemHTML .= '<h1 style="color:#8e7360;">Papelegância - Contato</h1>';
	$mensagemHTML .= '<p style="color:#000;font-size:18px;font-weight: bold;">Nome: <span style="color:#888;">'.$name.'</span></p>';
	$mensagemHTML .= '<p style="color:#000;font-size:18px;font-weight: bold;">Email: <span style="color:#888;">'.$email.'</span></p>';
	$mensagemHTML .= '<p style="color:#000;font-size:18px;font-weight: bold;">Celular: <span style="color:#888;">'.$cel.'</span></p>';
	$mensagemHTML .= '<p style="color:#000;font-size:18px;font-weight: bold;">Mensagem: <span style="color:#888;">'.$msg.'</span></p>';
	$mensagemHTML .= '<p style="color:#000;font-size:18px;font-weight: bold;">Cep: <span style="color:#888;">'.$cep.'</span></p>';
	$mensagemHTML .= '<p style="color:#000;font-size:18px;font-weight: bold;">Rolos: <span style="color:#888;">'.$rolls.'</span></p>';
	$mensagemHTML .= '</body></html>';

	/* Montando o cabeÃ§alho da mensagem */
	$headers = "MIME-Version: 1.1" .$quebra_linha;
	$headers .= "Content-type: text/html; charset=iso-8859-1" .$quebra_linha;
	// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
	$headers .= "From: " .$emailsender .$quebra_linha;
	$headers .= "Cc: " .$comcopia .$quebra_linha;
	$headers .= "Bcc: " .$comcopiaoculta .$quebra_linha;
	$headers .= "Reply-To: " .$email .$quebra_linha;
	// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)
	
	/* Enviando a mensagem */

	//É obrigatório o uso do parâmetro -r (concatenação do "From na linha de envio"), aqui na Locaweb:

	$Response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.SECRET_KEY.'&response='.$token);
	$Return = json_decode($Response);
	if($Return->success == true && $Return->score > 0.4) {
		if(!mail($emaildestinatario, $assunto, $mensagemHTML, $headers ,"-r".$emailsender)) { // Se for Postfix
			$headers .= "Return-Path: " . $emailsender . $quebra_linha; // Se "não for Postfix"
			mail($emaildestinatario, $assunto, $mensagemHTML, $headers );
			echo "Sua mensagem foi enviada com sucesso!";
		}
	} else {
		//ERRO: Robot verification failed, please try again.
		echo 'Voce é um robô';
	}

}
else {
	echo "<script>window.location = 'https://papelegancia.com.br'</script>";
}

?>
