<?php
/*htmlspecialcharsを短くする*/
function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}

/*DB接続*/
function dbconnect(){
    $db = new mysqli ('localhost','root','','stud-conect');
    if(!$db) {
        die($db->error);
        echo"DB接続エラー";
    }

    return $db;
}

?>