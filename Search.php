<?php
    $dbCnt = 0;
    try{
        $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8', 'root', '',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        // トランザクションを開始する
        $conn->beginTransaction();
        
        $sql = "select";
        $sql .= "    ID";
        $sql .= "   ,NAME";
        $sql .= "   ,CASE SEX WHEN '1' THEN '男' WHEN '2' THEN '女' ELSE '未設定' END AS SEX";
        $sql .= "   ,MID(POSTNO FROM 1 FOR 3) AS POSTNO1";
        $sql .= "   ,MID(POSTNO FROM 4 FOR 4) AS POSTNO2";
        $sql .= "   ,ADDRESS1";
        $sql .= "   ,ADDRESS2";
        $sql .= "   ,BIKO";
        $sql .= " from";
        $sql .= "  T_USER_INFO";
        $sql .= "  WHERE 1=1 ";

        if($ID != null) {
            $sql .= " AND ID = '";
            $sql .= $ID;
            $sql .= "'";
        }

        if($NAME != null) {
            $sql .= " AND NAME LIKE '%";
            $sql .= $NAME;
            $sql .= "%'";
        }

        if($SEX != '0') {
            $sql .= " AND SEX ='";
            $sql .= $SEX;
            $sql .= "'";
        }

        if($POSTNO != null) {
            $sql .= " AND POSTNO ='";
            $sql .= $POSTNO;
            $sql .= "'";
        }

        if($ADDRESS1 != null) {
            $sql .= " AND ADDRESS1 LIKE '%";
            $sql .= $ADDRESS1;
            $sql .= "%'";
        }

        if($ADDRESS2 != null) {
            $sql .= " AND ADDRESS2 LIKE '%";
            $sql .= $ADDRESS2;
            $sql .= "%'";
        }

        if($BIKO != null) {
            $sql .= " AND BIKO LIKE '%";
            $sql .= $BIKO;
            $sql .= "%'";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        // データ件数をカウントする
        foreach($result as $row) {
            $dbCnt++;
        }

        // コミット
        $conn->commit();

    } catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
    }
?>