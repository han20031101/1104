<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// 權限檢查：只有管理員可以進入此頁面
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    include 'header.php';
    ?>
    <div style="margin-top: 120px;"></div>
    <div class="container mt-5">
      <div class="alert alert-danger">只有管理員可以刪除</div>
    </div>
    <?php
    include 'footer.php';
    if (isset($conn)) mysqli_close($conn);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    mysqli_close($conn);
    header('Location: index.php?msg=' . urlencode('參數錯誤'));
    exit;
}

$action = $_GET['action'] ?? '';

// 若 action=confirmed 則執行刪除（使用 prepared statement）
if ($action === 'confirmed') {
    $sql = "DELETE FROM `event` WHERE `id` = ? LIMIT 1";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log('activity_delete prepare error: ' . mysqli_error($conn));
        mysqli_close($conn);
        header('Location: index.php?msg=' . urlencode('伺服器錯誤'));
        exit;
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: index.php?msg=' . urlencode('刪除成功'));
    exit;
}

// 否則顯示刪除確認畫面：先讀取該筆資料
$name = $description = '';
$sql = "SELECT `id`, `name`, `description` FROM `event` WHERE `id` = ? LIMIT 1";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    error_log('activity_delete prepare error: ' . mysqli_error($conn));
    mysqli_close($conn);
    header('Location: index.php?msg=' . urlencode('伺服器錯誤'));
    exit;
}
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $f_id, $f_name, $f_description);
if (mysqli_stmt_fetch($stmt)) {
    $id = $f_id;
    $name = $f_name;
    $description = $f_description;
} else {
    // 找不到資料
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: index.php?msg=' . urlencode('找不到資料'));
    exit;
}
mysqli_stmt_close($stmt);

// 包含頁首後輸出確認畫面（此時已完成權限檢查）
include 'header.php';
?>
<div style="margin-top: 120px;"></div>

<div class="container">
  <h3 class="mb-3">刪除活動確認</h3>
  <table class="table table-bordered table-striped">
    <tr>
      <td>編號</td>
      <td>活動名稱</td>
      <td>活動說明</td>
    </tr>
    <tr>
      <td><?= htmlspecialchars($id) ?></td>
      <td><?= htmlspecialchars($name) ?></td>
      <td><?= nl2br(htmlspecialchars($description)) ?></td>
    </tr>
  </table>

  <div class="mb-3">
    <!-- 使用確認按鈕導到同一頁並帶 action=confirmed（後端仍會再檢查 role） -->
    <a href="activity_delete.php?id=<?= urlencode($id) ?>&action=confirmed" class="btn btn-danger"
       onclick="return confirm('確定要刪除此活動？');">刪除</a>
    <a href="index.php" class="btn btn-secondary">取消</a>
  </div>
</div>

<?php
mysqli_close($conn);
include 'footer.php';
?>