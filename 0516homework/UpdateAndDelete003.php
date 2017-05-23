<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'jusjh47y'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

$result_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // メッセージの削除
  if (!empty($_POST['id'])) {
    $result = $mysqli->query("SELECT * FROM messages WHERE id = '{$_POST['id']}'");  //messageテーブルをidの降順にする
    if (!empty($_POST['pass'])) {
      $mysqli->query("DELETE from `messages` where `id` = {$_POST['id']} AND password = '{$_POST['pass']}'");
      $delete_count = $mysqli->affected_rows;   //deleteの件数を取得

      if ($delete_count == 1) {   //削除件数が１件の時
        $result_message = 'メッセージを削除しました;)';
      } elseif ($delete_count == 0) {   //パスワードが違う時エラーとを表示
        $result_message = 'パスワードが違います;)';
        print '<script>
        alert("パスワードが違います");
        location.href = "UpdateAndDelete003.php";
        </script>';
      } else {    //それ以外の場合のエラー処理
        printf("Connect failed: %s\n", $mysqli->connect_errno);
        exit();
      }
    }
  }
}

$result = $mysqli->query('SELECT * from `messages` order by id desc');  //messageテーブルをidの降順にする
?>

<html>
<head>
  <meta charset="UTF-8">
</head>

<body>
  <?php
  //mysqlに接続できなかった時
  if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_errno);
    exit();
  }
  echo $result_message;  //メッセージの登録、削除、更新ができたか出力
  ?>

  <h1>メッセージの削除</h1>

  <!--bbsテーブルのmessageテーブルを表で表示-->
  <table border="2">
    <caption>bbsデータベースmessageテーブル</caption>
    <tr>
      <th>投稿者</th>
      <th>本文</th>
      <th>投稿日時</th>
      <th>パスワード</th>
    </tr>
    <?php foreach ($result as $row):  //テーブルの内容を投稿者、本文、投稿日時の順に表示する?>
      <tr>
        <td><?= $row['writer'] ?></td>
        <td><?= $row['body'] ?></td>
        <td><?= $row['timestamp'] ?></td>
        <form action="" method="post">
          <td><input type="password" name="pass"></td>
          <td>  <!--メッセージ削除-->
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />  <!--隠し値を設定する。valueは送信される値-->
            <input type="submit" value="削除" />  <!--以上の内容で更新情報送信-->
          </td>
        </form>
      </tr>
    <?php endforeach; ?>
  </table>

</body>
</html>
