<?php
    session_start();


    require('../library.php');

    //checkよりrewriteでかえってくるか
    if(isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])){
        $form =  $_SESSION['form'];
    }else{
        //初期セットは空白
        $form = [
            //配列の初期化
            //HTML側で<issetするか初期化するかしないと配列として動かない
            'name' => '',   //nameという形で使うことを宣言したうえで初期化するとUndefineエラーの対策
            'email' => '',  
            'password' => '',
        ];         
    }
    $error = [];

    //phpプログラムをpost送信されたときに起動という形にしないと効率悪い
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //name
        $form['name'] = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
        if ($form['name'] === ''){
            $error['name'] = 'blank';   //ここてエラー処理を記録してhtml側に連絡して表示する
        }
        
        //email
        $form['email'] = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
        if ($form['email'] === ''){
            $error['email'] = 'blank';
        } else {
            //メールアドレスの重複の確認
            $db = dbconnect();
            $stmt = $db->prepare('select count(*) from user where email=?');    //?の部分にユーザが指定したメールアドレスが入ればいい
            if(!$stmt){
                die($db->error);
            }
            $stmt->bind_param('s', $form['email']);
            $success = $stmt->execute();
            
            if(!$success){
                die($db->error);
            }
            
            $stmt->bind_result($cnt);   //cntにcountの中身を入れていく
            $stmt->fetch();
            

            if($cnt > 0) {
                $error['email'] = 'duplicate';
            }


        }

        //password
        $form['password'] = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
        if ($form['password'] === ''){
            $error['password'] = 'blank';   
        } else if ( 3 >= strlen($form['password']) || strlen($form['password']) >= 10 ){    //passwordの文字数チェック
            $error['password'] = 'length';
        }

        //画像のチェック
        $image = $_FILES['image'];
        if ($image['name'] !== '' && $image['error'] === 0) {    //nameに画像のファイル名が入ってない且エラーメッセージを受けとった時に処理をする
            $type = mime_content_type($image['tmp_name']);
            if ($type !== 'image/png' && $type !== 'image/jpeg'){
                $error['image'] = 'type';  //typeというエラーコードを入れていく
            }
        }

        
        //確認画面への画面遷移→sessionの流れ
        if (empty($error)){         //各判定でエラーの中身が入ってなければ、ってこと
            $_SESSION['form'] = $form;      //ここで$_sessionに一気に渡せるようにするために$form,password,emailなどを配列にしている
            
            //画像のアップロード
            if($image['name'] !== '' ){
                $filename = date('YmdHis') . '_' . $image['name'];
                if( !move_uploaded_file($image['tmp_name'], '../member_picture/' . $filename) ) { //htdocsフォルダの中にmember_pictureっていうファイルを作っとかないといけない。mac開発環境だとフォルダの「情報をみる」から共有権とアクセスを許可しておかないと無理
                    die('ファイルのアップロードに失敗しました');     
                }
                $_SESSION['form']['image'] = $filename;
    
            }else{
                $_SESSION['form']['image'] = '';
            }


            header('Location: check.php');
            exit();
        }
    }


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員登録</title>

    <link rel="stylesheet" href="../style.css"/>
</head>
<body>
<div id="wrap">
    <div id="head">
        <h1>会員登録</h1>
    </div>

    <div id="content">
        <p>次のフォームに必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>ニックネーム<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="name" size="35" maxlength="255" value="<?php echo h($form['name']); ?>"/>

                    <!-- バックエンド処理 -->
                    <?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
                        <p class="error">* ニックネームを入力してください</p>
                    <?php endif; ?>

                </dd>
                <dt>メールアドレス<span class="required">必須</span></dt>
                <dd>
                    <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($form['email']); ?>"/>
                    
                    <!-- バックエンド処理 -->
                    <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
                        <p class="error">* メールアドレスを入力してください</p>
                    <?php endif; ?>
                        
                    <?php if (isset($error['email']) && $error['email'] === 'duplicate'): ?>
                        <p class="error">* 指定されたメールアドレスはすでに登録されています</p>
                    <?php endif; ?>

                <dt>パスワード(4~10桁)<span class="required">必須</span></dt>
                <dd>
                    <input type="password" name="password" size="10" maxlength="20" value="<?php echo h($form['password']); ?>"/>

                     <!-- バックエンド処理 -->
                    <?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
                        <p class="error">* 4~10桁のパスワードを入力してください</p>
                    <?php endif; ?>

                    <?php if(isset($error['password']) && $error['password'] === 'length'): ?>
                    <p class="error">* パスワードは4文字以上10文字以内で入力してください</p>
                    <?php endif; ?>

                </dd>
                <dt>写真など</dt>
                <dd>
                    <input type="file" name="image" size="35" value=""/>

                     <!-- バックエンド処理 -->
                     <?php if (isset($error['image']) && $error['image'] === 'type'): ?>
                        <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                        <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
                    <?php endif; ?>

                </dd>
            </dl>
            <div><input type="submit" value="入力内容を確認する"/></div>
        </form>
    </div>
</body>

</html>