<?php
require_once 'config/database.php';
verificar_login(); 

// 1. Validação e obtenção do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php?erro=ID inválido para edição.");
    exit();
}

// 2. Busca dos dados do faq usando Prepared Statement
$stmt = mysqli_prepare($conn, "SELECT pergunta, resposta FROM faq WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    header("Location: index.php?erro=Pergunta não encontrada.");
    exit();
}

$faq = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

include 'includes/header.php';
$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>

<h1 class="mb-4">Editar Pergunta (FAQ)</h1>

<?php if (!empty($mensagem_erro)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form action="processa.php" method="POST">
            <input type="hidden" name="acao" value="editar_faq">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="mb-3">
                <label for="pergunta" class="form-label">Pergunta:</label>
                <textarea class="form-control" id="pergunta" name="pergunta" rows="3" required><?php echo htmlspecialchars($faq['pergunta']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="resposta" class="form-label">Resposta:</label>
                <textarea class="form-control" id="resposta" name="resposta" rows="5" required><?php echo htmlspecialchars($faq['resposta']); ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-warning btn-lg">Atualizar Pergunta</button>
                <a href="index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
mysqli_close($conn);
?>