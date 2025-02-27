<?php

require_once "db_connect.php"; // DB接続ファイルを読み込む
$pdo = db_connect(); // DB接続を取得
$stmt = $pdo->prepare('SELECT * FROM task_list WHERE del_flg = 0 ORDER BY id');
if(!$stmt) {
    die(print_r($pdo->errorInfo(), true));
}
$stmt->execute();

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!doctype html>
<html lang=ja>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>編集・削除画面</title>
    <link rel="stylesheet" href="sanitize.css">
    <link rel="stylesheet" href="style_edit.css">
</head>
<body>
<!--ヘッダー-->
<header>
    <!--        レイアウト調整領域（ヘッダー）-->
    <div class="header_inner">
        <div class="tsk_tittle">
            <h1>タスク編集画面</h1>
        </div>
    </div>
</header>
<!--メイン-->
<div class="main">
    <!--        レイアウト調整領域（メイン）-->
    <div class="main_inner">
        <!--            タスク一覧表示領域-->

        <form action="update.php" method="post">
            <div class="button_area">
                <!--            一覧画面遷移用ボタン-->
                <div class="allnemu_button">
                    <a href="index.php">タスク一覧へ戻る</a>
                </div>
                <div class="update_button">
                    <input type="submit" value="タスクを更新">
                </div>
            </div>
            <table class="task">
                <tr class="title">
                    <th>項番</th>
                    <th>タスク名称</th>
                    <th>開始日</th>
                    <th>終了日</th>
                    <th>内容</th>
                    <th>進捗状況</th>
                    <th>削除区分</th>
                </tr>
                <?php foreach ($tasks as $index => $task): ?>
                    <tr class="tsk_content">
                        <td>
                            <!-- ID を表示（編集不可） -->
                            <input type="text" name="tasks[<?= $index ?>][id]" value="<?= htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                            <!-- ID を隠しフィールドとしても送信 -->
                            <input type="hidden" name="tasks[<?= $index ?>][id_hidden]" value="<?= htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <input type="text" name="tasks[<?= $index ?>][task_name]" value="<?= htmlspecialchars($task['task_name'], ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <input type="date" name="tasks[<?= $index ?>][ymd_to]" value="<?= $task['ymd_to'] ?>">
                        </td>
                        <td>
                            <input type="date" name="tasks[<?= $index ?>][ymd_from]" value="<?= $task['ymd_from'] ?>">
                        </td>
                        <td>
                            <input type="text" name="tasks[<?= $index ?>][task_content]" value="<?= htmlspecialchars($task['task_content'], ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <select name="tasks[<?= $index ?>][syori_status]">
                                <option value=1 <?= $task['syori_status'] == 1 ? 'selected' : '' ?>>未着手</option>
                                <option value=2 <?= $task['syori_status'] == 2 ? 'selected' : '' ?>>対応中</option>
                                <option value=3 <?= $task['syori_status'] == 3 ? 'selected' : '' ?>>完了</option>
                            </select>
                        </td>
                        <td>
                            <select name="tasks[<?= $index ?>][del_flg]">
                                <option value=0 <?= $task['del_flg'] == 0 ? 'selected' : '' ?>>有効</option>
                                <option value=1 <?= $task['del_flg'] == 1 ? 'selected' : '' ?>>削除</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    </div>
</div>
<footer>

</footer>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("select[name$='[syori_status]']").forEach(select => {
            select.addEventListener("change", function () {
                const taskRow = this.closest("tr"); // 選択された行の `<tr>` を取得
                const taskId = taskRow.querySelector("input[name$='[id_hidden]']").value; // 隠しID取得
                const newStatus = this.value; // 新しいステータス値を取得

                console.log("送信するID:", taskId);
                console.log("送信する新ステータス:", newStatus);
                console.log("this:", this);
                console.log("親要素:", this.parentElement);
                console.log("祖先要素:", this.closest("tr"));
                console.log("taskRow:", taskRow);

                // 送信データを作成
                const formData = new FormData();
                formData.append("id", taskId);
                formData.append("syori_status", newStatus);

                // 非同期通信でデータを送信
                fetch("update_status.php", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log("サーバーからのレスポンス:", data);
                        if (data.success) {
                            alert("ステータスの更新を行いました。");
                        } else {
                            alert("ステータスの更新に失敗しました。");
                            console.error("エラー詳細:", data);
                        }
                    })
                    .catch(error => {
                        console.error("通信エラー:", error);
                        alert("通信エラーが発生しました。");
                    });
            });
        });
        document.querySelectorAll("input[type='date']").forEach(input => {
            input.addEventListener("change", function() {
                const datePattern = /^\d{4}\/\d{2}\/\d{2}$/; // yyyy/mm/dd フォーマット
                const value = this.value.replace(/-/g, "/"); // ハイフンをスラッシュに変換

                if (!datePattern.test(value)) {
                    alert("日付は yyyy/mm/dd の形式で入力してください。");
                    this.value = ""; // 入力をクリア
                }
            });
        });
    });
</script>
</body>
</html>