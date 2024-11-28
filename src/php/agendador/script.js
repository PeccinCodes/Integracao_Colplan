
const puppeteer = require('puppeteer');

// Função para obter o horário atual formatado
let horario = () => {
    let data = new Date();
    let horas = (data.getHours() < 10 ? '0' : '') + data.getHours();
    let minutos = (data.getMinutes() < 10 ? '0' : '') + data.getMinutes();
    let segundos = (data.getSeconds() < 10 ? '0' : '') + data.getSeconds();
    return [horas, minutos, segundos].join(':');
}
console.log(`\nSCRIPT DE AUTOMACAO DO ZENDESK INICIADO AS ${horario()}\n\nCARGA GERAL INICIADA! AGUARDE...\n`);

async function cargaGeral() {

    ///////////////////////////////////////////////////////////////////////////////

    // MENSAGEM DE INICIO DAS CARGAS //
    console.log(`# IMPORTAÇÃO INICIADO: ${horario()} \n`);

    ///////////////////////////////////////////////////////////////////////////////

    //INICIO DO GET
    console.log(`# POSTs INICIADO\n`);

    // ITENS
    const navegadorProdutos = await puppeteer.launch({
    headless: 'new', // Ativa o novo modo headless
});
    const produtos = await navegadorItens.newPage();
    console.log(`INICIO DA IMPORTAÇÃO DE PRODUTOS: ${horario()}`);
    await produtos.goto('http://pcn-sig.peccin.local/integracao_colplan/src/php/controller/post/produtos.php', { waitUntil: 'load', timeout: 0 });
    console.log(`FIM DA DA IMPORTAÇÃO DE PRODUTOS ${horario()} \n`);
    await navegadorProdutos.close();

    // MARCA
    const navegadorCliente = await puppeteer.launch({
    headless: 'new', // Ativa o novo modo headless
});                                                                                    // ABRE UM NOVO NAVEGADOR
    const cliente = await navegadorMarca.newPage();                                                                                                 // ABRE UMA GUIA DO NAVEGADOR
    console.log(`INICIO DA IMPORTAÇÃO DE CLIENTES: ${horario()}`);                                                                                  // INFORMA O INICIO DA IMPORTAÇÃO DOS DADOS
    await cliente.goto('http://pcn-sig.peccin.local/integracao_colplan/src/php/controller/post/clientes.php', { waitUntil: 'load', timeout: 0 });   // PASSA O LINK DA API NA GUIA
    console.log(`FIM DA IMPORTAÇÃO DE CLIENTES: ${horario()} \n`);                                                                                  // INFORMA SE HOUVE IMPORTAÇÃO DOS DADOS
    await navegadorCliente.close();                                                                                                                 // FECHA O NAVEGADOR
    
    // NEGOCIO
    const navegadorFaturamento = await puppeteer.launch({
    headless: 'new', // Ativa o novo modo headless
});
    const paginaFaturamento = await navegadorNegocio.newPage();
    console.log(`INICIO DA IMPORTAÇÃO DE FATURAMENTO: ${horario()}`);
    await paginaFaturamento.goto('http://pcn-sig.peccin.local/integracao_colplan/src/php/controller/post/faturamento.php', { waitUntil: 'load', timeout: 0 });
    console.log(`FIM DA DA IMPORTAÇÃO DE FATURAMENTO ${horario()} \n`);
    await navegadorFaturamento.close();
    
    // SEGMENTO - FAMILIA
    const navegadorCD = await puppeteer.launch({
    headless: 'new', // Ativa o novo modo headless
});
    const paginaCD = await navegadorFamilia.newPage();
    console.log(`INICIO DA IMPORTAÇÃO DE RESULTADOS ATRIBUTO CD: ${horario()}`);
    await paginaCD.goto('http://pcn-sig.peccin.local/integracao_colplan/src/php/controller/post/atributo_cd.php', { waitUntil: 'load', timeout: 0 });
    console.log(`FIM DA DA IMPORTAÇÃO DE RESULTADOS ATRIBUTO CD ${horario()} \n`);
    await navegadorCD.close();
	
	// SHELF LIFE - ITENS
    const navegadorDis = await puppeteer.launch({
    headless: 'new', // Ativa o novo modo headless
});
    const paginaDis= await navegadorLife.newPage();
    console.log(`INICIO DA IMPORTAÇÃO DE ATRIBUTO DIST: ${horario()}`);
    await paginaDis.goto('http://pcn-sig.peccin.local/integracao_colplan/src/php/controller/post/atributo_dist.php', { waitUntil: 'load', timeout: 0 });
    console.log(`FIM DA DA IMPORTAÇÃO DE ATRIBUTO DIST ${horario()} \n`);
    await navegadorDis.close();

    // SHELF LIFE - ITENS
    const navegadorRep = await puppeteer.launch({
    headless: 'new', // Ativa o novo modo headless
});
    const paginaRep = await navegadorRep.newPage();
    console.log(`INICIO DA IMPORTAÇÃO DE ATRIBUTO DIST: ${horario()}`);
    await paginaRep.goto('http://pcn-sig.peccin.local/integracao_colplan/src/php/controller/post/atributo_rep.php', { waitUntil: 'load', timeout: 0 });
    console.log(`FIM DA DA IMPORTAÇÃO DE ATRIBUTO DIST ${horario()} \n`);
    await navegadorRep.close();

    ///////////////////////////////////////////////////////////////////////////////

    // MENSAGEM DE FINALIZACAO DAS CARGAS
    console.log(`# CARGA FINALIZADA AS: ${horario()}\n\n////////////////////////////////////////////////\n\nPROXIMA CARGA EM ${tempoCarga / 60000} MINUTOS! AGUARDE...\n\n////////////////////////////////////////////////\n`);

    ///////////////////////////////////////////////////////////////////////////////

    // Após finalizar, agendar próxima execução
    agendarProximaExecucao();

};

// Função para calcular o tempo até o próximo dia 1º
function calcularTempoAteProximoDia1() {
    let agora = new Date();
    let ano = agora.getFullYear();
    let mes = agora.getMonth();

    // Próximo mês (se for dezembro, vira janeiro do próximo ano)
    let proximoMes = mes + 1;
    let proximoAno = ano;
    if (proximoMes > 11) {
        proximoMes = 0;
        proximoAno += 1;
    }

    // Data do próximo dia 1º à meia-noite
    let proximoDia1 = new Date(proximoAno, proximoMes, 1, 0, 0, 0);

    // Tempo em milissegundos até o próximo dia 1º
    return proximoDia1 - agora;
}

// Função para agendar a próxima execução
function agendarProximaExecucao() {
    let tempoAteProximoDia1 = calcularTempoAteProximoDia1();

    console.log(`Próxima execução programada para o dia 1º do próximo mês em aproximadamente ${Math.round(tempoAteProximoDia1 / 1000 / 60 / 60)} horas (${new Date(Date.now() + tempoAteProximoDia1)}).\n`);

    setTimeout(() => {
        console.log(`DIA 1º DETECTADO: ${horario()} - INICIANDO CARGA...`);
        cargaGeral();
    }, tempoAteProximoDia1);
}

// Inicia a primeira carga imediatamente e agenda a próxima
cargaGeral();