<?php
session_start();
error_reporting(0);
include("../../db/usuariosAutorizados.php");
// Configurações do servidor LDAP
$ldapServer = '192.168.203.5';
$ldapPort = 389;
$ldapBaseDN = 'OU=USUARIOS SETORES,DC=peccin,DC=local'; // Substitua pelo seu base DN
$ldapBaseDNAva = 'OU=TI,OU=USUARIOS AVANCADOS,DC=peccin,DC=local'; // Substitua pelo seu base DN
$ldapAdminDN = 'peccin\system.admin'; // Substitua pelo seu usuário administrador LDAP
$ldapAdminPassword = 'Arm@d1lh@#1956'; // Substitua pela senha do usuário administrador LDAP

// Dados do formulário de login
$username = $_POST['username']; // Nome de usuário fornecido pelo usuário
$password = $_POST['password']; // Senha fornecida pelo usuário

// Conectando ao servidor LDAP
$ldapConn = ldap_connect($ldapServer, $ldapPort);

if (!$ldapConn) {
    die('Não foi possível se conectar ao servidor LDAP.');
}

// Bind com o usuário administrador LDAP
$ldapBind = ldap_bind($ldapConn, $ldapAdminDN, $ldapAdminPassword);

if (!$ldapBind) {
    die('Não foi possível autenticar o administrador LDAP.');
}

// Pesquisar o usuário no LDAP Usuários Setores
$searchFilter = "(samaccountname=$username)";
$searchResult = ldap_search($ldapConn, $ldapBaseDN, $searchFilter);
$entries = ldap_get_entries($ldapConn, $searchResult);

// Pesquisar o usuário no LDAP Usuários Avançados
$searchResultAva = ldap_search($ldapConn, $ldapBaseDNAva, $searchFilter);
$entriesAva = ldap_get_entries($ldapConn, $searchResultAva);

if ($entries['count'] == 1) {
    // Encontrou o usuário no LDAP, agora tente autenticá-lo
    $userDN = $entries[0]['dn'];
    
    // Tente autenticar o usuário com a senha fornecida
    if (ldap_bind($ldapConn, $userDN, $password)) {
        // Autenticação bem-sucedida
        $samaccountname = $entries[0]['samaccountname'][0];
        
        if (in_array($samaccountname, $usuariosAutorizados)) {
            // Usuário está autorizado, permitir o acesso
            $_SESSION['usuarioAd'] = $samaccountname;
            $response = array('sucess' => 'Usuário autorizado');
            echo json_encode($response);
            //header('Location: ../pages/cadastro.php');
        } else {
            // Usuário não está autorizado, negar o acesso
            $response = array('error' => 'O usuário não tem permissão para acessar o sistema.');
            echo json_encode($response);
        }
    } else {
        // Senha incorreta
        $response = array('error' => 'Usuário ou senha incorretos!');
        echo json_encode($response);
        
    }
} elseif ($entriesAva['count'] == 1) {
        // Encontrou o usuário no LDAP, agora tente autenticá-lo
        $userDN = $entriesAva[0]['dn'];
    
        // Tente autenticar o usuário com a senha fornecida
        if (ldap_bind($ldapConn, $userDN, $password)) {
            // Autenticação bem-sucedida
            $samaccountname = $entriesAva[0]['samaccountname'][0];
            
            if (in_array($samaccountname, $usuariosAutorizados)) {
                // Usuário está autorizado, permitir o acesso
                $_SESSION['usuarioAd'] = $samaccountname;
                $response = array('sucess' => 'Usuário autorizado');
                echo json_encode($response);
                //header('Location: ../pages/cadastro.php');
            } else {
                // Usuário não está autorizado, negar o acesso
                $response = array('error' => 'O usuário não tem permissão para acessar o sistema.');
                echo json_encode($response);
            }
        } else {
        // Senha incorreta
        $response = array('error' => 'Usuário ou senha incorretos!');
        echo json_encode($response);
        
    }
}  else {
        // Usuário não encontrado no LDAP
        $response = array('error' => 'Usuário não cadastrado no sistema!');
        echo json_encode($response);
}

// Fechar a conexão LDAP
ldap_close($ldapConn);
?>