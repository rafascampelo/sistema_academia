<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <title>CRUD Produtos (ADM)</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/produto_list.view.css">
    <script src="scripts/scriptProdutoCrud.js" defer></script>
    </head>
<body>
<div class="container" id="container">
    <h1>Gerenciar Produtos</h1>

    <?php if (!empty($_GET['mensagem'])): ?>
        <p class="mensagem"><?php echo htmlspecialchars($_GET['mensagem']); ?></p>
    <?php endif; ?>

    <form method="POST" id="cadastro">
        <input type="hidden" name="cod_prod" value="<?php echo $produto_data['cod_prod'] ?? ''; ?>">

        <input type="text" name="nome_prod" placeholder="Nome do Produto" value="<?php echo $produto_data['nome_prod'] ?? ''; ?>" required>
        
        <input type="number" name="quantidade" placeholder="Quantidade em Estoque" id="quantidade" value="<?php echo $produto_data['quant_estoque'] ?? 0; ?>" required>

        <select name="cod_forn" required>
            <option value="">Selecione o Fornecedor</option>
            <?php foreach ($fornecedores as $forn): ?>
                <option value="<?php echo $forn['cod_forn']; ?>" 
                    <?php if(isset($produto_data['cod_forn']) && $produto_data['cod_forn'] == $forn['cod_forn']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($forn['nome_forn']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="number" step="0.01" name="preco" placeholder="Preço (R$)" id="preco" required value="<?php echo $produto_data['preco'] ?? ''; ?>">

        <label for="valid">Data de Validade</label>
        <input type="date" name="valid" id="valid" required value="<?php echo $produto_data['valid'] ?? ''; ?>">

        <button type="submit">
            <?php echo isset($produto_data) ? 'Atualizar' : 'Cadastrar'; ?>
        </button>
    </form>

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
            <td class="button-acoes">
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
    <a class="button-delete" id="voltar" href="#" style="cursor:pointer;">
        Voltar à Tela Inicial
    </a>
</p>


</div></body>
</html>