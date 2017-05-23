<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'jusjh47y'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

$result_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['id'])) {
    $result = $mysqli->query("SELECT * FROM messages WHERE id = '{$_POST['id']}'");  //messageテーブルをidの降順にする
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['body'])  && !empty($_POST['id']) && !empty($_POST['pass'])) {
    $mysqli->query("UPDATE messages SET body='{$_POST['body']}' WHERE id={$_POST['id']} AND password = '{$_POST['pass']}'");
    $update_count = $mysqli->affected_rows;   //更新件数の取得
      if ($update_count == 0) {
        print "passwordが違うか変更がされていません";
        exit();
      } elseif ($update_count == 1) {
        header('location: ./UpdateAndDelete001.php');
      }
  }

}
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

    <h1>メッセージの更新</h1>

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
        <form action="" method="post">
        <td><input type="text" name="body" value="<?= $row['writer'] ?>"></td>
        <td><input type="text" name="body" value="<?= $row['body'] ?>"></td>
        <td><?= $row['timestamp'] ?></td>
        <form action="" method="post">
        <td><input type="password" name="pass"></td>
        <td>  <!--メッセージ削除-->
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />  <!--隠し値を設定する。valueは送信される値-->
            <input type="submit" value="更新" />  <!--以上の内容で更新情報送信-->
          </td>
        </tr>
      </form>
      <?php endforeach; ?>
    </table>

  </body>
</html>
