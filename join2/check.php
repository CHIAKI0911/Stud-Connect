<?php
	require('../library.php');
	session_start();		//こいつを入れることでセッションが使える

	if(isset($_SESSION['form'])){
		$form = $_SESSION['form'];
	}else{
		header('Location: ../login.php');
		exit();
	}


	if($_SERVER['REQUEST_METHOD'] === 'POST'){
			
		$db = dbconnect();
		$stmt = $db->prepare('insert into user (id, name, email, password, picture) VALUES (?,?,?,?,?)');

		if(!$stmt){
			die(db->error);
		}

		$password = password_hash($form['password'], PASSWORD_DEFAULT);
		$stmt->bind_param('sssss',$form['id'], $form['name'], $form['email'], $password, $form['image'] );	//この値の順で????に入る
		$success = $stmt->execute();
		if(!$success){
			die($db->error);
		}

		//phpからsql側にデータ登録ができた際に保持しているセッションを初期化する
		unset($_SESSION['form']);
		header('Location: thanks.php');

	} 



?>



<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>会員登録</h1>
		</div>

		<div id="content">
			<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
			<form action="" method="post">
				<dl>
					<dt>User_ID</dt>
					<dd><?php echo h($form['id']); ?></dd>
					<dt>ニックネーム</dt>
					<dd><?php echo h($form['name']); ?></dd>
					<dt>メールアドレス</dt>
					<dd><?php echo h($form['email']); ?></dd>
					<dt>パスワード</dt>
					<dd>
						【表示されません】
					</dd>
					<dt>写真など</dt>
					<dd>
							<img src="../member_picture/<?php echo h($form['image']); ?>" width="100" alt="" />
					</dd>
				</dl>
				<div><a href="../login.php?action=rewrite" >&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
			</form>
		</div>

	</div>
</body>

</html>