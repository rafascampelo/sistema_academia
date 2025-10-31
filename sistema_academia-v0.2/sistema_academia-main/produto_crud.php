<?php
// Arquivo: produto_crud.php
session_start();
require_once 'backend/core/auth_functions.php';
require_once 'backend/class/Produto.class.php'; // Inclui a nova classe de CRUD

// 1. AUTORIZAÇÃO: Só permite acesso se for ADM!
if (!is_adm()) {
    header('Location: index.php?error=acesso_negado');
    exit();
}

$crud = new ProdutoCRUD();
$mensagem = '';
$action = $_GET['action'] ?? 'list';
$produto_data = null; 

// Carrega a lista de fornecedores para o formulário
$fornecedores = $crud->listarFornecedores();

// 2. Processamento das Requisições POST (Inclusão/Alteração)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;
    
    // 🚨 Validação de Dados (Focando apenas em Produto e Estoque)
    if (empty($dados['nome_prod']) || !isset($dados['quantidade'])) {
        $mensagem = 'Erro: Nome do Produto e Quantidade em Estoque são obrigatórios.';
    } else {
        
        // Formata os dados para o método CRUD - REMOVEMOS FORNECEDOR/PREÇO/VALID
        $dados_crud = [
            'nome_prod'  => $dados['nome_prod'],
            'quantidade' => (int)$dados['quantidade'],
            // Removidas as chaves: 'cod_forn', 'preco', 'valid'
        ];
        
        // Ação de Alteração (UPDATE)
        if (isset($dados['cod_prod']) && !empty($dados['cod_prod'])) {
            if ($crud->atualizar($dados['cod_prod'], $dados_crud)) {
                $mensagem = 'Produto alterado com sucesso!';
            } else {
                $mensagem = 'Erro ao alterar produto (Verifique os dados).';
            }
        } else {
            // Ação de Inclusão (CREATE)
            // Se for inclusão, o usuário precisa selecionar o fornecedor, então adicionamos a validação de FORNECIMENTO APENAS NO CREATE
            // Se for o CREATE, mantemos a lógica de fornecedor para a primeira compra.
            $dados_crud['cod_forn'] = $dados['cod_forn'] ?? null;
            $dados_crud['preco'] = floatval($dados['preco'] ?? 0.00);
            $dados_crud['valid'] = $dados['valid'] ?? null;
            
            if ($crud->inserir($dados_crud)) {
                $mensagem = 'Produto cadastrado com sucesso!';
            } else {
                $mensagem = 'Erro ao cadastrar produto (Verifique os dados).';
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
    if ($crud->excluir($_GET['id'])) {
        $mensagem = 'Produto excluído com sucesso!';
    } else {
        $mensagem = 'Erro ao excluir produto.';
    }
    header('Location: produto_crud.php?mensagem=' . urlencode($mensagem));
    exit();
}

if ($action === 'edit' && isset($_GET['id'])) {
    // Busca os dados para pré-preencher o formulário
    $produto_data = $crud->buscarPorId($_GET['id']);
}

// 4. LISTAGEM
$produtos = $crud->listarTodosComRelacoes();

// 5. RENDERIZAÇÃO
require_once 'views/produto_list.view.php'; 
?>