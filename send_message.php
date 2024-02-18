<?php
session_start();

$pdo = new PDO(
    "mysql:dbname=stud-conect;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
);

$message = $_POST["message"];
// $users_id = $_SESSION["id"];
$room_id = 1;
$field_id = "001";

$regist = $pdo->prepare("INSERT INTO message (id, room_id, massage, field_id) VALUES (:id, :room_id, :massage, :field_id)");
$regist->bindParam(":id", $id);
$regist->bindParam(":room_id", $room_id);
$regist->bindParam(":massage", $message);
$regist->bindParam(":field_id", $field_id);
$regist->execute();

header("Location: Group_chat.php");
exit;