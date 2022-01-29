<?php
    $dbCnt = 0;
    try{
        $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8', 'root', '',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

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

        // 検索条件の設定
        if($ID != null) {
            $sql .= " AND ID = :ID";
        }

        if($NAME != null) {
            $sql .= " AND NAME LIKE :NAME";
        }

        if($SEX != "0") {
            $sql .= " AND SEX = :SEX";
        }

        if($POSTNO != null) {
            $sql .= " AND POSTNO =:POSTNO";
        }

        if($ADDRESS1 != null) {
            $sql .= " AND ADDRESS1 LIKE :ADDRESS1";
        }

        if($ADDRESS2 != null) {
            $sql .= " AND ADDRESS2 LIKE :ADDRESS2";    
        }

        if($BIKO != null) {
            $sql .= " AND BIKO LIKE :BIKO";
        }

        // SQLの設定
        $stmt = $conn->prepare($sql);

        // バインド設定
        if($ID != null) {
            $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        }

        if($NAME != null) {
            $NAME = '%'.$NAME.'%';
            $stmt->bindParam(':NAME',$NAME, PDO::PARAM_STR);
        }

        if($SEX != "0") {
            $stmt->bindParam(':SEX', $SEX, PDO::PARAM_INT);
        }

        if($POSTNO != null) {
            $stmt->bindParam(':POSTNO', $POSTNO, PDO::PARAM_STR);
        }

        if($ADDRESS1 != null) {
            $ADDRESS1 = '%'.$ADDRESS1.'%';
            $stmt->bindParam(':ADDRESS1', $ADDRESS1, PDO::PARAM_STR);
        }

        if($ADDRESS2 != null) {
            $ADDRESS2 = '%'.$ADDRESS2.'%';
            $stmt->bindParam(':ADDRESS2', $ADDRESS2, PDO::PARAM_STR);
        }

        if($BIKO != null) {
            $BIKO = '%'.$BIKO.'%';
            $stmt->bindParam(':BIKO', $BIKO, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetchAll();

        // データ件数をカウントする
        foreach($result as $row) {
            $dbCnt++;
        }

        $_SESSION['dbCnt'] = $dbCnt; //取得数を保持

    } catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
    }
?>