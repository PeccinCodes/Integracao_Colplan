<?php
	header('Content-Type: text/html; charset=UTF-8');
	include('../php/login/validaLogin.php');
	require_once("../../config.php");
    require_once(ROOT_PATH . "/src/partials/header.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
		<meta charset='UTF-8'>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Log Active</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
		<link rel="shortcut icon" href="../img/iconweb.ico" type="image/x-icon"/>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/bootstrap/bootstrap.min.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/default.css'>
        <script src='../js/bootstrap/bootstrap.min.js'></script>
        <script src='../js/default.js'></script>
    </head>
    <body>
        <div class="container py-5 mt-4 mb-4 container-min">
            <div class="row justify-content-center align-items-center " style="height: auto;">
                <div class="col-md-12">
                    <div class="card p-4 shadow-form">
                        <main class="form-signin mt-2">
                            <div class="accordion" id="accordionExample">
                                <div class="card-body p-4">
                                <h2 class="mb-4 text-center">Log de requisições da API</h2>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                           <b>SUCESS</b>
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <iframe src="../log/successLog.txt" frameborder="0" scrolling="yes" height="400" width="1175"></iframe> 
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                  <h2 class="accordion-header">
                                    <button class="accordion-button collapsed btn-peccin text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <b>ERROR</b>
                                    </button>
                                  </h2>
                                  <div id="collapseTwo" class="accordion-collapse collapse " data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <iframe src="../log/errorLog.txt" frameborder="0" scrolling="yes" height="400" width="1175"></iframe>  
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
	<script src="../js/jquery/jquery-3.7.1.min.js"></script>
	<script src="../js/script.js"></script>
	<?php require("../partials/footer.php");?>
    </body>
</html>