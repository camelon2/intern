<head>
  <meta charset="UTF-8">
</head>

<?php
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn,$user,$password);
//------------------------------------------------------------------------------
  $sql = "CREATE TABLE mission4"
  ."("
  ."id INT,"
  ."name char(32),"
  ."comment TEXT,"
  ."pass TEXT"
  .");";
  $stmt = $pdo -> query($sql);
//------------------------------------------------------------------------------
  $result = $pdo -> query($sql);
  $pass_del = $_POST['pass_del'];
  $pass_chan = $_POST['pass_chan'];
  $id_max = $pdo -> query("SELECT MAX(id) FROM mission4")->fetchColumn();
  $num = $id_max + 1;
  $sql = 'SELECT * FROM mission4';

  $name = $_POST['name'];
  $comment = $_POST['comment'];
  $hide = $_POST['hide'];
  $pass = $_POST['pass'];

  if(!empty($pass_del) && !empty($_POST['delete'])){
    $results = $pdo -> query($sql);
    foreach ($results as $row){
      if($row['id'] == $_POST['delete']){
        if($row['pass'] == $pass_del){
          $delete = $_POST['delete'];
        }
        elseif($row['pass'] != $pass_del){
          echo "パスワードが違います。";
        }
      }
    }
  }

  if(!empty($pass_chan) && !empty($_POST['change'])){
    $results = $pdo -> query($sql);
    foreach ($results as $row){
      if($row['id'] == $_POST['change']){
        if($row['pass'] == $pass_chan){
          $change = $_POST['change'];
        }
        elseif($row['pass'] != $pass_chan){
          echo "パスワードが違います。";
        }
      }
    }
  }
?>

<!--============================送信フォーム=================================-->
<form action = "mission_4.php" method = "post">
  <input type = "text" name = "name" placeholder = "名前"
    value = "<?php
      if(!empty($change)){
        $results = $pdo -> query($sql);
        foreach ($results as $row){
          if($row['id'] == $change){
            echo $row['name'];
          }
        }
      }
    ?>"><br>
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
  <input type = "text" name = "comment" placeholder = "コメント"
    value = "<?php
      if(!empty($change)){
        $results = $pdo -> query($sql);
        foreach ($results as $row){
          if($row['id'] == $change){
            echo $row['comment'];
          }
        }
      }
    ?>"><br>
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
  <input type = "text" name = "pass" placeholder = "パスワード">
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
  <input type = "submit" value = "送信">
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
  <input type = "hidden" name = "hide" value = "<?php echo $change ?>">
</form>
<!--============================削除フォーム=================================-->
<form action = "mission_4.php" method = "post">
  <input type = "text" name = "delete" placeholder = "削除対象番号"><br>
  <input type = "text" name = "pass_del" placeholder = "パスワード">
  <input type = "submit" value = "削除">
</form>

<!--============================編集フォーム=================================-->
<form action = "mission_4.php" method = "post">
  <input type = "text" name = "change" placeholder = "編集対象番号"><br>
  <input type = "text" name = "pass_chan" placeholder = "パスワード">
  <input type = "submit" value = "編集">
</form>


<!--==============================以下PHP====================================-->


<?php

//----------------------------------編集----------------------------------------
  if(!empty($name) && !empty($comment) && !empty($pass) && !empty($hide)){
    $id = $hide;
    $nm = $_POST['name'];
    $kome = $_POST['comment'];
    $pasu = $_POST['pass'];
    $sql = "update mission4 set name = '$nm', comment = '$kome', pass = '$pasu' where id = $id";
    $result = $pdo -> query($sql);
  }
//----------------------------------送信----------------------------------------
  elseif(!empty($name) && !empty($comment) && !empty($pass) && empty($hide)){
    $sql = $pdo -> prepare("INSERT INTO mission4 (id, name, comment, pass) VALUES (:id, :name, :comment, :pass)");
    $sql -> bindParam(':id', $id, PDO::PARAM_INT);
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $id = $num;
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $pass = $_POST['pass'];
    $sql -> execute();
  }
//----------------------------------削除----------------------------------------
  if(!empty($delete)){
    $sql = "delete from mission4 where id = $delete";
    $result = $pdo -> query($sql);
  }
//----------------------------------表示----------------------------------------
  $sql = 'SELECT * FROM mission4 ORDER BY id ASC';
  $results = $pdo -> query($sql);
  foreach ($results as $row){
    echo $row['id'].' ';
    echo $row['name'].' ';
    echo $row['comment'].'<br>';
  }
?>
