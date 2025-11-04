<?php
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
header('Location: ' . $redirect);
exit;
