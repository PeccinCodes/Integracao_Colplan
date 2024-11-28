<?php 
// Caminho do arquivo a ser verificado
$successLog = '../../log/successLog.txt';
$errorLog   = '../../log/successLog.txt';

function excluirArquivoAntigo($successLog, $errorLog)  {
    // Verifique se o arquivo existe
    if (file_exists($successLog) && file_exists($errorLog)) {
        // Obtenha a data de modificação do arquivo
        $dataModificacao = filemtime($successLog);
        $dataModificacao .= filemtime($errorLog);

        // Obtenha a data atual
        $dataAtual = time();
        
        // Calcule a diferença em segundos entre a data atual e a data de modificação do arquivo
        $diferencaEmSegundos = $dataAtual - $dataModificacao;

        // Calcule a diferença em dias
        $diferencaEmDias = $diferencaEmSegundos / (60 * 60 * 24);

        // Se o arquivo tiver mais de 7 dias, exclua-o
        if ($diferencaEmDias > 7) {
            unlink($successLog, $errorLog);
            echo "O arquivo $successLog, $errorLog  foi excluído.";
        } else {
            echo "O arquivo $successLog, $errorLog  não será excluído, pois não tem mais de 7 dias.";
        }
    } else {
        echo "O arquivo $successLog, $errorLog  não existe.";
    }
}

// Chame a função para executar a verificação e exclusão
excluirArquivoAntigo($successLog, $errorLog);
