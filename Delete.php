<?php
    require_once('ConnectInfo.php');

    // インスタンス生成
    $ConnectInfo = new ConnectInfo();

    try {
        // PostgreSQL データベースへの接続
        $conn = new PDO(
            $ConnectInfo->getCon(),               // 接続情報
            $ConnectInfo->getUser(),              // ユーザー名
            $ConnectInfo->getPassword(),          // パスワード
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) // エラーモード設定
        );

        // トランザクションを開始する
        $conn->beginTransaction();

        // DELETE処理
        // テーブル名とカラム名を二重引用符で囲む
        $sql = "DELETE FROM \"T_USER_INFO\" WHERE \"ID\" = :ID";
        $stmt = $conn->prepare($sql);

        // GETパラメータのIDがセットされているか確認（安全のため）
        if (isset($_GET['id'])) {
            $stmt->bindParam(':ID', $_GET['id'], PDO::PARAM_STR);
            $stmt->execute();

            // 削除件数を取得する
            $deleteCount = $stmt->rowCount();

            // コミット
            $conn->commit();

            // 削除に成功した場合
            if ($deleteCount > 0) {
                // 親ウィンドウをリロードしてから、小窓を閉じる
                echo "<script type='text/javascript'>";
                echo "if (window.opener) {"; // 親ウィンドウが存在するか確認
                echo "    window.opener.location.reload();"; // 親ウィンドウをリロード
                echo "}";
                echo "window.close();"; // この小窓を閉じる
                echo "</script>";
            } else {
                // 削除対象が見つからなかった場合など、メッセージを表示して小窓を閉じる
                echo "<script type='text/javascript'>";
                echo "alert('削除対象のレコードが見つかりませんでした。');"; // 必要であればメッセージ表示
                echo "window.close();"; // 小窓を閉じる
                echo "</script>";
            }
        } else {
            // IDが指定されていない場合
            echo "<script type='text/javascript'>";
            echo "alert('削除するIDが指定されていません。');"; // メッセージ表示
            echo "window.close();"; // 小窓を閉じる
            echo "</script>";
        }
    } catch (PDOException $e) {
        // エラーハンドリング
        error_log('Delete.php Error: ' . $e->getMessage()); // エラーログに記録
        // エラーメッセージを小窓に表示してから閉じる
        echo "<script type='text/javascript'>";
        echo "alert('データベースエラーが発生しました。詳細: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "');";
        // ロールバック
        if (isset($conn)) { // 接続が確立されているか確認
            $conn->rollBack();
        }
        echo "window.close();"; // エラー時も小窓を閉じる
        echo "</script>";
        die(); // スクリプトの実行を停止
    }
?>