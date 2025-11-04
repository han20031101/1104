<?php
require_once 'db.php';

// 確保 Session 啟動
if (session_status() === PHP_SESSION_NONE) session_start();

// 權限檢查：使用者必須登入
if (!isset($_SESSION['account'])) {
    header('Location: login.php?msg=' . urlencode('請先登入'));
    exit;
}

$current_account = $_SESSION['account'];
$error = '';
$message = '';
$name = ''; // 用來儲存並顯示目前的姓名

// --- 載入使用者資料 ---
$sql_select = "SELECT `name`, `password` FROM `user` WHERE `account` = ? LIMIT 1";
$stmt_select = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt_select, $sql_select)) {
    error_log('Profile select prepare error: ' . mysqli_error($conn));
    $error = '伺服器錯誤：無法載入資料';
} else {
    mysqli_stmt_bind_param($stmt_select, "s", $current_account);
    mysqli_stmt_execute($stmt_select);
    mysqli_stmt_bind_result($stmt_select, $r_name, $r_password_hash); // 這裡 $r_password_hash 是資料庫中的密碼
    if (mysqli_stmt_fetch($stmt_select)) {
        $name = $r_name;
    } else {
        $error = '找不到使用者資料';
        mysqli_stmt_close($stmt_select);
        mysqli_close($conn);
        header('Location: logout.php');
        exit;
    }
    mysqli_stmt_close($stmt_select);
}

// --- 處理表單送出（更新）---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 取得並驗證輸入
    $new_name = trim($_POST['name'] ?? '');
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $update_name = false;
    $update_password = false;
    $has_error = false;
    
    // a. 驗證姓名
    if ($new_name === '') {
        $error = '姓名不能為空';
        $has_error = true;
    } elseif ($new_name !== $name) {
        $update_name = true; // 姓名有變動
        $name = $new_name; // 立即更新 $name 讓表單顯示新值
    }

    // b. 驗證密碼（只有在新密碼欄位不為空時才執行密碼檢查）
    if (!$has_error && $new_password !== '') {
        $update_password = true; 

        if ($old_password === '') {
            $error = '請輸入舊密碼進行驗證';
            $has_error = true;
        } elseif ($old_password !== $r_password_hash) { 
            $error = '舊密碼輸入錯誤';
            $has_error = true;
        }elseif ($new_password !== $confirm_password) {
            $error = '新密碼與確認密碼不一致';
            $has_error = true;
        } 
    }

    // 2. 執行更新
    if (!$has_error && ($update_name || $update_password)) {
        $fields = [];
        $bind_types = '';
        $bind_values = [];

        if ($update_name) {
            $fields[] = '`name` = ?';
            $bind_types .= 's';
            $bind_values[] = $name;
        }

        if ($update_password) {
            $fields[] = '`password` = ?';
            $bind_types .= 's';
            $bind_values[] = $new_password;
        }
        
        $sql_update = "UPDATE `user` SET " . implode(', ', $fields) . " WHERE `account` = ? LIMIT 1";
        $bind_types .= 's';
        $bind_values[] = $current_account;
        
        $stmt_update = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt_update, $sql_update)) {
            error_log('Profile update prepare error: ' . mysqli_error($conn));
            $error = '伺服器錯誤：更新失敗';
        } else {
            // 動態綁定參數
            mysqli_stmt_bind_param($stmt_update, $bind_types, ...$bind_values);
            
            if (mysqli_stmt_execute($stmt_update)) {
                $message = '資料更新成功！';
                $_SESSION['name'] = $name; // 更新 Session 中的姓名
            } else {
                $error = '資料更新失敗，請稍後再試';
            }
            mysqli_stmt_close($stmt_update);
        }
    }
}

// 關閉連線並顯示頁面
if (isset($conn)) mysqli_close($conn);

include 'header.php'; 

?>

<div style="margin-top: 120px;"></div>

<div class="container">
  <h3 class="mb-3">個人資料</h3>

  <?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif ($message !== ''): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form action="profile_update.php" method="post">
    <div class="mb-3 row">
      <label for="_account" class="col-sm-2 col-form-label">帳號</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="_account" value="<?= htmlspecialchars($current_account) ?>">
      </div>
    </div>
    
    <div class="mb-3 row">
      <label for="_name" class="col-sm-2 col-form-label">姓名</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="_name"
          placeholder="姓名" value="<?= htmlspecialchars($name) ?>" required>
      </div>
    </div>

    <hr>
    
    <h4 class="mb-3">修改密碼</h4>

    <div class="mb-3 row">
      <label for="_old_password" class="col-sm-2 col-form-label">舊密碼</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="old_password" id="_old_password" placeholder="請輸入舊密碼">
      </div>
    </div>

    <div class="mb-3 row">
      <label for="_new_password" class="col-sm-2 col-form-label">新密碼</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="new_password" id="_new_password" placeholder="請輸入新密碼">
      </div>
    </div>
    
    <div class="mb-3 row">
      <label for="_confirm_password" class="col-sm-2 col-form-label">確認新密碼</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="confirm_password" id="_confirm_password" placeholder="請再次輸入新密碼">
      </div>
    </div>

    <input class="btn btn-primary" type="submit" value="更新">
    <a href="index.php" class="btn btn-secondary">取消</a>
  </form>
</div>

<?php include 'footer.php'; ?>