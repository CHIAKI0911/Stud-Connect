<?php
// ログインしていない場合はログインページにリダイレクト
session_start();
// if (!isset($_SESSION["id"])) {
//    header("Location: login.php");
//    exit;
// }
?>

<!DOCTYPE html>
<html>

<head>
    <title>チャットアプリ</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css"/>
</head>

<body>
    <div id="head" class="container">
        <h1>チャット</h1>
    </div>
    
    <div id="content" class="chat-box">
        <div class="msg">

            <form action="send_message.php" method="post" class="chat-form">
                <dl>
                    <dt><label for="message">メッセージを入力してください:</label></dt>
                </dl>
                <input type="text" id="message" name="message" required>
                <button type="submit">送信</button>
            </form>

            <br>
            <button onclick="location.href='Group_home.php'">部屋選択に戻る</button>
            <br><hr>

<?php
$pdo = new PDO(
    "mysql:dbname=stud-conect;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
);

                $rid = $_SESSION['rid'];

                // メッセージを取得
                $regist = $pdo->prepare("SELECT * FROM message ORDER BY id DESC");
                $regist->execute();

                $num_rows = 0;

                    while ($row = $regist->fetch()) {
                        echo "<p>" . $row['massage'] . "</p>";
                        echo "<p class=day>" . $row['created'] ;"</p>";
                    }

                ?>
            </div>
        </div>
    <script>
        // チャットボックスが常に下にスクロールされるようにする関数
        window.onload = () => {
            let chatBox = document.querySelector(".chat-box");
            chatBox.scrollTop = chatBox.scrollHeight;
        };
    </script>
</body>


</html>