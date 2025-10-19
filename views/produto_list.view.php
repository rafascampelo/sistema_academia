<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD Produtos (ADM)</title>
    </head>
<body>
    <h1>Gerenciamento de Produtos (CRUD)</h1>
    
    <?php if ($mensagem): ?>
        <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <h2><?php echo $produto_data ? 'Editar Produto' : 'Novo Produto'; ?></h2>
    <form action="produto_crud.php" method="POST">
        <?php if ($produto_data): ?>
            <input type="hidden" name="cod_prod" value="<?php echo $produto_data['cod_prod']; ?>">
        <?php endif; ?>

        <label>Nome do Produto:</label>
        <input type="text" name="nome_prod" value="<?php echo $produto_data['nome_prod'] ?? ''; ?>" required><br><br>
        
        <label>Quantidade em Estoque:</label>
        <input type="number" name="quantidade" value="<?php echo $produto_data['quant_estoque'] ?? 0; ?>" required><br><br>
        
        <label>Fornecedor:</label>
        <select name="cod_forn">
            <option value="">Selecione o Fornecedor (Opcional)</option>
            <?php 
            // Lista de fornecedores (Carregada no produto_crud.php)
            foreach ($fornecedores as $forn): ?>
                <option value="<?php echo $forn['cod_forn']; ?>"
                    <?php if (isset($produto_data['cod_forn']) && $produto_data['cod_forn'] == $forn['cod_forn']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($forn['nome_forn']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Preço de Compra:</label>
        <input type="number" step="0.01" name="preco" value="<?php echo $produto_data['preco'] ?? ''; ?>"><br><br>
        
        <label>Data de Validade (opcional):</label>
        <input type="date" name="valid" value="<?php echo $produto_data['valid'] ?? ''; ?>"><br><br>

        <button type="submit"><?php echo $produto_data ? 'Salvar Alterações' : 'Cadastrar Produto'; ?></button>
        <a href="produto_crud.php">Cancelar/Novo</a>
    </form>
    
    <hr>
    
    <h2>Lista de Produtos para Gerenciamento</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Cód. Prod</th>
                <th>Nome Prod.</th>
                <th>Estoque (Quant.)</th>
                <th>Fornecedor Atual</th>
                <th>Preço Compra</th>
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
                <td>R$ <?php echo number_format($produto['preco'] ?? 0, 2, ',', '.'); ?></td>
                <td><?php echo $produto['valid'] ?? 'N/A'; ?></td>
                <td>
                    <a href="produto_crud.php?action=edit&id=<?php echo $produto['cod_prod']; ?>">Editar/Atualizar</a> | 
                    <a href="produto_crud.php?action=delete&id=<?php echo $produto['cod_prod']; ?>" 
                       onclick="return confirm('ATENÇÃO: Excluirá o produto, estoque e fornecimento. Tem certeza?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p><a href="index.php">Voltar à Tela Principal</a></p>
</body>
</html>