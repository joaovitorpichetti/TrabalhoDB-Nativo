-- Criação das Tabelas

-- Tabela Editora (Lado "1" da relação 1:N)
CREATE TABLE editoras (
                          id INTEGER PRIMARY KEY AUTOINCREMENT,
                          nome VARCHAR(255) NOT NULL,
                          cnpj VARCHAR(18) NOT NULL UNIQUE
);

-- Tabela Livro (Lado "N" da relação 1:N)
CREATE TABLE livros (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        titulo VARCHAR(255) NOT NULL,
                        ano INTEGER NOT NULL,

    -- O Autor é apenas um texto, sem tabela própria
                        autor_texto VARCHAR(255),

    -- Chave estrangeira para a relação 1:N com editoras
                        editora_id INTEGER,
                        FOREIGN KEY (editora_id) REFERENCES editoras(id)
);


-- Inserção de Dados de Exemplo

INSERT INTO editoras (nome, cnpj) VALUES
                                      ('Editora Fantástica', '11.222.333/0001-44'),
                                      ('Editora Saber', '55.666.777/0001-88');

INSERT INTO livros (titulo, ano, autor_texto, editora_id) VALUES
                                                              ('A Lenda do Dragão', 2021, 'João Silva', 1),
                                                              ('A História do Mundo', 2022, 'Maria Souza', 2),
                                                              ('Planetas Distantes', 2023, 'Carlos Pera, João Silva', 1);
