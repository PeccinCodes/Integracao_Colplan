<?php
	header('Content-Type: text/html; charset=UTF-8');
	include('../php/login/validaLogin.php');
	require_once("../../config.php");
    require(ROOT_PATH . "/src/partials/header.php");

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
		<meta charset='UTF-8'>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Baixar Fatura</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel="shortcut icon" href="../img/iconweb.ico" type="image/x-icon"/>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/bootstrap/bootstrap.min.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/default.css'>
        <script src='../js/bootstrap/bootstrap.min.js'></script>
    </head>
    <body>
        <div class="container py-5 mt-4 mb-4 container-min">
            <div class="row justify-content-center align-items-center " style="height: auto;">
                <div class="col-md-12">
                    <div class="card p-4 shadow-form">
                        <main class="form-signin mt-2">
                            <div class="card-body p-4">
                            	<h2 class="mb-2 text-center">POST via API Manual</h2>
								<form id="form_cadastro" class="validate-form" action="">
                                    <div class="row justify-content-center align-items-center">
                                        <div class="col-md-3">
                                            <button type="button" id="reprocessarButton" class="w-100 btn btn-peccin mt-3" onclick="getReprocessar()">REPROCESSAR</button>
                                        </div>
                                    </div>
                                    <!-- Inicio Modal de Alerta -->
                                    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
                                        <!-- JS Adiciona a estrutura do modal com a mensagem -->
                                    </div>
                                    <!-- Fim Modal de Alerta -->
                                </form>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
	<script src="../js/jquery/jquery-3.7.1.min.js"></script>
	<script src="../js/post.js"></script>
	<?php require("../partials/footer.php");?>
    </body>
</html>