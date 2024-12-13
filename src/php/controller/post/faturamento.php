<?php
    ini_set('memory_limit', '512M');
    include('../../../db/connection.php');
    include('../../../db/token/token.php');

    $arquivoLogError = '../../../log/errorLog.txt';
    $arquivoLogSuccess = '../../../log/successLog.txt';

    // Caminho do arquivo SQL
    $sqlFilePath = "../../../db/sql/scripts/faturamento.sql";
    $api_url = "https://peccin-colplan.ve3.com.br/api/async/invoices/channel-hierarchy";
    //$api_url = "https://homo-peccin-colplan.ve3.com.br/api/async/invoices/channel-hierarchy";

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

    // Função para converter os dados para o formato desejado - AGRUPA TODOS OS DETALHES
    function formatData($data) {
        $formattedData = [];
        
        // Agrupando os dados pelo "order_code"
        $groupedData = [];
        foreach ($data as $item) {
            $orderCode = $item['ORDER_CODE'];
            
            // Inicializa o grupo se ainda não existir
            if (!isset($groupedData[$orderCode])) {
                $groupedData[$orderCode] = [
                    "order_code" => $orderCode,
                    "details" => [],
                    "date" => $item['DATE2'],
                    "code" => $item['CODE'],
                    "invoice_status" => "01", // Adicione um valor fixo ou ajuste conforme necessário
                    "recipient_identifier" => formatCNPJ($item['RECIPIENT_IDENTIFIER']),
                    "issuing_identifier" => formatCNPJ($item['ISSUING_IDENTIFIER']),
                    "channel_code_level_1" => $item['CHANNEL_CODE_LEVEL_1'],
                    "channel_code_level_2" => $item['CHANNEL_CODE_LEVEL_2'],
                    "channel_code_level_3" => $item['CHANNEL_CODE_LEVEL_3'],
                    "channel_code_level_4" => $item['CHANNEL_CODE_LEVEL_4']
                ];
            }
            
            // Adiciona os detalhes ao grupo
            $groupedData[$orderCode]['details'][] = [
                "sku_code" => $item['SKU_CODE'],
                "quantity" => (int) $item['QUANTITY'], // Converte para inteiro
                "gross_revenue" => (float) $item['GROSS_REVENUE'], // Converte para float
                "net_revenue" => (float) $item['VALOR_LIQUIDO'] // Converte para float
            ];
        }
        
        // Reorganiza os dados agrupados
        foreach ($groupedData as $group) {
            $formattedData[] = $group;
        }
        
        return $formattedData;
    }

    // Função para formatar CNPJ/CPF com máscara
    function formatCNPJ($identifier) {
        if (strlen($identifier) === 14) { // CNPJ
            return substr($identifier, 0, 2) . '.' . 
                substr($identifier, 2, 3) . '.' . 
                substr($identifier, 5, 3) . '/' . 
                substr($identifier, 8, 4) . '-' . 
                substr($identifier, 12, 2);
        }
        return $identifier; // Retorna sem máscara caso não seja CNPJ
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

    // Converte os dados para o formato desejado
    $formattedData = formatData($resultados);

    $json_resultados = json_encode($formattedData);

    // Converte para JSON
    header('Content-Type: application/json');
    
    //echo json_encode($formattedData, JSON_PRETTY_PRINT);

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

    // Decodifica a resposta JSON
    $response_data = json_decode($response, true);

    // Verifica se a decodificação foi bem-sucedida
    if ($response_data !== null && json_last_error() === JSON_ERROR_NONE) {
        if (isset($response_data['status']) && $response_data['status'] === 202) {
            salvarLog($arquivoLogSuccess, "Sucesso: " . json_encode($response_data));
            echo json_encode([
                "success" => true,
                "message" => "Dados enviados com sucesso!",
                "response" => $response_data
            ]);
        } else {
            salvarLog($arquivoLogError, "Erro no retorno da API: " . json_encode($response_data));
            echo json_encode([
                "success" => false,
                "message" => "Erro no retorno da API!",
                "response" => $response_data
            ]);
        }
    } else {
        $erroJson = json_last_error_msg();
        salvarLog($arquivoLogError, "Erro ao decodificar JSON de resposta: $erroJson");
        die("Erro ao decodificar JSON de resposta: $erroJson");
    }

    // Fecha a sessão cURL
    curl_close($ch);
