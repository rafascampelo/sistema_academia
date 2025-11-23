<?php
// Arquivo: produto_view.php
session_start();

require_once '../backend/core/auth_functions.php';
require_once '../backend/class/Produto.class.php';
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
    <link rel="stylesheet" href="../styles/produto_list.view.css">
    <script src="../scripts/scriptproduto_list.view.js" defer></script>
</head>
<body>
    <div class="container" id="container" >
    <h1>Visualização de Produtos</h1>
    <p>Você está logado como: <strong><?php echo htmlspecialchars($_SESSION['funcao']); ?></strong></p>

    <table>
    <thead>
        <tr>
            <th>Cód. Prod</th>
            <th>Nome</th>
            <th>Estoque</th>
            <th>Fornecedor</th>
            <th>Preço</th>
            <th>Validade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?php echo $produto['cod_prod']; ?></td>
            <td><?php echo htmlspecialchars($produto['nome_prod']); ?></td>
            <td><?php echo $produto['quant_estoque'] ?? 0; ?></td>
            <td><?php echo htmlspecialchars($produto['nome_forn'] ?? 'N/A'); ?></td>
            <td><?php echo number_format($produto['preco'] ?? 0,2,',','.'); ?></td>
            <td><?php echo !empty($produto['valid']) ? date('d/m/Y', strtotime($produto['valid'])) : 'N/A'; ?></td>
            <td>
                <a class="button-edit" 
                   href="produto_crud.php?action=edit&id=<?php echo $produto['cod_prod']; ?>">
                   Editar
                </a>

                <a class="button-delete" 
                   href="produto_crud.php?action=delete&id=<?php echo $produto['cod_prod']; ?>" 
                   onclick="return confirm('Deseja realmente excluir?');">
                   Excluir
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p style="text-align:center; margin-top:20px;">
    <a class="button-delete" id="voltar" style="cursor:pointer;">
        Voltar à Tela Inicial
    </a>
</p>
</div>
</body>
</html>