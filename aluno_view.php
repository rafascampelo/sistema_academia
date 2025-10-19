<?php
// Arquivo: aluno_view.php (Acesso para Supervisor e ADM)
session_start();
require_once 'auth_functions.php';
require_once 'Aluno.class.php';

// 1. AUTORIZAÇÃO: Permite acesso se for ADM OU Supervisor!
if (!is_supervisor()) {
    // Se não for nenhum dos dois, nega o acesso.
    header('Location: index.php?error=acesso_negado');
    exit();
}

// 2. Cria a instância da classe CRUD (Objeto)
$crud = new AlunoCRUD();

// 3. Busca a lista de alunos (Requisito: Listagem de dados a partir do banco)
$alunos = $crud->listarTodos();

// 4. Determina o nível de acesso para a visualização
$funcao = $_SESSION['funcao'];
$is_admin = is_adm();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Visualização de Alunos (Supervisor)</title>
    <link rel="stylesheet" href="css/estilo.css"> 
</head>
<body>
    <h1>Visualização de Alunos</h1>
    <p>Função Atual: <?php echo htmlspecialchars($funcao); ?></p>
    
    <table border="1">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Plano</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alunos as $aluno): ?>
            <tr>
                <td><?php echo htmlspecialchars($aluno['cod_aluno']); ?></td>
                <td><?php echo htmlspecialchars($aluno['nome_aluno']); ?></td>
                <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                <td><?php echo htmlspecialchars($aluno['plano_pag']); ?></td>
                <td><?php echo htmlspecialchars($aluno['telefone']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p><a href="index.php">Voltar à Tela Principal</a></p>
</body>
</html>