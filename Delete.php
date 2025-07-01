<?php
    require_once('ConnectInfo.php');

    // インスタンス生成
    $ConnectInfo = new ConnectInfo();

    // ヘッダーでJSONレスポンスであることを指定
    header('Content-Type: application/json');

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
                // 成功メッセージをJSONで返す
                echo json_encode(['success' => true, 'message' => 'レコードが正常に削除されました。']);
            } else {
                // 削除対象が見つからなかった場合
                echo json_encode(['success' => false, 'message' => '削除対象のレコードが見つかりませんでした。']);
            }
        } else {
            // IDが指定されていない場合
            echo json_encode(['success' => false, 'message' => '削除するIDが指定されていません。']);
        }
    } catch (PDOException $e) {
        // エラーハンドリング
        error_log('Delete.php Error: ' . $e->getMessage()); // エラーログに記録

        // ロールバック
        if (isset($conn)) { // 接続が確立されているか確認
            $conn->rollBack();
        }
        // エラーメッセージをJSONで返す
        echo json_encode(['success' => false, 'message' => 'データベースエラーが発生しました。詳細: ' . $e->getMessage()]);
        // die()はJSON出力後に実行されるように調整
    }
?>
