<?php

function asaas_CriarCliente($dataForm, $config) {

	include('config.php');

	//removendo campos que não serão usados para incluir o cliente
	unset($dataForm['value']);











    // Tabela que sera feita a consulta
    $tabela = "tb_clientes";

    // ID que você deseja pesquisar
    $email = $dataForm['email'];

    // Consulta SQL
    $sql = "SELECT asaas_id FROM $tabela WHERE email = :email";

    // Preparar a consulta
    $stmt = $conn->prepare($sql);

    // Vincular o valor do parâmetro
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Executar a consulta
    $stmt->execute();

    // Obter o resultado como um array associativo
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


    // Verifica se a consulta retornou algum resultado
    if ($stmt->rowCount() > 0) {
		// Verificar se o resultado foi encontrado
		if ($resultado) {
			// Atribuir o valor da coluna à variável, ex.: "nome" = $nome
			$retorno['id'] = $resultado['asaas_id'];
			return $retorno['id'];
		}
    } else {
		












		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $config['asaas_api_url'].'/api/v3/customers',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($dataForm),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'access_token: '.$config['asaas_api_key']
			)
		));
		$response = curl_exec($curl);
		curl_close($curl);
	
		$retorno = json_decode($response, true);
	
		if($retorno['object'] == 'customer') {
	
			$tabela = 'tb_clientes';
	
			$stmt = $conn->prepare("INSERT INTO $tabela (nome, email, phone, cpf, cep, endereco, numero, complemento, municipio, cidade, uf, asaas_id) VALUES (
				:name, :email, :phone, :cpfCnpj, :postalCode, :address, :addressNumber, :complement, :province, :city, :state, :id)");
			
			// Bind dos parâmetros
			$stmt->bindParam(':name', $retorno['name'], PDO::PARAM_STR);
			$stmt->bindParam(':email', $retorno['email'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $dataForm['phone'], PDO::PARAM_STR);
			$stmt->bindParam(':cpfCnpj', $dataForm['cpfCnpj'], PDO::PARAM_STR);
			$stmt->bindParam(':postalCode', $dataForm['postalCode'], PDO::PARAM_STR);
			$stmt->bindParam(':address', $retorno['address'], PDO::PARAM_STR);
			$stmt->bindParam(':addressNumber', $retorno['addressNumber'], PDO::PARAM_INT);
			$stmt->bindParam(':complement', $retorno['complement'], PDO::PARAM_STR);
			$stmt->bindParam(':province', $retorno['province'], PDO::PARAM_STR);
			$stmt->bindParam(':city', $dataForm['city'], PDO::PARAM_STR);
			$stmt->bindParam(':state', $retorno['state'], PDO::PARAM_STR);
			$stmt->bindParam(':id', $retorno['id'], PDO::PARAM_STR);
	
			// Executando o update
			$stmt->execute();
	
			return $retorno['id'];
		} else {
			echo $response;
			exit();
		}















    }





















	

}