<!-- tasisenntaku.php -->

<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <title>多肢選択式クイズ</title>
</head>

<body>
  <div class="container">
    <div class="page-header">
      <h1>この授業のTAは誰でしょう？</h1>
    </div>

    <form action="tasisenntaku.php" method="post">
      <div class="form-group lead">
        <input type="radio" name="t1" value="さとう"> さとう
        <input type="radio" name="t1" value="かんの"> かんの
        <input type="radio" name="t1" value="ひらの"> ひらの
        <input type="radio" name="t1" value="ふくさか"> ふくさか
      </div>
      <input type="submit" class="btn-primary">
    </form>

    <div class="lead">
      <?php
      if ($_POST['t1'] == NULL) {   //選択されていない場合何も表示しない
      } elseif ($_POST['t1'] === "ひらの") {     //正解(ひらの)が選択された場合正解と表示
        echo "正解.";
      } else {
        echo "不正解．";
      }
      ?>
    </div>
  </div>
</body>
</html>
