<?php
/**
 *
 * Este script demonstra a Parte 2 (Nativa) com uma estrutura
 * de banco de dados mais simples.
 *
 * TABELAS:
 * - editoras (id, nome, cnpj)
 * - livros (id, titulo, ano, autor_texto, editora_id)
 *
 * RELAÇÃO:
 * - 1 Editora TEM N Livros.
 */

echo "=============================================\n";
echo "== INÍCIO DO SCRIPT DE CRUD NATIVO (PDO) ==\n";
echo "=============================================\n\n";

// --- CONEXÃO COM O BANCO ---
try {
    $db_path = __DIR__ . '/db.sqlite';
    $pdo = new PDO('sqlite:' . $db_path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ESSENCIAL: Habilitar o uso de chaves estrangeiras
    $pdo->exec('PRAGMA foreign_keys = ON;');

    echo "[SUCESSO] Conexão com SQLite estabelecida.\n\n";
} catch (PDOException $e) {
    die("[ERRO] Não foi possível conectar: " . $e->getMessage() . "\n");
}


// --- LOOP PRINCIPAL DO MENU ---
while (true) {

    // Menu simplificado.
    echo "====================================\n";
    echo "==         MENU INTERATIVO        ==\n";
    echo "====================================\n";
    echo "--- Editoras ---\n";
    echo "  1. CREATE Editora\n";
    echo "  2. READ   Editoras (Listar)\n";
    echo "  3. UPDATE Editora\n";
    echo "  4. DELETE Editora\n";
    echo "\n--- Livros ---\n";
    echo "  5. CREATE Livro\n";
    echo "  6. READ   Livros (Listar)\n";
    echo "  7. UPDATE Livro\n";
    echo "  8. DELETE Livro\n";
    echo "\n------------------------------------\n";
    echo "  9. Sair\n";
    echo "====================================\n";
    echo "Opção: ";

    $escolha = trim(fgets(STDIN));

    switch ($escolha) {

        // --- 1. CREATE Editora ---
        case '1':
            echo "\n--- 1. CREATE (Nova Editora) ---\n";
            echo "Digite o nome da editora: ";
            $nome = trim(fgets(STDIN));
            echo "Digite o CNPJ da editora: ";
            $cnpj = trim(fgets(STDIN));

            try {
                $sql = "INSERT INTO editoras (nome, cnpj) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $cnpj]);
                $novoId = $pdo->lastInsertId();
                echo "\n[SUCESSO] Editora '{$nome}' criada com ID: {$novoId}.\n\n";
            } catch (PDOException $e) {
                // Vai falhar se o CNPJ for duplicado (UNIQUE)
                echo "\n[ERRO AO CRIAR] " . $e->getMessage() . "\n\n";
            }
            break;

        // --- 2. READ Editoras ---
        case '2':
            echo "\n--- 2. READ (Listar Editoras) ---\n";

            $stmt = $pdo->query("SELECT id, nome, cnpj FROM editoras");
            echo "Listando todas as editoras:\n";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "-----------------------------------\n";
                echo "  [ID: {$row['id']}] {$row['nome']} (CNPJ: {$row['cnpj']})\n";

                // PONTO DE "DOR" (N+1 Query):
                // Para CADA editora, fazemos uma NOVA consulta
                // para encontrar seus livros.
                echo "    Livros desta editora:\n";
                $sqlLivros = "SELECT titulo FROM livros WHERE editora_id = ?";
                $stmtLivros = $pdo->prepare($sqlLivros);
                $stmtLivros->execute([$row['id']]);

                $livrosEncontrados = 0;
                while ($rowLivro = $stmtLivros->fetch(PDO::FETCH_ASSOC)) {
                    echo "      - {$rowLivro['titulo']}\n";
                    $livrosEncontrados++;
                }
                if ($livrosEncontrados == 0) {
                    echo "      (Nenhum livro associado)\n";
                }
            }
            echo "-----------------------------------\n\n";
            break;

        // --- 3. UPDATE Editora ---
        case '3':
            echo "\n--- 3. UPDATE (Atualizar Editora) ---\n";
            echo "Digite o ID da editora que deseja atualizar: ";
            $id = trim(fgets(STDIN));
            echo "Digite o NOVO nome da editora: ";
            $novoNome = trim(fgets(STDIN));
            echo "Digite o NOVO CNPJ da editora: ";
            $novoCnpj = trim(fgets(STDIN));

            try {
                $sql = "UPDATE editoras SET nome = ?, cnpj = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$novoNome, $novoCnpj, $id]);

                if ($stmt->rowCount() > 0) {
                    echo "\n[SUCESSO] Editora ID {$id} atualizada.\n\n";
                } else {
                    echo "\n[AVISO] Editora ID {$id} não foi encontrada. Nenhuma alteração feita.\n\n";
                }
            } catch (PDOException $e) {
                echo "\n[ERRO AO ATUALIZAR] " . $e->getMessage() . "\n\n";
            }
            break;

        // --- 4. DELETE Editora ---
        case '4':
            echo "\n--- 4. DELETE (Excluir Editora) ---\n";
            echo "Digite o ID da editora que deseja excluir: ";
            $id = trim(fgets(STDIN));

            // PONTO DE "DOR": Gerenciamento Manual de Chave Estrangeira
            // Não podemos simplesmente excluir. E se ela tiver livros?
            // O 'PRAGMA foreign_keys = ON;' vai travar a exclusão.
            // Vamos usar um try...catch para pegar essa "trava".
            try {
                $sql = "DELETE FROM editoras WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id]);

                if ($stmt->rowCount() > 0) {
                    echo "\n[SUCESSO] Editora ID {$id} foi excluída.\n\n";
                } else {
                    echo "\n[AVISO] Editora ID {$id} não foi encontrada.\n\n";
                }

            } catch (PDOException $e) {
                // Este é o erro que o SQLite/PDO retorna
                // quando uma 'FOREIGN KEY constraint' falha.
                if (str_contains($e->getMessage(), 'FOREIGN KEY constraint failed')) {
                    echo "\n[ERRO AO EXCLUIR] Você não pode excluir a Editora ID {$id}.\n";
                    echo "Ela ainda possui livros associados. Exclua os livros primeiro.\n\n";
                } else {
                    // Outro erro qualquer
                    echo "\n[ERRO AO EXCLUIR] " . $e->getMessage() . "\n\n";
                }
            }
            break;

        // --- 5. CREATE Livro ---
        case '5':
            echo "\n--- 5. CREATE (Novo Livro) ---\n";
            echo "Digite o título do livro: ";
            $titulo = trim(fgets(STDIN));
            echo "Digite o ano do livro: ";
            $ano = trim(fgets(STDIN));

            // SIMPLIFICADO: Apenas pedimos o autor como texto
            echo "Digite o nome do Autor (como texto): ";
            $autor_texto = trim(fgets(STDIN));

            // PONTO DE "DOR": Gerenciamento Manual de Chave Estrangeira
            // Ainda precisamos listar as editoras para o usuário saber o ID.
            echo "\nEditoras disponíveis:\n";
            $stmtEditoras = $pdo->query("SELECT id, nome FROM editoras");
            while ($row = $stmtEditoras->fetch(PDO::FETCH_ASSOC)) {
                echo "  - [ID: {$row['id']}] {$row['nome']}\n";
            }
            echo "Digite o ID da Editora para este livro: ";
            $editora_id = trim(fgets(STDIN));

            try {
                // Query simplificada, agora inclui 'autor_texto'
                $sql = "INSERT INTO livros (titulo, ano, autor_texto, editora_id) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$titulo, $ano, $autor_texto, $editora_id]);
                $novoId = $pdo->lastInsertId();
                echo "\n[SUCESSO] Livro '{$titulo}' criado com ID: {$novoId}.\n\n";
            } catch (PDOException $e) {
                echo "\n[ERRO AO CRIAR] (Verifique se o ID da Editora existe): " . $e->getMessage() . "\n\n";
            }
            break;

        // --- 6. READ Livros ---
        case '6':
            echo "\n--- 6. READ (Listar Livros) ---\n";

            // PONTO DE "DOR": SQL com JOIN
            // Ainda precisamos fazer o JOIN com editoras
            $sql = "SELECT livros.id, livros.titulo, livros.ano, livros.autor_texto, editoras.nome AS editora_nome
                    FROM livros
                    LEFT JOIN editoras ON livros.editora_id = editoras.id";
            $stmt = $pdo->query($sql);

            echo "Listando todos os livros:\n";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "-----------------------------------\n";
                $editora = $row['editora_nome'] ?? 'Sem Editora';
                $autor = $row['autor_texto'] ?? 'Autor Desconhecido';

                echo "  [ID: {$row['id']}] {$row['titulo']} ({$row['ano']})\n";
                echo "    Autor: {$autor}\n";
                echo "    Editora: {$editora}\n";
            }
            echo "-----------------------------------\n\n";
            break;

        // --- 7. UPDATE Livro ---
        case '7':
            echo "\n--- 7. UPDATE (Atualizar Livro) ---\n";
            echo "Digite o ID do livro que deseja atualizar: ";
            $id = trim(fgets(STDIN));
            echo "Digite o NOVO título do livro: ";
            $novoTitulo = trim(fgets(STDIN));
            echo "Digite o NOVO ano do livro: ";
            $novoAno = trim(fgets(STDIN));

            // Apenas atualizamos o texto do autor
            echo "Digite o NOVO nome do Autor (texto): ";
            $novoAutorTexto = trim(fgets(STDIN));

            echo "\nEditoras disponíveis:\n";
            $stmtEditoras = $pdo->query("SELECT id, nome FROM editoras");
            while ($row = $stmtEditoras->fetch(PDO::FETCH_ASSOC)) {
                echo "  - [ID: {$row['id']}] {$row['nome']}\n";
            }
            echo "Digite o NOVO ID da Editora: ";
            $novoEditoraId = trim(fgets(STDIN));

            try {
                $sql = "UPDATE livros SET titulo = ?, ano = ?, autor_texto = ?, editora_id = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$novoTitulo, $novoAno, $novoAutorTexto, $novoEditoraId, $id]);

                if ($stmt->rowCount() > 0) {
                    echo "\n[SUCESSO] Livro ID {$id} atualizado.\n\n";
                } else {
                    echo "\n[AVISO] Livro ID {$id} não foi encontrado. Nenhuma alteração feita.\n\n";
                }
            } catch (PDOException $e) {
                echo "\n[ERRO AO ATUALIZAR] (Verifique se o ID da Editora existe): " . $e->getMessage() . "\n\n";
            }
            break;

        // --- 8. DELETE Livro ---
        case '8':
            echo "\n--- 8. DELETE (Excluir Livro) ---\n";
            echo "Digite o ID do livro que deseja excluir: ";
            $id = trim(fgets(STDIN));

            try {
                $sql = "DELETE FROM livros WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id]);

                if ($stmt->rowCount() > 0) {
                    echo "\n[SUCESSO] Livro ID {$id} foi excluído.\n\n";
                } else {
                    echo "\n[AVISO] Livro ID {$id} não foi encontrado.\n\n";
                }

            } catch (PDOException $e) {
                echo "\n[ERRO AO EXCLUIR] " . $e->getMessage() . "\n\n";
            }
            break;

        // --- 9. SAIR ---
        case '9':
            echo "\nSaindo...\n";
            break 2; // Quebra o 'switch' E o 'while (true)'

        // --- OPÇÃO INVÁLIDA ---
        default:
            echo "\n[ERRO] Opção '{$escolha}' inválida. Por favor, tente novamente.\n\n";
            break;
    }
}

// --- FIM DO SCRIPT ---
$pdo = null;
echo "Conexão fechada.\n";

echo "=============================================\n";
echo "== FIM DO SCRIPT ==\n";
echo "=============================================\n";
?>


