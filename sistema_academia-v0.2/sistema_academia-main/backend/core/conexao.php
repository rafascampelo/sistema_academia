<?php
// Arquivo: conexao.php

// Definições do seu banco de dados (Academia)
define('DB_HOST', '127.0.0.1'); // Geralmente é localhost
define('DB_USER', 'root'); // Ex: root
define('DB_PASS', ''); // A senha que você usa
define('DB_NAME', 'Academia'); // O nome exato do seu DB

/**
 * Função para retornar a conexão PDO
 * @return PDO
 */
function conectar_db() {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
    
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        
        // Configura o PDO para lançar exceções em caso de erro SQL (melhor para debug)
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Retorna a conexão
        return $pdo;

    } catch (PDOException $e) {
        // Se a conexão falhar, exibe o erro e para a execução do script
        die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
    }
}