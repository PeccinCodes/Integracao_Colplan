<?php    
    
    include('../../../db/connection.php'); // Conexão Oracle
    
    $query = oci_parse($ora_conexao,"SELECT DISTINCT SOP_ID AS ID FROM PCN_INTEGRA_COLPLAN_SOP");

    //Executa a query
    oci_execute($query);

    // Prepara para armazenar os resultados
    $resultados = array();

    // Itera sobre os resultados e os armazena em um array
    while (($row = oci_fetch_assoc($query)) != false) {
        $resultados[] = $row;
    }
        
    return print_r(json_encode($resultados));
