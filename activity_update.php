<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// 權限檢查：只有管理員可以編輯
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    include 'header.php';
    ?>
    <div style="margin-top: 120px;"></div>
    <div class="container mt-5">
      <div class="alert alert-danger">只有管理員可以編輯活動資訊</div>
    </div>
    <?php
    include 'footer.php';
    if (isset($conn)) mysqli_close($conn);
    exit;
}

// 取得 id
$id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
} elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

if ($id <= 0) {
    if (isset($conn)) mysqli_close($conn);
    header('Location: index.php?msg=' . urlencode('參數錯誤'));
    exit;
}

// 處理表單送出（更新）
if (isset($_GET['action']) && $_GET['action'] === 'confirmed') {
    $id = intval($_GET['id'] ?? 0);
    $name= isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if ($id <= 0 || $name === '' || $description === '') {
        $error = '參數或欄位錯誤，請確認後再送出';
    } else {
        $sql = "UPDATE `event` SET `name` = ?, `description` = ? WHERE `id` = ? LIMIT 1";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            error_log('activity_update prepare error: ' . mysqli_error($conn));
            if (isset($stmt)) mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: index.php?msg=' . urlencode('伺服器錯誤'));
            exit;
        }
        mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        if ($ok) {
            header('Location: index.php?msg=' . urlencode('更新成功'));
            exit;
        } else {
            header('Location: index.php?msg=' . urlencode('更新失敗'));
            exit;
        }
    }
}

// 讀取該筆資料以顯示在表單（若先前驗證失敗則保留使用者輸入）
if (!isset($name) || !isset($description) || isset($error)) {
    $sql = "SELECT id, name, description FROM `event` WHERE id = ? LIMIT 1";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log('activity_update select prepare error: ' . mysqli_error($conn));
        if (isset($stmt)) mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: index.php?msg=' . urlencode('伺服器錯誤'));
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $r_id, $r_name, $r_description);
    if (mysqli_stmt_fetch($stmt)) {
        if (!isset($name) || isset($error)) $name = $r_name;
        if (!isset($description) || isset($error)) $description = $r_description;
        $id = $r_id;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: index.php?msg=' . urlencode('找不到資料'));
        exit;
    }
    mysqli_stmt_close($stmt);
}

include 'header.php';
?>

<div style="margin-top: 120px;"></div>

<div class="container">
  <h3 class="mb-3">編輯活動資料</h3>

  <?php if (isset($error) && $error !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="activity_update.php?id=<?= htmlspecialchars($id) ?>&action=confirmed" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <div class="mb-3 row">
      <label for="_name" class="col-sm-2 col-form-label">活動標題</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="_name"
          placeholder="活動標題" value="<?= htmlspecialchars($name) ?>" required>
      </div>
    </div>
    <div class="mb-3">
      <label for="_description" class="form-label">活動內容</label>
      <textarea class="form-control" id="_description" name="description"
        rows="10" required><?= htmlspecialchars($description) ?></textarea>
    </div>
    <input class="btn btn-primary" type="submit" value="送出">
    <a href="index.php" class="btn btn-secondary">取消</a>
  </form>
</div>

<?php
if (isset($conn))
  include 'footer.php';
?>