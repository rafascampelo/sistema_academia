<?php 
// views/aluno_list.view.php
// Esta view é responsável por exibir a lista e o formulário de CRIAÇÃO/EDIÇÃO

// ... (HTML de cabeçalho, link para CSS externo) ...

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>CRUD Alunos (ADM)</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/aluno.css">
    <script src="scripts/scriptAlunoList.js"></script>
    </head>
<body>
<div class="container" id="container">

    <h1>Gerenciar Alunos</h1>

    <?php if (!empty($_GET['mensagem'])): ?>
        <p class="mensagem"><?php echo htmlspecialchars($_GET['mensagem']); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="cod_aluno" value="<?php echo $aluno_data['cod_aluno'] ?? ''; ?>">

        <input type="text" name="nome_aluno" placeholder="Nome" value="<?php echo $aluno_data['nome_aluno'] ?? ''; ?>">
        <input type="text" name="email" placeholder="Email" value="<?php echo $aluno_data['email'] ?? ''; ?>">
        <input type="text" name="telefone" placeholder="Telefone" value="<?php echo $aluno_data['telefone'] ?? ''; ?>">
        <input type="text" name="cpf" placeholder="CPF" value="<?php echo $aluno_data['cpf'] ?? ''; ?>">
        <input type="number" name="idade" placeholder="Idade" value="<?php echo $aluno_data['idade'] ?? ''; ?>">
        <input type="text" name="plano_pag" placeholder="Plano" value="<?php echo $aluno_data['plano_pag'] ?? ''; ?>">
        <input type="date" name="Data_matricula" value="<?php echo $aluno_data['Data_matricula'] ?? ''; ?>">

        <button type="submit">
            <?php echo isset($aluno_data) ? 'Atualizar' : 'Cadastrar'; ?>
        </button>
    </form>

<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Email</th>
            <th>CPF</th>
            <th>Plano</th>
            <th>Telefone</th>
            <th>Data Matrícula</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($alunos as $aluno): ?>
        <tr>
            <td><?php echo $aluno['cod_aluno']; ?></td>
            <td><?php echo htmlspecialchars($aluno['nome_aluno']); ?></td>
            <td><?php echo htmlspecialchars($aluno['email']); ?></td>
            <td><?php echo htmlspecialchars($aluno['cpf']); ?></td>
            <td><?php echo htmlspecialchars($aluno['plano_pag']); ?></td>
            <td><?php echo htmlspecialchars($aluno['telefone']); ?></td>
            <td><?php echo !empty($aluno['Data_matricula']) ? date('d/m/Y', strtotime($aluno['Data_matricula'])) : 'N/A'; ?></td>

            <td>
                <a class="button-edit" 
                   href="aluno_crud.php?action=edit&id=<?php echo $aluno['cod_aluno']; ?>">
                   Editar
                </a>

                <a class="button-delete"
                   href="aluno_crud.php?action=delete&id=<?php echo $aluno['cod_aluno']; ?>"
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


</div>



</body>
</html>