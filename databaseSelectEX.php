<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'jusjh47y'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('select * from `messages` order by id DESC');

// var_dumpで確認
foreach ($result as $row) {
  var_dump($row);
}
