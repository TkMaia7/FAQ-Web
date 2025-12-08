<?php
// Inclui a configuração do BD e as funções de segurança
require_once 'config/database.php';

// Recebe a ação a ser executada (geralmente via GET na URL)
$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

switch ($acao) {

    // ===========================
    // CRIAÇÃO DE USUARIO
    // ===========================
    case 'criar_admin':
        require_once 'config/install.php';
        $resultado = criar_admin();
        
        if ($resultado === true) {
            header("Location: login.php?msg=Sucesso! Use: admin / 123456");
        } else {
            header("Location: login.php?erro=" . urlencode($resultado));
        }
        break;
        
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
    // CRUD DE FAQs
    // =================
    
    case 'cadastrar_faq':
        verificar_login();
        
        $pergunta = filter_input(INPUT_POST, 'pergunta', FILTER_SANITIZE_STRING);
        $resposta = filter_input(INPUT_POST, 'resposta', FILTER_SANITIZE_STRING);

        // Prepared Statement para INSERT (Proteção contra SQL Injection)
        $stmt = mysqli_prepare($conn, "INSERT INTO faq (pergunta, resposta) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $pergunta, $resposta);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=Nova pergunta cadastrada com sucesso!");
        } else {
            header("Location: faq_cadastrar.php?erro=Erro ao cadastrar: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        break;
    
    case 'editar_faq':
        verificar_login();
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $pergunta = filter_input(INPUT_POST, 'pergunta', FILTER_SANITIZE_STRING);
        $resposta = filter_input(INPUT_POST, 'resposta', FILTER_SANITIZE_STRING);

        if (!$id) {
            header("Location: index.php?erro=ID inválido.");
            exit();
        }

        // Prepared Statement para UPDATE
        $stmt = mysqli_prepare($conn, "UPDATE faq SET pergunta = ?, resposta = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $pergunta, $resposta, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=Pergunta ID: $id atualizada com sucesso!");
        } else {
            header("Location: faq_editar.php?id=$id&erro=Erro ao atualizar: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        break;

    case 'excluir_faq':
        verificar_login();
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header("Location: index.php?erro=ID inválido.");
            exit();
        }

        // Prepared Statement para DELETE
        $stmt = mysqli_prepare($conn, "DELETE FROM faq WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=Pergunta excluída com sucesso!");
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