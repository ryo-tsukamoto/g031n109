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

// 削除ボタンが押された時
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['bbs_delete'])) {
    // SQLインジェクション処理
    $password = $mysqli->real_escape_string($_POST['password']);
    //passwordとidが一致の場合delete
    $delete = $mysqli->query("DELETE FROM `messages` WHERE id = {$_POST['id']} AND password = '{$password}'");
    $delete_count = $mysqli->affected_rows;   //message_make.phpで入力したパスワードと一致する、削除したいレコードの件数を取得
    if ($delete_count == 1) {   //削除件数が１件の時
      header("location: ./message_see.php?id={$_POST['thread_id']}");
      exit();
      //message_make/phpで入力されたパスワードが違う時パスワード入力画面へ戻る
    } elseif ($delete_count == 0) {
      print '<script>
      alert("パスワードが違うか入力されていません");
      history.back(-1);
      </script>';
    } else {    //それ以外の場合のエラー処理
      printf("Connect failed: %s\n", $mysqli->connect_errno);
      exit();
    }
  }
}

//　更新ボタンが押された時　更新するコメントのレコードを読み込む
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['bbs_update'])) {
    // SQLインジェクション処理
    $password = $mysqli->real_escape_string($_POST['password']);
    //passwordが一致した時レコードの読み込み
    $result = $mysqli->query("SELECT * FROM messages WHERE id = {$_POST['id']} AND password = '{$password}'");
    $result_count = $mysqli->affected_rows;   //message_make.phpで入力したパスワードと一致する、更新したいレコードの件数を取得
    //message_make/phpで入力されたパスワードが違う時パスワード入力画面へ戻る
    if ($result_count == 0) {
      print '<script>
      alert("パスワードが違うか入力されていません");
      history.back(-1);
      </script>';
    } elseif ($result_count == -1) {    //それ以外のエラー処理
      printf("Query failed: %s\n", $mysqli->error);
      exit();
    }
  }
}


//フォームの値を受け取りmysqlを更新する
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['body'])) {   //bodyの値が空値でない時
    //SQLインジェクション処理
    $body = $mysqli->real_escape_string($_POST['body']);
    $id = $mysqli->real_escape_string($_POST['id']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $update = $mysqli->query("UPDATE `messages` SET `body`='{$body}' WHERE id= {$id} AND password = '{$password}'");
    $update_count = $mysqli->affected_rows;   ///message_make.phpで入力したパスワードと一致する、更新したいレコードの件数を取得
    if ($update_count == 1) {   //更新が成功した場合、選択スレッドの閲覧画面に戻る
      header("location: ./message_see.php?id={$_POST['thread_id']}");
      exit();
    } else {    //それ以外のエラー処理
      printf("Query failed: %s\n", $mysqli->error);
      exit();
    }
  }
}

//接続を閉じる
$mysqli->close();

?>

<html>
  <head>
    <meta charset="utf-8">
    <title>掲示板</title>
  </head>

  <body>
    <div class="container">
      <div class="page-header">
        <h1>投稿内容の編集</h1>
          <form action="thread_action.php" style="text-align:">
            <input type="submit" class="btn btn-info" value="スレッド一覧へ戻る">
          </form>
      </div>
    </div>

        <Hr>
        <table rules="all" class="table table-striped">
        <thead>
          <tr>
            <th style="width:20%;">投稿者</th>
            <th style="width:60%;">本文</th>
            <th>更新ボタン</th>
          </tr>
        </thead>

        <?php
          foreach ($result as $row):
            //XSS対策
            $writer = htmlspecialchars($row['writer']);
            $body = htmlspecialchars($row['body']);
            $password = htmlspecialchars($row['password']);
            $id = htmlspecialchars($row['id']);
            $thread_id = htmlspecialchars($row['thread_id']);
          ?>

          <Hr>
          <form name="edit" action="" method="post">
            <tbody>
              <tr>
                <td><?= $writer ?></td>
                <td><input type="text" name="body" class="form-control" value="<?= $body ?>"></td>
                <td>
                  <input type="hidden" name="password" value="<?= $password ?>">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <input type="hidden" name="thread_id" value="<?= $thread_id ?>">
                  <input type="submit" value="更新" class="btn btn-warning" onclick="check()">
                </td>
              </tr>
            </tbody>
          </form>
        <?php endforeach; ?>
      </table>
    </div>

    <!-- コメント投稿時の文字入力がない場合 -->
    <script language="JavaScript">
    function check() {
        if(document.edit.body.value == "") {   //コメント投稿欄が空欄だった場合
          alert("本文を記入してください.");
          return ;
        }
    }
    </script>
  </body>
</html>
