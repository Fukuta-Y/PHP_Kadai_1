<?php
    require_once('ErrCheck.php');
    require_once('ConnectInfo.php');

    $dbCnt = 0;
    // インスタンス生成
    $ErrChk = new ErrCheck();
    $ConnectInfo = new ConnectInfo();

    try {

        $conn = new PDO($ConnectInfo->getCon(), $ConnectInfo->getUser(), '', 
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
        if($ErrChk->nullCheck($ID)) {
            $sql .= " AND ID = :ID";
        }

        if($ErrChk->nullCheck($NAME)) {
            $sql .= " AND NAME LIKE :NAME";
        }

        if($SEX != "0") {
            $sql .= " AND SEX = :SEX";
        }

        if($ErrChk->nullCheck($POSTNO)) {
            $sql .= " AND POSTNO =:POSTNO";
        }

        if($ErrChk->nullCheck($ADDRESS1)) {
            $sql .= " AND ADDRESS1 LIKE :ADDRESS1";
        }

        if($ErrChk->nullCheck($ADDRESS2)) {
            $sql .= " AND ADDRESS2 LIKE :ADDRESS2";    
        }

        if($ErrChk->nullCheck($BIKO)) {
            $sql .= " AND BIKO LIKE :BIKO";
        }

        // SQLの設定
        $stmt = $conn->prepare($sql);

        // バインド設定
        if($ErrChk->nullCheck($ID)) {
            $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        }

        if($ErrChk->nullCheck($NAME)) {
            $NAME = '%'.$NAME.'%';
            $stmt->bindParam(':NAME',$NAME, PDO::PARAM_STR);
        }

        if($SEX != "0") {
            $stmt->bindParam(':SEX', $SEX, PDO::PARAM_INT);
        }

        if($ErrChk->nullCheck($POSTNO)) {
            $stmt->bindParam(':POSTNO', $POSTNO, PDO::PARAM_STR);
        }

        if($ErrChk->nullCheck($ADDRESS1)) {
            $ADDRESS1 = '%'.$ADDRESS1.'%';
            $stmt->bindParam(':ADDRESS1', $ADDRESS1, PDO::PARAM_STR);
        }

        if($ErrChk->nullCheck($ADDRESS2)) {
            $ADDRESS2 = '%'.$ADDRESS2.'%';
            $stmt->bindParam(':ADDRESS2', $ADDRESS2, PDO::PARAM_STR);
        }

        if($ErrChk->nullCheck($BIKO)) {
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