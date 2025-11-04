<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$account = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$redirect = $_POST['redirect'] ?? 'index.php';


if ($account === '' || $password === '') {
    header('Location: login.php?msg=' . urlencode('請輸入帳號與密碼') . '&redirect=' . urlencode($redirect));
    exit;
}

// 使用 mysqli_real_escape_string() 避免 SQL injection
$account = mysqli_real_escape_string($conn, $account);
$password = mysqli_real_escape_string($conn, $password);


$sql = "SELECT * FROM user WHERE account = '$account' AND password = '$password' LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['account'] = $row['account'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['role'] = $row['role'];

    mysqli_free_result($result);
    mysqli_close($conn);
    header('Location: ' . $redirect);
    exit;
} else {
    var_dump($result);
    die;
    if ($result) mysqli_free_result($result);
    mysqli_close($conn);
    header('Location: login.php?msg=' . urlencode('帳號或密碼錯誤') . '&redirect=' . urlencode($redirect));
    exit;
}