<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// 權限檢查：只有管理員 (role === 'M') 可新增
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    include 'header.php';
    ?>
    <div style="margin-top: 120px;"></div>
    <div class="container mt-5">
      <div class="alert alert-danger">只有管理員可以新增求才資訊</div>
    </div>
    <?php
    include 'footer.php';
    if (isset($conn)) mysqli_close($conn);
    exit;
}

$msg = '';

// POST 處理（在包含 header.php 之前，以便可安全使用 header() 轉址）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = trim($_POST['company'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($company === '' || $content === '') {
        $msg = '請填寫所有欄位';
    } else {
        $sql = "INSERT INTO `job` (`company`, `content`, `pdate`) VALUES (?, ?, NOW())";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $company, $content);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            if ($result) {
                mysqli_close($conn);
                header('Location: job.php');
                exit;
            } else {
                $msg = '無法新增資料';
                error_log('job_insert execute error: ' . mysqli_error($conn));
            }
        } else {
            $msg = 'SQL 準備失敗';
            error_log('job_insert prepare error: ' . mysqli_error($conn));
        }
    }
}

include 'header.php';
?>

<!-- 固定導覽列遮擋內容 -->
<div style="margin-top: 120px;"></div>

<div class="container">
<form action="job_insert.php" method="post">
  <div class="mb-3 row">
    <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="company" id="_company" placeholder="公司名稱" required value="<?= htmlspecialchars($_POST['company'] ?? '') ?>">
    </div>
  </div>
  <div class="mb-3">
    <label for="_content" class="form-label">求才內容</label>
    <textarea class="form-control" name="content" id="_content" rows="10" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
  </div>
  <input class="btn btn-primary" type="submit" value="送出">
  <?php if ($msg): ?>
    <div class="mt-3 text-danger"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
</form>
</div>

<?php
if (isset($conn)) mysqli_close($conn);
include('footer.php');
?>