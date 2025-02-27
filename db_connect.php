<?php

    // DB接続情報を定数で定義（セキュリティのため直接書かず、環境変数にするのがベスト）
    define('host', 'localhost');
    define('port', '8889');
    define('dbname', 'kensyu_tsk01');
    define('user', 'root');
    define('password', 'root');
    define('charset', 'utf8');

    // DSNの作成
    $dsn = "mysql:host=" . host . ";port=" . port . ";dbname=" . dbname . ";charset=" . charset;

    // PDO接続関数（再利用可能）
    function db_connect() {
        try {
            $pdo = new PDO($GLOBALS['dsn'], user, password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("データベース接続に失敗しました: " . $e->getMessage());
        }
    }
?>