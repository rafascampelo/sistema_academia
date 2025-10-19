<?php
// Arquivo: Produto.class.php
require_once __DIR__ . '/../core/conexao.php';

// Classe de domínio (Produto)
class Produto {
    // ... propriedades e encapsulamento ...
}

// 🌟 CLASSE PRINCIPAL PARA AS OPERAÇÕES DE BANCO (CRUD) 🌟
class ProdutoCRUD {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar_db();
    }

    // LISTAGEM (Read)
    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM produto");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // INCLUSÃO (Create)
    public function inserir(array $dados) {
        $sql = "INSERT INTO produto (nome_prod, preco, descricao) 
                VALUES (:nome_prod, :preco, :descricao)";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Adapte os campos conforme a sua tabela 'produto'
        return $stmt->execute([
            'nome_prod' => $dados['nome_prod'],
            'preco'     => $dados['preco'] ?? 0.00,
            'descricao' => $dados['descricao'] ?? null,
        ]);
    }
    
    // ALTERAÇÃO (Update)
    public function atualizar($cod_prod, array $dados) {
        $sql = "UPDATE produto SET nome_prod = :nome_prod, preco = :preco, descricao = :descricao 
                WHERE cod_prod = :cod_prod";
        
        $dados_exec = [
            'nome_prod' => $dados['nome_prod'],
            'preco'     => $dados['preco'] ?? 0.00,
            'descricao' => $dados['descricao'] ?? null,
            'cod_prod'  => $cod_prod
        ];
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($dados_exec);
    }

    // EXCLUSÃO (Delete)
    public function excluir($cod_prod) {
        $sql = "DELETE FROM produto WHERE cod_prod = :cod_prod";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute(['cod_prod' => $cod_prod]);
    }
    
    // Busca um produto específico
    public function buscarPorId($cod_prod) {
        $stmt = $this->pdo->prepare("SELECT * FROM produto WHERE cod_prod = :id");
        $stmt->execute(['id' => $cod_prod]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}