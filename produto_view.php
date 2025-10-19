<?php
// Arquivo: produto_view.php
session_start();

require_once 'backend/core/auth_functions.php';
require_once 'backend/class/Produto.class.php';
// 1. AUTORIZAÇÃO: Permite acesso se for ADM OU Supervisor!
// 🚨 O problema pode estar aqui: Confirme que is_supervisor() está funcionando
if (!is_supervisor()) {
    header('Location: index.php?error=acesso_negado_produto');
    exit();
}

// 2. Cria a instância da classe CRUD
$crud = new ProdutoCRUD();
$produtos = $crud->listarTodosComRelacoes(); // Chamada ao DB

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualização de Produtos</title>
</head>
<body>
    <h1>Visualização de Produtos (Supervisor)</h1>
    <p>Você está logado como: <?php echo htmlspecialchars($_SESSION['funcao']); ?></p>

    <?php if (empty($produtos)): ?>
        <p>Nenhum produto cadastrado no momento.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Cód. Prod</th>
                <th>Nome Prod.</th>
                <th>Estoque</th>
                <th>Fornecedor</th>
                <th>Preço Compra</th>
                <th>Validade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
            <tr>
                    <td><?php echo $produto['cod_prod']; ?></td>
                <td><?php echo htmlspecialchars($produto['nome_prod']); ?></td>
                <td><?php echo $produto['quant_estoque'] ?? 0; ?></td>
                <td><?php echo htmlspecialchars($produto['nome_forn'] ?? 'N/A'); ?></td>
                <td>R$ <?php echo number_format($produto['preco'] ?? 0, 2, ',', '.'); ?></td>
                <td><?php echo $produto['valid'] ?? 'N/A'; ?></td>
            </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <p><a href="index.php">Voltar à Tela Principal</a></p>
</body>
</html>