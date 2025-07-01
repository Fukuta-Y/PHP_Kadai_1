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
        $sql = "DELETE FROM T_USER_INFO WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $_GET['id'], PDO::PARAM_STR);
        $stmt->execute();

        // 削除件数を取得して表示する
        $deleteCount = $stmt->rowCount();
        
        // コミット
        $conn->commit();

        // 削除に成功した場合
        if($deleteCount <> 0) {
            echo "<script type='text/javascript'>window.close();</script>";
        }

    } catch (PDOException $e) { 
        print('Error:'.$e->getMessage());
        // ロールバック
        $conn->rollBack();
        die();
    }
?>