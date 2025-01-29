<?php

 
session_start();
require_once 'funcs.php';
loginCheck();

// POSTデータ取得

$content = $_POST['content'];
$genre = $_POST['genre']; // ジャンルを取得
$user_id = $_SESSION['user_id']; // ログインユーザーidを取得


// 画像アップロードの処理
 

$image = '';


if (isset($_FILES['image'])) {

    $upload_file = $_FILES['image']['tmp_name']; // 一時保存されている場所
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // 拡張子を取得
    $new_name = uniqid() . '.' . $extension; // ユニークな文字列で保存する指示
    $image_path = 'img/' . $new_name;

    if (move_uploaded_file($upload_file, $image_path)) {
        $image = $image_path;
    }
}

// DB接続します
$pdo = db_conn();

// つぶやき登録SQL作成

$stmt = $pdo->prepare('INSERT INTO contents(user_id, content, genre, image, created_at) VALUES (:user_id, :content, :genre, :image, NOW());');
$stmt->bindValue(':content', $content, PDO::PARAM_STR);
$stmt->bindValue(':genre', $genre, PDO::PARAM_STR); // 正しくジャンルをバインド
$stmt->bindValue(':image', $image, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$status = $stmt->execute(); // 実行

 

 

// 4. ライフハック登録処理後

if (!$status) {
    sql_error($stmt);
} else {
    redirect('index.php');
}