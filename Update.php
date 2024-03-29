<?php
    require_once('ConnectInfo.php');

    // インスタンス生成
    $ConnectInfo = new ConnectInfo();

    $getCnt = "0";
    try {

        // DBコネクションを取得する
        $conn = new PDO($ConnectInfo->getCon(), $ConnectInfo->getUser(), '', 
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        // トランザクションを開始する
        $conn->beginTransaction();
        $sql = "SELECT COUNT(*) AS CNT FROM T_USER_INFO WHERE ID = :ID";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();

        // データ件数を取得する
        foreach($result as $row) {
            $getCnt = $row['CNT'];
        }

        // 取得結果が0の場合、追加
        if($getCnt == "0" ) {
            $sql = "SELECT MAX(ID) + 1 AS ID FROM T_USER_INFO";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            // 最大IDを取得する
            foreach($result as $row) {
                $ID = $row['ID'];
            }

            // 初回時はNULLのため
            if($ID == null) {
                $ID ='1';
            }

            $sql = " insert into ";
            $sql .= " T_USER_INFO (";
            $sql .= "    ID";
            $sql .= "   ,NAME";
            $sql .= "   ,SEX";
            $sql .= "   ,POSTNO";
            $sql .= "   ,ADDRESS1";
            $sql .= "   ,ADDRESS2";
            $sql .= "   ,BIKO";
            $sql .= "   ) VALUES (";
            $sql .= "    :ID";
            $sql .= "   ,:NAME";
            $sql .= "   ,:SEX";
            $sql .= "   ,:POSTNO";
            $sql .= "   ,:ADDRESS1";
            $sql .= "   ,:ADDRESS2";
            $sql .= "   ,:BIKO";
            $sql .= " )";
        } else {
            // 取得結果が1以上の場合、更新
            $sql = " update ";
            $sql .= " T_USER_INFO ";
            $sql .= "   set";
            $sql .= "  NAME=:NAME";
            $sql .= " ,SEX=:SEX";
            $sql .= " ,POSTNO=:POSTNO";
            $sql .= " ,ADDRESS1=:ADDRESS1";
            $sql .= " ,ADDRESS2=:ADDRESS2";
            $sql .= " ,BIKO=:BIKO";
            $sql .= " WHERE ID =:ID";
        }

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

        // 更新追加件数を取得して表示する
        $stmt->rowCount();

    } catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        // ロールバック
        $conn->rollBack();
        die();
    }
?>