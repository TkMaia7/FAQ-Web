<?php
require_once 'config/database.php';
verificar_login(); // Protege a página: só acessa se estiver logado
include 'includes/header.php';

$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>

<h1 class="mb-4">Cadastrar Novo Contato</h1>

<?php if (!empty($mensagem_erro)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form action="processa.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar_contato">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome Completo:</label>
                    <input type="text" class="form-control" id="nome" name="nome" required maxlength="100">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" maxlength="20">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nasc.:</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" maxlength="100">
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Morada (Opcional):</label>
                <textarea class="form-control" id="endereco" name="endereco" rows="3"></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success btn-lg">Salvar Contato</button>
                <a href="index.php" class="btn btn-secondary btn-lg">Voltar</a>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>