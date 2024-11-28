<?php
// BASE TESTE
date_default_timezone_set('America/Recife');
$ora_user = "apps";
$ora_senha = "appstst";

$ora_bd = "(DESCRIPTION= 
(ADDRESS=(PROTOCOL=tcp)(HOST=192.168.203.102)(PORT=1523)) 
       (CONNECT_DATA= 
             (SERVICE_NAME=TST)
)
)";

$ora_conexao = oci_connect($ora_user,$ora_senha,$ora_bd, 'AL32UTF8'); // AL32UTF8 esse é o valor para definir o charset do ORACLE utilizando o UTF-8;);
$setup = oci_parse($ora_conexao,"alter session set nls_language = 'BRAZILIAN PORTUGUESE' NLS_TERRITORY = 'BRAZIL'");
oci_execute($setup);

$view = oci_parse($ora_conexao,
"begin 
      fnd_client_info.set_org_context('103');  
      dbms_application_info.set_client_info('103');  
      apps.mo_global.set_policy_context('S','103'); 
END;
");
oci_execute($view);
