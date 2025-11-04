<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['account'])) {
    $redirect = urlencode($_SERVER["REQUEST_URI"]);
    header("Location: login.php?redirect=$redirect");
    exit;
}
?>
<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>表單測試</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- 固定導覽列遮擋內容 -->
    <div style="margin-top: 120px;"></div>

    <form action="status_proccess.php" method="post">
        姓名: <?= htmlspecialchars($_SESSION['name']) ?>
        <input type="hidden" name="name" value="<?= htmlspecialchars($_SESSION['name']) ?>" /><br/>

        身分：<?= $_SESSION['role'] === 'T' ? '老師' : '學生' ?>
        <input type="hidden" name="status" value="<?= htmlspecialchars($_SESSION['role']) ?>" /><br/>
        <input type="radio" name="dinner" value="no" checked /> 不需要晚餐
        <input type="radio" name="dinner" value="yes" /> 需要晚餐<br/>
        <input type="submit" value="Submit" />
    </form>
</body>
    <?php include("footer.php"); ?>
</html>
