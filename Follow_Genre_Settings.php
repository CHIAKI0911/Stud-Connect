<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォロージャンル画面</title>
    <link rel="stylesheet" href="css/Follow_Genre_Settings.css">
</head>
<body>

    <h1>フォロージャンル画面</h1>

    <table>
        <!-- フォローボタンとジャンル -->
        <tr class="genre-container">
            <td><b><p class="genre">アルバイト</p></b></td>
            <td><button class="follow-button" value="フォロー" onclick="toggleFollow()">フォローする</button></td>
        </tr>

        <tr class="genre-container">
            <td><b><p class="genre">サークル</p></b></td>
            <td><button class="follow-button" value="フォロー" onclick="toggleFollow()">フォローする</button></td>
        </tr>

        <tr class="genre-container">
            <td><b><p class="genre">就活相談</p></b></td>
            <td><button class="follow-button" value="フォロー" onclick="toggleFollow()">フォローする</button></td>
        </tr>

        <tr class="genre-container">
            <td><b><p class="genre">恋愛相談</p></b></td>
            <td><button class="follow-button" value="フォロー" onclick="toggleFollow()">フォローする</button></td>
        </tr>

        <tr class="genre-container">
            <td><b><p class="genre">プログラム</p></b></td>
            <td><button class="follow-button" value="フォロー" onclick="toggleFollow()">フォローする</button></td>
        </tr>

        <tr class="genre-container">
            <td><b><p class="genre">学生生活</p></b></td>
            <td><button class="follow-button" value="フォロー" onclick="toggleFollow()">フォローする</button></td>
        </tr>
    </table>

    <!-- 掲示板画面へのボタンと次へボタン -->
    <div class="navigation-buttons">
        <a href="board-example.html"><button>掲示板画面へ</button></a>
        <a href=""><button>次へ</button></a>
    </div>

    <script>
        function toggleFollow() {
            var followButton = event.target; // クリックされたボタンを取得
            var genreContainer = followButton.closest('.genre-container');
            var followStatus = genreContainer.querySelector('.followStatus');

            if (followButton.innerHTML === 'フォローする') {
                followButton.innerHTML = 'フォロー済み';
                followStatus.style.display = 'block';
            } else {
                followButton.innerHTML = 'フォローする';
                followStatus.style.display = 'none';
            }
        }
    </script>

</body>
</html>