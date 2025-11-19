CREATE DATABASE IF NOT EXISTS Academia; -- Cria o Database (se não existir)
USE Academia; -- Usa o Database

-- Tabela de Produtos
CREATE TABLE produto (
    cod_prod bigint PRIMARY KEY auto_increment,
    nome_prod varchar(50) not null
);

-- Tabela de Estoque (Relacionamento com Produto)
CREATE TABLE estoque (
    cod_prod bigint,
    quantidade int not null,
    FOREIGN KEY (cod_prod) REFERENCES produto(cod_prod)
);

-- Tabela de Fornecedores
CREATE TABLE fornecedor (
    cod_forn bigint PRIMARY KEY auto_increment,
    nome_forn varchar(50) not null,
    endereco varchar(100),
    telefone varchar (15)
);
INSERT INTO fornecedor (nome_forn, endereco, telefone) VALUES
('Growth', 'Loja Online', '1155678877'),
('Integralmedica', 'Rua do Suplemento, 100', '1141982233'),
('Max Titanium', 'Av. dos Atletas, 250', '1633845566'),
('Essential Nutrition', 'Alameda Saúde, 50', '4130771122'),
('Optimum Nutrition', 'Distribuidor Nacional', '2125547777');


-- Tabela de Fornecimento (Tabela PIVOT/Link entre Produto e Fornecedor)
CREATE TABLE fornecimento (
    cod_prod bigint,
    cod_forn bigint,
    preco decimal (10,2),
    valid DATE,
    data_comp DATE,
    PRIMARY KEY (cod_prod, cod_forn),
    FOREIGN KEY (cod_prod) REFERENCES produto (cod_prod),
    FOREIGN KEY (cod_forn) REFERENCES fornecedor (cod_forn)
);

INSERT INTO fornecimento (cod_prod, cod_forn, preco, valid, data_comp) VALUES
(1,1,120.00,'2026-01-10','2025-11-01'),
(2,2,85.00,'2026-02-15','2025-10-20'),
(3,3,99.00,'2025-12-10','2025-09-15'),
(4,4,110.00,'2026-03-01','2025-10-25'),
(5,5,8.50,'2025-12-01','2025-11-05');

SELECT 
    p.cod_prod, p.nome_prod,
    e.quantidade AS quant_estoque, 
    f.cod_forn, fo.nome_forn, 
    f.preco, f.valid, f.data_comp
FROM produto p
LEFT JOIN estoque e ON p.cod_prod = e.cod_prod
LEFT JOIN fornecimento f ON p.cod_prod = f.cod_prod -- AQUI está o problema da duplicação
LEFT JOIN fornecedor fo ON f.cod_forn = fo.cod_forn
ORDER BY p.cod_prod, f.data_comp DESC;


-- Tabela de Planos de Pagamento
CREATE TABLE pag_aluno (
    plano_pag varchar (20) PRIMARY KEY,
    valor decimal (6,2)
);
INSERT INTO pag_aluno (plano_pag, valor) VALUES
('Mensal',100),('Anual',900),('Diario',10);

-- Tabela de Alunos (Membros) - Corrigida para mapear com o Model Membro
CREATE TABLE aluno (
    cod_aluno bigint PRIMARY KEY auto_increment,
    nome_aluno varchar (50) not null,
    plano_pag varchar (20) not null, -- Ajuste para 20 caracteres
    idade varchar (2),
    telefone char(14),
    cpf char(14),
    email varchar (50),
    FOREIGN KEY (plano_pag) REFERENCES pag_aluno(plano_pag)
); 

alter table aluno add Data_matricula date ;

INSERT INTO aluno (nome_aluno, plano_pag, idade, telefone, cpf, email, Data_matricula) VALUES
('João Silva','Mensal','25','11988887777','12345678901','joao@academia.com','2025-01-10'),
('Maria Souza','Anual','30','11977776666','98765432100','maria@academia.com','2025-02-15'),
('Pedro Costa','Diario','20','11955554444','65432198700','pedro@academia.com','2025-03-20');


CREATE TABLE funcionario (
    cod_func bigint PRIMARY KEY auto_increment,
    nome_func varchar (50) not null,
    email varchar (50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    funcao varchar (50),
    idade varchar (2),
    telefone char(14),
    cpf char(14)
);

INSERT INTO funcionario (nome_func, email, password, funcao, idade, telefone, cpf) VALUES
('Rafaela ADM','rafaela.adm@academia.com',PASSWORD('senha123'),'ADM','30','11951861263','59510664847'),
('Lucas','lucas.professor@academia.com',PASSWORD('senha123'),'Professor','50','11961861263','59512664847'),
('Carla','carla.vendas@academia.com',PASSWORD('senha123'),'ADM','28','11999887766','99512664847');


    select * from funcionario;
-- usado para hashear as senhas de funcionario    // o hash agora está automatico no backend, a parte de cadastro de usuario e fornecedor é feita a partir do banco
-- UPDATE funcionario 
-- SET password = '$2y$10$9gV1dLq5nXeISbmWPY.CcO.SFG3afvenI6C7buhK6zbgirgeLEfSe', funcao = 'ADM'
-- WHERE email = 'rafaela.adm@academia.com'; -- Use o email correto!

-- Backup
CREATE TABLE produto_backup AS SELECT * FROM produto;
CREATE TABLE estoque_backup AS SELECT * FROM estoque;
CREATE TABLE fornecedor_backup AS SELECT * FROM fornecedor;
CREATE TABLE fornecimento_backup AS SELECT * FROM fornecimento;
CREATE TABLE aluno_backup AS SELECT * FROM aluno;
CREATE TABLE funcionario_backup AS SELECT * FROM funcionario;


-- Consultas
SELECT * FROM produto;
SELECT nome_aluno, plano_pag FROM aluno;
SELECT nome_func, funcao FROM funcionario;
SELECT nome_forn, telefone FROM fornecedor;
SELECT plano_pag, valor FROM pag_aluno;

-- select com left join e join
SELECT p.nome_prod, e.quantidade FROM produto p LEFT JOIN estoque e ON p.cod_prod=e.cod_prod;
SELECT p.nome_prod, f.nome_forn, fs.preco FROM fornecimento fs JOIN fornecedor f ON fs.cod_forn=f.cod_forn JOIN produto p ON fs.cod_prod=p.cod_prod;
SELECT a.nome_aluno, pa.valor FROM aluno a JOIN pag_aluno pa ON a.plano_pag=pa.plano_pag;
SELECT f.nome_func, f.funcao FROM funcionario f WHERE f.funcao='ADM';
SELECT f.nome_forn, p.nome_prod FROM fornecimento fs JOIN produto p ON fs.cod_prod=p.cod_prod JOIN fornecedor f ON fs.cod_forn=f.cod_forn;



