<?php

    // 変数 `$updateMessages` を初期化（← ここが重要！）
    $updateMessages = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // DB接続設定
        $host = 'localhost';
        $dbname = 'kensyu_tsk01';
        $user = 'root';
        $password = 'root';

        // DSN文字列（MySQLの場合）
        $dsn = "mysql:host=$host;port=8889;dbname=$dbname;charset=utf8";
        try {
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("データベース接続に失敗しました: " . $e->getMessage());
        }

        // `POST` データを配列として取得
        $tasks = $_POST['tasks']; // 配列で受け取ることを想定

        // 現在のデータを取得
        $sql_check = "SELECT * FROM task_list WHERE id = :id";
        $stmt_check = $pdo->prepare($sql_check);

        foreach ($tasks as $task) {
            $stmt_check->bindValue(':id', (int)$task['id'], PDO::PARAM_INT);
            $stmt_check->execute();
            $currentData = $stmt_check->fetch(PDO::FETCH_ASSOC);

            // 変更があったかチェック
            if (
                $currentData['task_name'] != $task['task_name'] ||
                $currentData['ymd_to'] != $task['ymd_to'] ||
                $currentData['ymd_from'] != $task['ymd_from'] ||
                $currentData['task_content'] != $task['task_content'] ||
                $currentData['syori_status'] != $task['syori_status'] ||
                $currentData['del_flg'] != $task['del_flg']
            ) {
                $id = $task['id'];
                $task_name = $task['task_name'];
                $ymd_to = $task['ymd_to'];
                $ymd_from = $task['ymd_from'];
                $task_content = $task['task_content'];
                $syori_status = $task['syori_status'];
                $del_flg = $task['del_flg'];

                // UPDATE文の準備（1レコードずつ更新）
                $sql = "UPDATE task_list 
                    SET task_name = :task_name,
                        ymd_to = :ymd_to,
                        ymd_from = :ymd_from,
                        task_content = :task_content,
                        syori_status = :syori_status,
                        del_flg = :del_flg
                    WHERE id = :id";

                $stmt_update = $pdo->prepare($sql);

                // 各レコードごとにバインドする
                $stmt_update->bindValue(':id', (int)$id, PDO::PARAM_INT);
                $stmt_update->bindValue(':task_name', (string)$task_name, PDO::PARAM_STR);
                $stmt_update->bindValue(':ymd_to', (string)$ymd_to, PDO::PARAM_STR);
                $stmt_update->bindValue(':ymd_from', (string)$ymd_from, PDO::PARAM_STR);
                $stmt_update->bindValue(':task_content', (string)$task_content, PDO::PARAM_STR);
                $stmt_update->bindValue(':syori_status', (int)$syori_status, PDO::PARAM_INT);
                $stmt_update->bindValue(':del_flg', (int)$del_flg, PDO::PARAM_INT);
                // 変更があった場合に UPDATE を実行
                if ($stmt_update->execute()) {
                    $updateMessages .= "<p class='update-success'>task update success !!! (ID: {$task['id']})</p>";
                } else {
                    $updateMessages .= "<p class='update-fail'>task update fail ×× (ID: {$task['id']})</p>";
                }
            } else {
//                $updateMessages .= "<p class='update-nochange'>No changes detected for ID: {$task['id']}</p>";
            }
        }
    }
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>更新結果確認画面</title>
    <link rel="stylesheet" href="sanitize.css">
    <link rel="stylesheet" href="style_update.css">
</head>
<body>
    <div class="main">
        <div class="main_inner">
            <div class="button_area">
                <div class="allmenu_button">
                    <a href="index.php">All menu</a>
                </div>
            </div>
            <div class="update_output">
                <?= $updateMessages ?>

            </div>
        </div>
    </div>
</body>
</html>
