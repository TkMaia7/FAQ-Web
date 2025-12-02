<?php
require_once 'config/database.php';
verificar_login(); // Protege a página: só acessa se estiver logado
include 'includes/header.php';

$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>

<h1 class="mb-4">Cadastrar Nova Pergunta (FAQ)</h1>

<?php if (!empty($mensagem_erro)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form action="processa.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar_faq">
            
            <div class="mb-3">
                <label for="pergunta" class="form-label">Pergunta:</label>
                <textarea class="form-control" id="pergunta" name="pergunta" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="resposta" class="form-label">Resposta:</label>
                <textarea class="form-control" id="resposta" name="resposta" rows="5" required></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success btn-lg">Salvar Pergunta</button>
                <a href="index.php" class="btn btn-secondary btn-lg">Voltar</a>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>