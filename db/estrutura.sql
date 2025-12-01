CREATE DATABASE IF NOT EXISTS agenda_db;

USE agenda_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100),
    senha VARCHAR(255) NOT NULL, -- Armazena a senha criptografada (hash)
    nivel ENUM('Admin', 'Comum') NOT NULL DEFAULT 'Comum'
);

CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100),
    data_nascimento DATE,
    endereco TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir um utilizador Administrador padrão: 'admin' com senha '123456'
-- O hash é gerado com password_hash('123456', PASSWORD_DEFAULT)
INSERT INTO usuarios (usuario, email, senha, nivel) VALUES
('admin', 'admin@agenda.com.br', '$2y$10$YiBpez0RPaoPnQM60H8hT0J/jeh0g7k4NFGMpABJj5Hto0lcHC9G.', 'Admin');
