<?php
// Arquivo: aluno_crud.php
session_start();
require_once 'backend/core/conexao.php';
require_once 'backend/core/auth_functions.php'; 
// E os CRUDS (se for o caso)
require_once 'backend/class/Aluno.class.php';
// 1. AUTORIZAÇÃO: Só permite acesso se for ADM!
if (!is_adm()) {
    // Se não for ADM, redireciona ou mostra erro 403
    header('Location: index.php?error=acesso_negado');
    exit();
}

// 2. Cria a instância da classe CRUD (Objeto)
$crud = new AlunoCRUD();
$mensagem = '';
$action = $_GET['action'] ?? 'list'; // Ação padrão é listar
$aluno_data = null; // Para formulários de edição

// 3. Processamento das Requisições (Requisito: POST/GET e Inclusão/Exclusão/Alteração)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🚨 VALIDAÇÃO DE DADOS (Requisito do professor)
    $dados = $_POST;
    
    // Validação de campos obrigatórios (Simples)
    if (empty($dados['nome_aluno']) || empty($dados['plano_pag'])) {
        $mensagem = 'Erro: Nome e Plano de Pagamento são obrigatórios.';
    } else {
        // Formata os dados para o método CRUD
        $dados_crud = [
            'nome_aluno' => $dados['nome_aluno'],
            'plano_pag'  => $dados['plano_pag'],
            'idade'      => $dados['idade'] ?? null,
            'telefone'   => $dados['telefone'] ?? null,
            'cpf'        => $dados['cpf'] ?? null,
            'email'      => $dados['email'] ?? null,
            'Data_matricula' => $dados['Data_matricula'] ?? null,
        ];
        
        // Verifica se é Alteração (UPDATE) ou Inclusão (CREATE)
        if (isset($dados['cod_aluno']) && !empty($dados['cod_aluno'])) {
            // Ação de Alteração
            if ($crud->atualizar($dados['cod_aluno'], $dados_crud)) {
                $mensagem = 'Aluno alterado com sucesso!';
            } else {
                $mensagem = 'Erro ao alterar aluno.';
            }
        } else {
            // Ação de Inclusão
            if ($crud->inserir($dados_crud)) {
                $mensagem = 'Aluno cadastrado com sucesso!';
            } else {
                $mensagem = 'Erro ao cadastrar aluno.';
            }
        }
        // Redireciona para evitar reenvio do formulário
        header('Location: aluno_crud.php?mensagem=' . urlencode($mensagem));
        exit();
    }
}

// 4. Processamento das Ações GET (Excluir e Editar)
if (isset($_GET['mensagem'])) {
    $mensagem = $_GET['mensagem'];
}

if ($action === 'delete' && isset($_GET['id'])) {
    // Requisito: Exclusão de registros
    if ($crud->excluir($_GET['id'])) {
        $mensagem = 'Aluno excluído com sucesso!';
    } else {
        $mensagem = 'Erro ao excluir aluno.';
    }
    header('Location: aluno_crud.php?mensagem=' . urlencode($mensagem));
    exit();
}

if ($action === 'edit' && isset($_GET['id'])) {
    // Preenche o formulário para Alteração
    $aluno_data = $crud->buscarPorId($_GET['id']);
}

// 5. LISTAGEM e RENDERIZAÇÃO
$alunos = $crud->listarTodos();

?>
<?php require_once 'views/aluno_list.view.php'; ?>