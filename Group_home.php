<?php
session_start();

//DB接続情報を設定します。
$pdo = new PDO(
    "mysql:dbname=stud-conect;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
);
//ここで「DB接続NG」だった場合、接続情報に誤りがあります。
if ($pdo) {
    echo "";
} else {
    echo "DB接続NG";
}
//SQLを実行。
$regist = $pdo->prepare("SELECT room.id AS rid, room.name AS rname, substance, user_id1, room.field_id, field.id, field.name FROM room INNER JOIN field ON room.field_id = field.id");
$regist->execute();


//ここで「登録失敗」だった場合、SQL文に誤りがあります。
if ($regist) {
    echo "";
} else {
    echo "登録失敗";
}
?>

<!DOCTYPE html>
<head>
 <meta charset="UTF-8">
 <title>Stud-Connect</title>
 <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <div id="wrap">
        <div id="head">
            <h1>グループチャット</h1>
        </div>
        <div id="content">
        <section>
                <dt><h2>部屋一覧</h2></dt>
                <div style="text-align: right"><a href="Group_create.php">部屋作成はこちら</a></div>
                <form method='POST' action='Group_chat.php'>
                <?php foreach($regist as $loop):?>
                <div><b>名前：</b><?php echo $loop['rname']?></div>
                <div><b>投稿内容：</b><?php echo $loop['substance']?></div>
                <div><b>分野：</b><?php echo $loop['name']?></div>
                <input type='submit' value='参加' />
                <?php if ($_SESSION['id'] === $loop['user_id1']) :?>
                                [<a href="delete.php?id=<?php echo h($id); ?>" style="color: #F33;">削除</a>]
                            <?php endif; ?>
                <div>------------------------------------------</div>
                <?php endforeach;?>
        </section>
        </div> 
    </div>
</body>