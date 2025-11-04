<?php include('header.php'); ?>
<?php
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
$msg = $_GET['msg'] ?? '';
?>
<div style="margin-top: 120px;"></div>
<div class="container" style="max-width: 400px; margin-top: 40px; margin-bottom: 40px;">
    <div class="card p-4 ">
        <h3 class="text-center mb-4">登入</h3>
        <form method="post" action="login_process.php">
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
            <div class="mb-3">
                <label for="username" class="form-label">帳號</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">密碼</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">登入</button>
        </form>

        <?php if ($msg): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include('footer.php'); ?>