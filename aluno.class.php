<?php
// Arquivo: Aluno.class.php
// Inclui a conexão para que as classes possam usá-la
require_once 'conexao.php';

class Aluno {
    // Propriedades privadas
    private $cod_aluno;
    private $nome_aluno;
    private $plano_pag;
    private $idade;
    private $telefone;
    private $cpf;
    private $email;
    
    // Construtor e Getters/Setters (para atender ao Encapsulamento)
    // ... (Aqui ficariam os métodos de Aluno para validar dados, etc.)
    
    // Simplificação: vamos usar uma classe separada para o CRUD

    public function __construct(array $dados = []) {
        // Mapeamento de dados para o objeto (opcional para esta fase, mas bom para POO)
        foreach ($dados as $chave => $valor) {
            if (property_exists($this, $chave)) {
                $this->$chave = $valor;
            }
        }
    }
    
    // Exemplo de Getter para o requisito de POO:
    public function getNome() {
        return $this->nome_aluno;
    }
}

// 🌟 CLASSE PRINCIPAL PARA AS OPERAÇÕES DE BANCO (CRUD) 🌟
class AlunoCRUD {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar_db();
    }

    // LISTAGEM (Read)
    public function listarTodos() {
        $stmt = $this->pdo->query("SELECT * FROM aluno");
        // Requisito: Listagem de dados a partir do banco
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // INCLUSÃO (Create)
    public function inserir(array $dados) {
        // Requisito: Inclusão de registros através do PHP
        $sql = "INSERT INTO aluno (nome_aluno, plano_pag, idade, telefone, cpf, email) 
                VALUES (:nome_aluno, :plano_pag, :idade, :telefone, :cpf, :email)";
        
        $stmt = $this->pdo->prepare($sql);
        
        // PDO::prepare + bindValue/bindParam cumpre o requisito de segurança
        return $stmt->execute($dados);
    }
    
    // ALTERAÇÃO (Update)
    public function atualizar($cod_aluno, array $dados) {
        // Requisito: Alteração de dados do banco de dados via PHP
        $sql = "UPDATE aluno SET nome_aluno = :nome_aluno, plano_pag = :plano_pag, 
                idade = :idade, telefone = :telefone, cpf = :cpf, email = :email 
                WHERE cod_aluno = :cod_aluno";
        
        $dados['cod_aluno'] = $cod_aluno;
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($dados);
    }

    // EXCLUSÃO (Delete)
    public function excluir($cod_aluno) {
        // Requisito: Exclusão de registros através do PHP
        $sql = "DELETE FROM aluno WHERE cod_aluno = :cod_aluno";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute(['cod_aluno' => $cod_aluno]);
    }
    
    // Busca um aluno específico
    public function buscarPorId($cod_aluno) {
        $stmt = $this->pdo->prepare("SELECT * FROM aluno WHERE cod_aluno = :id");
        $stmt->execute(['id' => $cod_aluno]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}