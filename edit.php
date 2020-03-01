<?php

require_once('config.php');
require_once('functions.php');

// データベースへの接続
$dbh = connectDb();

// SQLの準備と実行
$sql = "select * from plans where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

// 結果の取得
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

// 受け取ったデータ// タスクの編集
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $due_date = $_POST['due_date'];

  $errors = [];
  if ($title == '') {
    $errors['title'] = '学習内容を入力してください';
  }

  if ($due_date == '') {
    $errors['due_date'] = '日付が変更されていません';
  }

// エラーが1つもなければレコードを更新
  if (empty($errors)) {
    $sql = "update plans set (title, due_date, created_at, updated_at)
    values (:title, :due_date, now(), now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":due_date", $due_date);
    $stmt->execute();

    header('Location: index.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>編集画面</title>
</head>
<body>
  <h2>編集</h2>
  <p>
    <form action="" method="post">
      <label for="title">学習内容:
        <input type="text" name="title">
      </label>
      <label for="due_date">期限日:</label>
        <input type="date" name="due_date" id="">
        <input type="submit" value="編集">
    </form>
  </p>
    <?php if ($errors) : ?>
      <ul style="color:red;">
        <?php foreach ($errors as $error) : ?>
          <li><?php echo h($error); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
</body>
</html>