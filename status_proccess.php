<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>迎新茶會表單結果</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- 固定導覽列遮擋內容 -->
    <div style="margin-top: 120px;"></div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name   = $_POST["name"]   ?? "未填寫";
        $status = $_POST["status"] ?? "N/A";
        $dinner = $_POST["dinner"] ?? "no";

        echo htmlspecialchars($name), "<br/>";
        echo ($dinner === "yes" ? "需要晚餐" : "不需要晚餐") . "<br/>";

        $fee = 0;
        if ($status === "T" || $status === "M") {
            $fee = 0;
        } elseif ($status === "S") {
            $fee = ($dinner === "yes") ? 60 : 0;
        }

        echo "請繳{$fee}元<br/>";
    } else {
        echo "請由表單頁面提交資料！";
    }
    ?>
</body>
<?php include("footer.php"); ?>
</html>
