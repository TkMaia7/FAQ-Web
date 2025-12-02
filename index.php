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

// ====================================================
// ÁREA PÚBLICA E RESTRITA (MISTA)
// ====================================================

// Consulta SQL para buscar todas as perguntas da nova tabela (Visível para todos)
$sql = "SELECT id, pergunta, resposta, data_criacao FROM faq ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <h1>Perguntas Frequentes (FAQ)</h1>
    
    <?php if (isset($_SESSION['usuario_logado'])): ?>
        <a href="faq_cadastrar.php" class="btn btn-success">+ Nova Pergunta</a>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-between mb-3">
    <p class="h5 text-muted">Total de perguntas encontradas: <strong><?php echo mysqli_num_rows($resultado); ?></strong></p>
</div>

<?php if (mysqli_num_rows($resultado) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 30%">Pergunta</th>
                    <th style="width: 45%">Resposta</th>
                    
                    <?php if (isset($_SESSION['usuario_logado'])): ?>
                        <th style="width: 20%">Ações (Admin)</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($faq = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $faq['id']; ?></td>
                        <td><?php echo htmlspecialchars($faq['pergunta']); ?></td>
                        <td><?php echo htmlspecialchars($faq['resposta']); ?></td>
                        
                        <?php if (isset($_SESSION['usuario_logado'])): ?>
                            <td>
                                <a href="faq_editar.php?id=<?php echo $faq['id']; ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                
                                <a href="processa.php?acao=excluir_faq&id=<?php echo $faq['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja EXCLUIR esta pergunta?');">
                                   Excluir
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info py-4 text-center">
        <h4>Ainda não há perguntas cadastradas.</h4>
        <p>Volte em breve para consultar nosso FAQ.</p>
    </div>
<?php endif;

// Inclui o rodapé
include 'includes/footer.php';

// Fecha a conexão com o banco
mysqli_close($conn);
?>