<?php
    try{
        // DBコネクションを取得する
        $conn = new PDO('mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8', 'root', '',
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        // トランザクションを開始する
        $conn->beginTransaction();

        // DELETE処理
        $sql = "DELETE FROM T_USER_INFO WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        $stmt->execute();

        // 削除件数を取得して表示する
        $deleteCount = $stmt->rowCount();
        
        // コミット
        $conn->commit();

        if($deleteCount >=1){
            print "削除 OK";
        }

    }catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
    }
?>