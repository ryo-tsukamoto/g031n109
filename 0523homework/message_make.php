<?php
//データベースに接続
$db_user = 'root';         // ユーザー名
$db_pass = 'jusjh47y';     // パスワード
$db_name = 'bbs';          // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
//mysqlの接続エラー処理
if ($mysqli->connect_errno) {
  printf("Connect failed: %s\n", $mysqli->connect_errno);
  exit();
}

//スレッドの読み込み　最新のスレッドを一件読み込む
$result = $mysqli->query('SELECT * FROM threads ORDER BY id DESC LIMIT 1');
if (!$result) {    //queryエラーの場合，エラーを表示する
  printf("Query failed: %s\n", $mysqli->error);
  exit();
} else {
  foreach ($result as $row) {
    //XSS対策
    $writer = htmlspecialchars($row['writer']);
    $id = htmlspecialchars($row['id']);
  }
}

//スレッドにコメントを登録
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['id']) && !empty($_POST['writer']) && !empty($_POST['body']) && !empty($_POST['password'])) {    //formの値がそれぞれ空でない時
    //SQLインジェクション処理
    $id = $mysqli->real_escape_string($_POST['id']);
    $writer = $mysqli->real_escape_string($_POST['writer']);
    $body = $mysqli->real_escape_string($_POST['body']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $insert = $mysqli->query("INSERT INTO `messages` (`thread_id`, `writer`, `body`, `password`) VALUES ('{$id}', '{$writer}', '{$body}', '{$password}')");
    if ($insert) {  //登録できた場合スレッド内容一覧を表示
      header("location: ./message_make.php?id={$id}");
    } else {    //それ以外の場合エラー処理
      printf("Query failed: %s\n", $mysqli->error);
      exit();
    }
  }
}

//GETでthread_idを受け取り、スレッドのコメントの読み込み　messagesのid降順
$query = "SELECT * FROM messages WHERE thread_id = {$_GET['id']} ORDER BY id DESC";
$result = $mysqli->query($query);
$result_count = $mysqli->affected_rows;   //resultの件数を取得


//接続を閉じる
$mysqli->close();

?>

<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <title>掲示板</title>
  </head>

  <body>
    <div class="container">
      <div class="page-header">
        <h1>
          <?= $thread_name = htmlspecialchars($row['name']); ?>スレッド
        </h1>
        <div style="text-align: right; margin: -5rem 0 10px;">
          <button type="button" class="btn" onclick="location.href='./thread_action.php'">スレッド一覧
            <span class="glyphicon glyphicon-arrow-left"></span>
          </button>
        </div>
      </div>

      <!-- 新規コメントの登録 -->
      <form name="comment" action="" method="post">
        <table class="table">
          <thead>
            <tr>
              <th>Writer</th>
              <th>Body</th>
              <th colspan="2">Password</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="text" name="writer" class="form-control"></td>
              <td><input type="text" name="body" class="form-control"></td>
              <td><input type="password" name="password" class="form-control"></td>
              <td>
                <input type="hidden" name="id" value="<?= $id ?> ">
                <input type="submit" class="btn btn-primary" value="Submit" onclick="check()"></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>

    <!-- コメントの表示 -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th style="width:15%;">Writer</th>
          <th style="width:30%;">Body</th>
          <th style="width:20%;">投稿日時</th>
          <th>パスワード</th>
          <th>編集</th>
          <th>削除</th>
        </tr>
      </thead>
      <tbody>

          <!-- コメントの削除・編集form -->
          <?php foreach ($result as $row): ?>
          <form action="./massage_edit.php" method="post" name="bbs">
            <tr>
              <!--XSS対策-->
              <td><?= htmlspecialchars($row['writer']) ?></td>
              <td><?= htmlspecialchars($row['body']) ?></td>
              <td><?= htmlspecialchars($row['timestamp']) ?></td>
              <td>
                <input type="password" name="password" class="form-control" size="10">
                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                <input type="hidden" name="thread_id" value="<?= htmlspecialchars($row['thread_id']) ?>">
              </td>
              <td>
                <input type="submit" name="bbs_update" value="編集" class="btn btn-success">
              </td>
              <td>
                <input type="submit" name="bbs_delete" value="削除" class="btn btn-danger">
              </td>
            </tr>
          </form>
          <?php endforeach; ?>
      </tbody>
    </table>
  </div>

    <!-- 文字入力がない場合の警告ポップアップ -->
    <script language="JavaScript">
    function check() {
        if(document.comment.writer.value == "" || document.comment.body.value == "" || document.comment.password.value == "") { //新規スレッドフォームのnameかpasswordの値が空だった場合
          alert("Name・comment・passwordを記入してください.");    //alertを表示する
          return ;
        }
    }
    </script>
  </body>
</html>
