<?php
// Arquivo: Funcionario.class.php
require_once __DIR__ . '/../core/conexao.php';

// ... (Defina a classe Funcionario de domínio aqui)

class FuncionarioCRUD {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar_db();
    }

    // 🌟 1. Método de Inserção de NOVO FUNCIONÁRIO com HASH Automático
    public function inserir(array $dados) {
        // 🚨 Segurança: Gera o hash da senha automaticamente
        $dados['password'] = password_hash($dados['password'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO funcionario (nome_func, email, password, funcao, idade, telefone, cpf) 
                VALUES (:nome_func, :email, :password, :funcao, :idade, :telefone, :cpf)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($dados);
    }
    
    // 🌟 2. Método de Alteração com HASH Condicional
    public function atualizar($cod_func, array $dados) {
        // Se a senha foi enviada no formulário, faça o hash
        if (!empty($dados['password'])) {
            $dados['password'] = password_hash($dados['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE funcionario SET nome_func = :nome_func, password = :password, funcao = :funcao 
                    WHERE cod_func = :cod_func";
        } else {
            // Se a senha estiver vazia, mantenha a antiga
            $sql = "UPDATE funcionario SET nome_func = :nome_func, funcao = :funcao 
                    WHERE cod_func = :cod_func";
            unset($dados['password']); // Remove a chave para não dar erro no execute
        }
        
        // ... (resto da execução)
    }

    // ... (Outros métodos: listarTodos, buscarPorId, excluir)
}