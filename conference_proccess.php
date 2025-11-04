<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>資管一日營表單結果</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
    <!-- 固定導覽列遮擋內容 -->
    <div style="margin-top: 100px;"></div>
<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name   = $_POST["name"]   ?? "未填寫";
        $status = $_POST["status"] ?? "N/A";
        $programs = $_POST["program"] ?? [];

        echo $_POST["name"], "<br/>";

        $fee = 0;
        if ($status === "T" || $status === "M") {
            $fee = 0; // 老師和管理員免費
        } elseif ($status === "student") {
            if (in_array("am", $programs)) $fee += 150;
            if (in_array("pm", $programs)) $fee += 100;
            if (in_array("lunch", $programs)) $fee += 50; 
        }

        echo "<strong>您要繳交{$fee}元</strong><br/>";
    } else {
        echo "請由表單頁面提交資料！";
    }
?>
<?php include("footer.php"); ?>
