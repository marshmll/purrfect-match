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