<?php
    $dbCnt = 0;
    try{
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8", "root", "");
        $sql = "select";
        $sql .= "    ID";
        $sql .= "   ,NAME";
        $sql .= "   ,CASE WHEN SEX = '1' THEN '男' WHEN SEX ='2' THEN '女' END AS 'SEX'";
        $sql .= "   ,MID(POSTNO FROM 1 FOR 3) AS POSTNO1";
        $sql .= "   ,MID(POSTNO FROM 4 FOR 4) AS POSTNO2";
        $sql .= "   ,ADDRESS1";
        $sql .= "   ,ADDRESS2";
        $sql .= "   ,BIKO";
        $sql .= " from";
        $sql .= "  T_USER_INFO";
        $sql .= "  WHERE 1=1 ";

        if($NAME != null)
        {
            $sql .= " AND NAME LIKE '%";
            $sql .= $NAME;
            $sql .= "%'";
        }

        if($SEX != '0')
        {
            $sql .= " AND SEX ='";
            $sql .= $SEX;
            $sql .= "'";
        }

        if($POSTNO1 != null && $POSTNO2 != null)
        {
            $sql .= "  AND POSTNO ='";
            $sql .= $POSTNO1;
            $sql .= $POSTNO2;
            $sql .= "'";
        }

        if($ADDRESS1 != null)
        {
            $sql .= "  AND ADDRESS1 LIKE '%";
            $sql .= $ADDRESS1;
            $sql .= "%'";
        }


        if($ADDRESS2 != null)
        {
            $sql .= "  AND ADDRESS2 LIKE '%";
            $sql .= $ADDRESS2;
            $sql .= "%'";
        }

        if($BIKO != null)
        {
            $sql .= "  AND BIKO LIKE '%";
            $sql .= $BIKO;
            $sql .= "%'";
        }

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();

        // データ件数をカウントする
        foreach($result as $row){
            $dbCnt++;
        }
    }catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
    }
?>