<?php
// DB接続設定
$host     = 'localhost';
$dbname   = 'kensyu_tsk01';
$user     = 'root';
$password = 'root';

// DSN文字列（MySQLの場合）
$dsn = "mysql:host=$host;port=8889;dbname=$dbname;charset=utf8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続に失敗しました: " . $e->getMessage());
}
$stmt = $pdo->prepare('SELECT * FROM task_list WHERE del_flg = 0 ORDER BY id');
if(!$stmt) {
    die(print_r($pdo->errorInfo(), true));
}
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>検索画面</title>
    <link rel="stylesheet" href="sanitize.css">
    <link rel="stylesheet" href="style_search.css">
</head>
<body>
    <header>
        <div class="tsk_tittle">
            <h1>Search Page</h1>
        </div>
    </header>
<!--メイン-->
    <div class="main">
        <div class="main_inner">
            <div class="button_area">
                <!--            一覧画面遷移用ボタン-->
                <div class="allmenu_button">
                    <a href="index.php">All menu</a>
                </div>

<!--                ステータス検索-->
                <form action="searchlist.php" method="post">
                    <div class="status_name">
                        <p>status search:</p>
                    </div>
                    <select name="syori_status">
                        <option value=1>未着手</option>
                        <option value=2>対応中</option>
                        <option value=3>完了</option>
                    </select>
                    <input type="submit" value="Search">
                </form>
<!--                タスク名検索-->
                <form action="searchlist.php" method="post">
                    <div class="tsk_name">
                        <p>task name search:</p>
                    </div>
                    <input type="text" name="task_name" value="">
                    <input type="submit" value="Search">
                </form>
<!--                日付検索-->
                <form action="searchlist.php" method="post">
                    <div class="ymd_name">
                        <p>ymd search:</p>
                    </div>
                    <input type="text" name="ymd_search" value="">
                    <input type="submit" value="Search">
                </form>
            </div>
        </div>
    </div>
</body>
</html>