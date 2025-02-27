<?php
// JSONヘッダーを最初に設定
header("Content-Type: application/json");

// ログファイルの設定
$logFile = "error_log.txt"; // エラーログを出力するファイル名

require_once "db_connect.php"; // DB接続ファイルを読み込む
$pdo = db_connect(); // DB接続を取得

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskId = $_POST["id"] ?? null;
    $newStatus = $_POST["syori_status"] ?? null;

    if (isset($taskId, $newStatus)) {
        try {
            $stmt = $pdo->prepare("UPDATE task_list SET syori_status = ? WHERE id = ?");
            $success = $stmt->execute([$newStatus, $taskId]);

            echo json_encode(["success" => $success]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "パラメータが不足しています"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "不正なリクエスト"]);
}
?>