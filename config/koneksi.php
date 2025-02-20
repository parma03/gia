<?php
// Konfigurasi dasar

//define('BASE_URL', 'https://da40-140-213-125-90.ngrok-free.app/imam/');

define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/imam/');
define('BASE_PATH', dirname(dirname(__FILE__)) . '/');

// Helper functions untuk URL dan Path
function url($path = '')
{
    return BASE_URL . ltrim($path, '/');
}

function asset($path = '')
{
    return BASE_URL . 'assets/' . ltrim($path, '/');
}

function view($path = '')
{
    return BASE_PATH . 'views/' . ltrim($path, '/');
}

// Helper untuk redirect
function redirect($path = '')
{
    header('Location: ' . url($path));
    exit();
}

// Helper untuk cek aktif menu
function isMenuActive($current_page, $pages)
{
    return in_array($current_page, array_map('basename', $pages));
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "db_gia";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>