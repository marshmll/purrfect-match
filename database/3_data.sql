-- Inserções na tabela users
INSERT INTO users (name, username, date_birth, pass_salt, pass_hash, role, contact_email, contact_phone)
VALUES 
('Carlos Silva', 'carlossilva', '1992-10-12', 'salto123', 'hashcarlossilva123', 'supervisor', 'carlos.silva@gmail.com', '11987654321'),
('Ana Pereira', 'anapereira', '1988-06-22', 'saltoana456', 'hashanapereira456', 'regular', 'ana.pereira@yahoo.com', '11965432178'),
('Felipe Souza', 'felipesouza', '1995-03-08', 'saltfelipe789', 'hashfelipe789', 'regular', 'felipe.souza@outlook.com', '21987654321'),
('Mariana Oliveira', 'marianaoliveira', '2000-09-15', 'saltmari123', 'hashmariana123', 'regular', 'mariana.oliveira@gmail.com', '31965432109'),
('Lucas Lima', 'lucaslima', '1985-02-14', 'saltlucas456', 'hashlucas123', 'regular', 'lucas.lima@hotmail.com', '11943210987'),
('Julia Ramos', 'juliaramos', '1993-11-25', 'saltjulia789', 'hashjulia456', 'regular', 'julia.ramos@gmail.com', '31987654321'),
('Thiago Alves', 'thiagoalves', '1991-05-09', 'saltthiago123', 'hashthiago123', 'supervisor', 'thiago.alves@yahoo.com', '21965432109'),
('Bianca Torres', 'biancatorres', '1997-07-12', 'saltbianca456', 'hashbianca456', 'regular', 'bianca.torres@hotmail.com', '32987654321'),
('Ricardo Neves', 'ricardoneves', '1989-09-30', 'saltricky123', 'hashricky789', 'regular', 'ricardo.neves@gmail.com', '41987654321'),
('Camila Santos', 'camilasantos', '1996-12-02', 'saltcamila789', 'hashcamila456', 'regular', 'camila.santos@outlook.com', '51965432109'),
('Paulo Nunes', 'paulonunes', '1984-01-20', 'saltpaulo123', 'hashpaulo123', 'regular', 'paulo.nunes@gmail.com', '61987654321'),
('Helena Costa', 'helenacosta', '1992-06-18', 'salthelena456', 'hashhelena456', 'regular', 'helena.costa@yahoo.com', '71987654321'),
('Vitor Lima', 'vitorlima', '1990-03-11', 'saltvitor123', 'hashvitor789', 'regular', 'vitor.lima@gmail.com', '81987654321'),
('Lara Martins', 'laramartins', '1999-04-04', 'saltlara789', 'hashlara456', 'regular', 'lara.martins@hotmail.com', '91987654321'),
('Rodrigo Borges', 'rodrigoborges', '1994-08-17', 'saltrodrigo456', 'hashrodrigo123', 'supervisor', 'rodrigo.borges@outlook.com', '10198765432');

-- Inserções na tabela cats
INSERT INTO cats (name, age, sex, physical_description, picture_url)
VALUES 
('Bola de Neve', 4, 'M', 'Gato branco com olhos azuis, muito carinhoso.', 'https://www.petz.com.br/blog/wp-content/uploads/2020/04/gato-branco-de-olho-azul-pet-1280x720.jpg'),
('Tigrinho', 2, 'M', 'Pelagem listrada, muito ativo e brincalhão.', 'https://i0.statig.com.br/bancodeimagens/97/36/ck/9736ck4gne6f8cqttnrs866uf.jpg'),
('Luna', 1, 'F', 'Gata preta de pelos curtos, bastante tímida.', 'https://blog-static.petlove.com.br/wp-content/uploads/2022/05/gato-preto-deitado-Petlove.jpg'),
('Mimi', 5, 'F', 'Gata cinza com olhos verdes, muito dócil.', 'https://img.freepik.com/fotos-premium/lindo-gato-cinza-bonito-com-olhos-verdes-o-animal-de-estimacao-esta-na-cama_101881-517.jpg'),
('Tom', 3, 'M', 'Gato laranja, muito curioso.', 'https://www.petz.com.br/blog/wp-content/uploads/2022/09/gato-laranja-topo.jpg'),
('Pipoca', 4, 'F', 'Gata malhada, muito brincalhona.', 'https://blog-static.petlove.com.br/wp-content/uploads/2021/09/gata-tricolor-Petlove.jpg'),
('Fred', 2, 'M', 'Gato cinza com temperamento tranquilo.', 'https://t1.ea.ltmcdn.com/pt/posts/1/7/4/nomes_para_gatos_cinzas_22471_600.jpg'),
('Lili', 3, 'F', 'Gata branca e laranja, muito carinhosa.', 'https://img.freepik.com/fotos-premium/gato-amarelo-e-branco-no-parque_258246-7538.jpg'),
('Tico', 1, 'M', 'Gato preto e branco, extremamente ativo.', 'https://blog-static.petlove.com.br/wp-content/uploads/2021/08/gato-preto-e-branco-deitado-petlove.jpg'),
('Nina', 6, 'F', 'Gata preta de pelos longos, adora dormir.', 'https://www.whiskas.com.br/sites/g/files/fnmzdf2156/files/inline-images/supersticao-gato-preto-da-azar.png'),
('Simba', 4, 'M', 'Gato marrom, muito observador.', 'https://t1.ea.ltmcdn.com/pt/razas/0/1/2/havana_210_0_600.jpg'),
('Maggie', 5, 'F', 'Gata cinza com olhos azuis, muito sociável.', 'https://www.patasdacasa.com.br/sites/default/files/images-carrossel/15666-gato-cinza-olho-azul-do-vira-lata-e-apa-orig-1.webp'),
('Chico', 2, 'M', 'Gato rajado, gosta de brincar ao ar livre.', 'https://i0.statig.com.br/bancodeimagens/bo/9j/c1/bo9jc1o9wns5fw6clokt3oii3.jpg'),
('Fiona', 4, 'F', 'Gata preta com temperamento forte.', 'https://img.freepik.com/fotos-gratis/gato-preto-com-olhos-verdes-descansando-em-uma-grama_181624-30967.jpg'),
('Max', 1, 'M', 'Gato branco de olhos verdes, muito ativo.', 'https://i.pinimg.com/originals/6b/dd/b1/6bddb1a5cb9da51ae92228b2ab888e79.jpg');

-- Inserções na tabela diseases
INSERT INTO diseases (name, description)
VALUES 
('Leucemia Felina', 'Doença viral que afeta o sistema imunológico dos gatos.'),
('Panleucopenia Felina', 'Doença viral grave que causa diarreia severa em gatos.'),
('Rinotraqueíte', 'Infecção viral que afeta o trato respiratório superior dos gatos.'),
('Imunodeficiência Felina', 'Doença que compromete o sistema imunológico dos gatos.'),
('Raiva', 'Doença viral que afeta o sistema nervoso, potencialmente fatal.'),
('Peritonite Infecciosa Felina', 'Doença inflamatória grave que pode ser fatal.'),
('Gripe Felina', 'Infecção viral leve que afeta o trato respiratório superior.'),
('Calicivirose Felina', 'Doença viral que afeta o trato respiratório e cavidade oral.'),
('Dermatite Felina', 'Inflamação da pele que pode ser causada por parasitas.'),
('Cistite Felina', 'Infecção urinária comum em gatos.'),
('Anemia Infecciosa', 'Doença que ataca as células vermelhas do sangue dos gatos.'),
('Toxoplasmose', 'Infecção parasitária comum em gatos.'),
('Diabetes Felino', 'Distúrbio metabólico que afeta os níveis de glicose no sangue.'),
('Obesidade Felina', 'Excesso de peso que pode levar a complicações de saúde.'),
('Hipertensão Felina', 'Pressão arterial elevada em gatos.');

-- Inserções na tabela personalities
INSERT INTO personalities (name, description)
VALUES 
('Brincalhão', 'Gosta de correr e brincar com brinquedos e pessoas.'),
('Preguiçoso', 'Prefere passar o dia dormindo e descansando.'),
('Aventureiro', 'Gosta de explorar o ambiente e se envolve em atividades arriscadas.'),
('Carinhoso', 'Adora receber atenção e carinho dos humanos.'),
('Independente', 'Prefere ficar sozinho e explorar por conta própria.'),
('Protetor', 'Gosta de cuidar dos outros animais ou da casa.'),
('Sociável', 'Adora estar em ambientes com outras pessoas ou animais.'),
('Calmo', 'Gosta de ficar tranquilo e quieto.'),
('Curioso', 'Explora tudo ao redor com muito interesse.'),
('Timido', 'Prefere ficar em ambientes calmos e evitar muita interação.'),
('Territorial', 'Protege seu espaço com muita intensidade.'),
('Alegre', 'Sempre parece feliz e animado com tudo.'),
('Caçador', 'Tem instintos fortes de caça e adora perseguir objetos.'),
('Leal', 'Mantém-se próximo ao dono e segue-o por todos os lados.'),
('Astuto', 'É muito inteligente e resolve problemas rapidamente.');

-- Inserções na tabela colors
INSERT INTO colors (name)
VALUES 
('Preto'), 
('Branco'), 
('Cinza'), 
('Laranja'), 
('Marrom'), 
('Amarelo'), 
('Tigrado'), 
('Malhado'), 
('Bege'), 
('Creme'), 
('Caramelo'), 
('Azul'), 
('Verde'), 
('Rosa'), 
('Prateado');

-- Inserções na tabela cat_diseases
INSERT INTO cat_diseases (cat_id, disease_id)
VALUES 
(1, 1), 
(2, 2), 
(3, 3), 
(4, 4), 
(5, 5), 
(6, 6), 
(7, 7), 
(8, 8), 
(9, 9), 
(10, 10), 
(11, 11), 
(12, 12), 
(13, 13), 
(14, 14), 
(15, 15);

-- Inserções na tabela cat_colors
INSERT INTO cat_colors (cat_id, color_id)
VALUES 
(1, 2), 
(2, 4), 
(3, 1), 
(4, 3), 
(5, 4), 
(6, 7), 
(7, 3), 
(8, 9), 
(9, 1), 
(10, 1), 
(11, 5), 
(12, 3), 
(13, 4), 
(14, 1), 
(15, 2);

-- Inserções na tabela cat_personalities
INSERT INTO cat_personalities (cat_id, personality_id)
VALUES 
(1, 4), 
(2, 1), 
(3, 10), 
(4, 2), 
(5, 9), 
(6, 7), 
(7, 8), 
(8, 4), 
(9, 1), 
(10, 2), 
(11, 3), 
(12, 7), 
(13, 5), 
(14, 6), 
(15, 9);

-- Inserções na tabela vaccines
INSERT INTO vaccines (name, description)
VALUES 
('V3', 'Vacina contra doenças respiratórias e gastrointestinais felinas.'),
('V4', 'Protege contra doenças respiratórias, gastrointestinais e leucemia felina.'),
('Antirrábica', 'Vacina contra a raiva em gatos.'),
('Vacina de Giárdia', 'Protege contra infecções de giárdia em felinos.'),
('V5', 'Vacina completa que protege contra as principais doenças felinas.'),
('Vacina de Leishmaniose', 'Protege contra a leishmaniose felina.'),
('Vacina de Panleucopenia', 'Protege contra a panleucopenia felina.'),
('Vacina de Calicivirose', 'Protege contra a calicivirose felina.'),
('Vacina de Dermatofitose', 'Protege contra dermatofitose em gatos.'),
('Vacina de Imunodeficiência', 'Protege contra a imunodeficiência felina.'),
('Vacina de Peritonite', 'Protege contra a peritonite infecciosa felina.'),
('Vacina de Gripe', 'Protege contra a gripe felina.'),
('Vacina de Toxoplasmose', 'Protege contra a toxoplasmose em felinos.'),
('Vacina de Diabetes', 'Vacina experimental para diabetes em gatos.'),
('Vacina de Cistite', 'Protege contra infecções urinárias em gatos.');

-- Inserções na tabela color_preferences
INSERT INTO color_preferences (color_id, user_id)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- Inserções na tabela personality_preferences
INSERT INTO personality_preferences (personality_id, user_id)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- Inserções na tabela vaccine_prevents_disease
INSERT INTO vaccine_prevents_disease (vaccine_id, disease_id)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- Inserções na tabela rescues
INSERT INTO rescues (user_id, request_datetime, status, closure_datetime, addr_city, addr_state, addr_street, addr_number, addr_zipcode, characteristics, description) VALUES
(1, '2024-10-01 08:30:00', 'Pendente', NULL, 'São Paulo', 'SP', 'Avenida Paulista', 1000, '01310-100', 'Gato, grande, pelagem preta', 'Gato encontrado perambulando na avenida.'),
(2, '2024-10-02 09:00:00', 'Em andamento', NULL, 'Rio de Janeiro', 'RJ', 'Rua da Praia', 200, '22050-001', 'Gato, pequeno, olhos verdes', 'Gato preso em árvore.'),
(3, '2024-10-03 10:15:00', 'Fechado', '2024-10-05 15:00:00', 'Belo Horizonte', 'MG', 'Rua dos Inconfidentes', 150, '30130-000', 'Gato, médio, pelagem marrom', 'Gato resgatado e entregue a abrigo.'),
(4, '2024-10-04 11:45:00', 'Pendente', NULL, 'Curitiba', 'PR', 'Avenida Sete de Setembro', 500, '80010-000', 'Gato, pelagem cinza', 'Gato encontrado na calçada.'),
(5, '2024-10-05 14:00:00', 'Em andamento', NULL, 'Salvador', 'BA', 'Rua do Passeio', 300, '40060-000', 'Gato, pequeno, muito agitado', 'Gato solto em área movimentada.'),
(6, '2024-10-06 16:30:00', 'Pendente', NULL, 'Fortaleza', 'CE', 'Avenida Beira Mar', 750, '60165-000', 'Gato, filhote, pelagem branca', 'Filhote encontrado na praia.'),
(7, '2024-10-07 17:00:00', 'Fechado', '2024-10-08 12:00:00', 'Porto Alegre', 'RS', 'Rua dos Andradas', 1234, '90010-000', 'Gato, pelagem tigrada', 'Gato resgatado e adotado.'),
(8, '2024-10-08 18:15:00', 'Pendente', NULL, 'Manaus', 'AM', 'Rua 10 de Julho', 80, '69000-000', 'Gato, grande, muito amigável', 'Gato encontrado no centro da cidade.'),
(9, '2024-10-09 19:30:00', 'Em andamento', NULL, 'Brasília', 'DF', 'Setor Comercial Sul', 150, '70070-000', 'Gato, pelagem preta com manchas brancas', 'Gato perdido em prédio comercial.'),
(10, '2024-10-10 20:45:00', 'Pendente', NULL, 'Recife', 'PE', 'Avenida Boa Viagem', 400, '51011-000', 'Gato, médio, pelagem dourada', 'Gato avistado na praia.'); 

-- Inserções na tabela adoptions
INSERT INTO adoptions (user_id, cat_id, request_datetime, hand_over_datetime, status)
VALUES
(2, 2, '2023-08-12 15:45:00', '2023-08-15 10:00:00', 'concluded'),
(3, 3, '2023-07-08 10:30:00', '2023-07-10 12:00:00', 'concluded');

-- Inserções na tabela favorites
INSERT INTO favorites (user_id, cat_id, choice_datetime)
VALUES
(2, 2, '2023-09-15 12:00:00'),
(3, 3, '2023-08-20 11:45:00'),
(4, 4, '2023-07-12 10:30:00'),
(5, 5, '2023-06-25 09:00:00'),
(6, 6, '2023-05-18 13:15:00'),
(7, 7, '2023-04-10 08:00:00'),
(8, 8, '2023-03-05 16:30:00'),
(9, 9, '2023-02-27 14:30:00'),
(10, 10, '2023-01-15 09:00:00'),
(11, 11, '2022-12-05 11:30:00'),
(12, 12, '2022-11-21 15:00:00'),
(13, 13, '2022-10-11 13:00:00'),
(14, 14, '2022-09-03 10:45:00'),
(15, 15, '2022-08-19 17:30:00');
