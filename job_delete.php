<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// 權限檢查：只有管理員可以執行刪除或查看刪除確認頁面
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    include 'header.php';
    ?>
    <div style="margin-top: 120px;"></div>
    <div class="container mt-5">
      <div class="alert alert-danger">只有管理員可以刪除求才資訊</div>
    </div>
    <?php
    include 'footer.php';
    if (isset($conn)) mysqli_close($conn);
    exit;
}

$postid = 0;
$company = "";
$content = "";
$pdate = "";

// 取得 id 並驗證
if (isset($_GET['postid'])) {
    $postid = intval($_GET['postid']);
}

if ($postid <= 0) {
    if (isset($conn)) mysqli_close($conn);
    header('Location: job.php?msg=' . urlencode('參數錯誤'));
    exit;
}

$action = $_GET['action'] ?? '';

// 若 action=confirmed 則執行刪除（使用 prepared statement）
if ($action === 'confirmed') {
    $sql = "DELETE FROM `job` WHERE `postid` = ? LIMIT 1";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log('job_delete prepare error: ' . mysqli_error($conn));
        if (isset($stmt)) mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: job.php?msg=' . urlencode('伺服器錯誤'));
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $postid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: job.php?msg=' . urlencode('刪除成功'));
    exit;
}

// 否則顯示刪除確認畫面：讀取該筆資料
$sql = "SELECT postid, company, content, pdate FROM `job` WHERE postid = ? LIMIT 1";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    error_log('job_delete prepare error: ' . mysqli_error($conn));
    if (isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: job.php?msg=' . urlencode('伺服器錯誤'));
    exit;
}
mysqli_stmt_bind_param($stmt, "i", $postid);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $r_postid, $r_company, $r_content, $r_pdate);
if (mysqli_stmt_fetch($stmt)) {
    $postid = $r_postid;
    $company = $r_company;
    $content = $r_content;
    $pdate = $r_pdate;
} else {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: job.php?msg=' . urlencode('找不到資料'));
    exit;
}
mysqli_stmt_close($stmt);

include 'header.php';
?>

<!-- 固定導覽列遮擋內容 -->
<div style="margin-top: 120px;"></div>

<div class="container">
  <h3 class="mb-3">刪除求才確認</h3>
  <table class="table table-bordered table-striped">
    <tr>
      <td>編號</td>
      <td>求才廠商</td>
      <td>求才內容</td>
      <td>刊登日期</td>
    </tr>
    <tr>
      <td><?= htmlspecialchars($postid) ?></td>
      <td><?= htmlspecialchars($company) ?></td>
      <td><?= nl2br(htmlspecialchars($content)) ?></td>
      <td><?= htmlspecialchars($pdate) ?></td>
    </tr>
  </table>

  <a href="job_delete.php?postid=<?= urlencode($postid) ?>&action=confirmed" class="btn btn-danger"
     onclick="return confirm('確定要刪除此筆求才資料？');">刪除</a>
  <a href="job.php" class="btn btn-secondary">取消</a>
</div>

<?php
if (isset($conn)) mysqli_close($conn);
include 'footer.php';
?>