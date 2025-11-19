<?php
// Arquivo: login.php

// Inicia a sessão (essencial para armazenar o status de login)
session_start();
require_once 'backend/core/conexao.php';
require_once 'backend/core/auth_functions.php'; 
require_once 'backend/class/Aluno.class.php';
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
    if ($funcionario) {
        echo "Funcao no DB: " . $funcionario['funcao'] . "<br>";
        echo "Password no DB (Hash): " . $funcionario['password'] . "<br>";
        echo "Resultado password_verify: " . (password_verify($senha_digitada, $funcionario['password']) ? 'SUCESSO' : 'FALHOU') . "<br>";
        
        // 🚨 SUCESSO NO LOGIN!

        // 4. Cria a Sessão
        $_SESSION['logado'] = true;
        $_SESSION['cod_func'] = $funcionario['cod_func'];
        $_SESSION['nome'] = $funcionario['nome_func'];
        $_SESSION['funcao'] = $funcionario['funcao']; // <-- Usado para autorização!

        // 5. Redireciona para a Tela Principal
        header("Location: index.php");
        exit();

    } else {
        $erro_login = 'Email ou senha inválidos.';
    }
}
?>  

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Academia Fit</title>
    <link rel="stylesheet" href="styles/styleslogin.css"> 
    <script src="scripts/script.js"></script>
</head>
<body>
  <div class="overlay"></div>
  <div class="container" id="login-container">
    <h2>Login Academia</h2>
            <?php if ($erro_login): ?>
            <p style="color: red;"><?php echo $erro_login; ?></p>
        <?php endif; ?>

    <form id="login-form" action="login.php" method="POST">
      <input type="email" name="email" placeholder="Digite seu e-mail" required>
      <input type="password" name="password" placeholder="Digite sua senha" required>
      <button type="submit">Entrar</button>
    </form>
  </div>

</body>
</html>