<?php
    require('library.php');
	session_start();

    $name = $_POST['name'];
    $substance = $_POST['substance'];
    $user_id1 = $_SESSION['id'];
    $field = $_POST['field'];

    try{

        $db = dbconnect();
        $stmt = $db->prepare('INSERT INTO room (id, name, substance, user_id1, field_id) VALUES (?, ?, ?, ?, ?)');

		if(!$stmt){
			die(db->error);
		}

		$stmt->bind_param('sssss',$id, $name, $substance, $user_id1, $field );	//この値の順で????に入る
		$success = $stmt->execute();
		if(!$success){
			die($db->error);
		}

		//phpからsql側にデータ登録ができた際に保持しているセッションを初期化する
		unset($_SESSION['form']);
		header('Location: thanks.php');
    
    } catch (PDOException $e) {
        exit('データベース接続失敗'.$e->getMessage());
    }

	if(isset($_SESSION['form'])){
		$form = $_SESSION['form'];
	}else{
		header('Location: Group_home.php');
		exit();
	}

    unset($_SESSION['form']);
		header('Location: Group_home.php');

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
			<h2>部屋登録</h2>
		</div>

		<div id="content">
			<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
			<form action="" method="post">
				<dl>
					<dt>部屋名</dt>
					<dd><?php echo h($form['name']); ?></dd>
					<dt>内容</dt>
					<dd><?php echo h($form['substance']); ?></dd>
					<dt>分野</dt>
					<dd><?php $field ?></dd>
					
				</dl>
				<div><a href="../Group_cretate.php?action=rewrite" >&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
			</form>
		</div>

	</div>
</body>

</html>
