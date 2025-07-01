<?php
    require_once('ConnectInfo.php');

    // インスタンス生成
    $ConnectInfo = new ConnectInfo();

    $getCnt = "0"; // レコードの存在チェック用カウント変数
    // MasterMente.phpから渡されることを想定されるグローバル変数を宣言
    global $ID, $NAME, $SEX, $POSTNO, $ADDRESS1, $ADDRESS2, $BIKO;

    try {
        // DBコネクションを取得する
        $conn = new PDO(
            $ConnectInfo->getCon(),
            $ConnectInfo->getUser(),
            $ConnectInfo->getPassword(),
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) // エラーモード設定
        );

        // トランザクションを開始する
        $conn->beginTransaction();

        // ユーザーIDの存在チェック
        // テーブル名とカラム名を二重引用符で囲む
        $sql = "SELECT COUNT(*) AS CNT FROM \"T_USER_INFO\" WHERE \"ID\" = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        $stmt->execute();
        $getCnt = $stmt->fetchColumn();

        // 取得結果が0の場合、追加
        if ($getCnt == "0") {
            // 最大IDを取得して、次のIDを計算
            // テーブル名とカラム名を二重引用符で囲む
            $sql = "SELECT COALESCE(MAX(\"ID\"), 0) + 1 AS ID FROM \"T_USER_INFO\"";
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
                // テーブル名とカラム名を二重引用符で囲む
                $sql = "SELECT COUNT(*) AS CNT FROM \"T_USER_INFO\" WHERE \"ID\" = :ID";
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
            // テーブル名とカラム名を二重引用符で囲む
            $sql = "INSERT INTO \"T_USER_INFO\" (\"ID\", \"NAME\", \"SEX\", \"POSTNO\", \"ADDRESS1\", \"ADDRESS2\", \"BIKO\") 
                            VALUES (:ID, :NAME, :SEX, :POSTNO, :ADDRESS1, :ADDRESS2, :BIKO)";
        } else {
            // 取得結果が1以上の場合、更新
            // テーブル名とカラム名を二重引用符で囲む
            $sql = "UPDATE \"T_USER_INFO\" SET \"NAME\" = :NAME, \"SEX\" = :SEX, \"POSTNO\" = :POSTNO, 
                            \"ADDRESS1\" = :ADDRESS1, \"ADDRESS2\" = :ADDRESS2, \"BIKO\" = :BIKO 
                            WHERE \"ID\" = :ID";
        }

        // SQL実行準備
        $stmt = $conn->prepare($sql);
        // パラメータのバインド
        // カラム名に対応するバインド名に注意
        $stmt->bindParam(':ID', $ID, PDO::PARAM_STR);
        $stmt->bindParam(':NAME', $NAME, PDO::PARAM_STR);
        $stmt->bindParam(':SEX', $SEX, PDO::PARAM_INT); // SEXはINT型
        $stmt->bindParam(':POSTNO', $POSTNO, PDO::PARAM_STR);
        $stmt->bindParam(':ADDRESS1', $ADDRESS1, PDO::PARAM_STR);
        $stmt->bindParam(':ADDRESS2', $ADDRESS2, PDO::PARAM_STR);
        $stmt->bindParam(':BIKO', $BIKO, PDO::PARAM_STR);

        // SQL実行
        $stmt->execute();

        // コミット
        $conn->commit();

        // 更新/追加件数を取得して表示（この値が必要な場合）
        // $stmt->rowCount(); は、影響を受けた行数を返します。
        // ここでは特に出力がないため、必要であれば変数に格納したりログに出力したりします。

    } catch (PDOException $e) {
        // エラーハンドリング
        print('Error: ' . $e->getMessage());
        $conn->rollBack();  // エラー時はロールバック
        die(); // スクリプトの実行を停止
    }
?>
