<?php

    include('../../../db/token/token.php');

    $arquivoLogError = '../../../log/errorLog.txt';
    $arquivoLogSuccess = '../../../log/successLog.txt';

    $page    = '1';
    $id      = $_GET['id_ciclo'];
    $data    = $_GET['finish_date'];
    $channel = $_GET['scenario_channel_level'];
    $product = $_GET['scenario_product_level'];

    $api_url = "https://peccin-colplan.ve3.com.br/api/sop-disaggregation?page=".$page."&sop-id=".$id."&financial-data=true&date=".$data."&channel-group-level=".$channel."&product-group-level=".$product."&unit-view-mode=um2&page-size=10000";

    function salvarLog($arquivo, $mensagem) {
        $arquivoHandle = fopen($arquivo, 'a');
        if ($arquivoHandle) {
            $dataHora = date('Y-m-d H:i:s');
            fwrite($arquivoHandle, "[$dataHora] $mensagem\n");
            fclose($arquivoHandle);
        }
    }

    // Verifica se o token está disponível
    if (!isset($token) || empty($token)) {
        salvarLog($arquivoLogError, "Token de autenticação não definido.");
        die(json_encode(["type" => "danger", "message" => "Erro: Token de autenticação não definido."]));
    }

    function inserirDadosNoBanco($itens, $arquivoLogSuccess, $arquivoLogError) {
        include('../../../db/connection.php'); // Conexão Oracle
    
        $sql = "INSERT INTO PCN_INTEGRA_COLPLAN_SOP (
                    SOP_ID,
                    DATE_1,
                    CHANNEL_LEVEL_1_NAME,
                    CHANNEL_LEVEL_1_DESCRIPTION,
                    CHANNEL_LEVEL_2_NAME,
                    CHANNEL_LEVEL_2_DESCRIPTION,
                    CHANNEL_LEVEL_3_NAME,
                    CHANNEL_LEVEL_3_DESCRIPTION,
                    CHANNEL_LEVEL_4_NAME,
                    CHANNEL_LEVEL_4_DESCRIPTION,
                    PRODUCT_LEVEL_1_NAME,
                    PRODUCT_LEVEL_1_DESCRIPTION,
                    PRODUCT_LEVEL_2_NAME,
                    PRODUCT_LEVEL_2_DESCRIPTION,
                    PRODUCT_LEVEL_3_NAME,
                    PRODUCT_LEVEL_3_DESCRIPTION,
                    PRODUCT_LEVEL_4_NAME,
                    PRODUCT_LEVEL_4_DESCRIPTION,
                    QUANTITY_BUDGET,
                    QUANTITY_MARKETING,
                    QUANTITY_FORECAST,
                    QUANTITY_ARRSES,
                    SUGGESTION_1,
                    SUGGESTION_2,
                    SUGGESTION_3,
                    CONSENSUS,
                    CONSENSUS_LAST,
                    CONSTRICTED_DEMAND,
                    UNIT_VIEW_MODE,
                    PRICE_BUDGET,
                    PRICE_MARKETING,
                    PRICE_FORECAST,
                    PRICE_ARRSES,
                    PRICE_SUGGESTION_1,
                    PRICE_SUGGESTION_2,
                    PRICE_SUGGESTION_3,
                    PRICE_CONSENSUS,
                    PRICE_CONSENSUS_LAST,
                    PRICE_CONSTRICTED_DEMAND
                ) VALUES (
                    :SOP_ID,
                    TO_DATE(:DATE_1, 'YYYY-MM-DD'),
                    :CHANNEL_LEVEL_1_NAME,
                    :CHANNEL_LEVEL_1_DESCRIPTION,
                    :CHANNEL_LEVEL_2_NAME,
                    :CHANNEL_LEVEL_2_DESCRIPTION,
                    :CHANNEL_LEVEL_3_NAME,
                    :CHANNEL_LEVEL_3_DESCRIPTION,
                    :CHANNEL_LEVEL_4_NAME,
                    :CHANNEL_LEVEL_4_DESCRIPTION,
                    :PRODUCT_LEVEL_1_NAME,
                    :PRODUCT_LEVEL_1_DESCRIPTION,
                    :PRODUCT_LEVEL_2_NAME,
                    :PRODUCT_LEVEL_2_DESCRIPTION,
                    :PRODUCT_LEVEL_3_NAME,
                    :PRODUCT_LEVEL_3_DESCRIPTION,
                    :PRODUCT_LEVEL_4_NAME,
                    :PRODUCT_LEVEL_4_DESCRIPTION,
                    :QUANTITY_BUDGET,
                    :QUANTITY_MARKETING,
                    :QUANTITY_FORECAST,
                    :QUANTITY_ARRSES,
                    :SUGGESTION_1,
                    :SUGGESTION_2,
                    :SUGGESTION_3,
                    :CONSENSUS,
                    :CONSENSUS_LAST,
                    :CONSTRICTED_DEMAND,
                    :UNIT_VIEW_MODE,
                    :PRICE_BUDGET,
                    :PRICE_MARKETING,
                    :PRICE_FORECAST,
                    :PRICE_ARRSES,
                    :PRICE_SUGGESTION_1,
                    :PRICE_SUGGESTION_2,
                    :PRICE_SUGGESTION_3,
                    :PRICE_CONSENSUS,
                    :PRICE_CONSENSUS_LAST,
                    :PRICE_CONSTRICTED_DEMAND                
                )";
    
        $stmt = oci_parse($ora_conexao, $sql);
        if (!$stmt) {
            salvarLog($arquivoLogError, "Erro ao preparar SQL: " . oci_error($ora_conexao)['message']);
            return false;
        }
    
        $registrosInseridos = 0;
    
        foreach ($itens as $item) {
            // Bind das variáveis
            oci_bind_by_name($stmt, ":sop_id", $item['sop_id']);
            oci_bind_by_name($stmt, ":date_1", $item['date']);
            oci_bind_by_name($stmt, ":channel_level_1_name", $item['channel_level_1_name']);
            oci_bind_by_name($stmt, ":channel_level_1_description", $item['channel_level_1_description']);
            oci_bind_by_name($stmt, ":channel_level_2_name", $item['channel_level_2_name']);
            oci_bind_by_name($stmt, ":channel_level_2_description", $item['channel_level_2_description']);
            oci_bind_by_name($stmt, ":channel_level_3_name", $item['channel_level_3_name']);
            oci_bind_by_name($stmt, ":channel_level_3_description", $item['channel_level_3_description']);
            oci_bind_by_name($stmt, ":channel_level_4_name", $item['channel_level_4_name']);
            oci_bind_by_name($stmt, ":channel_level_4_description", $item['channel_level_4_description']);
            oci_bind_by_name($stmt, ":product_level_1_name", $item['product_level_1_name']);
            oci_bind_by_name($stmt, ":product_level_1_description", $item['product_level_1_description']);
            oci_bind_by_name($stmt, ":product_level_2_name", $item['product_level_2_name']);
            oci_bind_by_name($stmt, ":product_level_2_description", $item['product_level_2_description']);
            oci_bind_by_name($stmt, ":product_level_3_name", $item['product_level_3_name']);
            oci_bind_by_name($stmt, ":product_level_3_description", $item['product_level_3_description']);
            oci_bind_by_name($stmt, ":product_level_4_name", $item['product_level_4_name']);
            oci_bind_by_name($stmt, ":product_level_4_description", $item['product_level_4_description']);
            oci_bind_by_name($stmt, ":quantity_budget", $item['quantity_budget']);
            oci_bind_by_name($stmt, ":quantity_marketing", $item['quantity_marketing']);
            oci_bind_by_name($stmt, ":quantity_forecast", $item['quantity_forecast']);
            oci_bind_by_name($stmt, ":quantity_arrses", $item['quantity_arrses']);
            oci_bind_by_name($stmt, ":suggestion_1", $item['suggestion_1']);
            oci_bind_by_name($stmt, ":suggestion_2", $item['suggestion_2']);
            oci_bind_by_name($stmt, ":suggestion_3", $item['suggestion_3']);
            oci_bind_by_name($stmt, ":consensus", $item['consensus']);
            oci_bind_by_name($stmt, ":consensus_last", $item['consensus_last']);
            oci_bind_by_name($stmt, ":constricted_demand", $item['constricted_demand']);
            oci_bind_by_name($stmt, ":unit_view_mode", $item['unit_view_mode']);
            oci_bind_by_name($stmt, ":price_budget", $item['price_budget']);
            oci_bind_by_name($stmt, ":price_marketing", $item['price_marketing']);
            oci_bind_by_name($stmt, ":price_forecast", $item['price_forecast']);
            oci_bind_by_name($stmt, ":price_arrses", $item['price_arrses']);
            oci_bind_by_name($stmt, ":price_suggestion_1", $item['price_suggestion_1']);
            oci_bind_by_name($stmt, ":price_suggestion_2", $item['price_suggestion_2']);
            oci_bind_by_name($stmt, ":price_suggestion_3", $item['price_suggestion_3']);
            oci_bind_by_name($stmt, ":price_consensus", $item['price_consensus']);
            oci_bind_by_name($stmt, ":price_consensus_last", $item['price_consensus_last']);
            oci_bind_by_name($stmt, ":price_constricted_demand", $item['price_constricted_demand']);
    
            // Executa o SQL
            if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                $registrosInseridos++;
            } else {
                $error = oci_error($stmt);
                salvarLog($arquivoLogError, "Erro ao inserir item: " . $error['message'].' Teste com formatação: ' .$item['consensus_last']);
            }
        }
    
        // Commit após inserção de todos os itens
        if ($registrosInseridos > 0) {
            if (oci_commit($ora_conexao)) {
                salvarLog($arquivoLogSuccess, "$registrosInseridos registros inseridos com sucesso.");
            } else {
                salvarLog($arquivoLogError, "Erro ao fazer commit: " . oci_error($ora_conexao)['message']);
                oci_rollback($ora_conexao);
            }
        } else {
            oci_rollback($ora_conexao);
        }
    
        oci_free_statement($stmt);
        return true;
    }

    // Não esta sendo utilizado
    function salvarLogJSON($json, $page) {
        $logPath = '../../../log/';
        $fileName = "response_page_{$page}_" . date('Ymd_His') . ".json";
        $filePath = $logPath . $fileName;
        file_put_contents($filePath, $json);
    }
    
    function processarPagina($url, $token, $arquivoLogError, $arquivoLogSuccess) {
        // Inicializa cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($httpCode >= 200 && $httpCode < 300) {
            $response_data = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                salvarLog($arquivoLogSuccess, "Nova requisição GET bem-sucedida. \nURL:".$url);
    
                // Salvar o JSON da resposta em um arquivo
                $page = parse_url($url, PHP_URL_QUERY);
                parse_str($page, $queryParams);
                $pageNumber = $queryParams['page'] ?? '1'; 
                //salvarLogJSON($response, $pageNumber); // Material para debuger
    
                $itens = $response_data['_embedded']['items'] ?? [];
                $nextPageUrl = $response_data['_links']['next']['href'] ?? null;
    
                // Inserir os itens na tabela
                if (!empty($itens)) {
                    inserirDadosNoBanco($itens, $arquivoLogSuccess, $arquivoLogError);
                } else {
                    salvarLog($arquivoLogError, "Nenhum item encontrado na página $pageNumber.");
                }
    
                // Retorna a próxima página se existir
                return $nextPageUrl;
            } else {
                salvarLog($arquivoLogError, "Erro ao decodificar JSON: " . json_last_error_msg());
                die(json_encode(["type" => "danger", "message" => "Erro ao decodificar JSON: " . json_last_error_msg()]));
            }
        } else {
            salvarLog($arquivoLogError, "Erro na requisição: HTTP $httpCode. Resposta: $response");
            die(json_encode(["type" => "danger", "message" => "Erro na requisição: HTTP $httpCode"]));
        }
    }

    // Lógica principal para processar todas as páginas
    do {
        $api_url = processarPagina($api_url, $token, $arquivoLogError, $arquivoLogSuccess);
    } while ($api_url);

    die(json_encode(["type" => "success", "message" => "Integração completa."]));
