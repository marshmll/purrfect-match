CREATE TABLE users (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(20) NOT NULL,
    date_birth DATE NOT NULL,
    datetime_register DATETIME NOT NULL,
    pass_salt CHAR(32) NOT NULL,
    pass_hash CHAR(64) NOT NULL,
    role VARCHAR(10) NOT NULL,
    contact_email VARCHAR(50),
    contact_phone CHAR(15) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE TABLE cats (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    age SMALLINT NOT NULL,
    sex CHAR(1) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE diseases (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(1024),
    PRIMARY KEY (id)
);

CREATE TABLE personalities (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    description VARCHAR(1024) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE colors (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(10) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE cat_diseases (
    cat_id INTEGER NOT NULL,
    disease_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, disease_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(disease_id) REFERENCES diseases (id)
);

CREATE TABLE cat_colors (
    cat_id INTEGER NOT NULL,
    color_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, color_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(color_id) REFERENCES colors (id)
);

CREATE TABLE cat_personalities (
    cat_id INTEGER NOT NULL,
    personality_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, personality_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(personality_id) REFERENCES personalities (id)
);

CREATE TABLE vaccines (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(1024),
    disease_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY(disease_id) REFERENCES diseases (id)
);

CREATE TABLE color_preferences (
    color_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (color_id, user_id),
    FOREIGN KEY(color_id) REFERENCES colors (id),
    FOREIGN KEY(user_id) REFERENCES users (id)
);

CREATE TABLE personality_preferences (
    personality_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (personality_id, user_id),
    FOREIGN KEY(personality_id) REFERENCES personalities (id),
    FOREIGN KEY(user_id) REFERENCES users (id)
);

CREATE TABLE rescues (
    user_id INTEGER NOT NULL,
    request_datetime DATETIME NOT NULL,
    status VARCHAR(50) NOT NULL,
    closure_datetime SMALLINT,
    description VARCHAR(1024) NOT NULL,
    addr_city VARCHAR(50) NOT NULL,
    addr_state VARCHAR(100) NOT NULL,
    addr_street VARCHAR(100) NOT NULL,
    addr_number INTEGER NOT NULL,
    addr_zipcode CHAR(8) NOT NULL,
    PRIMARY KEY (user_id, request_datetime),
    FOREIGN KEY(user_id) REFERENCES users (id)
);

CREATE TABLE adoptions (
    user_id INTEGER NOT NULL,
    cat_id INTEGER NOT NULL,
    request_datetime DATETIME NOT NULL,
    hand_over_datetime DATETIME,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (user_id, cat_id),
    FOREIGN KEY(user_id) REFERENCES users (id),
    FOREIGN KEY(cat_id) REFERENCES cats (id)
);

CREATE TABLE favorites (
    user_id INTEGER NOT NULL,
    cat_id INTEGER NOT NULL,
    choice_datetime DATETIME NOT NULL,
    PRIMARY KEY (user_id, cat_id, choice_datetime),
    FOREIGN KEY(user_id) REFERENCES users (id),
    FOREIGN KEY(cat_id) REFERENCES cats (id)
);

CREATE TABLE physical_descriptions (
    cat_id INTEGER NOT NULL,
    description VARCHAR(1024) NOT NULL,
    PRIMARY KEY (cat_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id)
);


CREATE TABLE messages (
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    sent_datetime DATETIME NOT NULL,
    content VARCHAR(2000) NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (sender_id, receiver_id, sent_datetime),
    FOREIGN KEY(sender_id) REFERENCES users (id),
    FOREIGN KEY(receiver_id) REFERENCES users (id)
);


CREATE TABLE vaccinations (
    cat_id INTEGER NOT NULL,
    vaccine_id INTEGER NOT NULL,
    dose CHAR(3) NOT NULL,
    appl_date DATE NOT NULL,
    next_date DATE,
    PRIMARY KEY (cat_id, vaccine_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(vaccine_id) REFERENCES vaccines (id)
);

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