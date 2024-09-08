<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Purrfect Match</title>
</head>
<body>
    <main>
        <form action="../php/auth.php" method="POST">
            <label for="username">Usuário</label>
            <input type="text" name="username" id="username">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password">
            <input type="submit" value="Entrar">
        </form>
    </main>
</body>
</html>