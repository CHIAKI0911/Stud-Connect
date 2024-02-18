<?php

    session_start();
    require('library.php');
    

    //セッションの情報を保持しているかどうか
    if(isset($_SESSION['id']) && isset($_SESSION['name'])){
        $id = $_SESSION['id'];          //$_SESSION['id']と直接使うのをさけたい session['id']にはuserテーブルのuser_idが入っている
        $name = $_SESSION['name'];
    }else{
        header('Location: login.php');
        exit();
    }

    $post_id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING);
    if(!$post_id){
        header('Location: index.php');
        exit();
    }
    
    $db = dbconnect();

    $stmt = $db->prepare('delete from question where id=? and user_id=? limit 1');
    if(!$stmt){
        die($db->error);
    }
    $stmt->bind_param('ii',$post_id,$id);  //$user_idにはセッションの['id']を格納する。ちなみに$idにも
    $success = $stmt->execute();
    if(!$success){
        die($db->error);
    }

header('Location: index.php');
exit();
?>
