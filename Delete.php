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
        $stmt->bindParam(':ID', $_GET['id'], PDO::PARAM_STR);
        $stmt->execute();

        // 削除件数を取得する
        $deleteCount = $stmt->rowCount();

        // コミット
        $conn->commit();

        // 削除に成功した場合、ウィンドウを閉じるスクリプトを出力
        if ($deleteCount > 0) { // 0件より大きい場合（削除された場合）
            echo "<script type='text/javascript'>window.close();</script>";
        } else {
            // 削除対象が見つからなかった場合などの処理をここに追加することもできます
            // 例: echo "削除対象のレコードが見つかりませんでした。";
        }
    } catch (PDOException $e) {
        // エラーハンドリング
        print('Error:' . $e->getMessage());
        // ロールバック
        $conn->rollBack();
        die(); // スクリプトの実行を停止
    }
?>
