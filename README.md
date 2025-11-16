# Trabalho Prático: Conexão Nativa vs. Framework ORM

Análise Comparativa de Estratégias de Conexão com Banco de Dados para a disciplina de Banco de Dados II do IFRS - Campus Vacaria.

## 🎯 Objetivo do Projeto

O objetivo deste trabalho é analisar e comparar duas abordagens fundamentais para a conexão e manipulação de um banco de dados a partir do PHP:

1.  [cite_start]**Conexão Nativa ("Crua"):** Utilizando a extensão PDO (PHP Data Objects) para escrever queries SQL manualmente[cite: 19].
2.  [cite_start]**Conexão Abstraída (Framework):** Utilizando o ORM Eloquent do framework Laravel para abstrair o SQL[cite: 27].

Ambos os projetos irão realizar as operações de CRUD (Create, Read, Update, Delete) em um banco de dados comum para permitir uma comparação direta.

## 🛠️ Tecnologias Escolhidas

* [cite_start]**Linguagem:** PHP [cite: 10]
* [cite_start]**SGBD:** SQLite [cite: 10]
* [cite_start]**Abordagem Nativa (Parte 2):** PHP (CLI) + PDO [cite: 21]
* [cite_start]**Abordagem Framework (Parte 3):** Framework Laravel + ORM Eloquent [cite: 29]

## 🗂️ Estrutura do Repositório

Este repositório está dividido em duas pastas principais, representando cada parte do trabalho:

* `/projeto-nativo`: Contém o script `crud.php` interativo que usa PDO.
* `/projeto-laravel`: Contém a aplicação Laravel completa que usa o Eloquent.
* `criar_popular_database.sql`: Script SQL para criar e popular o banco de dados.

---

## 🚀 Instruções de Execução

Para executar e avaliar os projetos, siga os passos abaixo.

### Pré-Requisitos

Antes de começar, garanta que você tenha os seguintes softwares instalados e configurados no `PATH` do seu sistema:

* **PHP** (recomenda-se usar o XAMPP, que já inclui o PHP)
* **SQLite3** (para criar o banco de dados a partir do terminal)
* **Composer** (para o projeto Laravel)

### 1. Criar o Banco de Dados (Comum aos dois projetos)

O primeiro passo é criar o arquivo de banco de dados `db.sqlite` que será usado por ambas as aplicações.

1.  Clone este repositório.
2.  Abra um terminal na pasta raiz do projeto.
3.  [cite_start]Execute o seguinte comando para criar o banco de dados `db.sqlite` e populá-lo usando o script SQL[cite: 16]:

    ```bash
    sqlite3 db.sqlite < criar_popular_database.sql
    ```

    > **Nota:** Você pode usar uma ferramenta visual como o **DB Browser for SQLite** ou a aba "Database" do **PHPStorm** para abrir o arquivo `db.sqlite` e verificar se as tabelas `editoras` e `livros` foram criadas e populadas corretamente.

---

### [cite_start]2. Executar o Projeto Nativo (Parte 2: PDO) [cite: 19]

Esta aplicação é um script de console interativo.

1.  Navegue até a pasta do projeto nativo:

    ```bash
    cd projeto-nativo
    ```

2.  Execute o script `crud.php` usando o PHP:

    ```bash
    php crud.php
    ```

3.  O script irá se conectar ao `db.sqlite` (localizado na pasta raiz) e um menu interativo aparecerá no terminal, permitindo realizar todas as operações de CRUD.

    > **Foco de Análise[cite: 26]:** Note neste código (`crud.php`) a "dor" da abordagem nativa:
    > * A escrita manual de queries SQL (`INSERT INTO...`, `LEFT JOIN...`, etc).
    > * O gerenciamento de "placeholders" (`?`) para segurança.
    > * A manipulação manual de resultados (laços `while` e `fetch`).
    > * O tratamento manual de erros de chave estrangeira (como tentar excluir uma editora que tem livros).

---

### [cite_start]3. Executar o Projeto Framework (Parte 3: Laravel/Eloquent) [cite: 27]

Esta aplicação é um projeto web padrão Laravel.

1.  Navegue até a pasta do projeto Laravel:

    ```bash
    cd projeto-laravel
    ```

2.  Instale as dependências do PHP usando o Composer:

    ```bash
    composer install
    ```

3.  Copie o arquivo de configuração de ambiente:

    ```bash
    cp .env.example .env
    ```

4.  **Importante:** Abra o arquivo `.env` e configure-o para usar o **mesmo** banco de dados SQLite que criamos no Passo 1. O Laravel irá apenas *ler* as tabelas existentes.

    Altere as variáveis `DB_` para que fiquem assim:

    ```ini
    DB_CONNECTION=sqlite
    DB_DATABASE=/caminho/completo/para/o/seu/projeto/db.sqlite
    ```
    *(Substitua pelo caminho absoluto do arquivo `db.sqlite` na sua máquina)*

5.  Inicie o servidor de desenvolvimento do Laravel:

    ```bash
    php artisan serve
    ```

6.  Abra o navegador e acesse `http://localhost:8000` para ver a aplicação em funcionamento.

    > **Foco de Análise[cite: 30, 31]:** Note como o ORM Eloquent abstrai a complexidade:
    > * As queries SQL "desaparecem", sendo substituídas por métodos (`Editora::find(1)`).
    > * Os resultados são retornados como **Objetos** (`$livro->titulo`) e não arrays.
    > * O gerenciamento de relações é automático (ex: `$editora->livros` para buscar todos os livros associados).

## [cite_start]🧑‍💻 Autores [cite: 8]

* Aluno 1 João Vitor do Amaral Pichetti
