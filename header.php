
<?php
$current = basename($_SERVER['PHP_SELF']);
function nav_active($file) {
    global $current;
    return $current === $file ? ' active' : '';
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活動報名系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand" href="index.php">活動報名系統</a>
      <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" 
              type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
              aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav nav-underline me-auto">
          <li class="nav-item">
              <a class="nav-link<?=nav_active('index.php')?>" aria-current="index.php" href="index.php">首頁</a>
          </li>
          <li class="nav-item">
              <a class="nav-link<?=nav_active('status.php')?>" href="status.php">迎新茶會</a>
          </li>
          <li class="nav-item">
              <a class="nav-link<?=nav_active('conference.php')?>" href="conference.php">資管一日營</a>
          </li>
          <li class="nav-item">
              <a class="nav-link<?=nav_active('job.php')?>" href="job.php">求才資訊</a>
          </li>
          <li class="nav-item">
              <a class="nav-link<?=nav_active('profile_update.php')?>" href="profile_update.php">個人資料</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <?php
            if (session_status() === PHP_SESSION_NONE) session_start();
            $redirect = urlencode($_SERVER["REQUEST_URI"]);
            if (isset($_SESSION['account'])) {
            ?>
              <a class="btn btn-outline-light" href="logout.php?redirect=<?= $redirect ?>">登出</a>
            <?php } else { ?>
              <a class="btn btn-outline-light" href="login.php?redirect=<?= $redirect ?>">登入</a>
            <?php } ?>
          </li>
        </ul>
      </div>
    </div>
</nav>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        

