<?php

session_start();
require_once 'funcs.php';
loginCheck();
//データベース接続

$pdo = db_conn();

//投稿内容を取得するSQL

$stmt = $pdo->prepare('SELECT * FROM contents ORDER BY created_at DESC;');
$status = $stmt->execute();

//投稿内容の取得

$posts = [];
if (!$status) {
    sql_error($stmt);

} else {
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ライフハック登録</title>

    <link rel="stylesheet" href="css/common.css" />
    <link rel="stylesheet" href="css/index.css" />

</head>

 

<body>
    <!-- Head[Start] -->

    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header"><a class="navbar-brand" href="logout.php">ログアウト</a></div>
                <div class="navbar-header user-name"><p><?= $_SESSION['user_name'] ?></p></div>

            </div>
        </nav>
    </header>

    <!-- Head[End] -->

    <!-- 投稿フォーム[Start] -->
    <form method="POST" action="insert.php" enctype="multipart/form-data">
        <div class="jumbotron">
            <fieldset>
                <legend>ライフハック</legend>
                <div> 
                    <label for="content">内容：</label>
                    <textarea id="content" name="content" rows="4" cols="40"></textarea>
                </div>

                <div>
                    <label for="genre">ジャンル：</label>
                    <select name="genre" id="genre">
                        <option value="cook">料理</option>
                        <option value="laundry">洗濯</option>
                        <option value="cleaning">掃除</option>
                        <option value="housework">家事</option>
                        <option value="storage">収納</option>
                        <option value="trip">旅行</option>
                        <option value="IT">IT</option>
                        <option value="school">学校</option>
                        <option value="other">その他</option>

                    </select>

                </div>

                <div>

                    <label for="image">動画・画像：</label>
                    <input type="file" name="image" id="image">
                </div>               

                <div>
                <input type="submit" value="送信">
                </div>
            </fieldset>

        </div>
    </form>

    <!-- 投稿フォーム[End] -->

<!-- 投稿リスト[Start] -->

<div class="post-list">
    <h2>投稿一覧</h2>
    <div class="card-container"> <!-- カードを包むコンテナ -->
        <?php foreach ($posts as $post): ?>
            <div class="card"> <!-- 各投稿をカードとして表示 -->
                <h3><?= h($post['content']) ?> (ジャンル: <?= h($post['genre']) ?>)</h3>
                <p>投稿日時: <?= h($post['created_at']) ?></p>
                <?php if (!empty($post['image'])): ?> <!-- 画像がある場合は表示 -->
               
 <img src="<?= h($post['image']) ?>" alt="投稿画像" class="post-image"><p></p>
                <?php endif; ?>
                <a href="detail.php?id=<?= h($post['id']) ?>" class="detail-link">詳細</a>
                <a href="delete.php?id=<?= h($post['id']) ?>" class="delete-link">削除</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>




<!-- 投稿リスト[End] -->

 

</body>

</html>


