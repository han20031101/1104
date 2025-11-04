<?php
$servername = "localhost";
$dbname = "practice";
$dbUsername = "root"; // 假設您的 MySQL 帳號是 root
$dbPassword = "";     // 請確認您的 root 密碼，如果沒有設定，則留空

// 1. 建立連線
$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);

// 檢查連線是否成功
if (!$conn) {
    // 使用 die() 函式停止腳本執行並輸出錯誤訊息
    die("連線失敗: " . mysqli_connect_error());
}

// 2. 指定連線編碼（推薦使用 utf8mb4 以支援更廣泛的字元）
mysqli_set_charset($conn, "utf8mb4");

echo "成功連線並準備查詢資料。<br>";

// 3. 撰寫並執行 SQL 查詢
$sql = "SELECT postid, company, content, pdate FROM job"; // 從 'job' 表格中選擇所有欄位
$result = mysqli_query($conn, $sql);

// 4. 檢查是否有結果回傳
if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>公司</th><th>內容</th><th>日期</th></tr>";
    
    // 5. 迴圈讀取並列出每一行資料 (必須在 mysqli_close() 之前)
    while($row = mysqli_fetch_assoc($result)) {
        // print_r($row); // 如果只是想看原始資料，可以使用這個
        
        // 格式化輸出到 HTML 表格
        echo "<tr>";
        echo "<td>" . $row["postid"] . "</td>";
        echo "<td>" . $row["company"] . "</td>";
        echo "<td>" . $row["content"] . "</td>";
        echo "<td>" . $row["pdate"] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} else {
    echo "表格中沒有資料。";
}

// 6. 釋放查詢結果的記憶體（可選，但推薦）
mysqli_free_result($result);

// 7. 關閉連線 (必須在所有資料讀取完成後)
mysqli_close($conn);
?>
