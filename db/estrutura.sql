CREATE DATABASE IF NOT EXISTS faq_db;
USE faq_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100),
    senha VARCHAR(255) NOT NULL, -- Hash da senha
    nivel ENUM('Admin', 'Comum') NOT NULL DEFAULT 'Comum'
);

INSERT INTO usuarios (usuario, email, senha, nivel) VALUES
('admin', 'admin@faq.com.br', '$2y$10$YiBpez0RPaoPnQM60H8hT0J/jeh0g7k4NFGMpABJj5Hto0lcHC9G.', 'Admin');

CREATE TABLE IF NOT EXISTS faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pergunta TEXT NOT NULL,
    resposta TEXT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO faq (pergunta, resposta) VALUES 
('O que configura o impedimento?', 'Um jogador está em posição de impedimento se estiver mais próximo da linha de meta adversária do que a bola e o penúltimo adversário no momento em que a bola é tocada por um companheiro de equipe.'),

('Quando o VAR pode ser acionado?', 'O Árbitro de Vídeo (VAR) só pode intervir em quatro situações de "erro claro e óbvio": validação de gols (se houve falta ou impedimento), marcação de pênaltis, cartões vermelhos diretos e confusão de identidade de jogadores.'),

('É possível fazer um gol direto de arremesso lateral?', 'Não. Segundo a regra, não é válido um gol marcado diretamente de um arremesso lateral. Se a bola entrar no gol adversário sem tocar em ninguém, é marcado tiro de meta; se entrar no próprio gol, é escanteio.'),

('Quantas substituições são permitidas em uma partida oficial?', 'Atualmente, a regra geral da FIFA permite até 5 substituições por equipe, que devem ser realizadas em no máximo três paradas durante o jogo (o intervalo não conta como parada).'),

('Qual a diferença entre tiro livre direto e indireto?', 'No tiro livre direto, o gol pode ser validado se a bola entrar direto na meta. No tiro livre indireto (geralmente marcado por jogo perigoso ou recuo para o goleiro), a bola precisa tocar em outro jogador antes de entrar no gol.'),

('O goleiro pode pegar a bola com a mão se receber um recuo?', 'Não se o recuo for feito intencionalmente com os pés por um companheiro de equipe. Se o recuo for de cabeça, peito ou coxa, o goleiro pode usar as mãos. Se usar as mãos num recuo com o pé, é marcado tiro livre indireto.'),

('Qual a duração regulamentar de uma partida?', 'Uma partida oficial consiste em dois tempos de 45 minutos cada, totalizando 90 minutos, mais os acréscimos determinados pelo árbitro para compensar o tempo perdido.'),

('O que acontece se um jogador receber dois cartões amarelos?', 'Se um jogador receber o segundo cartão amarelo na mesma partida, ele receberá automaticamente um cartão vermelho e será expulso do campo, não podendo ser substituído.'),

('Qual a distância oficial da marca do pênalti?', 'A marca da penalidade máxima fica localizada a exatos 11 metros (ou 12 jardas) do centro da linha do gol, equidistante das traves.'),

('Qual o número mínimo de jogadores para uma partida acontecer?', 'Uma partida não pode começar ou continuar se qualquer uma das equipes tiver menos de 7 jogadores em campo (incluindo o goleiro).');
