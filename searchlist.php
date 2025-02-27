<?php

require_once "db_connect.php"; // DB接続ファイルを読み込む
$pdo = db_connect(); // DB接続を取得

// フォームからのデータ取得
var_dump($_POST);
$task_name = $_POST['task_name'] ?? '';
$str_ymd_search = $_POST['str_ymd_search'] ?? '';
$end_ymd_search = $_POST['end_ymd_search'] ?? '';
$syori_status = $_POST['syori_status'] ?? '';

// 検索処理
$results = [];

if (!empty($task_name) || !empty($str_ymd_search) || !empty($end_ymd_search) || !empty($syori_status)) {
    // 検索用SQLの初期化
    $sql = "SELECT * FROM task_list WHERE 1=1";
    $params = [];

    // タスク名検索の条件を追加
    if (!empty($task_name)) {
        $sql .= " AND task_name LIKE :task_name AND del_flg=0";
        $params[':task_name'] = "%{$task_name}%";
    }

    if (!empty($str_ymd_search) && !empty($emd_ymd_search)) {

        // SQL文で `BETWEEN` を使用
        $sql .= " AND (ymd_to BETWEEN :str_ymd_search AND :emd_ymd_search) AND (ymd_from BETWEEN :str_ymd_search AND :emd_ymd_search) AND del_flg=0";
        $params[':str_ymd_search'] = $str_ymd_search;
        $params[':emd_ymd_search'] = $emd_ymd_search;
    }

// デバッグ用ログ
    error_log("実行SQL: " . $sql);
    foreach ($params as $key => $value) {
        error_log("バインド: $key = $value");
    }
    // ステータス検索の条件を追加
    if (!empty($syori_status)) {
        $sql .= " AND syori_status = :syori_status AND del_flg=0";
        $params[':syori_status'] = $syori_status;
    }

    // SQL実行準備
    $stmt = $pdo->prepare($sql);

    // バインドパラメータの設定
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    // SQL実行
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    ver_dump($results);
}
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>検索結果一覧</title>
    <link rel="stylesheet" href="sanitize.css">
    <link rel="stylesheet" href="style_search_list.css">
</head>
<body>
    <header>
        <div class="tsk_tittle">
            <h1>検索結果</h1>
        </div>
    </header>
<!--メイン-->
    <div class="main">
        <div class="main_inner">
            <div class="button_area">
                <div class="allnemu_button">
                    <a href='index.php'>タスク一覧へ戻る</a>
                </div>
                <div class="search_button">
                    <a href="search.php">検索画面へ戻る</a>
                </div>
            </div>
            <div class="status">
                <?php
                if (count($results) > 0) {
                    // `syori_status` のユニークな値を取得
                    $statuses = array_unique(array_column($results, 'syori_status'));


                    foreach ($statuses as $status) {
                        echo "<p>ステータス：";
                        switch ($status) {
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
                        echo "</p>";
                    }
                ?>
            </div>
                <table class="task">
                    <tr class="title">
                        <th>項番</th>
                        <th>タスク名称</th>
                        <th>開始日</th>
                        <th>終了日</th>
                        <th>内容</th>
                    </tr>
                    <?php foreach ($results as $row): ?>
                    <tr class="tsk_content">
                        <td class="id"><?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="tsk_nm"><?= htmlspecialchars($row['task_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="start"><?= htmlspecialchars($row['ymd_to'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="end"><?= htmlspecialchars($row['ymd_from'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="tsk"><?= htmlspecialchars($row['task_content'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php } else {
                    echo "対象の一覧がありません。";
                }
?>
        </div>
    </div>
<!--フッター-->
    <footer>

    </footer>
</body>
</html>