<?php 

// Configurações do banco de dados

$servidor = "localhost"; //geramente localhost 127.256.001.1 - caso não fosse local
$usuario = "root"; // root padrão do usuário MySQL
$senha = "";  //Por padrão local, é sem senha
$banco = "projeto_clinica"; // nome de dados que será usado

// 1. Estabelece a conexão
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

//2. Verifica se a conexão falhou
if ($conexao->connect_error) {
    die("Falha de conexão: " . $conexao->connect_error);
} else {
    //echo "Deu certo!";
}

//3. definir o charset de caracteres especiais
$conexao->set_charset("utf8");

?>