<?php
session_start();
require_once __DIR__ . '/backend/core/conexao.php';
require_once __DIR__ . '/backend/core/auth_functions.php';
require_once __DIR__ . '/backend/class/estatisticas.class.php';


$nome_func = $_SESSION['nome'];
$funcao = $_SESSION['funcao'];
$stats = (new Estatisticas())->getVisaoGeral();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Dashboard - Academia</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="styles/styles.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="scripts/script.js"></script>

</head>
<body class="min-h-screen bg-cover bg-center relative flex items-center justify-center" style="background-image: url('assets/fundo.jpg');">
  <div class="absolute inset-0 bg-black/80 z-0"></div>

  <div id="container" class="relative z-10 max-w-5xl w-full mx-4 flex flex-col items-center gap-8 transform translate-y-12 opacity-0 transition-all duration-700">
    <h1 class="text-4xl font-bold text-gray-100 text-center">Bem-vindo(a), <?php echo htmlspecialchars($nome_func); ?>!</h1>
    <p class="text-gray-400 text-lg text-center">Função: <strong><?php echo htmlspecialchars($funcao); ?></strong></p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full max-w-3xl">
      <?php if (is_adm()): ?>
        <a href="aluno_crud.php?action=list" class="bg-indigo-600 hover:bg-indigo-700 hover:scale-[1.02] transform transition rounded-xl p-6 text-white font-semibold text-center shadow-lg flex flex-col items-center justify-center gap-2">
        <img src="assets/gym.svg" alt="Gerenciar Alunos" class="h-10 w-10 text-white filter brightness-0 invert">
        Gerenciar Alunos
        </a>

        <a href="produto_crud.php?action=list" class="bg-emerald-600 hover:bg-emerald-700 hover:scale-[1.02] transform transition rounded-xl p-6 text-white font-semibold text-center shadow-lg flex flex-col items-center justify-center gap-2">
        <img src="assets/protein-supplement.svg" alt="Gerenciar Produtos" class="h-10 w-10 text-white filter brightness-0 invert">
        Gerenciar Produtos
        </a>

      <?php endif; ?>

      <?php if (is_supervisor() && !is_adm()): ?>
        <a href="aluno_view.php" class="bg-indigo-600 hover:bg-indigo-700 hover:scale-[1.02] transform transition rounded-xl p-6 text-white font-semibold text-center shadow-lg flex flex-col items-center justify-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          Visualizar Alunos
        </a>
        <a href="produto_view.php" class="bg-emerald-600 hover:bg-emerald-700 hover:scale-[1.02] transform transition rounded-xl p-6 text-white font-semibold text-center shadow-lg flex flex-col items-center justify-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />
          </svg>
          Visualizar Produtos
        </a>
      <?php endif; ?>
    </div>

    <div 
      class="p-8 rounded-3xl w-full max-w-4xl shadow-2xl shadow-black/60 frosted-glass" 
    >
      <h2 class="text-gray-100 text-3xl font-bold text-center mb-8 tracking-wide">
        Visão Geral
      </h2>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">

        
        <div class="flex flex-col items-center p-4 rounded-xl transition hover:bg-white/5 bg-gray-900/40 border border-gray-700/50">
          <img src="assets/gym.svg" alt="Alunos" class="h-8 w-8 mb-2 text-indigo-400 filter brightness-0 invert">
          <p class="text-2xl font-bold text-indigo-400"><?php echo $stats['quant_alunos']; ?></p>
          <p class="text-gray-400 text-sm mt-1 text-center font-medium">Total de Alunos</p>
        </div>

        <div class="flex flex-col items-center p-4 rounded-xl transition hover:bg-white/5 bg-gray-900/40 border border-gray-700/50">
          <img src="assets/protein-supplement.svg" alt="Produtos" class="h-8 w-8 mb-2 text-emerald-400 filter brightness-0 invert">
          <p class="text-2xl font-bold text-emerald-400"><?php echo $stats['quant_produtos_estoque']; ?></p>
          <p class="text-gray-400 text-sm mt-1 text-center font-medium">Produtos em Estoque</p>
        </div>

        <div class="flex flex-col items-center p-4 rounded-xl transition hover:bg-white/5 bg-gray-900/40 border border-gray-700/50">
          <img src="assets/employee.svg" alt="Funcionários" class="h-8 w-8 mb-2 text-purple-400 filter brightness-0 invert">
          <p class="text-2xl font-bold text-purple-400"><?php echo $stats['quant_func']; ?></p>
          <p class="text-gray-400 text-sm mt-1 text-center font-medium">Total de Funcionários</p>
        </div>

        <?php if (!empty($stats['plano_mais_caro'])): ?>
        <div class="flex flex-col items-center p-4 rounded-xl transition hover:bg-white/5 bg-gray-900/40 border border-gray-700/50">
          <img src="assets/money.svg" alt="Plano caro" class="h-8 w-8 mb-2 text-amber-400 filter brightness-0 invert">
          <p class="text-xl font-bold text-amber-400 text-center leading-tight"><?php echo htmlspecialchars($stats['plano_mais_caro']['plano_pag']); ?></p>
          <p class="text-gray-400 text-sm mt-1 text-center font-medium">R$ <?php echo number_format($stats['plano_mais_caro']['valor'], 2, ',', '.'); ?></p>
        </div>
        <?php endif; ?>

      </div>

    </div>

    <button onclick="window.location.href='logout.php'" class="mt-4 w-40 py-2 bg-red-700 hover:bg-red-800 rounded-xl font-bold text-sm text-white transition shadow-lg">Sair</button>

  </div>



</body>
</html>