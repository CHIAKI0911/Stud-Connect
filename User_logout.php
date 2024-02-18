<?php
session_start();
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<title>Stud-Connect</title>
<link rel="stylesheet" type="text/css" href="User_register.css">
</head>

<body>
<div class="register">
<h1 class="reg-header">
<font size='5'><b>ログアウト</b>しました</font>
</h1>
<div class="reg-container">
<p><a href=''>ログインページに戻る</a></p>
</div>
</div>
</body>
</html>