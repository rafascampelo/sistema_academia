<?php
// Arquivo: aluno_view.php (Acesso para Supervisor e ADM)
session_start();

require_once '../backend/core/conexao.php';
require_once '../backend/core/auth_functions.php'; 

require_once '../backend/class/Aluno.class.php';
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
    <title>Visualização de Alunos (Professor)</title>
    <link rel="stylesheet" href="../styles/aluno.css"> 
    <script src="../scripts/scriptAlunoView.js"></script>
</head>
<body>
<div class="container" id="container">
    <h1>Visualização de Alunos</h1>
    <p class="info">Função Atual: <?php echo htmlspecialchars($funcao); ?></p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Plano</th>
                <th>Telefone</th>
                <th>Data Matrícula</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($alunos as $aluno): ?>
            <tr>
                <td><?php echo $aluno['cod_aluno']; ?></td>
                <td><?php echo htmlspecialchars($aluno['nome_aluno']); ?></td>
                <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                <td><?php echo htmlspecialchars($aluno['plano_pag']); ?></td>
                <td><?php echo htmlspecialchars($aluno['telefone']); ?></td>
                <td><?php echo !empty($aluno['Data_matricula']) ? date('d/m/Y', strtotime($aluno['Data_matricula'])) : 'N/A'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p style="text-align:center; margin-top:20px;">
        <a class="button" id="voltar">Voltar à Tela Inicial</a>
    </p>
</div>


</body>
</html>