<?php
    header('Content-Type: text/html; charset=UTF-8');
    $data = date("Y");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv="Content-Type" content="text/html; charset='iso-8859-1" />
        <title>Integração Colplan</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
		<link rel="shortcut icon" href="../img/iconweb.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
        <link rel='stylesheet' type='text/css' media='screen' href='../css/bootstrap/bootstrap.min.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/default.css'>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src='../js/bootstrap/bootstrap.min.js'></script>
        <script src='../js/login.js'></script>
    </head>
    <body onload="verificarNavegador()">  
        <div class="container ">
            <div class="row justify-content-center align-items-center " style="height: 100vh;">
                <div class="col-md-4">
                    <div class="card p-4 shadow-login">
                        <main class="form-signin mt-2">
                            <form action="../php/login/efetuaLogin.php" method="post" class="validate-form">
                                <div class="text-center mb-4" >
                                    <img class="" src="../img/logo.png" alt="" width="164" height="62">
                                </div>
                                <h5 class="text-center mt-2 mb-4"><b>INTEGRAÇÃO COLPLAN</b></h5>
                                <div class="form-group">
                                    <label for="user">Usuário:</label>
                                    <input type="user" class="form-control-peccin mt-2 mb-2" name="username" id="username" placeholder="Insira seu usuário" required>
                                </div>
                                <div class="form-group div-pass">
                                    <label for="password">Senha:</label>
                                    <input type="password" class="form-control-peccin mt-2 mb-2 " name="password" id="password" placeholder="Insira sua senha" required>
                                    <i class="bi bi-eye-fill input-login-pass" id="btn-pass" onclick="mostrarPass()"></i>
                                </div>
                                <button class="w-100 btn btn-lg btn-peccin mt-3" type="submit" >Acessar</button>
                            </form>
                            <div class="error-message mt-4 mb-4 text-center alert alert-danger d-none" id="error-message"></div>
                            <p class="mt-4 mb-2 text-muted text-center">&copy; <?php echo $data; ?> Peccin S.A.<br>
                                Dúvidas?<a href="mailto:ti@peccin.com.br" class="text-decoration-none text-muted"> ti@peccin.com.br</a>
                            </p>
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>