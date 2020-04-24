<?php

$link_gs = '';

$bdd = new PDO(
    "mysql:host={$_SERVER['DB_HOST']};dbname={$_SERVER['DB_NAME']};charset=utf8",
    $_SERVER['DB_LOGIN'],
    $_SERVER['DB_PASSWORD'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

function http_not_found() {
    http_response_code(404);
    exit;
}

function http_forbidden() {
    http_response_code(403);
    exit;
}

function l(string $datetime) {
    static $datefmt = NULL;

    if (is_null($datefmt)) {
        $datefmt = new IntlDateFormatter('fr_FR', NULL, NULL, NULL, NULL, 'EEEE dd LLLL HH:mm');
    }

    return $datefmt->format(new DateTime($datetime));
}
