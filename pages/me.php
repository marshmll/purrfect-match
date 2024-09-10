<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste</title>
</head>
<body>
    <?php
        require_once("../php/net.php");
        require_once("../php/auth.php");

        if (!hasAuthenticationCookieSet()) {
            header("Location: http://localhost/purrfect-match/pages/login.php");
            die();
        }

        $curl = new CurlFetcher([
            'url' => API_URL . '/user/me/',
            'method' => "GET",
            'opt_array' => [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'accept: application/json',
                    'Authorization: Bearer ' . getAuthenticationCookie()
                ],
            ],
        ]);

        $res = $curl->fetch();

        if ($res != false) {
            echo "
            <main>
                <h2>$res[name]</h2>
                <h2>$res[username]</h2>
                <h2>$res[date_birth]</h2>
                <h2>$res[datetime_register]</h2>
                <h2>$res[role]</h2>
                <h2>$res[contact_email]</h2>
                <h2>$res[contact_phone]</h2>
            </main>";
        }

    ?>
</body>
</html>