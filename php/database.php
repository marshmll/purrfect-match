<?php 
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    const hostname = "localhost";
    const username = "client";
    const password = "bancodedados";
    const database = "purrfect_db";

    $mysqli = new mysqli(hostname, username, password, database);
?>