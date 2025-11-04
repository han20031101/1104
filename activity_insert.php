<?php
require_once 'db.php';
$msg = '';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    include 'header.php';
?>
    <div style="margin-top: 120px;"></div>
    <div class="container mt-5">
      <div class="alert alert-danger">只有管理員可以新增活動</div>
    </div>
    <?php
    include 'footer.php';
    if (isset($conn)) mysqli_close($conn);
    exit;
}

// POST 處理放在最上方（header.php 尚未輸出任何內容）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['title'] ?? '');
    $description = trim($_POST['content'] ?? '');

    if ($name === '' || $description === '') {
        $msg = '請填寫所有欄位';
    } else {
        // 調整欄位名稱為資料表實際欄位：此處範例用 `name` 與 `description`
        $sql = "INSERT INTO `event` (`name`, `description`) VALUES (?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 'ss', $name, $description);
            $result = mysqli_stmt_execute($stmt);
            if ($result) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header('Location: index.php');
                exit;
            } else {
                $msg = '執行失敗: ' . mysqli_stmt_error($stmt);
                error_log('activity_insert execute error: ' . $msg);
                mysqli_stmt_close($stmt);
            }
        } else {
            $msg = 'SQL 準備失敗: ' . mysqli_error($conn);
            error_log('activity_insert prepare error: ' . $msg);
        }
    }
}

include 'header.php';
?>

<div style="margin-top: 120px;"></div>

<div class="container" style="max-width:800px;">
  <h3 class="mb-4">新增活動</h3>

  <form action="activity_insert.php" method="post">
    <div class="mb-3">
      <label for="title" class="form-label">活動標題</label>
      <input type="text" class="form-control" id="title" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">活動內容</label>
      <textarea class="form-control" id="content" name="content" rows="8" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">送出</button>
    <?php if ($msg): ?>
      <div class="alert alert-danger mt-3"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
  </form>
</div>

<?php
// 關閉資料庫連線
mysqli_close($conn);
include 'footer.php';
?>