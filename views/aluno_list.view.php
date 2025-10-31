<?php 
// views/aluno_list.view.php
// Esta view é responsável por exibir a lista e o formulário de CRIAÇÃO/EDIÇÃO

// ... (HTML de cabeçalho, link para CSS externo) ...

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>CRUD Alunos (ADM)</title>
    </head>
<body>
    <h1>Gerenciamento de Alunos (CRUD)</h1>
    
    <?php if ($mensagem): ?>
        <p style="color: green;"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <h2><?php echo $aluno_data ? 'Editar Aluno' : 'Novo Aluno'; ?></h2>
    <form action="aluno_crud.php" method="POST">
        <?php if ($aluno_data): ?>
            <input type="hidden" name="cod_aluno" value="<?php echo $aluno_data['cod_aluno']; ?>">
        <?php endif; ?>

        <label>Nome:</label>
        <input type="text" name="nome_aluno" value="<?php echo $aluno_data['nome_aluno'] ?? ''; ?>" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $aluno_data['email'] ?? ''; ?>"><br>
        
        <label>Plano:</label>
        <input type="text" name="plano_pag" value="<?php echo $aluno_data['plano_pag'] ?? ''; ?>" required><br>
        
        <label>Data matricula:</label>
        <input type="date" name="Data_matricula" value="<?php echo $aluno_data['Data_matricula'] ?? ''; ?>" required><br>

         <label>Telefone:</label>
        <input type="text" name="telefone" value="<?php echo $aluno_data['telefone'] ?? ''; ?>" required><br>


        <button type="submit"><?php echo $aluno_data ? 'Salvar Alterações' : 'Cadastrar'; ?></button>
        <a href="aluno_crud.php">Cancelar/Novo</a>
    </form>
    
    <h2>Lista de Alunos Cadastrados</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Data matricula</th>
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
                <td>
  <?php
    echo !empty($aluno['Data_matricula'])
      ? date('d/m/y', strtotime($aluno['Data_matricula']))
      : 'N/A';
  ?>
</td>
                <td>
                    <a href="aluno_crud.php?action=edit&id=<?php echo $aluno['cod_aluno']; ?>">Editar</a> | 
                    
                    <a href="aluno_crud.php?action=delete&id=<?php echo $aluno['cod_aluno']; ?>" 
                       onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p><a href="index.php">Voltar à Tela Principal</a></p>
</body>
</html>