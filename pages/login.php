<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Purrfect Match</title>
</head>

<body>
    <?php
    require_once('../php/auth.php');

    if (isset($_COOKIE['Authentication'])) {
        header("Location: http://localhost/purrfect-match/");
        die();
    }

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $res = authenticateUser($username, $password);

        if ($res != false) {
            header("Location: http://localhost/purrfect-match/");
            die();
        }
        else {
            echo '<span>Usuário ou senha incorretos.</span>';
        }
    }
    ?>
    <main>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <label for="username">Usuário</label>
            <input type="text" name="username" id="username">
            <br>
            <label for="password">Senha</label>
            <input type="password" name="password" id="password">
            <br>
            <input type="submit" value="Entrar">
        </form>
    </main>
</body>

</html>