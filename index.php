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
        <div class="tsk_tittle">
            <h1>業務進捗ダッシュボード</h1>
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
                    <a href="search.php">タスク検索</a>
                </div>
                <div class="edit_button">
                    <input type="submit" value="タスク編集">
                </div>
            </div>
            <table class="task">
                <tr class="title">
                    <th class="id">項番</th>
                    <th class="tsk_nm">タスク名称</th>
                    <th class="start">開始日▼▲</th>
                    <th class="end">終了日▼▲</th>
                    <th class="tsk">内容</th>
                    <th class="state">進捗状況</th>
                </tr>
                <?php foreach ($results as $row): ?>

                    <tr class="tsk_content">
                        <td class="id"><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="tsk_nm"><?= htmlspecialchars($row['task_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="start"><?= htmlspecialchars($row['ymd_to'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="end"><?= htmlspecialchars($row['ymd_from'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="tsk"><?= htmlspecialchars($row['task_content'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="state">
                            <?php
                                switch ($row['syori_status']) {
                                    case 1:
                                        echo '未着手';
                                        break;
                                    case 2:
                                        echo '対応中';
                                        break;
                                    case 3:
                                        echo '完了';
                                        break;
                                    default:
                                        echo '不明';
                                }
                            ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </table>

        </form>
    </div>
</div>
<!--フッター-->
<footer>

</footer>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let sortOrder = 1; // 1: 昇順, -1: 降順

        function sortTable(columnClass) {
            const table = document.querySelector(".task");
            const tbody = table.querySelector("tbody") || table; // `tbody` がない場合、`table` で処理
            const rows = Array.from(tbody.querySelectorAll(".tsk_content"));

            // ソート処理
            rows.sort((rowA, rowB) => {
                let dateA = new Date(rowA.querySelector("." + columnClass).textContent.trim());
                let dateB = new Date(rowB.querySelector("." + columnClass).textContent.trim());

                return (dateA - dateB) * sortOrder;
            });

            // ソート順を逆にする（昇順⇔降順）
            sortOrder *= -1;

            // テーブルのデータを並び替え
            rows.forEach(row => tbody.appendChild(row));
        }

        // クリックイベントを設定
        document.querySelector(".start").addEventListener("click", function () {
            sortTable("start");
        });
        document.querySelector(".end").addEventListener("click", function () {
            sortTable("end");
        });
    });
</script>

</body>
</html>