<?php

    include('../../../db/connection.php');
    include('../../../db/token/token.php');

    $arquivoLogError = '../../../log/errorLog.txt';
    $arquivoLogSuccess = '../../../log/successLog.txt';

    // Caminho do arquivo SQL (não utilizado neste código, mas mantendo para contexto futuro)
    $sqlFilePath = "../../../db/sql/scripts/produtos.sql";
    $api_url = "https://peccin-colplan.ve3.com.br/api/sop";

    // Função para salvar log das operações
    function salvarLog($arquivo, $mensagem) {
        $arquivoHandle = fopen($arquivo, 'a');
        if ($arquivoHandle) {
            $dataHora = date('Y-m-d H:i:s');
            fwrite($arquivoHandle, "[$dataHora] $mensagem\n");
            fclose($arquivoHandle);
        } else {
            echo "Não foi possível abrir o arquivo de log.";
        }
    }

    // Verifica se o token está disponível
    if (!isset($token) || empty($token)) {
        salvarLog($arquivoLogError, "Token de autenticação não definido.");
        die("Erro: Token de autenticação não definido.");
    }

    // Define o cabeçalho de resposta para JSON
    header('Content-Type: application/json');

    // Inicializa cURL
    $ch = curl_init($api_url);

    // Configura opções do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);

    // Executa a requisição cURL
    $response = curl_exec($ch);

    // Verifica se houve erro na requisição
    if ($response === false) {
        $erro = curl_error($ch);
        salvarLog($arquivoLogError, "Erro na requisição cURL: $erro");
        die(json_encode(["erro" => "Erro na requisição cURL: $erro"]));
    }

    // Obtém o código de resposta HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Fecha a sessão cURL
    curl_close($ch);

    // Verifica o código de resposta HTTP
    if ($httpCode >= 200 && $httpCode < 300) {
        // Decodifica a resposta JSON
        $response_data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // Log de sucesso
            salvarLog($arquivoLogSuccess, "\n(Lista de Ciclos) - Requisição bem-sucedida.");
            // Exibe a resposta decodificada
            $sop_disaggregation = json_encode($response_data);
            print_r($sop_disaggregation);
        } else {
            salvarLog($arquivoLogError, "Erro ao decodificar resposta JSON: " . json_last_error_msg());
            die(json_encode(["erro" => "Erro ao decodificar resposta JSON: " . json_last_error_msg()]));
        }
    } else {
        salvarLog($arquivoLogError, "Requisição falhou com código HTTP: $httpCode. Resposta: $response");
        die(json_encode(["erro" => "Requisição falhou com código HTTP: $httpCode."]));
    }
