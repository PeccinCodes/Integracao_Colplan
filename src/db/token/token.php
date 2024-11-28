<?php
    //Efetua a requisição do token para a requisição
    $url = "https://peccin-colplan.ve3.com.br/api/oauth";
    //$url = "https://homo-peccin-colplan.ve3.com.br/api/oauth";

    // Credenciais para autenticação
    $body = [
        "client_id" => "importer", // Substitua pelo seu client_id
        "client_secret" => "importer", // Substitua pelo seu client_secret
        "grant_type" => "client_credentials"
    ];

    // Inicializa o cURL
    $ch = curl_init($url);

    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna a resposta como string
    curl_setopt($ch, CURLOPT_POST, true); // Define que será uma requisição POST
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body)); // Converte o corpo para JSON

    // Executa a requisição
    $response = curl_exec($ch);

    // Verifica se ocorreu algum erro
    if (curl_errno($ch)) {
        echo "Erro: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Fecha a conexão cURL
    curl_close($ch);

    // Decodifica a resposta JSON
    $responseData = json_decode($response, true);

    // Verifica se o token foi retornado
    if (isset($responseData['access_token'])) {
        $token = $responseData['access_token'];
        //echo "Token obtido com sucesso: $token";
    } else {
        //echo "Erro ao obter o token: " . $response;
        exit;
    }

