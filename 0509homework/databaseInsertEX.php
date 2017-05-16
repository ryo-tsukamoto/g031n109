<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'jusjh47y'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

//mysqlに接続できなかった時
if ($mysqli->connect_errno) {
  printf("Connect failed: %s\n", $mysqli->connect_errno);
  exit();
}


$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['writer']) && !empty($_POST['body'])) {  //投稿者欄と本文の欄がどちらも埋められていた時
    //投稿者と本文をデータベースのwriterとbodyにインサート
    $mysqli->query("INSERT into `messages` (`writer`, `body`) values ('{$_POST['writer']}', '{$_POST['body']}')");
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
    <h1>bbsデータベースのmessagesテーブルに挿入します</h1>
    <form action="databaseInsertEX.php" method="post">
      投稿者 :   <input type="text" name="writer" /><br>  <!--入力した文字列がbodyになる-->
      本文 : <input type="text" name="body" /><br>    <!--入力した文字列がbodyになる-->
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
      </tr>
      <?php endforeach; ?>
    </table>

    <script language="JavaScript">
    function check() {
      if(document.bbs.name.value == "" || document.bbs.comment.value == "") { //投稿者と本文のいずれかが入力されていなかったとき
        alert("投稿者と本文を記入してください.");
        return false;
      }
    }
    </script>

  </body>
</html>
