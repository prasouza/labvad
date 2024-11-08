<?php
$email = isset($_POST['email']) ? $_POST['email'] : FALSE;
if (! empty($email)) {
	//echo $email;
	require_once 'app.ado/TConnection.class.php';
	$conn = TConnection::open();
	$sql  = $conn->prepare("SELECT 
                                        pessoas.id, 
                                        pessoas.nome, 
                                        pessoas.email  
                                 FROM 
                                        pessoas 
                                 WHERE
                                        (pessoas.email = :email)
                                 LIMIT 1 ");
	$sql->bindParam(':email', $email);
	$sql->execute();		
	$resultado = $sql->fetchObject();	
	if ($resultado) {
		if ($resultado->email == $email) {
			$id = $resultado->id;
			$novaSenha = rand(11998844, 78963214565);
			$sql = $conn->prepare("UPDATE pessoas SET recuperar_senha = MD5(:recuperar_senha), senha_temp = CURRENT_DATE() WHERE id = :id LIMIT 1 ");
			$sql->bindParam(':recuperar_senha', $novaSenha);
			$sql->bindParam(':id', $id);
			$sql->execute();
                        
			$conteudo = http_build_query(array(
				'email' => $email,
				'senha' => $novaSenha
			));
			
			$context = stream_context_create(array(
				'http' => array(
					'method'  => 'POST',
					'content' => $conteudo
				)
			));
			
			$resultado = @file_get_contents('http://labvad.com/redefinir.php', null, $context, -1, 40000);
			
			echo "Senha enviada para o seu email! Se não receber o e-mail em sua caixa de entrada, por favor, não deixe de verificar em seu Lixo Eletrônico ou Spam. Ele pode estar lá!";
		}
	}
	else {
		echo "Email não cadastrado no sistema!";
	}		
}
?>