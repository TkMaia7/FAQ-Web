<?php
require_once 'config/database.php';
include 'includes/header.php';

$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
$mensagem_sucesso = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-7">
        
        <?php if (!empty($mensagem_erro)): ?>
            <div class="alert alert-danger mb-3"><?php echo $mensagem_erro; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($mensagem_sucesso)): ?>
            <div class="alert alert-success mb-3"><?php echo $mensagem_sucesso; ?></div>
        <?php endif; ?>

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">Login Administrativo</h3>
            </div>
            <div class="card-body p-4">
                <form action="processa.php?acao=login" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Utilizador:</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Entrar no Sistema</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="mb-2">
                    <a href="index.php" class="text-decoration-none fw-bold">Voltar para o FAQ</a>
                </div>
                
                <small>
                    <a href="processa.php?acao=criar_admin" 
                       class="text-muted link-underline link-underline-opacity-0 link-underline-opacity-100-hover" 
                       style="font-size: 0.8rem;"
                       onclick="return confirm('Deseja configurar o usuário Admin?');">
                       Primeiro acesso? Criar Usuário
                    </a>
                </small>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>