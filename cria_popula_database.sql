-- Parte 1: Criação das Tabelas

-- Tabela Editora (Lado "1" da relação 1:N com Livros)
CREATE TABLE editoras (
                          id INTEGER PRIMARY KEY AUTOINCREMENT,
                          nome VARCHAR(255) NOT NULL,
                          cnpj VARCHAR(18) NOT NULL UNIQUE
);

-- Tabela Autor (Lado "N" da relação N:N com Livros)
CREATE TABLE autores (
                         id INTEGER PRIMARY KEY AUTOINCREMENT,
                         nome VARCHAR(255) NOT NULL,
                         biografia TEXT
);

-- Tabela Livro (Lado "N" da relação 1:N com Editora e Lado "N" da N:N com Autor)
CREATE TABLE livros (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        titulo VARCHAR(255) NOT NULL,
                        ano INTEGER NOT NULL,

    -- Chave estrangeira para a relação 1:N com editoras
                        editora_id INTEGER,
                        FOREIGN KEY (editora_id) REFERENCES editoras(id)
);

-- Tabela Pivô (Juncão) para a relação N:N entre livros e autores
CREATE TABLE autor_livro (
                             autor_id INTEGER,
                             livro_id INTEGER,
                             PRIMARY KEY (autor_id, livro_id),
                             FOREIGN KEY (autor_id) REFERENCES autores(id),
                             FOREIGN KEY (livro_id) REFERENCES livros(id)
);


-- Parte 2: Inserção de Dados de Exemplo

INSERT INTO editoras (nome, cnpj) VALUES
                                      ('Editora Fantástica', '11.222.333/0001-44'),
                                      ('Editora Saber', '55.666.777/0001-88');

INSERT INTO autores (nome, biografia) VALUES
                                          ('João Silva', 'Especialista em fantasia.'),
                                          ('Maria Souza', 'Historiadora renomada.'),
                                          ('Carlos Pera', 'Escritor de ficção científica.');

INSERT INTO livros (titulo, ano, editora_id) VALUES
                                                 ('A Lenda do Dragão', 2021, 1),
                                                 ('A História do Mundo', 2022, 2),
                                                 ('Planetas Distantes', 2023, 1);

-- Associando autores aos livros (N:N)
INSERT INTO autor_livro (autor_id, livro_id) VALUES
                                                 (1, 1), -- João Silva escreveu 'A Lenda do Dragão'
                                                 (2, 2), -- Maria Souza escreveu 'A História do Mundo'
                                                 (3, 3), -- Carlos Pera escreveu 'Planetas Distantes'
                                                 (1, 3); -- João Silva (também) escreveu 'Planetas Distantes'
