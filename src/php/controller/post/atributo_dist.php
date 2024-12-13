<?php

    include('../../../db/connection.php');
    include('../../../db/token/token.php');

    $arquivoLogError = '../../../log/errorLog.txt';
    $arquivoLogSuccess = '../../../log/successLog.txt';

    // Caminho do arquivo SQL
    $sqlFilePath = "../../../db/sql/scripts/atributo_dist.sql";
    $api_url = "https://peccin-colplan.ve3.com.br/api/async/channels-attributes";
    //$api_url = "https://homo-peccin-colplan.ve3.com.br/api/async/channels-attributes";

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

    // Seta contexto para View
    $caminhoView = oci_parse($ora_conexao, 
        "BEGIN
            fnd_client_info.set_org_context('103');
            dbms_application_info.set_client_info('103');
            apps.mo_global.set_policy_context('S','103'); 
        END;");
    oci_execute($caminhoView);

    // Verifica se o arquivo SQL existe
    if (!file_exists($sqlFilePath)) {
        die("Arquivo SQL não encontrado: $sqlFilePath");
    }

    // Carrega a consulta do arquivo
    $sqlQuery = file_get_contents($sqlFilePath);
    if ($sqlQuery === false) {
        die("Erro ao ler o arquivo SQL.");
    }

    // Prepara e executa a consulta
    $statement = oci_parse($ora_conexao, $sqlQuery);
    if (!$statement) {
        $erro = oci_error($ora_conexao);
        die("Erro ao preparar a consulta: " . $erro['message']);
    }

    if (!oci_execute($statement)) {
        $erro = oci_error($statement);
        die("Erro ao executar a consulta: " . $erro['message']);
    }

    // Obtém os resultados
    $resultados = array();
    while (($row = oci_fetch_assoc($statement)) !== false) {
        $resultados[] = $row;
    }

    // Libera recursos
    oci_free_statement($statement);
    oci_close($ora_conexao);


    // Função para converter chaves de "UPPERCASE" para "snake_case"
    function convertKeysToSnakeCase($array) {
        $convertedArray = [];
        foreach ($array as $key => $value) {
            // Converte as chaves para snake_case
            $snakeKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
            // Verifica se o valor é um array aninhado e aplica recursivamente
            $convertedArray[$snakeKey] = is_array($value) ? convertKeysToSnakeCase($value) : $value;
        }
        return $convertedArray;
    }

    // Converte os resultados para o formato esperado
    $convertedResults = array_map('convertKeysToSnakeCase', $resultados);

    // Converte para JSON o array com chaves ajustadas
    $json_resultados = json_encode($convertedResults);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Erro ao converter resultados para JSON: " . json_last_error_msg());
    }

    // Exibe o JSON ajustado (apenas para debug, remova no ambiente de produção)
    //echo($json_resultados);

    // Define o cabeçalho de resposta para JSON
    header('Content-Type: application/json');

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Erro ao converter resultados para JSON: " . json_last_error_msg());
    }
    
    // Inicializa cURL
    $ch = curl_init($api_url);

    // Configura opções do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
        'Content-Length: ' . strlen($json_resultados)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_resultados); // Envia o array diretamente no body

    // Executa a requisição cURL
    $response = curl_exec($ch);

    // Verifica se houve erro na requisição
    if ($response === false) {
        $erro = curl_error($ch);
        salvarLog($arquivoLogError, "Erro na requisição cURL: $erro");
        die("Erro na requisição cURL: $erro");
    }

    // Obtém o código de resposta HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Exibe informações de depuração
    //echo "HTTP Code: $httpCode\n";
    //echo "Response: $response\n";
    //echo($response);
    // Decodifica a resposta JSON
    $response_data = json_decode($response, true);

    // Verifica se a decodificação foi bem-sucedida
    if ($response_data !== null && json_last_error() === JSON_ERROR_NONE) {
        if (isset($response_data['status']) && $response_data['status'] === 202) {
            salvarLog($arquivoLogSuccess, "Sucesso (Atributo-Distribuidor): " . json_encode($response_data));
            echo json_encode([
                "success" => true,
                "message" => "Dados enviados com sucesso!",
                "response" => $response_data
            ]);
        } else {
            salvarLog($arquivoLogError, "Erro no retorno da API (Atributo-Distribuidor: " . json_encode($response_data));
            echo json_encode([
                "success" => false,
                "message" => "Erro no retorno da API!",
                "response" => $response_data
            ]);
        }
    } else {
        $erroJson = json_last_error_msg();
        salvarLog($arquivoLogError, "Erro ao decodificar JSON de resposta (Atributo-Distribuidor): $erroJson");
        die("Erro ao decodificar JSON de resposta: $erroJson");
    }

    // Fecha a sessão cURL
    curl_close($ch);
