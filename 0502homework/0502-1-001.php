<html>
  <head>
    <meta charset="utf-8">  //文字コーディング指定
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <title>一問一答クイズ</title>

    <!--  文字入力がない場合の警告ポップアップ -->
    <script language="Javascript">
    function check(){
      if(document.itimonn.answer.value == ""){
        alert("文字を入力してください");
        return false;
      }
    }
    </script>
  </head>
  <body>
    <div class="container">
      <div class="page-header">
    <h1>この授業のTAは誰ですか？</h1>
      </div>

    <form name="itimonn" action="0502-1-001.php" method="post">

    <div class="form-group">
    <label>Answer</label>
    <input type="text" name="answer" class="form-control">  //入力欄
    </div>

    <input type="submit" class="btn-primary" onClick="check()">  //送信ボタン
    </form>

    <div class="lead">
      <?php
      $taName = array('手塚','てつか','Tetsuka', 'tetsuka', '平野', 'ひらの', 'Hirano', 'hirano'); //正答の配列
      if ($_POST['answer'] == NULL) {   //文字列が入力されているかの確認
        echo "答えを入力してください.";
      } elseif (in_array($_POST['answer'], $taName)) {  //入力された文字列と正答の比較
        echo "正解です.";
      } else {
        echo "不正解．";
      }
      ?>
    </div>
  </div>
</body>
</html>
