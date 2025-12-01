<?php
// Inclui a conexão
require_once 'config/database.php';

// Define a senha que você quer usar
$senha_nova = '123456';
// Gera o hash correto e seguro
$senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);

// Atualiza o usuário admin no banco
$usuario = 'admin';
$stmt = mysqli_prepare($conn, "UPDATE usuarios SET senha = ? WHERE usuario = ?");
mysqli_stmt_bind_param($stmt, "ss", $senha_hash, $usuario);

if (mysqli_stmt_execute($stmt)) {
    echo "<h1>Sucesso!</h1>";
    echo "<p>A senha do usuário <strong>admin</strong> foi redefinida para: <strong>123456</strong></p>";
    echo "<p>O novo Hash gerado foi: " . $senha_hash . "</p>";
    echo "<a href='index.php'>Clique aqui para tentar logar novamente</a>";
} else {
    echo "Erro ao atualizar: " . mysqli_error($conn);
}
?>