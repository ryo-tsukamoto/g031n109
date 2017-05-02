<p>
<?php
  print "FizzBuzz問題<br/>";
  for($i = 1; $i <= 100; $i++){ //変数iが1から100の時
    if($i % 15 == 0){           //iが15で割り切れるとき
      print "FizzBuzz<br/>";
    }elseif($i % 3 == 0){       //iが15で割り切れず、3で割り切れるとき
      print "Fizz<br/>";
    }elseif($i % 5 == 0){       //iが15でも3でも割り切れず、5で割り切れるとき
      print "Buzz<br/>";
    }else{                      //iが15でも3でも5でも割り切れないとき
      print "$i<br/>";          //その15,3,5いずれでも割り切れない数を出力
    }
  }
?>
</p>
