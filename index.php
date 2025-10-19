<?php
// Arquivo: index.php
session_start();
require_once 'auth_functions.php';

// Protege a página: se não estiver logado, redireciona para o login.
proteger_pagina(); 

// Dados do usuário logado
$nome_func = $_SESSION['nome'];
$funcao = $_SESSION['funcao'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tela Principal - Dashboard</title>
</head>
<body>
    <h1>Bem-vindo à Tela Principal, <?php echo $nome_func; ?>!</h1>
    <p>Sua Função: <?php echo $funcao; ?></p>
    
    <h2>Navegação Principal</h2>
    
    <ul>
        <?php if (is_adm()): ?>
            <li><a href="aluno_crud.php?action=list">Gerenciar Alunos (CRUD - ADM)</a></li>
            <li><a href="produto_crud.php?action=list">Gerenciar Produtos (CRUD - ADM)</a></li>
        <?php endif; ?>

        <?php if (is_supervisor() && !is_adm()): ?>
            <li><a href="aluno_view.php">Visualizar Alunos (Supervisor)</a></li>
            <li><a href="produto_view.php">Visualizar Produtos (Supervisor)</a></li>
        <?php endif; ?>

        <li><a href="logout.php">Sair (Logout)</a></li>
    </ul>

    <div id="visao_geral">
        <h3>Visão Geral da Academia</h3>
        <p>A ser implementado...</p>
    </div>
</body>
</html>