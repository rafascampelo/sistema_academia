<?php
// Arquivo: index.php
session_start();
require_once 'backend/core/conexao.php';
require_once 'backend/core/auth_functions.php'; 
require_once 'backend/class/Estatisticas.class.php';
// Protege a página: se não estiver logado, redireciona para o login.
proteger_pagina(); 

// Dados do usuário logado
$nome_func = $_SESSION['nome'];
$funcao = $_SESSION['funcao'];

$stats = (new Estatisticas())->getVisaoGeral();
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
    
    <p>Total de Alunos: <strong><?php echo $stats['quant_alunos']; ?></strong></p>
    <p>Total de Produtos em Estoque: <strong><?php echo $stats['quant_produtos_estoque']; ?></strong></p>
    <p>Total de Funcionários: <strong><?php echo $stats['quant_func']; ?></strong></p>
    
    <?php if ($stats['plano_mais_caro']): ?>
        <p>Plano Mais Caro: <strong><?php echo htmlspecialchars($stats['plano_mais_caro']['plano_pag']); ?></strong> (R$ <?php echo number_format($stats['plano_mais_caro']['valor'], 2, ',', '.'); ?>)</p>
    <?php endif; ?>
    </div>
</body>
</html>