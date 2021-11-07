<?php
    try{
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8", "root", "");
        $sql = " delete ";
        $sql .= " from";
        $sql .= "  T_USER_INFO";
        $sql .= "  ID = :ID";

        // 挿入する値は空のまま、SQL実行の準備をする
        $stmt = $pdo->prepare($sql);
        
        // 挿入する値を配列に格納する
        $params = array(':ID' => $ID);
        
        // 挿入する値が入った変数をexecuteにセットしてSQLを実行
        $stmt->execute($params);

    }catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
    }
?>