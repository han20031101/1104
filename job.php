<?php
require_once "header.php"; 

try {
  require_once 'db.php';
  $order = $_POST["order"] ?? "";
  $searchtxt = trim($_POST["searchtxt"] ?? "");
  // 防止 SQL injection：對搜尋字串做 escape
  $s = mysqli_real_escape_string($conn, $searchtxt);

  $sql = "SELECT * FROM job";

  if ($searchtxt !== "") {
    $sql .= " WHERE (company LIKE '%$s%' OR content LIKE '%$s%')";
  }

  $valid_cols = ['company','content','pdate'];
  if (in_array($order, $valid_cols)) {
    $sql .= " ORDER BY $order DESC";
  } else {
    $sql .= " ORDER BY pdate DESC";
  }
  $result = mysqli_query($conn, $sql);
?>

<div style="margin-top: 120px;"></div>
<div class="container">
  <div class="container position-relative">
  <a href="job_insert.php" class="btn btn-primary position-absolute" style="top: 1rem; right: 1rem;">+</a>

  <form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="mb-3">
  <select name="order" aria-label="選擇排序欄位">
      <option selected value="">選擇排序欄位</option>
      <option value="company" <?= $order === 'company' ? 'selected' : '' ?>>求才廠商</option>
      <option value="content" <?= $order === 'content' ? 'selected' : '' ?>>求才內容</option>
      <option value="pdate" <?= $order === 'pdate' ? 'selected' : '' ?>>刊登日期</option>
    </select>
    <input placeholder="搜尋廠商及內容" type="text" name="searchtxt" value="<?=htmlspecialchars($searchtxt)?>">
    <input class="btn btn-primary" type="submit" value="搜尋">
  </form>
  

  <table class="table table-bordered table-striped">
  <tr>
    <td>求才廠商</td>
    <td>求才內容</td>
    <td>刊登日期</td>
    <td></td>
  </tr>
  <?php
  while($row = mysqli_fetch_assoc($result)) {?>
  <tr>
    <td><?=$row["company"]?></td>
    <td><?=$row["content"]?></td>
    <td><?=$row["pdate"]?></td>
    <td>
      <a href="job_update.php?postid=<?=$row["postid"]?>" class="btn btn-warning">編輯</a>
      <a href="job_delete.php?postid=<?=$row["postid"]?>" class="btn btn-danger">刪除</a>
    </td>
  </tr>
  <?php
    }
  ?>
  </table>
</div>


<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
require_once "footer.php";
?>