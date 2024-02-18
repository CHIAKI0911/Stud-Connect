<?php
try{
    $pdo = new PDO('mysql:dbname=Stud-conect;charset=utf8mb4','root','');
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM field";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $field = $stm->fetchAll(PDO::FETCH_ASSOC);

    }catch (PDOException $e){
        echo "接続エラー:".$e->getMessage();
        exit();
    }

    session_start();
    $user_name = $_SESSION['name'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<title>AM Service</title>
  <link rel="stylesheet" type="text/css" href=""> 
</head>

<body>

<div class="register">
<div class="reg-triangle"></div>

  <div class="reg-container">
  <form action="Create_check.php" method="post" onsubmit="return conrimMessage();">

  <h2>部屋作成</h2>

  <p><input type="text" name="name" id="name" required="required" placeholder="部屋名"></p>
  <p><input type="textarea" name="substance" id="substance" required="required" placeholder="内容"></p>
  <select name="field">
        <?php
        // <li>の点をのける
        foreach($field as $row){
            echo '<option value="', $row["id"], '">', $row["name"], "</option>";
        }
        // 授業名を選んでいるが実際はID
        echo '<input type="hidden" name="id" value="'.$field.'">'
        ?>
    </select>

  <p><input type="submit" class="button" title="登録" value="登録"></p>

  <p><a href="Group_home.php">戻る</a></p>
  </form>
</div>
</div>
</body>
</html>