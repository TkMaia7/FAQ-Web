<?php
// Inclui a configuração do banco e a sessão
require_once 'config/database.php';
// Inclui o cabeçalho (início do HTML, navbar)
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

// Consulta SQL
$sql = "SELECT id, pergunta, resposta FROM faq ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <h1>Perguntas Frequentes (FAQ)</h1>
    
    <?php if (isset($_SESSION['usuario_logado'])): ?>
        <a href="faq_cadastrar.php" class="btn btn-success">+ Nova Pergunta</a>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['usuario_logado'])): ?>

    <div class="alert alert-dark mb-3">
        <small>Modo Administrador</small>
    </div>

    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 30%">Pergunta</th>
                        <th style="width: 45%">Resposta</th>
                        <th style="width: 20%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($faq = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo $faq['id']; ?></td>
                            <td><?php echo htmlspecialchars($faq['pergunta']); ?></td>
                            <td><?php echo htmlspecialchars($faq['resposta']); ?></td>
                            <td>
                                <a href="faq_editar.php?id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                <a href="processa.php?acao=excluir_faq&id=<?php echo $faq['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja EXCLUIR esta pergunta?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Nenhuma pergunta cadastrada.</div>
    <?php endif; ?>

<?php else: ?>

    <div class="mt-4">
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            
            <?php $contador = 1; ?>

            <div class="list-group list-group-flush">
                <?php while ($faq = mysqli_fetch_assoc($resultado)): ?>
                    <div class="list-group-item py-4 bg-transparent border-bottom">
                        <h4 class="mb-3 text-primary fw-bold">
                            <?php 
                                echo $contador . ". " . htmlspecialchars($faq['pergunta']); 
                            ?>
                        </h4>
                        
                        <p class="text-secondary mb-0" style="font-size: 1.1rem; line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($faq['resposta'])); ?>
                        </p>
                    </div>

                    <?php $contador++; ?>
                    
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <div class="alert alert-light text-center py-5 shadow-sm">
                <h3>Ainda não temos perguntas sobre Regras do Futebol.</h3>
                <p class="text-muted">Volte em breve!</p>
            </div>
        <?php endif; ?>
    </div>

<?php endif;?>

<?php
include 'includes/footer.php';
mysqli_close($conn);
?>