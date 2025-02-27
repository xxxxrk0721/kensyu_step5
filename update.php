<?php

    // 変数 `$updateMessages` を初期化（← ここが重要！）
    $updateMessages = "";
    $tskMessages = "";
    $tskcontentMessages = "";
    $noChangeCount = 0; // 変更がなかった回数をカウント
    $valCheck = 0; // バリエーションチェック用のフラグ

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        require_once "db_connect.php"; // DB接続ファイルを読み込む
        $pdo = db_connect(); // DB接続を取得

        // `POST` データを配列として取得
        $tasks = $_POST['tasks']; // 配列で受け取ることを想定

        // 現在のデータを取得
        $sql_check = "SELECT * FROM task_list WHERE id = :id";
        $stmt_check = $pdo->prepare($sql_check);

        foreach ($tasks as $task) {
            $stmt_check->bindValue(':id', (int)$task['id'], PDO::PARAM_INT);
            $stmt_check->execute();
            $currentData = $stmt_check->fetch(PDO::FETCH_ASSOC);

            $task_name = trim($task['task_name'] ?? "");
            $length_task_name = mb_strlen($task_name, "UTF-8");
            $task_content = trim($task['task_content'] ?? "");
            $length_task_content = mb_strlen($task_content, "UTF-8");

            $valCheck = 0;

            if ($length_task_name > 19 || $length_task_name < 1) {
                $tskMessages .= "<p class='not-success'>タスク名を20文字以下、1文字以上で入力してください (項番: {$task['id']})</p>";
                $valCheck = 1;
            }

            if ($length_task_content > 49 || $length_task_content < 1) {
                $tskcontentMessages .= "<p class='not-success'>タスク内容を50文字以下、1文字以上で入力してください (項番: {$task['id']})</p>";
                $valCheck = 1;
            }

            if ($valCheck === 1) {
//                なにもしない
            } else {
                // 変更があったかチェック
                if (
                    $currentData['task_name'] != $task['task_name'] ||
                    $currentData['ymd_to'] != $task['ymd_to'] ||
                    $currentData['ymd_from'] != $task['ymd_from'] ||
                    $currentData['task_content'] != $task['task_content'] ||
                    $currentData['del_flg'] != $task['del_flg']
                ) {
                        $id = $task['id'];
                        $task_name = $task['task_name'];
                        $ymd_to = $task['ymd_to'];
                        $ymd_from = $task['ymd_from'];
                        $task_content = $task['task_content'];
                        $del_flg = $task['del_flg'];

                        // UPDATE文の準備（1レコードずつ更新）
                        $sql = "UPDATE task_list 
                        SET task_name = :task_name,
                            ymd_to = :ymd_to,
                            ymd_from = :ymd_from,
                            task_content = :task_content,
                            del_flg = :del_flg
                        WHERE id = :id";

                        $stmt_update = $pdo->prepare($sql);

                        // 各レコードごとにバインドする
                        $stmt_update->bindValue(':id', (int)$id, PDO::PARAM_INT);
                        $stmt_update->bindValue(':task_name', (string)$task_name, PDO::PARAM_STR);
                        $stmt_update->bindValue(':ymd_to', (string)$ymd_to, PDO::PARAM_STR);
                        $stmt_update->bindValue(':ymd_from', (string)$ymd_from, PDO::PARAM_STR);
                        $stmt_update->bindValue(':task_content', (string)$task_content, PDO::PARAM_STR);
                        $stmt_update->bindValue(':del_flg', (int)$del_flg, PDO::PARAM_INT);
                        // 変更があった場合に UPDATE を実行
                        if ($stmt_update->execute()) {
                            $updateMessages .= "<p class='update-success'>タスクの内容を更新しました !!! (項番: {$task['id']})</p>";
                        } else {
                            $updateMessages .= "<p class='update-fail'>タスクの更新ができませんでした ×× (項番: {$task['id']})</p>";
                        }
    //                }
                } else {
                    $noChangeCount++; // 変更がなかった回数をカウント
                }
            }
        }
        if ($noChangeCount === count($tasks)) {
            $updateMessages .= "<p class='update-nochange'>変更した項目はありません</p>";
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
                    <a href="index.php">タスク一覧へ戻る</a>
                </div>
                <div class="edit_button">
                    <a href="edit.php">タスクを編集</a>
                </div>
            </div>
            <div class="update_output">
                <?= $updateMessages ?>
                <?= $tskMessages ?>
                <?= $tskcontentMessages ?>

            </div>
        </div>
    </div>
</body>
</html>
