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
        // GETパラメータのIDがセットされているか確認し、そうでなければエラーとする
        if (isset($_GET['id'])) {
            $stmt->bindParam(':ID', $_GET['id'], PDO::PARAM_STR);
            $stmt->execute();

        // 削除件数を取得する
        $deleteCount = $stmt->rowCount();

        // コミット
        $conn->commit();

            if ($deleteCount > 0) {
                $response['success'] = true;
                $response['message'] = 'レコードが正常に削除されました。';
            } else {
                $response['message'] = '指定されたIDのレコードが見つかりませんでした。';
            }
        } else {
            $response['message'] = '削除するIDが指定されていません。';
        }
    } catch (PDOException $e) {
        // エラーハンドリング
        print('Error:' . $e->getMessage());
        $response['message'] = 'エラーが発生しました: ' . $e->getMessage();
        // ロールバック
        if (isset($conn)) {
            $conn->rollBack();
        }
    } finally {
        // JSON形式でレスポンスを出力し、スクリプトの実行を停止
        echo json_encode($response);
        exit();
    }
?>
