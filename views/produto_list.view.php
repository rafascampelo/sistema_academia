<?php 
// views/produto_list.view.php

// ... (HTML de cabeçalho) ...

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>CRUD Produtos (ADM)</title>
</head>
<body>
    <h1>Gerenciamento de Produtos (CRUD)</h1>
    
    <?php if ($mensagem): ?>
        <p style="color: green;"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <h2><?php echo $produto_data ? 'Editar Produto' : 'Novo Produto'; ?></h2>
    <form action="produto_crud.php" method="POST">
        <?php if ($produto_data): ?>
            <input type="hidden" name="cod_prod" value="<?php echo $produto_data['cod_prod']; ?>">
        <?php endif; ?>

        <label>Nome do Produto:</label>
        <input type="text" name="nome_prod" value="<?php echo $produto_data['nome_prod'] ?? ''; ?>" required><br>
        
        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" value="<?php echo $produto_data['preco'] ?? ''; ?>"><br>
        
        <label>Descrição:</label>
        <textarea name="descricao"><?php echo $produto_data['descricao'] ?? ''; ?></textarea><br>
        
        <button type="submit"><?php echo $produto_data ? 'Salvar Alterações' : 'Cadastrar'; ?></button>
        <a href="produto_crud.php">Cancelar/Novo</a>
    </form>
    
    <h2>Lista de Produtos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?php echo $produto['cod_prod']; ?></td>
                <td><?php echo htmlspecialchars($produto['nome_prod']); ?></td>
                <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                <td>
                    <a href="produto_crud.php?action=edit&id=<?php echo $produto['cod_prod']; ?>">Editar</a> | 
                    <a href="produto_crud.php?action=delete&id=<?php echo $produto['cod_prod']; ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p><a href="index.php">Voltar à Tela Principal</a></p>
</body>
</html>