<?php
    $getCnt = 0;
    try{
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8", "root", "");
        $sql = "select ";
        $sql .= "    count(*) AS CNT";
        $sql .= " from";
        $sql .= "  T_USER_INFO";
        $sql .= "  ID = '";
        $sql .= $ID;
        $sql .= "'";

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();

        // データ件数を取得する
        foreach($result as $row){
            $getCnt = $row['CNT'];
        }

        // 取得結果が0の場合、追加
        if($getCnt == "0" )
        {
            $sql = "select ";
            $sql .= "    MAX(ID) + 1 AS ID";
            $sql .= " from";
            $sql .= "  T_USER_INFO";
    
            $sth = $pdo->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll();
    
            // 最大IDを取得する
            foreach($result as $row){
                $ID = $row['ID'];
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
            $sql .= ")";

        } else {
            // 取得結果が1以上の場合、更新
            $sql = " update ";
            $sql .= " T_USER_INFO ";
            $sql .= "   set";
            $sql .= "    NAME=:NAME";
            $sql .= "   ,SEX=:SEX";
            $sql .= "   ,POSTNO=:POSTNO";
            $sql .= "   ,ADDRESS1=:ADDRESS1";
            $sql .= "   ,ADDRESS2=:ADDRESS2";
            $sql .= "   ,BIKO=:BIKO";
            $sql .= " WHERE ID =:ID";
        }

        // 挿入する値は空のまま、SQL実行の準備をする
        $stmt = $pdo->prepare($sql);
        
        // 挿入する値を配列に格納する
        $params = array(':ID' => $ID,':NAME' => $NAME,':SEX' => $SEX,':POSTNO' => $POSTNO,':ADDRESS1' => $ADDRESS1,':ADDRESS2' => $ADDRESS2,':BIKO' => $BIKO);
        
        // 挿入する値が入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);

    }catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
    }
?>