<?php
// Inclui a configuração do banco e a sessão
require_once 'config/database.php';
// Inclui o cabeçalho (início do HTML, navbar)
include 'includes/header.php';

// Variáveis para mensagens (sucesso ou erro)
$mensagem_sucesso = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
$mensagem_erro = isset($_GET['erro']) ? htmlspecialchars($_GET['erro']) : '';

// 1. EXIBIÇÃO DE MENSAGENS (Feedback do sistema)
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

// 2. LÓGICA CONDICIONAL: SE LOGADO OU NÃO
if (isset($_SESSION['usuario_logado'])):
    // ====================================================
    // ÁREA RESTRITA: UTILIZADOR LOGADO - EXIBIR AGENDA (READ)
    // ====================================================
    
    // Consulta SQL para buscar todos os contatos
    $sql = "SELECT id, nome, telefone, email, data_nascimento FROM contatos ORDER BY nome ASC";
    $resultado = mysqli_query($conn, $sql);
?>
    <h1 class="mb-4">Lista Completa de Contatos</h1>
    <div class="d-flex justify-content-between mb-3">
        <p class="h5 text-muted">Total de contatos: <strong><?php echo mysqli_num_rows($resultado); ?></strong></p>
        <a href="contatos_cadastrar.php" class="btn btn-success">+ Cadastrar Novo Contato</a>
    </div>

    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Nascimento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($contato = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contato['nome']); ?></td>
                            <td><?php echo htmlspecialchars($contato['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($contato['email']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($contato['data_nascimento'])); ?></td>
                            <td>
                                <a href="contatos_editar.php?id=<?php echo $contato['id']; ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                <a href="processa.php?acao=excluir_contato&id=<?php echo $contato['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja EXCLUIR o contato: <?php echo htmlspecialchars($contato['nome']); ?>?');">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Sua agenda está vazia. Comece cadastrando seu primeiro contato!
        </div>
    <?php endif; ?>

<?php else: ?>
    // =========================================================================
    // ÁREA PÚBLICA: UTILIZADOR DESLOGADO - EXIBIR FORMULÁRIO DE LOGIN
    // =========================================================================
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card card-login shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="mb-0">Acesso à Agenda Pessoal</h2>
                </div>
                <div class="card-body">
                    <p class="text-center text-muted">Credencial de teste: <strong>admin</strong> / <strong>123456</strong></p>
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
                            <button type="submit" class="btn btn-success btn-lg">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif;

// Inclui o rodapé
include 'includes/footer.php';

// Fecha a conexão com o banco
mysqli_close($conn);
?>