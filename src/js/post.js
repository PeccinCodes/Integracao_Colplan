$(document).ready(function() {
    $('#reprocessarButton').on('click', function() {
        showAlertLoading();
        $.ajax({
            url: '../php/controller/post/baixarFatura/postIntegra.php', // Faz requisição para atualizar os dados
            method: 'GET', // ou POST, dependendo do seu endpoint
            dataType: 'json', // ajuste conforme o retorno esperado
            success: function(response) {
                // Apresenta alerta para o usuário
                showAlertModal('success', 'Sucesso ao reprocessar!');
            },
            error: function(xhr, status, error) {
                // Apresenta alerta para o usuário
                showAlertModal('danger', xhr.responseJSON ? xhr.responseJSON.message : 'Erro desconhecido.');
                // Lida com erros de chamada AJAX aqui
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
        // Abre o modal
        $('#alertModal').modal('show');
    }

    // Montar o modal para apresentar o alerta
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
        // Abre o modal
        $('#alertModal').modal('show');
    }
});
