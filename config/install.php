<?php
function criar_admin(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "faq_db";

    // Conexão inicial
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        return "Falha na conexão com MySQL: " . $conn->connect_error;
    }

    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->select_db($dbname);

    // Cria Admin 
    $check = $conn->query("SELECT * FROM usuarios WHERE usuario = 'admin'");
    if ($check->num_rows == 0) {
        $senha_admin = '123456';
        $senha_hash = password_hash($senha_admin, PASSWORD_DEFAULT);
        $conn->query("INSERT INTO usuarios (usuario, email, senha, nivel) VALUES ('admin', 'admin@faq.com.br', '$senha_hash', 'Admin')");
    }

    return true;
}
?>