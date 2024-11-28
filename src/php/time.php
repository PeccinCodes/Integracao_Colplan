<?php

	$dataAtual = new DateTime();
	$dataAjust = $dataAtual->format('d/m/y');
	
	var_dump($dataAjust);