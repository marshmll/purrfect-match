CREATE USER 'client'@'%' IDENTIFIED BY 'bancodedados';
GRANT SELECT, UPDATE, DELETE, INSERT ON *.* TO 'client'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

INSERT INTO users (name, username, date_birth, datetime_register, pass_salt, pass_hash, role, contact_email, contact_phone)
VALUES ('Administrador', 'admin', '2024-10-05', '2024-10-06 00:24:42', 'f5c133024a2a1893db022174f5f54eb5', '24b303317717b3339b95c92383add4e797c808ed20f647fec780a543c0734400', 'root', 'admin@admin', '(00) 00000-0000');
