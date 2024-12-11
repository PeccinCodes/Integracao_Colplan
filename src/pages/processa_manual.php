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
        <title>Integração Colplan S&OP</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel="shortcut icon" href="../img/iconweb.ico" type="image/x-icon"/>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/bootstrap/bootstrap.min.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/default.css'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
        <script src='../js/bootstrap/bootstrap.min.js'></script>
    </head>
    <body>
        <div class="container py-5 mt-4 mb-4 container-min">
            <div class="row justify-content-center align-items-center " style="height: auto;">
                <div class="col-md-12">
                    <div class="card p-4 shadow-form">
                        <main class="form-signin mt-2">
                            <div class="card-body p-4">
                            	<h2 class="mb-2 text-center">IMPORTAÇÃO VIA API</h2>
								<form id="form_cadastro" class="validate-form" action="">
                                    <div class="col-md-12 mt-3">
                                        <div class="row justify-content-center align-items-center mb-2 mt-2" style="height: auto;">
                                            <div class="col-md-11 mt-4">
                                                <div class="form-floating">
                                                    <select class="form-control" name="nome_ciclo" id="nome_ciclo" placeholder="" data-tg-group="" required>
                                                    </select>
                                                    <label for="floatingTextarea">CICLO S&OP</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="id_ciclo" placeholder="" id="id_ciclo" data-tg-group="" readonly></input>
                                                    <label for="floatingTextarea">ID do Ciclo</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="finish_date" placeholder="" id="finish_date" data-tg-group="" readonly></input>
                                                    <label for="floatingTextarea">Data Final do ciclo</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="scenario_channel_level" placeholder="" id="scenario_channel_level" data-tg-group="" readonly></input>
                                                    <label for="floatingTextarea">Nivel de Canal</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-4">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="scenario_product_level" placeholder="" id="scenario_product_level" data-tg-group="" readonly></input>
                                                    <label for="floatingTextarea">Nivel de Produto</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-4">
                                                <div class="form-floating">
                                                    <button type="button" id="reprocessarButton" class="w-100 btn btn-peccin mt-3">IMPORTAR</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Inicio Modal de Alerta -->
                                    <div class="modal fade" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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