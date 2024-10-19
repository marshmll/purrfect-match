CREATE TABLE IF NOT EXISTS users (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(20) NOT NULL,
    date_birth DATE NOT NULL,
    datetime_register DATETIME NOT NULL DEFAULT(CURRENT_TIMESTAMP),
    pass_salt CHAR(32) NOT NULL,
    pass_hash CHAR(64) NOT NULL,
    role VARCHAR(10) NOT NULL,
    contact_email VARCHAR(50),
    contact_phone CHAR(15) NOT NULL,
    pfp_url VARCHAR(300) NOT NULL DEFAULT('https://i.pinimg.com/1200x/93/ba/87/93ba871d62b0e2b83acadb45803a4d46.jpg'),
    PRIMARY KEY (id),
    UNIQUE (username)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS cats (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    age SMALLINT NOT NULL,
    sex CHAR(1) NOT NULL,
    physical_description TEXT NOT NULL DEFAULT("Não informado."),
    picture_url TEXT NOT NULL DEFAULT('https://i.pinimg.com/736x/7f/16/a2/7f16a2ed1969e8c64b32801f9c48a066.jpg'),
    PRIMARY KEY (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS diseases (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS personalities (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS colors (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(10) NOT NULL,
    PRIMARY KEY (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS cat_diseases (
    cat_id INTEGER NOT NULL,
    disease_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, disease_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id) ON DELETE CASCADE,
    FOREIGN KEY(disease_id) REFERENCES diseases (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS cat_colors (
    cat_id INTEGER NOT NULL,
    color_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, color_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id) ON DELETE CASCADE,
    FOREIGN KEY(color_id) REFERENCES colors (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS cat_personalities (
    cat_id INTEGER NOT NULL,
    personality_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, personality_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id) ON DELETE CASCADE,
    FOREIGN KEY(personality_id) REFERENCES personalities (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS vaccines (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS color_preferences (
    color_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (color_id, user_id),
    FOREIGN KEY(color_id) REFERENCES colors (id) ON DELETE CASCADE,
    FOREIGN KEY(user_id) REFERENCES users (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS personality_preferences (
    personality_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (personality_id, user_id),
    FOREIGN KEY(personality_id) REFERENCES personalities (id) ON DELETE CASCADE,
    FOREIGN KEY(user_id) REFERENCES users (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS vaccine_prevents_disease (
    vaccine_id INTEGER NOT NULL,
    disease_id INTEGER NOT NULL,
    PRIMARY KEY (vaccine_id, disease_id),
    FOREIGN KEY (vaccine_id) REFERENCES vaccines (id) ON DELETE CASCADE,
    FOREIGN KEY (disease_id) REFERENCES diseases (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS rescues (
    user_id INTEGER NOT NULL,
    request_datetime DATETIME NOT NULL DEFAULT(CURRENT_TIMESTAMP),
    status VARCHAR(50) NOT NULL,
    closure_datetime SMALLINT,
    addr_city VARCHAR(50) NOT NULL,
    addr_state VARCHAR(100) NOT NULL,
    addr_street VARCHAR(100) NOT NULL,
    addr_number INTEGER NOT NULL,
    addr_zipcode CHAR(8) NOT NULL,
    cat_caracteristcs TEXT NOT NULL,
    description TEXT,
    PRIMARY KEY (user_id, request_datetime),
    FOREIGN KEY(user_id) REFERENCES users (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS adoptions (
    user_id INTEGER NOT NULL,
    cat_id INTEGER NOT NULL,
    request_datetime DATETIME NOT NULL DEFAULT(CURRENT_TIMESTAMP),
    hand_over_datetime DATETIME,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (user_id, cat_id),
    FOREIGN KEY(user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY(cat_id) REFERENCES cats (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS favorites (
    user_id INTEGER NOT NULL,
    cat_id INTEGER NOT NULL,
    choice_datetime DATETIME NOT NULL DEFAULT(CURRENT_TIMESTAMP),
    PRIMARY KEY (user_id, cat_id),
    FOREIGN KEY(user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY(cat_id) REFERENCES cats (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS messages (
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    sent_datetime DATETIME NOT NULL DEFAULT(CURRENT_TIMESTAMP),
    content TEXT NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (sender_id, receiver_id, sent_datetime),
    FOREIGN KEY(sender_id) REFERENCES users (id),
    FOREIGN KEY(receiver_id) REFERENCES users (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS vaccinations (
    cat_id INTEGER NOT NULL,
    vaccine_id INTEGER NOT NULL,
    dose CHAR(3) NOT NULL DEFAULT("1/1"),
    PRIMARY KEY (cat_id, vaccine_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id) ON DELETE CASCADE,
    FOREIGN KEY(vaccine_id) REFERENCES vaccines (id) ON DELETE CASCADE
) CHARACTER SET utf8mb4;