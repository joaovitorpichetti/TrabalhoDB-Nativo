# Trabalho Prático: Parte 2 - Conexão Nativa com PHP e PDO

Este repositório contém **apenas a Parte 2 (Conexão Nativa)** do trabalho prático sobre "Estratégias de Conexão com Banco de Dados", da disciplina de Banco de Dados II do IFRS - Campus Vacaria.

## 🎯 Objetivo deste Projeto

O objetivo deste código é demonstrar a abordagem "crua" (nativa) para a conexão e manipulação de um banco de dados, utilizando apenas a extensão PDO padrão do PHP, sem o auxílio de um framework ou ORM.

[cite_start]O foco é demonstrar os "pontos de dor" desta abordagem, conforme solicitado na atividade[cite: 26]:
* A escrita manual de queries SQL (`INSERT INTO...`, `LEFT JOIN...`, etc.).
* O gerenciamento manual da conexão (`new PDO`, `$pdo = null`).
* A manipulação manual dos resultados (laços `while` e `fetch`).
* O tratamento manual de erros e regras de negócio (como checar `rowCount()` ou capturar erros de chave estrangeira).

## 🗂️ Estrutura do Banco de Dados

O projeto utiliza uma estrutura de banco de dados simples com duas tabelas e um relacionamento **Um-para-Muitos (1:N)**:

* **`editoras` (Tabela 1):** Contém `id`, `nome`, `cnpj`.
* **`livros` (Tabela 2):** Contém `id`, `titulo`, `ano`, `autor_texto` e `editora_id` (a chave estrangeira).

## 🛠️ Tecnologias Utilizadas

* **Linguagem:** PHP (executado via CLI - Interface de Linha de Comando)
* **SGBD:** SQLite
* **Biblioteca de Conexão:** PDO (PHP Data Objects)

---

## 🚀 Instruções de Execução

Este projeto é um script de console interativo.

### Pré-Requisitos

Antes de começar, garanta que você tenha os seguintes softwares instalados e configurados no `PATH` do seu sistema:

* **PHP** (recomenda-se usar o XAMPP, que já inclui o PHP)
* **SQLite3** (para criar o banco de dados a partir do terminal)

### 1. Criar o Banco de Dados

O primeiro passo é criar o arquivo de banco de dados `db.sqlite`.

1.  Clone este repositório.
2.  Abra um terminal na pasta raiz do projeto (onde estão os arquivos `crud.php` e `criar_popular_database.sql`).
3.  Execute o seguinte comando para criar o banco de dados `db.sqlite` e populá-lo:

    ```bash
    sqlite3 db.sqlite < criar_popular_database.sql
    ```
    > **Nota:** Você pode usar uma ferramenta visual como o **DB Browser for SQLite** ou a aba "Database" do **PHPStorm** para abrir o arquivo `db.sqlite` e verificar se as tabelas `editoras` e `livros` foram criadas e populadas corretamente.

### 2. Executar o Script Nativo

Com o banco de dados criado, você pode executar o projeto.

1.  Certifique-se de estar no terminal, na mesma pasta.
2.  Execute o script `crud.php` usando o PHP:

    ```bash
    php crud.php
    ```

3.  O script irá se conectar ao `db.sqlite` e um menu interativo aparecerá no terminal, permitindo realizar todas as operações de CRUD.

## 🧑‍💻 Autores

* Aluno 1: João Vitor do Amaral Pichetti
* Aluno 2: Marco Antonio Zamboni Acosta
* Aluno 3: Nícolas Bitencourt Boeira
