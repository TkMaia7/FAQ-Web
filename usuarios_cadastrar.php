<?php
require_once 'config/database.php';
verificar_admin(); // APENAS ADMIN PODE ACESSAR ESTA PÁGINA
include 'includes/header.php';

// Variáveis para mensagens
$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>

<h1 class="mb-4">Cadastro de Novo Utilizador do Sistema</h1>

<?php if (!empty($mensagem_erro)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form action="processa.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar_usuario">
            
            <div class="mb-3">
                <label for="usuario" class="form-label">Nome de Utilizador (Login):</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required maxlength="50">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required maxlength="100">
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                <small class="form-text text-muted">A senha será criptografada (hash) no banco de dados.</small>
            </div>

            <div class="mb-3">
                <label for="nivel" class="form-label">Nível de Acesso:</label>
                <select class="form-select" id="nivel" name="nivel" required>
                    <option value="Comum">Comum (Apenas gerencia contatos)</option>
                    <option value="Admin">Admin (Gerencia contatos e utilizadores)</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Cadastrar Utilizador</button>
                <a href="usuarios.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>