<?php
// Arquivo: login.php

// Inicia a sessão (essencial para armazenar o status de login)
session_start();
require_once 'conexao.php'; // Inclui a conexão com o DB

$erro_login = '';

// Verifica se o formulário de login foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha_digitada = $_POST['password'] ?? '';

    // 1. Conecta ao Banco
    $pdo = conectar_db();

    // 2. Prepara a consulta SQL para buscar o funcionário pelo email
    $stmt = $pdo->prepare("SELECT cod_func, nome_func, email, password, funcao FROM funcionario WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Verifica se o funcionário existe e se a senha está correta
    if ($funcionario && password_verify($senha_digitada, $funcionario['password'])) {
        
        // 🚨 SUCESSO NO LOGIN!

        // 4. Cria a Sessão
        $_SESSION['logado'] = true;
        $_SESSION['cod_func'] = $funcionario['cod_func'];
        $_SESSION['nome'] = $funcionario['nome_func'];
        $_SESSION['funcao'] = $funcionario['funcao']; // <-- Usado para autorização!

        // 5. Redireciona para a Tela Principal
        header('Location: index.php');
        exit();

    } else {
        $erro_login = 'Email ou senha inválidos.';
    }
}

// Se o usuário já estiver logado, redireciona para a tela principal
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Academia Fit</title>
    <link rel="stylesheet" href="css/estilo.css"> 
</head>
<body>
    <div class="container">
        <h1>Acesso de Funcionários</h1>

        <?php if ($erro_login): ?>
            <p style="color: red;"><?php echo $erro_login; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>