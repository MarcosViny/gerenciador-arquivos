<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// BASE_URL
// define("BASE_URL", "http://".$_SERVER['SERVER_NAME'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
// define("BASE_URL", "http://".$_SERVER['SERVER_NAME'] . pathinfo(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), PATHINFO_DIRNAME) . "/");
define("BASE_URL", "http://localhost/cursos/gerenciador-arquivos/");

// Inicia sessão
session_start();

require_once(__DIR__ . '/controller/Funcoes.php');

if(isset($_GET['modulo'])) {
    switch ($_GET['modulo']) {
        case 'arquivos':
            require_once(__DIR__ . '/controller/arquivos/index.php');
            break;
    }
} else {
    require_once(__DIR__ . '/home.php');
}