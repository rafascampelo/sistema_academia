<?php
// Arquivo: Estatisticas.class.php
require_once __DIR__ . '/../core/conexao.php';

class Estatisticas {
    private $pdo;

    public function __construct() {
        $this->pdo = conectar_db();
    }

    public function getVisaoGeral() {
        $stats = [];
        
        // 1. Quantidade de Alunos
        $stats['quant_alunos'] = $this->pdo->query("SELECT COUNT(*) FROM aluno")->fetchColumn();
        
        // 2. Quantidade de Produtos em Estoque (Soma das quantidades)
        $stats['quant_produtos_estoque'] = $this->pdo->query("SELECT SUM(quantidade) FROM estoque")->fetchColumn() ?? 0;
        
        // 3. Quantidade de Funcionários
        $stats['quant_func'] = $this->pdo->query("SELECT COUNT(*) FROM funcionario")->fetchColumn();

        // 4. Plano de Pagamento Fixo (Mais caro/popular, ajuste a query conforme o que for 'fixo')
        $stats['plano_mais_caro'] = $this->pdo->query("SELECT plano_pag, valor FROM pag_aluno ORDER BY valor DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

        return $stats;
    }
}