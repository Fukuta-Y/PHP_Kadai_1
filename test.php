<?php
$host = 'aws-0-ap-northeast-1.pooler.supabase.com'; // PostgreSQLのホスト名
$port = '5432';  // PostgreSQLのポート
$dbname = 'postgres'; // データベース名
$user = 'postgres.szdcftaezxmhxjxqdeyi'; // ユーザー名
$password = 'Yuki1008!!!!'; // パスワード

// PDOで接続を試みる
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $conn = new PDO($dsn, $user, $password);
    
    // 接続成功した場合
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // エラーモードを設定
    echo "PostgreSQL connection successful!"; // 成功メッセージ
} catch (PDOException $e) {
    // エラーが発生した場合
    echo "Connection failed: " . $e->getMessage();
}
?>
