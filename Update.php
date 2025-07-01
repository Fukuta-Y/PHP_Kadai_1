<?php
    require_once('ConnectInfo.php');

    // インスタンス生成
    $ConnectInfo = new ConnectInfo();

    $getCnt = "0";
    try {
        // DBコネクションを取得する
        $conn = new PDO(
            $ConnectInfo->getCon(),
            $ConnectInfo->getUser(),
            $ConnectInfo->getPassword(),
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );

        // トランザクションを開始する
        $conn->beginTransaction();

        // ユーザーIDの存在チェック
        $sql = "SELECT COUNT(*) AS CNT FROM T_USER_INFO WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        $stmt->execute();
        $getCnt = $stmt->fetchColumn();

        // 取得結果が0の場合、追加
        if ($getCnt == "0") {
            // 最大IDを取得して、次のIDを計算
            $sql = "SELECT COALESCE(MAX(ID), 0) + 1 AS ID FROM T_USER_INFO";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $ID = $row['ID'];  // 最大ID + 1

            // 初回時はNULLのため、ID = 1
            if ($ID == null || $ID == 0) {
                $ID = '1';
            }

            // すでにID = 1が存在する場合、次のIDを再度確認する
            while (true) {
                // 新しいIDがすでに存在しないことを確認
                $sql = "SELECT COUNT(*) AS CNT FROM T_USER_INFO WHERE ID = :ID";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
                $stmt->execute();
                $checkCount = $stmt->fetchColumn();

                if ($checkCount == "0") {
                    break;  // 一意なIDが見つかったら終了
                } else {
                    $ID++;  // 次のIDを試す
                }
            }

            // データを挿入
            $sql = "INSERT INTO T_USER_INFO (ID, NAME, SEX, POSTNO, ADDRESS1, ADDRESS2, BIKO) 
                        VALUES (:ID, :NAME, :SEX, :POSTNO, :ADDRESS1, :ADDRESS2, :BIKO)";
        } else {
            // 取得結果が1以上の場合、更新
            $sql = "UPDATE T_USER_INFO SET NAME = :NAME, SEX = :SEX, POSTNO = :POSTNO, 
                        ADDRESS1 = :ADDRESS1, ADDRESS2 = :ADDRESS2, BIKO = :BIKO 
                        WHERE ID = :ID";
        }

        // SQL実行準備
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        $stmt->bindParam(':NAME', $NAME, PDO::PARAM_STR);
        $stmt->bindParam(':SEX', $SEX, PDO::PARAM_INT);
        $stmt->bindParam(':POSTNO', $POSTNO, PDO::PARAM_STR);
        $stmt->bindParam(':ADDRESS1', $ADDRESS1, PDO::PARAM_STR);
        $stmt->bindParam(':ADDRESS2', $ADDRESS2, PDO::PARAM_STR);
        $stmt->bindParam(':BIKO', $BIKO, PDO::PARAM_STR);

        // SQL実行
        $stmt->execute();

        // コミット
        $conn->commit();

        // 更新追加件数を取得して表示
        $stmt->rowCount();
    } catch (PDOException $e) {
        // エラーハンドリング
        print('Error: ' . $e->getMessage());
        $conn->rollBack();  // ロールバック
        die();
    }
?>
