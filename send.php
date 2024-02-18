<!DOCTYPE html>
<meta charset="UTF-8">
<title>掲示板サンプル</title>
<h1>掲示板サンプル</h1>
<section>
    <h2>投稿完了</h2>
    <button onclick="location.href='index.php'">戻る</button>
</section>

<?php
$id = null;
$user_id = "run1630";
$name = $_POST["name"];
$substance = $_POST["substance"];
date_default_timezone_set('Asia/Tokyo');
$time = date("Y-m-d H:i:s");
$field_id = "001";
// user_id, field_id固定で一旦登録中　別プログラムとあわせて変更予定

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
$regist = $pdo->prepare("INSERT INTO question(id, user_id, name, substance, time, field_id) VALUES (:id, :user_id, :name, :substance, :time, :field_id)");
$regist->bindParam(":id", $id);
$regist->bindParam(":user_id", $user_id);
$regist->bindParam(":name", $name);
$regist->bindParam(":substance", $substance);
$regist->bindParam(":time", $time);
$regist->bindParam(":field_id", $field_id);
$regist->execute();
//ここで「登録失敗」だった場合、SQL文に誤りがあります。
if ($regist) {
    echo "";
} else {
    echo "登録失敗";
}
?>