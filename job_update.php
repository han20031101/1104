<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// 權限檢查：只有管理員可以編輯
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    include 'header.php';
    ?>
    <div style="margin-top: 120px;"></div>
    <div class="container mt-5">
      <div class="alert alert-danger">只有管理員可以編輯求才資訊</div>
    </div>
    <?php
    include 'footer.php';
    if (isset($conn)) mysqli_close($conn);
    exit;
}

// 取得 postid（POST 優先，然後 GET）
$postid = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postid'])) {
    $postid = intval($_POST['postid']);
} elseif (isset($_GET['postid'])) {
    $postid = intval($_GET['postid']);
}

if ($postid <= 0) {
    if (isset($conn)) mysqli_close($conn);
    header('Location: job.php?msg=' . urlencode('參數錯誤'));
    exit;
}

// 處理表單送出（更新）
if (isset($_GET['action']) && $_GET['action'] === 'confirmed') {
    // 只接受 POST 的欄位，但 postid 從 GET 來（與你提供的範例一致）
    $postid = intval($_GET['postid'] ?? 0);
    $company = isset($_POST['company']) ? trim($_POST['company']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if ($postid <= 0 || $company === '' || $content === '') {
        // 欄位或參數錯誤，顯示錯誤訊息並回到表單
        $error = '參數或欄位錯誤，請確認後再送出';
    } else {
        $sql = "UPDATE `job` SET `company` = ?, `content` = ? WHERE `postid` = ? LIMIT 1";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            error_log('job_update prepare error: ' . mysqli_error($conn));
            if (isset($stmt)) mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: job.php?msg=' . urlencode('伺服器錯誤'));
            exit;
        }
        mysqli_stmt_bind_param($stmt, "ssi", $company, $content, $postid);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        if ($ok) {
            header('Location: job.php?msg=' . urlencode('更新成功'));
            exit;
        } else {
            header('Location: job.php?msg=' . urlencode('更新失敗'));
            exit;
        }
    }
}

// 讀取該筆資料以顯示在表單（如果是 POST 且欄位驗證失敗，使用剛剛提交的值）
if (!isset($company) || !isset($content) || isset($error)) {
    $sql = "SELECT postid, company, content, pdate FROM `job` WHERE postid = ? LIMIT 1";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log('job_update select prepare error: ' . mysqli_error($conn));
        if (isset($stmt)) mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: job.php?msg=' . urlencode('伺服器錯誤'));
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $postid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $r_postid, $r_company, $r_content, $r_pdate);
    if (mysqli_stmt_fetch($stmt)) {
        // 如果剛剛 POST 驗證失敗，保持使用者輸入的值（$company/$content），否則使用 DB 值
        if (!isset($company) || isset($error)) $company = $r_company;
        if (!isset($content) || isset($error)) $content = $r_content;
        $pdate = $r_pdate;
        $postid = $r_postid;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: job.php?msg=' . urlencode('找不到資料'));
        exit;
    }
    mysqli_stmt_close($stmt);
}

include 'header.php';
?>

<!-- 固定導覽列遮擋內容 -->
<div style="margin-top: 120px;"></div>

<div class="container">
  <h3 class="mb-3">編輯求才資料</h3>

  <?php if (isset($error) && $error !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="job_update.php?postid=<?= htmlspecialchars($postid) ?>&action=confirmed" method="post">
    <input type="hidden" name="postid" value="<?= htmlspecialchars($postid) ?>">
    <div class="mb-3 row">
      <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="company" id="_company"
          placeholder="公司名稱" value="<?= htmlspecialchars($company) ?>" required>
      </div>
    </div>
    <div class="mb-3">
      <label for="_content" class="form-label">求才內容</label>
      <textarea class="form-control" id="_content" name="content"
        rows="10" required><?= htmlspecialchars($content) ?></textarea>
    </div>
    <input class="btn btn-primary" type="submit" value="送出">
    <a href="job.php" class="btn btn-secondary">取消</a>
  </form>
</div>

<?php
if (isset($conn)) mysqli_close($conn);
include 'footer.php';
?>