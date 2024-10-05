ALTER DATABASE purrfect_db CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS users (
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
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS cats (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    age SMALLINT NOT NULL,
    sex CHAR(1) NOT NULL,
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
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(disease_id) REFERENCES diseases (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS cat_colors (
    cat_id INTEGER NOT NULL,
    color_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, color_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(color_id) REFERENCES colors (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS cat_personalities (
    cat_id INTEGER NOT NULL,
    personality_id INTEGER NOT NULL,
    PRIMARY KEY (cat_id, personality_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(personality_id) REFERENCES personalities (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS vaccines (
    id INTEGER NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    disease_id INTEGER NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY(disease_id) REFERENCES diseases (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS color_preferences (
    color_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (color_id, user_id),
    FOREIGN KEY(color_id) REFERENCES colors (id),
    FOREIGN KEY(user_id) REFERENCES users (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS personality_preferences (
    personality_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (personality_id, user_id),
    FOREIGN KEY(personality_id) REFERENCES personalities (id),
    FOREIGN KEY(user_id) REFERENCES users (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS rescues (
    user_id INTEGER NOT NULL,
    request_datetime DATETIME NOT NULL,
    status VARCHAR(50) NOT NULL,
    closure_datetime SMALLINT,
    description TEXT NOT NULL,
    addr_city VARCHAR(50) NOT NULL,
    addr_state VARCHAR(100) NOT NULL,
    addr_street VARCHAR(100) NOT NULL,
    addr_number INTEGER NOT NULL,
    addr_zipcode CHAR(8) NOT NULL,
    PRIMARY KEY (user_id, request_datetime),
    FOREIGN KEY(user_id) REFERENCES users (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS adoptions (
    user_id INTEGER NOT NULL,
    cat_id INTEGER NOT NULL,
    request_datetime DATETIME NOT NULL,
    hand_over_datetime DATETIME,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (user_id, cat_id),
    FOREIGN KEY(user_id) REFERENCES users (id),
    FOREIGN KEY(cat_id) REFERENCES cats (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS favorites (
    user_id INTEGER NOT NULL,
    cat_id INTEGER NOT NULL,
    choice_datetime DATETIME NOT NULL,
    PRIMARY KEY (user_id, cat_id),
    FOREIGN KEY(user_id) REFERENCES users (id),
    FOREIGN KEY(cat_id) REFERENCES cats (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS physical_descriptions (
    cat_id INTEGER NOT NULL,
    description VARCHAR(1024) NOT NULL,
    PRIMARY KEY (cat_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS messages (
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    sent_datetime DATETIME NOT NULL,
    content VARCHAR(2000) NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (sender_id, receiver_id, sent_datetime),
    FOREIGN KEY(sender_id) REFERENCES users (id),
    FOREIGN KEY(receiver_id) REFERENCES users (id)
) CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS vaccinations (
    cat_id INTEGER NOT NULL,
    vaccine_id INTEGER NOT NULL,
    dose CHAR(3) NOT NULL,
    appl_date DATE NOT NULL,
    next_date DATE,
    PRIMARY KEY (cat_id, vaccine_id),
    FOREIGN KEY(cat_id) REFERENCES cats (id),
    FOREIGN KEY(vaccine_id) REFERENCES vaccines (id)
) CHARACTER SET utf8mb4;