<?php
require_once 'config/database.php';
verificar_login(); // Protege a página

// 1. Validação e obtenção do ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php?erro=ID de contato inválido para edição.");
    exit();
}

// 2. Busca dos dados do contato usando Prepared Statement
$stmt = mysqli_prepare($conn, "SELECT nome, telefone, email, data_nascimento, endereco FROM contatos WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    header("Location: index.php?erro=Contato não encontrado.");
    exit();
}

$contato = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

include 'includes/header.php';

$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';
?>

<h1 class="mb-4">Editar Contato: <?php echo htmlspecialchars($contato['nome']); ?></h1>

<?php if (!empty($mensagem_erro)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body">
        <form action="processa.php" method="POST">
            <input type="hidden" name="acao" value="editar_contato">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome Completo:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($contato['nome']); ?>" required maxlength="100">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($contato['telefone']); ?>" maxlength="20">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="data_nascimento" class="form-label">Data de Nasc.:</label>
                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($contato['data_nascimento']); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($contato['email']); ?>" maxlength="100">
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Morada (Opcional):</label>
                <textarea class="form-control" id="endereco" name="endereco" rows="3"><?php echo htmlspecialchars($contato['endereco']); ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-warning btn-lg">Atualizar Contato</button>
                <a href="index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
mysqli_close($conn);
?>