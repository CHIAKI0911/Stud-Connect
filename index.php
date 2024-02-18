<?php
    session_start();
    require('library.php');
    

    //セッションの情報を保持しているかどうか
    if(isset($_SESSION['id']) && isset($_SESSION['name'])){
        $id = $_SESSION['id'];          //$_SESSION['id']と直接使うのをさけたい
        $name = $_SESSION['name'];
    }else{
        header('Location: login.php');
        exit();
    }

    $field_id = "001";  //ここの内容を後でpostから送信された値を入れるようにしといて
    $db = dbconnect();

    $error = [];

//メッセージの投稿
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);     //index.php→dbに送信するときは$message db→index.phpに送信する際は$substance
        if($message === ''){
            $error['message'] = 'message-blank';
        }

        $stmt = $db->prepare('insert into question (user_id,name,substance,field_id) values(?,?,?,?)');

        if(!$stmt){
            die($db->error);
        }

        $stmt->bind_param('ssss', $id,$name,$message,$field_id);
        $success = $stmt->execute();
        if(!$success){
            die($db->error);
        }

        header('Location: index.php'); //post送信の再読み込みをしたら内容がまたDBに送られてしまうのでindex.phpに飛ぶ
        exit();
    }

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ひとこと掲示板</title>

    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>ひとこと掲示板</h1>
        </div>
        <div id="content">
            <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
            <div style="text-align: right"><a href="Group_home.php">部屋へ</a></div>
            <form action="" method="post">
                <dl>
                    <dt><?php echo h($name); ?>さん、メッセージをどうぞ</dt>
                    <dd>
                        <textarea name="message" cols="50" rows="5"></textarea>
                    </dd>
                    <!-- <dd>
                            <select name="genre">
                                <option value="アルバイト">アルバイト</option>
                                <option value="春から大学生">春から大学生</option>
                                <option value="学生生活">学生生活</option>
                                <option value="恋愛">恋愛</option>
                                <option value="就活">就活</option>
                                <option value="プログラミング">プログラミング</option>
                                <option value="サークル">サークル</option>
                            </select>
                    </dd> -->
                </dl>
                <div>
                    <p>
                        <input type="submit" value="投稿する"/>
                    </p>
                </div>
                <div>

                </div>
            </form>

            <?php 
                $stmt = $db->prepare('select q.id, q.user_id, q.substance, q.time, u.name, u.picture from question q, user u where u.id=q.user_id order by id desc');
                if(!$stmt){
                    die($db->error);
                }
                $success = $stmt->execute();

                if(!$success){
                    die($db->error);   
                }
                
                $stmt->bind_result($id, $user_id, $substance, $post_time, $name, $picture);
                while($stmt->fetch()):
            ?>  

                    <div class="msg">
                        <?php if($picture): ?>
                        <img src="member_picture/<?php echo h($picture); ?>" width="48" height="48" alt=""/>
                        <?php endif; ?>
                        <p><?php echo h($substance); ?><span class="name"> <?php echo h($name);?></span></p>
                        <p class="day"><a href="view.php?id=<?php echo h($id); ?>"><?php echo h($post_time)?></a>
                        
                        <!-- ログインできているアカウントのみ削除を可能にする -->
                        <?php if ($_SESSION['id'] === $user_id) :?>
                            [<a href="delete.php?id=<?php echo h($id); ?>" style="color: #F33;">削除</a>]
                        <?php endif; ?>
                        </p>
                    </div>

            <?php 
                endwhile; 
            ?>
        </div>
    </div>
</body>

</html>