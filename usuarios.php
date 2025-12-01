<?php
require_once 'config/database.php';
verificar_admin(); // APENAS ADMIN PODE ACESSAR ESTA PÁGINA
include 'includes/header.php';

// Variáveis para mensagens (sucesso ou erro)
$mensagem_sucesso = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';

// 1. EXIBIÇÃO DE MENSAGENS
if (!empty($mensagem_sucesso)):
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sucesso!</strong> <?php echo $mensagem_sucesso; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
endif;

if (!empty($mensagem_erro)):
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
endif;

// 2. Consulta de utilizadores
$sql = "SELECT id, usuario, email, nivel FROM usuarios ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);
?>

<h1 class="mb-4">Gerenciamento de Utilizadores do Sistema</h1>
<a href="usuarios_cadastrar.php" class="btn btn-primary mb-3">+ Cadastrar Novo Utilizador</a>

<?php if (mysqli_num_rows($resultado) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm">
            <thead class="table-info">
                <tr>
                    <th>ID</th>
                    <th>Utilizador</th>
                    <th>Email</th>
                    <th>Nivel</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <?php
                            if ($usuario['nivel'] == 'Admin') {
                                echo '<span class="badge bg-danger">Admin</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Comum</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                                <a href="processa.php?acao=excluir_usuario&id=<?php echo $usuario['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('ATENÇÃO: Deseja realmente EXCLUIR o utilizador: <?php echo htmlspecialchars($usuario['usuario']); ?>?');">
                                    Excluir
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-outline-secondary" disabled>Você está logado(a)</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        Nenhum utilizador cadastrado.
    </div>
<?php endif; ?>

<?php
include 'includes/footer.php';
mysqli_close($conn);
?>