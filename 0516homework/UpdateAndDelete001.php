<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'jusjh47y'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['writer']) && !empty($_POST['body']) && !empty($_POST['password'])) {  //投稿者欄と本文の欄がどちらも埋められていた時
    //投稿者と本文をデータベースのwriterとbodyにインサート
    $mysqli->query("INSERT into `messages` (`writer`, `body`, `password`) values ('{$_POST['writer']}', '{$_POST['body']}', '{$_POST['password']}')");
    $result_message = 'データベースに登録しました！XD';
  } else {                                                   //投稿者欄と本文の欄がどちらかが空欄だった時
    $result_message = 'メッセージを入力してください...XO';
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

    <h1>bbsデータベースのmessagesテーブルに挿入します</h1>
    <form action="UpdateAndDelete001.php" method="post">
      投稿者 :   <input type="text" name="writer" /><br>  <!--入力した文字列がwriterになる-->
      本文 : <input type="text" name="body" /><br>    <!--入力した文字列がbodyになる-->
      パスワード : <input type="password" name="password" /><br>    <!--入力した文字列がpasswordになる-->
      <input type="submit" />  <!--クエリ送信-->
    </form>

    <!--bbsテーブルのmessageテーブルを表で表示-->
    <table border="2">
      <caption>bbsデータベースmessageテーブル</caption>
      <tr>
        <th>投稿者</th>
        <th>本文</th>
        <th>投稿日時</th>
      </tr>
      <?php foreach ($result as $row):  //テーブルの内容を投稿者、本文、投稿日時の順に表示する?>
      <tr>
        <td><?= $row['writer'] ?></td>
        <td><?= $row['body'] ?></td>
        <td><?= $row['timestamp'] ?></td>
        <td>  <!--メッセージ削除-->
          <form action="UpdateAndDelete002.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />  <!--隠し値を設定する。valueは送信される値-->
            <input type="submit" value="編集" />  <!--以上の内容で送信-->
          </form>
          <form action="UpdateAndDelete003.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />  <!--隠し値を設定する。valueは送信される値-->
            <input type="submit" value="削除" />  <!--以上の内容で送信-->
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>

  </body>
</html>
