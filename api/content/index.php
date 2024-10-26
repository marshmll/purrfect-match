<?php
require_once('../utils/database.php');
require_once('../utils/http_responses.php');

header('Content-Type: application/json');

$cats = Database::query("SELECT name, age, picture_url FROM cats");

sendOKResponse(json_encode($cats));
