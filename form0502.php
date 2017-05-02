<!-- form0502.php -->

<?php
// パスワードにhogehogeと入力されたらユーザー名を出力する
if(!empty($_GET)){
if ($_GET['password'] !== NULL and $_GET['password'] === 'hogehoge') {
  echo "Hello, {$_GET['username']}! :D";
}
}
?>

<html>
  <head>
  </head>

  <body>
    <form action="form0502.php" method="get">
      <input type="text" name="username" />
      <input type="passwword" name="password" />
      <input type="submit">
    </form>
  </body>
</html>
