  <?php

  require_once('config.php');
  require_once('functions.php');

  // データベースへの接続
  $dbh = connectDb();

  // 受け取ったレコードのID
  $id = $_GET['id'];

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
    $sql = "update plans set title = :title," . "updated_at = now()" . "where id = :id";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":id", $id);
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
      <title>編集画面</title>
  </head>
  <body>
  <h2>編集</h2>
  <p>
    <form action="" method="post">
      <label for="">学習内容:
        <input type="text">
      </label>
      <label for="">期限日:
        <input type="date" name="" id="">
        <input type="submit" value="編集">
      </label>
      <?php if (count($errors) > 0) : ?>
    <ul style="color:red;">
    <?php foreach ($errors as $key => $value) : ?>
      <li><?php echo h($value); ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
    </form>
  </p>
  </body>
  </html>