document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.validate-form');
    const errorElement = document.getElementById('error-message');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(loginForm);

        fetch('../php/login/efetuaLogin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                errorElement.textContent = data.error;
                errorElement.classList.remove('d-none'); // Mostrar a div de erro
            } else {
                errorElement.classList.add('d-none'); // Ocultar a div de erro
                // Redirecionar para a página de destino em caso de sucesso
                window.location.href = '../pages/log.php';
            }
        })
        .catch(error => {
            errorElement.textContent = 'Error!';
            errorElement.classList.remove('d-none'); // Mostrar a div de erro
        });
    });
});
function mostrarPass(){
    var inputPass   = document.getElementById('password');
    var btnShowPass = document.getElementById('btn-pass');

    if(inputPass.type === 'password'){
        inputPass.setAttribute('type', 'text')
        btnShowPass.classList.replace('bi-eye-fill','bi-eye-slash-fill')
    }else{
        inputPass.setAttribute('type', 'password')
        btnShowPass.classList.replace('bi-eye-slash-fill','bi-eye-fill')
    }
}; 
