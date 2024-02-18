<?php
//DB接続情報を設定します。
$pdo = new PDO(
    "mysql:dbname=stud-conect;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
);
//ここで「DB接続NG」だった場合、接続情報に誤りがあります。
if ($pdo) {
    echo "〇";
} else {
    echo "DB接続NG";
}

$user_id = "run1630";

//SQLを実行。
$regist = $pdo->prepare("UPDATE user SET :field_id WEHRE user_id");
$regist->bindParam(":user_id", $user_id);
$regist->bindParam(":field_id", $field_id);
$regist->execute();

if ($regist) {
    echo "〇";
} else {
    echo "登録失敗";
}
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<title>Stud-Connect</title>
<p>アカウント確認画面に移動するようにしてね</p>