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
    <title>タスク管理一覧</title>
    <link rel="stylesheet" href="sanitize.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!--ヘッダー-->
<header>
    <!--        レイアウト調整領域（ヘッダー）-->
    <div class="header_inner">
        <!--            編集画面遷移用ボタン-->
<!--        <div class="edit_button">-->
<!--            <a href="#">編集</a>-->
<!--        </div>-->
        <div class="tsk_tittle">
            <h1>Task Hub</h1>
        </div>

    </div>
</header>
<!--メイン-->
<div class="main">
    <!--        レイアウト調整領域（メイン）-->
    <div class="main_inner">
        <!--            タスク一覧表示領域-->

        <form action="edit.php" method="post">
            <div class="button_area">
            <!--            検索画面遷移用ボタン-->
                <div class="search_button">
                    <a href="search.php">Search</a>
                </div>
                <div class="edit_button">
                    <input type="submit" value="Edit">
                </div>
            </div>
            <table class="task">
                <tr class="title">
                    <th class="id">ID</th>
                    <th class="tsk_nm">Task Name</th>
                    <th class="start">Start ymd</th>
                    <th class="end">End ymd</th>
                    <th class="tsk">Task details</th>
                    <th class="state">Status</th>
<!--                    <th class="edit">編集</th>-->
                </tr>
                <?php foreach ($results as $row): ?>

                    <tr class="tsk_content">
                        <td class="id"><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="tsk_nm"><?= htmlspecialchars($row['task_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="start"><?= htmlspecialchars($row['ymd_to'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="end"><?= htmlspecialchars($row['ymd_from'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="tsk"><?= htmlspecialchars($row['task_content'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="state"><?= htmlspecialchars($row['syori_status'], ENT_QUOTES, 'UTF-8'); ?></td>
<!--                        <td><input type="submit" value="編集"></td>-->
                    </tr>

                <?php endforeach; ?>
            </table>
        </form>
    </div>
</div>
<!--フッター-->
<footer>

</footer>


</body>
</html>