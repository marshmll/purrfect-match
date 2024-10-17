INSERT INTO cats (name, age, sex)
VALUES
    ('Luna', 1, 'F'),
    ('Leo', 2, 'M'),
    ('Charlie', 5, 'M'),
    ('Max', 2, 'M'),
    ('Mia', 1, 'F');

INSERT INTO personalities (name, description)
VALUES
    ('Dócil', 'Um gatinho dócil é aquele que é tranquilo, calmo e fácil de lidar. Ele geralmente gosta de carinho, se adapta bem a diferentes situações e convive bem com outras pessoas e animais. É como um anjo de quatro patas! ❤️'),
    ('Agitado', 'Um gatinho agitado é pura energia! Ele adora brincar, correr, pular e explorar tudo ao redor. É como um furacão de fofura que não para um minuto! ️ Se você busca um companheiro para te tirar do tédio, um gatinho agitado é a escolha perfeita! Mas prepare-se para muitas aventuras e brincadeiras!'),
    ('Curioso', 'Um gatinho curioso é como um pequeno explorador! Ele adora investigar tudo o que está ao seu redor, desde uma simples caixa até os cantos mais altos da casa. Seus olhos grandes e brilhantes transmitem toda a sua curiosidade, e ele está sempre pronto para descobrir algo novo. Prepare-se para ter seus móveis inspecionados, suas plantas cheiradas e até mesmo seus pés investigados! Mas não se preocupe, essa curiosidade é parte do charme desses felinos inteligentes.'),
    ('Independente', 'Um gatinho independente adora ter seu próprio espaço e tempo. Ele é como um pequeno lobo solitário que aprecia sua liberdade. Apesar de gostar da companhia humana, ele não precisa de atenção constante e pode se entreter por conta própria. É um felino autossuficiente que sabe cuidar de si mesmo.');


INSERT INTO cat_personalities (cat_id, personality_id)
VALUES
    (1, 1),
    (1, 2),
    (2, 1),
    (2, 2),
    (3, 4),
    (3, 2),
    (4, 1),
    (5, 2),
    (5, 3);

INSERT INTO vaccines (name, description)
VALUES
    ('V3 (Tríplice Felina)', 'Protege contra rinotraqueíte (herpesvírus felino), calicivírus felino e panleucopenia felina (parvovírus). Recomendado para todos os gatos.'),
    ('V4 (Quádrupla Felina)', 'Além das três doenças cobertas pela V3, protege contra a clamidiose, uma infecção bacteriana que afeta os olhos e o sistema respiratório.'),
    ('Vacina contra a Raiva', 'Protege contra o vírus da raiva, uma doença fatal tanto para gatos quanto para humanos. É obrigatória em várias regiões.'),
    ('Vacina contra a FeLV (Leucemia Felina)', 'Protege contra o vírus da leucemia felina (FeLV), que compromete o sistema imunológico e aumenta o risco de infecções e câncer.'),
    ('Vacina contra a PIF (Peritonite Infecciosa Felina)', 'Protege contra a forma grave da infecção pelo coronavírus felino, que pode causar inflamação grave no abdômen e outras áreas do corpo.');


INSERT INTO diseases (name, description)
VALUES
    ('Rinotraqueíte Felina (Herpesvírus Felino)', 'Causada pelo herpesvírus felino tipo 1 (FHV-1). Afeta o sistema respiratório superior, causando espirros, coriza, febre e conjuntivite. Altamente contagiosa entre gatos.'),
    ('Calicivírus Felino', 'Vírus que causa infecções respiratórias e orais em gatos, com sintomas como úlceras na boca, espirros e pneumonia. Pode ser fatal em filhotes e gatos debilitados.'),
    ('Panleucopenia Felina (Parvovírus Felino)', 'Causada pelo parvovírus felino, afeta o sistema imunológico e digestivo, provocando vômitos, diarreia e desidratação. Altamente contagiosa e letal em filhotes.'),
    ('Clamidiose Felina', 'Infecção bacteriana causada por Chlamydia felis. Afeta principalmente os olhos e o sistema respiratório, causando conjuntivite, espirros e coriza.'),
    ('Raiva', 'Doença viral fatal que afeta o sistema nervoso central. Transmissível por mordida de animais infectados e zoonótica, sendo fatal tanto para gatos quanto para humanos.'),
    ('Leucemia Felina (FeLV)', 'Retrovírus que compromete o sistema imunológico, aumentando o risco de infecções e câncer. Transmitido por contato com fluidos corporais, especialmente saliva.'),
    ('Peritonite Infecciosa Felina (PIF)', 'Causada por mutação do coronavírus felino, pode ser fatal. Afeta gatos jovens ou com sistema imunológico fraco. Formas "seca" e "úmida" são possíveis, com acúmulo de líquido no abdômen ou tórax.');

INSERT INTO vaccine_prevents_disease (vaccine_id, disease_id)
VALUES
    (1, 1),
    (1, 2),
    (1, 3),
    (2, 1),
    (2, 2),
    (2, 3),
    (2, 4),
    (3, 5),
    (4, 6),
    (5, 7);