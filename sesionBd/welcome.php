<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

echo "¡Bienvenido, " . $_SESSION['username'] . "!";
?>
