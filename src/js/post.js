$(document).ready(function() {

    /*
    // Função para carregar os ciclos no select
    function carregarCiclos() {
        $.ajax({
            type: "GET",
            url: '../php/controller/get/sop_disaggregation.php',
            dataType: 'json',
            success: function(data) {
                const ciclos = data['_embedded']['items'];
                var selectCiclo = $('#nome_ciclo');
                // Limpa as opções atuais
                selectCiclo.empty(); 
                // Adiciona uma opção padrão
                selectCiclo.append('<option value="" disabled selected>Selecione o ciclo</option>');
                // Itera sobre os dados e adiciona as opções no select
                ciclos.forEach(function(ciclo) {
                    if (
                        ciclo.id &&
                        ciclo.finish_date &&
                        ciclo.scenario_channel_level &&
                        ciclo.scenario_product_level &&
                        ciclo.name
                    ) {
                        selectCiclo.append(`<option 
                            value="${ciclo.id}" 
                            data-finish-date="${ciclo.finish_date}" 
                            data-channel-level="${ciclo.scenario_channel_level}" 
                            data-product-level="${ciclo.scenario_product_level}">
                            ${ciclo.name}
                        </option>`);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Erro ao carregar os setores: " + error);
            }
        });
    }
        
    // Exibe os ciclos
    carregarCiclos();

    // Preencher os campos readonly ao selecionar um ciclo
    $('#nome_ciclo').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const idCiclo = selectedOption.val();
        // Formata a data
        const finishDate = selectedOption.data('finish-date') ? selectedOption.data('finish-date').split(' ')[0] : ''; 
        const channelLevel = selectedOption.data('channel-level');
        const productLevel = selectedOption.data('product-level');

        $('#id_ciclo').val(idCiclo);
        $('#finish_date').val(finishDate);
        $('#scenario_channel_level').val(channelLevel);
        $('#scenario_product_level').val(productLevel);
    });*/

    // Função para carregar os ciclos no select
    function carregarCiclos() {
        // Consulta para obter os ciclos
        $.ajax({
            type: "GET",
            url: '../php/controller/get/sop_disaggregation.php',
            dataType: 'json',
            success: function(data) {
                const ciclos = data['_embedded']['items'];

                // Consulta para obter os IDs a serem excluídos
                $.ajax({
                    type: "GET",
                    url: '../php/controller/get/id_sop_disaggregation.php',
                    dataType: 'json',
                    success: function(idsExcluidos) {
                        const idsInvalidos = idsExcluidos.map(item => item.ID);

                        var selectCiclo = $('#nome_ciclo');
                        // Limpa as opções atuais
                        selectCiclo.empty();
                        // Adiciona uma opção padrão
                        selectCiclo.append('<option value="" disabled selected>Selecione o ciclo</option>');

                        // Filtra os ciclos válidos
                        const ciclosValidos = ciclos.filter(ciclo => {
                            return (
                                ciclo.id && // ID não pode ser vazio
                                !idsInvalidos.includes(ciclo.id.toString()) && // ID não deve estar na lista de excluídos
                                ciclo.finish_date && // finish_date não pode ser vazio
                                ciclo.scenario_channel_level && // scenario_channel_level não pode ser vazio
                                ciclo.scenario_product_level && // scenario_product_level não pode ser vazio
                                ciclo.name // name não pode ser vazio
                            );
                        });

                        // Adiciona os ciclos válidos ao select
                        ciclosValidos.forEach(ciclo => {
                            selectCiclo.append(`<option 
                                value="${ciclo.id}" 
                                data-finish-date="${ciclo.finish_date}" 
                                data-channel-level="${ciclo.scenario_channel_level}" 
                                data-product-level="${ciclo.scenario_product_level}">
                                ${ciclo.id} - ${ciclo.name}
                            </option>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao carregar os IDs excluídos: " + error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Erro ao carregar os ciclos: " + error);
            }
        });
    }

    // Exibe os ciclos
    carregarCiclos();

    // Preencher os campos readonly ao selecionar um ciclo
    $('#nome_ciclo').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const idCiclo = selectedOption.val();
        // Formata a data
        const finishDate = selectedOption.data('finish-date') ? selectedOption.data('finish-date').split(' ')[0] : '';
        const channelLevel = selectedOption.data('channel-level');
        const productLevel = selectedOption.data('product-level');

        $('#id_ciclo').val(idCiclo);
        $('#finish_date').val(finishDate);
        $('#scenario_channel_level').val(channelLevel);
        $('#scenario_product_level').val(productLevel);
    });

    $('#reprocessarButton').on('click', function() {
        showAlertLoading();
        const idCiclo = $('#id_ciclo').val();
        const finishDate = $('#finish_date').val();
        const channelLevel = $('#scenario_channel_level').val();
        const productLevel = $('#scenario_product_level').val();

        $.ajax({
            url: '../php/controller/get/insert_sop_disaggregation.php',
            method: 'GET',
            dataType: 'json',
            data: {
                id_ciclo: idCiclo,
                finish_date: finishDate,
                scenario_channel_level: channelLevel,
                scenario_product_level: productLevel,
            },
            success: function(response) {
                console.log(response);
                showAlertModal(response.type, response.message);
            },
            error: function(xhr, status, error) {
                showAlertModal('danger', xhr.responseJSON ? xhr.responseJSON.message : 'Erro desconhecido.');
                console.error('Erro na chamada AJAX:', error);
            }
        });
    });

    // Montar o modal para apresentar o alerta
    function showAlertModal(type, message) {
        const alertModalBody = document.getElementById('alertModal');
        alertModalBody.innerHTML = `
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content mt-4">
                <div class="d-flex justify-content-center mt-4" style="font-size:5em; ">
                    <i class="bi bi-exclamation-triangle-fill" ></i>
                </div>
                <div class="modal-body" id="alertModalBody">
                    <div class="alert alert-${type} text-center" role="alert">
                        ${message}
                    </div>
                    <p class="text-center">Para fechar essa janela clique a tecla <b>ESC</b>.</p>
                </div>
            </div>
        </div>
        `;
        // Adiciona um listener de evento para a tecla "ESC"
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                // Atualiza a página quando a tecla "ESC" é pressionada
                window.location.reload();
            }
        });
        $('#alertModal').modal('show');
    }

    // Montar o modal para apresentar o alerta de carregamento
    function showAlertLoading() {
        const alertModalBody = document.getElementById('alertModal');   
        alertModalBody.innerHTML = `
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content mt-4">
                <div class="d-flex justify-content-center mt-4">
                    <div class="spinner-border text-info" style="width: 5rem; height: 5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="modal-body" id="alertModalBody">
                    <div class="alert alert-info text-center" role="alert">
                        Aguarde, em processamento!
                    </div>
                </div>
            </div>
        </div>
        `;
        $('#alertModal').modal('show');
    }

});
