<?php
require_once 'db.php';
//利用mysql_i procedural的方式，將資料庫裡的明碼密碼改成hash密碼
$sql = "select * from user";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $account = $row['account'];
        $plainPassword = $row['password'];
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE user SET password='$hashedPassword' WHERE account='$account'";
        mysqli_query($conn, $updateSql);
    }
    echo "密碼成功更新";
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
?>