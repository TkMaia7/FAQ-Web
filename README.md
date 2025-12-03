#FAQ do FUT

Este projeto é um sistema web desenvolvido em PHP Estruturado para o gerenciamento de um FAQ (Perguntas Frequentes) sobre regras de futebol. O sistema possui uma área pública com visualização em Collapse e uma área administrativa protegida para operações de CRUD.

## 🛠️ Tecnologias Utilizadas

* **PHP** (Procedural)
* **MySQL** (Banco de Dados)
* **Bootstrap 5** (Interface e Responsividade)
* **HTML5 / CSS3**

---

## 🚀 Instalação e Configuração

Siga os passos abaixo para rodar o projeto em seu ambiente local (XAMPP/LAMPP).

### 1. Arquivos
Mova a pasta do projeto para o diretório raiz do seu servidor web:
* **Windows:** `C:\xampp\htdocs\FAQ-Web`
* **Linux:** `/opt/lampp/htdocs/FAQ-Web`

### 2. Banco de Dados
1.  Acesse o **phpMyAdmin** (geralmente em `http://localhost/phpmyadmin`).
2.  Vá na aba **SQL**.
3.  Copie e execute o script em `/db/estrutura.sql` para criar o banco, as tabelas e o usuário administrador:

### 3. Configuração de Conexão (Opcional)
O sistema está configurado para conectar com usuário `root` e senha vazia (padrão XAMPP). Se o seu MySQL tiver senha definida, edite o arquivo:
* `config/database.php`

---

## Roteiro de Testes

Acesse o sistema pelo navegador: `http://localhost/FAQ-Web/` (ou o nome da pasta que você utilizou).

### 1. Teste da Visão Pública (Visitante)
* Ao acessar a página inicial sem estar logado, você verá a lista de perguntas.
* **Teste:** Clique no título de uma pergunta.
* **Resultado Esperado:** A resposta deve deslizar para baixo.
* *Nota:* Não devem aparecer botões de "Editar", "Excluir" ou "Nova Pergunta".

### 2. Acesso Administrativo
* No menu superior, clique em **"Entrar / Login"**.
* Utilize as credenciais padrão:
    * **Usuário:** `admin`
    * **Senha:** `123456`
      
#### Erro de "Credenciais Inválidas"
Caso o login falhe mesmo utilizando a senha correta (problemas comuns de hash ao importar bancos de dados), existe um script para resetar a senha.

1. Acesse no navegador: `http://localhost/FAQ-Web/reset_senha.php`
2. O script forçará a redefinição da senha do usuário **admin** para `123456`.
3. Tente fazer login novamente.

### 3. Teste do CRUD (Administrador)
Após o login, você será redirecionado para a `index.php`, agora em **Modo Tabela**.

* **Cadastrar (Create):**
    1. Clique no botão verde **"+ Nova Pergunta"**.
    2. Preencha a pergunta e a resposta.
    3. Salve e verifique se ela apareceu na tabela.

* **Editar (Update):**
    1. Escolha uma pergunta e clique no botão amarelo **"Editar"**.
    2. Altere o texto da resposta.
    3. Salve e confira a atualização na lista.

* **Excluir (Delete):**
    1. Clique no botão vermelho **"Excluir"**.
    2. Confirme o alerta do navegador.
    3. A pergunta deve sumir da lista.

### 4. Logout
* Clique no botão vermelho **"Sair"** no menu superior.
* O sistema deve retornar à visualização pública , escondendo as opções de administração.

