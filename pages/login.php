<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/styles/reset.css">
    <link rel="stylesheet" href="../public/assets/styles/pages/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <title>Login | Purrfect Match</title>
</head>

<body>
    <?php
    require_once('../php/auth.php');

    $valid = false;

    if (hasAuthenticationCookieSet()) {
        header("Location: http://localhost/purrfect-match/pages/me.php");
        echo $_COOKIE['token'];
        clearAuthenticationCookie();
        die();
    }

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $res = authenticateUser($username, $password);

        if ($res != false) {

            $lifetime = 0;

            if ($_POST['keep_connected'] == 'on') {
                $lifetime = time() + 60 * 60 * 24 * 30;
            }

            $valid = true;
            setAuthenticationCookie($res['access_token'], $lifetime);

            header("Location: http://localhost/purrfect-match/pages/me.php");
            die();
        }
    }
    ?>
    <main class="main">
        <div class="login">
            <h2 class="login__title">Seja bem-vindo de volta!</h2>
            <?php
            if (!$valid &&  isset($_POST['username']) && isset($_POST['password'])) {
                echo '<span class="login__invalid">Usuário ou senha incorretos.</span>';
            }
            ?>
            <form class="form" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="text" name="username" class="login__input" id="username" placeholder="Usuário" title="Usuário" required>
                <label for="username" class="login__label">Usuário</label>
                <input type="password" name="password" class="login__input" id="password" placeholder="Senha" title="Senha" required>
                <label for="password" class="login__label">Senha</label>
                <div class="login__bottom">
                    <div>
                        <input type="checkbox" name="keep_connected" id="keep_connected" checked>
                        <label for="keep_connected">Mantenha-me conectado</label>
                    </div>
                    <a class="login__forgot" href="./login.php">Esqueceu a senha?</a>
                </div>
                <br>
                <input type="submit" class="login__submit" value="ENTRAR">
            </form>
            <a class="register" href="./register.php">Não possui uma conta? Registre-se!</a>
        </div>
    </main>
</body>

</html>