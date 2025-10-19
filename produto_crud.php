<?php
// Arquivo: produto_crud.php
session_start();
require_once 'backend/core/auth_functions.php';
require_once 'backend/class/Produto.class.php'; 
// 1. AUTORIZAÇÃO: Só permite acesso se for ADM!
if (!is_adm()) {
    header('Location: index.php?error=acesso_negado');
    exit();
}

$crud = new ProdutoCRUD();
$mensagem = '';
$action = $_GET['action'] ?? 'list'; 
$produto_data = null; 

// 2. Processamento das Requisições POST (Inclusão/Alteração)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;
    
    // 🚨 Validação de Dados (Exemplo)
    if (empty($dados['nome_prod'])) {
        $mensagem = 'Erro: Nome do Produto é obrigatório.';
    } else {
        // Prepare os dados para a execução
        $dados_crud = [
            'nome_prod' => $dados['nome_prod'],
            'preco'     => floatval($dados['preco']),
            'descricao' => $dados['descricao'] ?? null,
        ];
        
        if (isset($dados['cod_prod']) && !empty($dados['cod_prod'])) {
            // Ação de Alteração
            if ($crud->atualizar($dados['cod_prod'], $dados_crud)) {
                $mensagem = 'Produto alterado com sucesso!';
            } else {
                $mensagem = 'Erro ao alterar produto.';
            }
        } else {
            // Ação de Inclusão
            if ($crud->inserir($dados_crud)) {
                $mensagem = 'Produto cadastrado com sucesso!';
            } else {
                $mensagem = 'Erro ao cadastrar produto.';
            }
        }
        header('Location: produto_crud.php?mensagem=' . urlencode($mensagem));
        exit();
    }
}

// 3. Processamento das Ações GET (Excluir e Editar)
if (isset($_GET['mensagem'])) {
    $mensagem = $_GET['mensagem'];
}

if ($action === 'delete' && isset($_GET['id'])) {
    // Requisito: Exclusão
    if ($crud->excluir($_GET['id'])) {
        $mensagem = 'Produto excluído com sucesso!';
    } else {
        $mensagem = 'Erro ao excluir produto.';
    }
    header('Location: produto_crud.php?mensagem=' . urlencode($mensagem));
    exit();
}

if ($action === 'edit' && isset($_GET['id'])) {
    // Preenche o formulário para Alteração
    $produto_data = $crud->buscarPorId($_GET['id']);
}

// 4. LISTAGEM
$produtos = $crud->listarTodos();

// 5. RENDERIZAÇÃO
require_once 'views/produto_list.view.php'; 
?>