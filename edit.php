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
            <h1>Edit task</h1>
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
                    <a href="index.php">All menu</a>
                </div>
                <div class="update_button">
                    <input type="submit" value="Update">
                </div>
            </div>
            <table class="task">
                <tr class="title">
                    <th>ID</th>
                    <th>Task name</th>
                    <th>Start ymd</th>
                    <th>End ymd</th>
                    <th>Task details</th>
                    <th>Status</th>
                    <th>Del flg</th>
                </tr>
                <?php foreach ($tasks as $index => $task): ?>
    <!--                <form action="update.php" method="post">-->
                    <tr class="tsk_content">

                        <td>
                            <!-- ID を表示（編集不可） -->
                            <input type="text" name="tasks[<?= $index ?>][id]" value="<?= htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                            <!-- ID を隠しフィールドとしても送信 -->
                            <input type="hidden" name="tasks[<?= $index ?>][id_hidden]" value="<?= htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <input type="text" name="tasks[<?= $index ?>][task_name]" value="<?= htmlspecialchars($task['task_name'], ENT_QUOTES, 'UTF-8'); ?>">
<!--                            <input type="text" name="task_name" value="--><?php //= htmlspecialchars($row['task_name'], ENT_QUOTES, 'UTF-8'); ?><!--">-->
                        </td>
                        <td>
                            <input type="date" name="tasks[<?= $index ?>][ymd_to]" value="<?= $task['ymd_to'] ?>">
<!--                            <input type="date" name="ymd_to" value="--><?php //= htmlspecialchars($row['ymd_to'], ENT_QUOTES, 'UTF-8'); ?><!--">-->
                        </td>
                        <td>
                            <input type="date" name="tasks[<?= $index ?>][ymd_from]" value="<?= $task['ymd_from'] ?>">
<!--                            <input type="date" name="ymd_from" value="--><?php //= htmlspecialchars($row['ymd_from'], ENT_QUOTES, 'UTF-8'); ?><!--">-->
                        </td>
                        <td>
                            <input type="text" name="tasks[<?= $index ?>][task_content]" value="<?= htmlspecialchars($task['task_content'], ENT_QUOTES, 'UTF-8'); ?>">
<!--                            <input type="text" name="task_content" value="--><?php //= htmlspecialchars($row['task_content'], ENT_QUOTES, 'UTF-8'); ?><!--">-->
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
<!--                        <td>-->
<!--                            <input type="submit" value="更新">-->
<!--                        </td>-->
                    </tr>

    <!--                <form action="delete.php" method="post">-->
    <!--                    <input type="hidden" name="id" value="--><?php //= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?><!--">-->
    <!--                    <input type="submit" value="削除">-->
    <!--                </form>-->
                <?php endforeach; ?>
            </table>
        </form>
    </div>
</div>
<footer>

</footer>
</body>
</html>