<?php
// Inclui a configuração do BD e as funções de segurança
require_once 'config/database.php';

// Recebe a ação a ser executada (geralmente via GET na URL)
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

switch ($acao) {
    // =========================================
    // AÇÕES DE AUTENTICAÇÃO (LOGIN / LOGOUT)
    // =========================================
    case 'login':
        // Filtra e limpa as entradas para segurança
        $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

        // Prepara a consulta para buscar o utilizador
        $stmt = mysqli_prepare($conn, "SELECT id, usuario, senha, nivel FROM usuarios WHERE usuario = ?");
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) == 1) {
            $linha = mysqli_fetch_assoc($resultado);
            // Verifica a senha fornecida contra o hash salvo (ESSENCIAL!)
            if (password_verify($senha, $linha['senha'])) {
                // Login bem-sucedido: Armazena dados essenciais na sessão
                $_SESSION['usuario_logado'] = $linha['usuario'];
                $_SESSION['usuario_nivel'] = $linha['nivel'];
                $_SESSION['usuario_id'] = $linha['id'];
                header("Location: index.php?msg=Bem-vindo(a), " . $linha['usuario'] . "! Login realizado com sucesso.");
                exit();
            }
        }
        // Falha no login
        header("Location: index.php?erro=Credenciais inválidas. Verifique utilizador e senha.");
        mysqli_stmt_close($stmt);
        break;

    case 'logout':
        session_unset(); // Limpa todas as variáveis de sessão
        session_destroy(); // Destrói a sessão
        header("Location: index.php?msg=Você foi desconectado(a) do sistema.");
        break;

    // =================
    // CRUD DE CONTATOS
    // =================
    case 'cadastrar_contato':
        verificar_login();
        
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $data_nascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_SANITIZE_STRING);
        $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);

        // Prepared Statement para INSERT (Proteção contra SQL Injection)
        $stmt = mysqli_prepare($conn, "INSERT INTO contatos (nome, telefone, email, data_nascimento, endereco) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $nome, $telefone, $email, $data_nascimento, $endereco);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=Contato '$nome' cadastrado com sucesso!");
        } else {
            header("Location: contatos_cadastrar.php?erro=Erro ao cadastrar: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        break;

    case 'editar_contato':
        verificar_login();
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $data_nascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_SANITIZE_STRING);
        $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);

        if (!$id) {
            header("Location: index.php?erro=ID de contato inválido.");
            exit();
        }

        // Prepared Statement para UPDATE
        $stmt = mysqli_prepare($conn, "UPDATE contatos SET nome = ?, telefone = ?, email = ?, data_nascimento = ?, endereco = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssssi", $nome, $telefone, $email, $data_nascimento, $endereco, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=Contato ID: $id atualizado com sucesso!");
        } else {
            header("Location: contatos_editar.php?id=$id&erro=Erro ao atualizar: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        break;

    case 'excluir_contato':
        verificar_login();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: index.php?erro=ID de contato inválido.");
            exit();
        }

        // Prepared Statement para DELETE
        $stmt = mysqli_prepare($conn, "DELETE FROM contatos WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=Contato excluído com sucesso!");
        } else {
            header("Location: index.php?erro=Erro ao excluir: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        break;

    // =======================================================
    // CRUD DE UTILIZADORES (Apenas para Administradores)
    // =======================================================
    case 'cadastrar_usuario':
        verificar_admin();
        
        $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha_original = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
        $nivel = filter_input(INPUT_POST, 'nivel', FILTER_SANITIZE_STRING);

        // Criptografa a senha antes de salvar (MANDATÓRIO)
        $senha_hash = password_hash($senha_original, PASSWORD_DEFAULT);

        // Prepared Statement para INSERT
        $stmt = mysqli_prepare($conn, "INSERT INTO usuarios (usuario, email, senha, nivel) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $usuario, $email, $senha_hash, $nivel);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: usuarios.php?msg=Utilizador '$usuario' cadastrado com sucesso!");
        } else {
            // Trata erro de duplicidade de utilizador (código 1062 no MySQL)
            $erro_msg = (mysqli_errno($conn) == 1062) ? "Utilizador já existe no banco de dados." : mysqli_error($conn);
            header("Location: usuarios_cadastrar.php?erro=Erro ao cadastrar: " . $erro_msg);
        }
        mysqli_stmt_close($stmt);
        break;

    case 'excluir_usuario':
        verificar_admin();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Não permite excluir o próprio utilizador logado
        if ($id === $_SESSION['usuario_id']) {
            header("Location: usuarios.php?erro=Você não pode excluir o seu próprio utilizador enquanto logado.");
            exit();
        }

        if (!$id) {
            header("Location: usuarios.php?erro=ID de utilizador inválido.");
            exit();
        }

        // Prepared Statement para DELETE
        $stmt = mysqli_prepare($conn, "DELETE FROM usuarios WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: usuarios.php?msg=Utilizador excluído com sucesso!");
        } else {
            header("Location: usuarios.php?erro=Erro ao excluir: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        break;

    default:
        // Ação desconhecida
        header("Location: index.php?erro=Ação inválida ou não especificada.");
        break;
}

mysqli_close($conn);
exit();
?>