<?php
// Array com os planos disponíveis
$planos = [
    'Diario' => 'Diário',
    'Mensal' => 'Mensal',
    'Anual'  => 'Anual'
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>CRUD Alunos (ADM)</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/aluno_list.view.css">
    <script src="scripts/scriptAlunoCrud.js" defer></script>
    </head>
<body>
<div class="container" id="container">

    <h1>Gerenciar Alunos</h1>

    <?php if (!empty($_GET['mensagem'])): ?>
        <p class="mensagem"><?php echo htmlspecialchars($_GET['mensagem']); ?></p>
    <?php endif; ?>

    <form method="POST" id="cadastro">
        <input type="hidden" name="cod_aluno" value="<?php echo $aluno_data['cod_aluno'] ?? ''; ?>">

        <input type="text" name="nome_aluno" placeholder="Nome" id="nome_aluno" required value="<?php echo $aluno_data['nome_aluno'] ?? ''; ?>">
        <input type="email" name="email" placeholder="Email" id="email" required value="<?php echo $aluno_data['email'] ?? ''; ?>">
        <input type="text" name="telefone" placeholder="Telefone" id="telefone" required value="<?php echo $aluno_data['telefone'] ?? ''; ?>">
        <input type="text" name="cpf" placeholder="CPF" id="cpf" required value="<?php echo $aluno_data['cpf'] ?? ''; ?>">
        <input type="number" name="idade" placeholder="Idade" id="idade" required value="<?php echo $aluno_data['idade'] ?? ''; ?>">
<select name="plano_pag" required>
    <option value="">Selecione o Plano</option>
    <?php foreach ($planos as $valor => $label): ?>
        <option value="<?php echo $valor; ?>" 
            <?php if (isset($aluno_data['plano_pag']) && $aluno_data['plano_pag'] === $valor) echo 'selected'; ?>>
            <?php echo htmlspecialchars($label); ?>
        </option>
    <?php endforeach; ?>
</select>
<label for="Data_matricula">Data Matrícula</label>
        <input type="date" name="Data_matricula" id="Data_matricula" required value="<?php echo $aluno_data['Data_matricula'] ?? ''; ?>">

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
           <th>Telefone</th>
            <th>Plano</th>
             <th>CPF</th>
             <th>Idade</th>
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
            <td><?php echo htmlspecialchars($aluno['telefone']); ?></td>
            <td><?php echo htmlspecialchars($aluno['plano_pag']); ?></td>
            <td><?php echo htmlspecialchars($aluno['cpf']); ?></td>
            <td><?php echo htmlspecialchars($aluno['idade']); ?></td>
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
    <a class="button-delete" id="voltar" style="cursor:pointer;">
        Voltar à Tela Inicial
    </a>
</p>


</div>



</body>
</html>