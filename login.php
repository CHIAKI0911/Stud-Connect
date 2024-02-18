<?php

    session_start();


    require('library.php');

    $errorOccurred = false;

    
    //checkよりrewriteでかえってくるか
    if(isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])){
        $signup_form =  $_SESSION['form'];
        $login_form =[
            'email' => '',
            'password' => '',
        ];
    }else{
        //初期セットは空白
        //＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊ここ後で入力項目増やすとき使うぞ

        $signup_form = [
            //配列の初期化
            //HTML側でissetするか初期化するかしないと配列として動かない
            'id' => '',
            'name' => '',   //nameという形で使うことを宣言したうえで初期化するとUndefineエラーの対策
            'email' => '',  
            'password' => '',
        ];         


        $login_form =[
            'name' => '',
            'email' => '',
            'password' => '',
        ];
    }

    $error = [];
    



    //phpプログラムをpost送信されたときに起動という形にしないと効率悪い
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

       $FormType = isset($_POST['form_type']) ? $_POST['form_type'] : '';       //form_typeには  <input type="hidden" name="form_type" value="login">のvalueの値が入る
        

       if($FormType === 'signup'){
            //signupのpost処理      post内容にpost_typeのvalueがsubmitかどうか$FormTypeを利用かform_typeを使うか→今回は後者を使う

            //id
            $signup_form['id'] = filter_input(INPUT_POST,'id',FILTER_SANITIZE_STRING);
            if($signup_form['id'] === ''){
                $error['id'] = 'blank';
            }elseif(!preg_match('/^[a-zA-Z0-9]+$/', $signup_form['id'])){
                $error['id'] = 'invalid';
            }

            //name
            $signup_form['name'] = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
            if ($signup_form['name'] === ''){
                $error['name'] = 'blank';   //ここてエラー処理を記録してhtml側に連絡して表示する
            }

            
            //password
            $signup_form['password'] = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
            if ($signup_form['password'] === ''){
                $error['password'] = 'blank';   
            } else if ( strlen($signup_form['password']) < 4 || strlen($signup_form['password']) > 10 ){    //passwordの文字数チェック
                $error['password'] = 'length';
            }


            //email
            $signup_form['email'] = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
            if ($signup_form['email'] === ''){
                $error['email'] = 'blank';
            } else {
                //メールアドレスの重複の確認
                $db = dbconnect();
                $stmt = $db->prepare('select count(*) from user where email=?');    //?の部分にユーザが指定したメールアドレスが入ればいい
                if(!$stmt){
                    die($db->error);
                }
                $stmt->bind_param('s', $signup_form['email']);
                $success = $stmt->execute();
                
                if(!$success){
                    die($db->error);
                }
                
                $stmt->bind_result($cnt);   //cntにcountの中身を入れていく
                $stmt->fetch();
                
    
                if($cnt > 0) {
                    $error['email'] = 'duplicate';
                    //メールアドレスの重複があるということはloginフォームで再送するはず→signupフォームの内容を空にしてhtmlで表示する
                   $signup_form['name'] = '';
                   $signup_form['password'] = '';
                 }
    
    
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
                $_SESSION['form'] = $signup_form;      //ここで$_sessionに一気に渡せるようにするために$form,password,emailなどを配列にしている
    
                // // エラーがない場合は login に戻す
                // $currentForm = 'login';
                
                //画像のアップロード
                if($image['name'] !== '' ){
                    $filename = date('YmdHis') . '_' . $image['name'];
                    if( !move_uploaded_file($image['tmp_name'], 'member_picture/' . $filename) ) { //htdocsフォルダの中にmember_pictureっていうファイルを作っとかないといけない。mac開発環境だとフォルダの「情報をみる」から共有権とアクセスを許可しておかないと無理
                        die('ファイルのアップロードに失敗しました');     
                    }
                    $_SESSION['form']['image'] = $filename;
    
                }else{
                    $_SESSION['form']['image'] = '';
                }
    
    
                header('Location: join2/check.php');
                exit();
            } elseif(!empty($error))  {
                    // ********************************************************************************************ここ要修正
                  // エラーが発生した場合は signup のままにする
                    $currentForm = 'signup';

                    // エラー情報をJavaScriptに渡す
                    echo "<script>var errorOccurred = true;</script>";
            }


        } elseif ($FormType === 'login'){
            //login機能の実装
            $login_form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $login_form['password'] = filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING);
            
            //login不可
            if($login_form['email'] === '' || $login_form['password'] === '') {
                $error['login'] = 'blank';

            }else {
                //login可

                //db接続
                $db = dbconnect();
                $stmt = $db->prepare('select id, name, password from user where email=? limit 1');

                if(!$stmt) {
                    die($db->error);
                }

                $stmt->bind_param('s',$login_form['email']);  //db内のパスワードの情報はハッシュ化されているからまずはメールの整合性
                $success = $stmt->execute();
                if(!$success){
                    die($db->error);
                }

                $stmt->bind_result($id,$name,$hash);    //ここでとってきたデータを変数に入れている---->もとは配列で処理
                $stmt->fetch();

                if(password_verify($login_form['password'],$hash)){
                    //ログイン成功
                  session_regenerate_id();
                  $_SESSION['id'] = $id;
                  $_SESSION['name'] = $name;
                  header('Location: index.php');
                  exit();
                }else{
                    //ログイン失敗
                    $error['login'] = 'failed';
                    
                }
            }

            

            
        }



    }

                // エラー検出用の変数を初期化
            $errorOccurred = false;

            // フォームの入力エラーが検出された場合に、エラーフラグを立てる
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($error)) {
                $errorOccurred = true;
            }

            // エラーが検出されていない場合にロゴを表示
            if (!$errorOccurred) {
                echo '<div id="AM">';
                echo '<div id="AM_logo">';
                echo '<img src="images/logo.jpg" alt="Stud-Connect ロゴ" class="fadeUp">';
                echo '</div>';
                echo '</div>';
            }


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/User.css">
    <link rel="stylesheet" href="css/Start.css">
</head>
<body>
<div id="AM">
    <div id="AM_logo">
      <img src="images/logo.jpg" alt="Stud-Connect ロゴ" class="fadeUp">
    </div>
  </div>

    <div class="wrapper">
        <div class="title-text">
            <div class="title login">
                Login Form
            </div>
            <div class="title signup">
                Signup From
            </div>
        </div>
        <div class="form-container">
            <div class="slide-controls">
                <input type="radio" name="slide" id="login">
                <input type="radio" name="slide" id="signup">
                <label for="login" class="slide">Login</label>
                <label for="signup" class="slide">Signup</label>
                <div class="slide-tab"></div>
            </div>
            
            <!-- 送信データ -->
            <div class="form-inner">

                <!-- ログイン内容 -->
                <form action="" class="login" method="post">    

                    <input type="hidden" name="form_type" value="login">

                    <div class="field">
                        <input type="text" name="email" placeholder="Email Address" required value="<?php echo h($login_form['email']); ?>" />

                        <!-- バックエンド処理 (入力エラー)-->
                        <?php if(isset($error['login']) && $error['login'] === 'blank'): ?>
                            <p class="error">＊メールアドレスとパスワードをご記入ください</p>
                        <?php endif; ?>

                        <!-- バックエンド処理　(㏈参照エラー) -->
                        <?php if(isset($error['login']) && $error['login'] === 'failed'): ?>
                            <p class="error">*パスワードが違います。</p>
                        <?php endif; ?>

                    </div>
                    <div class="field">
                        <input type="password" name="password" placeholder="Password" required value="<?php echo h($login_form['password']); ?>" />
                    </div>
                    <div class="pass-link">
                        <a href="Update.html">Forgot password?</a>
                    </div>
                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Login">
                    </div>
                    <div class="signup-link">
                        Not a  member? <a href="">Signup now</a>
                    </div>
                </form>

                <!-- サインイン内容 -->
                <form action="" class="signup" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="form_type" value="signup">

                    <div class="field">
                        <input type="text"  name="name" placeholder="名前" required value="<?php echo h($signup_form['name']); ?>">

                        <!-- バックエンド処理 -->
                        <?php if (isset($error['name']) && $error['name'] === 'blank'): ?>
                            <p class="error">* ニックネームを入力してください</p>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <input type="text" name="id" placeholder="User_ID" required value="<?php echo h($signup_form['id']); ?>">

                        <!-- バックエンド処理 -->
                        <?php if (isset($error['id']) && $error['id'] === 'blank'): ?>
                            <p class="error">* User_IDを入力してください</p>
                        <?php endif; ?>

                        <?php if (isset($error['id']) && $error['id'] === 'invalid'): ?>
                            <p class="error">* アルファベットもしくは数字で入力してください</p>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <input type="text"  name="email" placeholder="Email Address" required value="<?php echo h($signup_form['email']); ?>">

                        <!-- バックエンド処理 -->
                        <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
                            <p class="error">* メールアドレスを入力してください</p>
                        <?php endif; ?>

                        <?php if (isset($error['email']) && $error['email'] === 'duplicate'): ?>
                        <p class="error">* 指定されたメールアドレスはすでに登録されています</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="field">
                        <input type="password"  name="password" placeholder="Password" required value="<?php echo h($signup_form['password']); ?>">

                        <!-- バックエンド処理 -->
                        <!-- 入力処理 -->
                        <?php if (isset($error['password']) && $error['password'] === 'blank'): ?>
                            <p class="error">* 4~10桁のパスワードを入力してください</p>
                        <?php endif; ?>
                        <!-- 文字数処理 -->
                        <?php if(isset($error['password']) && $error['password'] === 'length'): ?>
                        <p class="error">* パスワードは4文字以上10文字以内で入力してください</p>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <input type="file" name="image" value="">

                        <!-- バックエンド処理 -->
                        <?php if (isset($error['image']) && $error['image'] === 'type'): ?>
                            <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                            <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
                        <?php endif; ?>
                    </div>

                    <div class="field btn">
                        <div class="btn-layer"></div>
                        <input type="submit" value="Signup">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/login.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.
cookie.js"></script>
<script src="js/Start.js"></script>
</body>
</html>
