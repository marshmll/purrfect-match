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
    ('Agitado', 'Um gatinho agitado é pura energia! Ele adora brincar, correr, pular e explorar tudo ao redor. É como um furacão de fofura que não para um minuto! ️ Se você busca um companheiro para te tirar do tédio, um gatinho agitado é a escolha perfeita! Mas prepare-se para muitas aventuras e brincadeiras!');

INSERT INTO cat_personalities (cat_id, personality_id)
VALUES
    (1, 1),
    (1, 2),
    (2, 1),
    (2, 2);