<?php
  session_start();
  header("Content-type: text/html; charset=utf-8");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<title>Stud-Connect</title>
  <link rel="stylesheet" type="text/css" href="register.css"> 
  <meta charset="UTF-8">
  <script src="passwordchecker.js" type="text/javascript"></script>
  <script src="common.js" type="text/javascript"></script>
  <script type="text/javascript">

  /*
  * 登録前チェック  
  */
  function conrimMessage() {
    var id = document.getElementById("id").value;
    var name = document.getElementById("name").value;
    var pass = document.getElementById("password").value;
    var conf = document.getElementById("confirm_password").value;

    //必須チェック
    if((id == "") || (name == "") || (pass == "") || (conf == "")) {
      alert("必須項目が入力されていません。");
      return false;
    }

    //パスワードチェック
    if (pass != conf) {
      alert("パスワードが一致していません。");
      return false;
    }

    if (passwordLevel < 3) {
      return confirm("パスワード強度が弱いですがよいですか？");
    }

    return true;
    }

  </script>
</head>

<div class="register">
<div class="reg-triangle"></div>
  <h1 class="reg-header"><b>新規ユーザー登録</b></h1>
  <hr>

  <div class="reg-container">
    <h2>登録者情報</h2>
  <form action="stu_register_check.php" method="post" onsubmit="return conrimMessage();">
    <p>名前<input type="text" name="name" id="name" required="required"></p>
    <p>名前カナ<input type="text" name="nameK" id="nameK" required="required"></p>
    <p>表示名<input type="text" name="displayname" id="displayname" required="required"></p>
    <p>ユーザID<input type="text" name="id" id="id" required="required"></p>
    <p>学部<input type="text" name="major" id="major" required="required"></p>
    <p>メールアドレス<input type="email" name="mail" id="mail" required="required"></p>
    <p>メールアドレス確認<input type="email" name="confirm_mail" id="confirm_mail" required="required"></p>
    <p>パスワード<input type="password" name="password" required="required"></p>
    <p>パスワード確認<input type="password" name="confirm_password" id="confirm_password" required="required"></p>
    <p>生年月日<input type="date" name="date" id="date" required="required"></p>

    <p><input type="submit" class="button" title="次へ" value="次へ"></p>

    <p><a href="">戻る</a></p>
    </form>
</div>
</div>
</body>
</html>