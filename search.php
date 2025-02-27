<?php

require_once "db_connect.php"; // DB接続ファイルを読み込む
$pdo = db_connect(); // DB接続を取得
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
            <h1>タスク検索画面</h1>
        </div>
    </header>
<!--メイン-->
    <div class="main">
        <div class="main_inner">
            <div class="button_area">
                <!--            一覧画面遷移用ボタン-->
                <div class="allmenu_button">
                    <a href="index.php">タスク一覧へ戻る</a>
                </div>

<!--                ステータス検索-->
                <form action="searchlist.php" method="post">
                    <div class="status_name">
                        <p>ステータス:</p>
                    </div>
                    <select name="syori_status">
                        <option value=1>未着手</option>
                        <option value=2>対応中</option>
                        <option value=3>完了</option>
                    </select>
<!--                タスク名検索-->
                    <div class="tsk_name">
                        <p>タスク名称:</p>
                    </div>
                    <input type="text" name="task_name" value="">
<!--                日付検索-->
                    <div class="ymd_name">
                        <p>開始日:</p>
                    </div>
                    <input type="date" name="str_ymd_search" value="">
                    <div class="ymd_name">
                        <p>終了日:</p>
                    </div>
                    <input type="date" name="emd_ymd_search" value="">
                    <input type="submit" value="検索">
                </form>
            </div>
        </div>
    </div>
</body>
</html>