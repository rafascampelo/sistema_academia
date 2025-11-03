<?php
// Arquivo: backend/class/Produto.class.php
require_once __DIR__ . '/../core/conexao.php'; 

class ProdutoCRUD {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar_db();
    }

    // ====================================================================
    // MÉTODOS DE CONSULTA
    // ====================================================================
    
    // LISTAGEM PRINCIPAL (Faz o JOIN de todas as tabelas)
    public function listarTodosComRelacoes() {
        $sql = "
            -- LISTAGEM PRINCIPAL (Faz o JOIN de todas as tabelas)
SELECT 
    p.cod_prod, p.nome_prod,
    e.quantidade AS quant_estoque, 
    f.cod_forn, fo.nome_forn, 
    f.preco, f.valid, f.data_comp
FROM produto p
LEFT JOIN estoque e ON p.cod_prod = e.cod_prod
LEFT JOIN fornecimento f ON p.cod_prod = f.cod_prod -- AQUI está o problema da duplicação
LEFT JOIN fornecedor fo ON f.cod_forn = fo.cod_forn
ORDER BY p.cod_prod, f.data_comp DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lista fornecedores para o <select>
    public function listarFornecedores() {
        $stmt = $this->pdo->query("SELECT cod_forn, nome_forn FROM fornecedor ORDER BY nome_forn");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca um produto e seus dados de estoque/fornecimento para EDIÇÃO
    public function buscarPorId($cod_prod) {
        $sql = "
            SELECT 
                p.cod_prod, p.nome_prod,
                e.quantidade AS quant_estoque,
                f.cod_forn, f.preco, f.valid, f.data_comp
            FROM produto p
            LEFT JOIN estoque e ON p.cod_prod = e.cod_prod
            LEFT JOIN fornecimento f ON p.cod_prod = f.cod_prod
            WHERE p.cod_prod = :id
            ORDER BY f.data_comp DESC LIMIT 1 -- Pega o fornecimento mais recente para a edição
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $cod_prod]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ====================================================================
    // MÉTODOS DE TRANSAÇÃO (CRUD)
    // ====================================================================

    // CRIAÇÃO (Create)
    public function inserir(array $dados) {
        try {
            $this->pdo->beginTransaction(); 

            // 1. Inserir em 'produto'
            $sql_prod = "INSERT INTO produto (nome_prod) VALUES (:nome_prod)";
            $stmt_prod = $this->pdo->prepare($sql_prod);
            $stmt_prod->execute(['nome_prod' => $dados['nome_prod']]);
            $cod_prod = $this->pdo->lastInsertId(); // Pega o ID recém-criado

            // 2. Inserir em 'estoque'
            $sql_est = "INSERT INTO estoque (cod_prod, quantidade) VALUES (:cod_prod, :quantidade)";
            $stmt_est = $this->pdo->prepare($sql_est);
            $stmt_est->execute([
                'cod_prod' => $cod_prod, 
                'quantidade' => $dados['quantidade'] ?? 0
            ]);

            // 3. Inserir em 'fornecimento' (Se dados estiverem presentes)
            if (!empty($dados['cod_forn']) && !empty($dados['preco'])) {
                 $sql_forn = "INSERT INTO fornecimento (cod_prod, cod_forn, preco, valid, data_comp) 
                              VALUES (:cod_prod, :cod_forn, :preco, :valid, NOW())";
                 $stmt_forn = $this->pdo->prepare($sql_forn);
                 $stmt_forn->execute([
                     'cod_prod' => $cod_prod,
                     'cod_forn' => $dados['cod_forn'],
                     'preco' => $dados['preco'],
                     'valid' => $dados['valid'] ?? '2099-12-31'
                 ]);
            }
            
            $this->pdo->commit(); 
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack(); 
            // 🚨 Em caso de debug, você pode usar: die("Erro: " . $e->getMessage());
            return false;
        }
    }
    
    // ALTERAÇÃO (Update) - Altera Produto e Estoque, e insere NOVO Fornecimento
// ... (Método ProdutoCRUD)

// ALTERAÇÃO (Update) - Altera Produto e Estoque, e ATUALIZA o Fornecimento mais recente
// Arquivo: backend/class/Produto.class.php

// ALTERAÇÃO (Update) - Altera Produto e Estoque, e ATUALIZA Fornecimento
public function atualizar($cod_prod, array $dados) {
    try {
        $this->pdo->beginTransaction();
        
        // 1. Atualizar 'produto' (apenas nome) - OK
        $sql_prod = "UPDATE produto SET nome_prod = :nome_prod WHERE cod_prod = :cod_prod";
        $this->pdo->prepare($sql_prod)->execute([
            'nome_prod' => $dados['nome_prod'], 
            'cod_prod' => $cod_prod
        ]);

        // 2. Atualizar/Inserir 'estoque' - OK (Mantenha a lógica de upsert)
        $sql_est = "UPDATE estoque SET quantidade = :quantidade WHERE cod_prod = :cod_prod";
        $stmt_est = $this->pdo->prepare($sql_est);
        $stmt_est->execute([
            'cod_prod' => $cod_prod, 
            'quantidade' => $dados['quantidade'] ?? 0
        ]);

        if ($stmt_est->rowCount() === 0) {
             $sql_insert_est = "INSERT INTO estoque (cod_prod, quantidade) VALUES (:cod_prod, :quantidade)";
             $this->pdo->prepare($sql_insert_est)->execute([
                 'cod_prod' => $cod_prod, 
                 'quantidade' => $dados['quantidade'] ?? 0
             ]);
        }
        
        // 3. ATUALIZAR REGISTRO DE FORNECIMENTO (A SOLUÇÃO)
        $pode_atualizar_forn = (
            !empty($dados['cod_forn']) && 
            $dados['preco'] >= 0
            // valid pode ser null, mas se for alterado, deve ser incluído
        );
        
        if ($pode_atualizar_forn) {
            // Utilizamos UPDATE para modificar o registro existente, usando a chave composta na cláusula WHERE.
            $sql_forn = "UPDATE fornecimento 
                         SET preco = :preco, valid = :valid, data_comp = NOW()
                         WHERE cod_prod = :cod_prod AND cod_forn = :cod_forn";
            
            $stmt_forn = $this->pdo->prepare($sql_forn);
            $stmt_forn->execute([
                'cod_prod' => $cod_prod,
                'cod_forn' => $dados['cod_forn'],
                'preco' => $dados['preco'],
                'valid' => $dados['valid'] ?? null // Permite enviar null se o campo for opcional
            ]);
            
            // 🚨 Tratamento para o caso de ter mudado o fornecedor (Exigiria DELETE + INSERT, mas é arriscado).
            // Se o fornecedor foi alterado no formulário, a linha de UPDATE acima não afetará 
            // nenhuma linha (rowCount() == 0). Neste caso, a ação correta seria um novo INSERT:
            if ($stmt_forn->rowCount() === 0) {
                // Se nenhum registro foi atualizado, vamos tentar INSERIR (novo fornecedor)
                $sql_insert_forn = "INSERT INTO fornecimento (cod_prod, cod_forn, preco, valid, data_comp) 
                                    VALUES (:cod_prod, :cod_forn, :preco, :valid, NOW())";
                $this->pdo->prepare($sql_insert_forn)->execute([
                    'cod_prod' => $cod_prod,
                    'cod_forn' => $dados['cod_forn'],
                    'preco' => $dados['preco'],
                    'valid' => $dados['valid'] ?? null
                ]);
            }

        } // Fim do if ($pode_atualizar_forn)
        
        $this->pdo->commit(); 
        return true;

    } catch (Exception $e) {
        $this->pdo->rollBack(); 
        // 🚨 Mantenha o die() se quiser ver o erro, mas remova-o para produção
        // die("Erro de Transação no Update Final: " . $e->getMessage()); 
        return false;
    }
}
    // EXCLUSÃO (Delete) - Remove de todas as tabelas relacionadas
    public function excluir($cod_prod) {
        try {
            $this->pdo->beginTransaction();

            // 1. Excluir em fornecimento (foreign key)
            $sql_forn = "DELETE FROM fornecimento WHERE cod_prod = :cod_prod";
            $this->pdo->prepare($sql_forn)->execute(['cod_prod' => $cod_prod]);

            // 2. Excluir em estoque (foreign key)
            $sql_est = "DELETE FROM estoque WHERE cod_prod = :cod_prod";
            $this->pdo->prepare($sql_est)->execute(['cod_prod' => $cod_prod]);

            // 3. Excluir em produto (tabela principal)
            $sql_prod = "DELETE FROM produto WHERE cod_prod = :cod_prod";
            $this->pdo->prepare($sql_prod)->execute(['cod_prod' => $cod_prod]);

            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}