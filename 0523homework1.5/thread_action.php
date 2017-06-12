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

//新規スレッドの作成
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['name']) && !empty($_POST['password'])) {    //スレッドのthread_nameとpasswordの値が空値でない場合
    //SQLインジェクション処理
    $name = $mysqli->real_escape_string($_POST['name']);
    $password = $mysqli->real_escape_string($_POST['password']);
    $insert = $mysqli->query("INSERT INTO `threads` (`name`, `password`) VALUES ('{$name}', '{$password}')");
    if ($insert) {   //スレッドが作成できた場合、メッセージの登録画面へ遷移
      header("location: ./message_make.php");
    } else {    //それ以外の場合エラー処理
      printf("Query failed: %s\n", $mysqli->error);
      exit();
    }
  }
}

// スレッドの削除
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['delete'])) {
    // SQLインジェクション処理
    $password = $mysqli->real_escape_string($_POST['password']);
    //スレッドのpasswordとidが一致の場合、該当するスレッドを削除
    $delete = $mysqli->query("DELETE from `threads` where threads.id = {$_POST['delete']} AND threads.password = '{$password}'");
    $delete_count = $mysqli->affected_rows;   //deleteする件数取得、すなわちパスワードが一致していた場合
    if ($delete_count >= 1) {   //削除ができた場合
      print '<script>
      alert("削除しました．");
      location.href = "./thread_action.php";
      </script>';
    } elseif ($delete_count == 0) {   //パスワードが違う時エラーを表示
      print '<script>
      alert("パスワードが違うか未入力です");
      location.href = "./thread_action.php";
      </script>';
    } else {    //それ以外の場合のエラー処理
      printf("Connect failed: %s\n", $mysqli->connect_errno);
      exit();
    }
  }
}

//id降順でスレッドの読み込み　
$result = $mysqli->query('SELECT * FROM threads ORDER BY id DESC');
if (!$result) {    //queryエラーの場合，エラーを表示する
  printf("Query failed: %s\n", $mysqli->error);
  exit();
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
      <h1>スレッド一覧</h1>
    </div>
    <Hr>

    <!-- 新規スレッドの作成 -->
    <form action="" method="post">
      <table class="table">
        <thead>
          <tr>
            <th>スレッド名</th>
            <th colspan="2">パスワード</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td><input type="text" name="name" class="form-control"></td>
            <td><input type="password" name="password" class="form-control"></td>
            <td><input type="submit" class="btn btn-info" value="作成" onclick="check()"></td>
          </tr>
        </tbody>
      </table>
    </form>
    <Hr>

    <!-- スレッドの表示 -->
    <table rules="all" class="table table-striped table-hover">
      <thead>
        <tr>
          <th style="width:30%;">スレッド名</th>
          <th style="width:30%;">作成日時</th>
          <th>パスワード</th>
          <th>削除</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($result as $row):
          //XSS対策
          $id = htmlspecialchars($row['id']);
          $name = htmlspecialchars($row['name']);
          $timestamp = htmlspecialchars($row['timestamp']);
          ?>

          <!-- スレッドの削除フォーム -->
          <form action="" method="post">
            <tr>
              <!--スレッド名にそれぞれのスレッドへのリンクを貼る-->
              <td>
                <a href="./message_see.php?id=<?= $id ?>" style="text-decoration:none"><?= $name ?></a>
              </td>
              <td><?= $timestamp ?></td>
              <td>
                <input type="password" name="password" class="form-control">
              </td>
              <td>
                <input type="hidden" name="delete" value="<?= $id ?>">
                <input type="submit" value="削除" class="btn btn-danger">
              </td>
            </tr>
          </form>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- 文字入力がない場合は警告ポップアップを出す -->
  <script language="JavaScript">
  function check() {
    if(document.thread.name.value == "" || document.bbs.password.value == "") { //新規スレッドフォームのnameかpasswordの値が空だった場合
      alert("スレッド名、パスワードを記入してください.");
      return ;
    }
  }
  </script>

</body>
</html>
