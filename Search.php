<?php
    require_once('ErrCheck.php');
    require_once('ConnectInfo.php');

    // SearchList.phpからインクルードされる際に、
    // MasterMente.phpから渡された検索条件が
    // グローバル変数として利用されるように宣言します。
    global $ID, $NAME, $SEX, $POSTNO, $ADDRESS1, $ADDRESS2, $BIKO;

    $result = []; // 検索結果を格納する配列
    $dbCnt = 0;   // 検索結果件数
    $error = null; // エラーメッセージを格納するための変数

    // インスタンス生成
    $ErrChk = new ErrCheck();
    $ConnectInfo = new ConnectInfo();

    try {
        $conn = new PDO(
            $ConnectInfo->getCon(),               // 接続情報
            $ConnectInfo->getUser(),              // ユーザー名
            $ConnectInfo->getPassword(),          // パスワード
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) // エラーモード設定
        );

        // SQLクエリの構築
        $sql = "SELECT";
        $sql .= "    \"ID\","; // カラム名を二重引用符で囲む
        $sql .= "   \"NAME\","; // カラム名を二重引用符で囲む
        $sql .= "   CASE \"SEX\" WHEN '1' THEN '男' WHEN '2' THEN '女' ELSE '未設定' END AS SEX,"; // カラム名を二重引用符で囲む
        $sql .= "   SUBSTRING(\"POSTNO\" FROM 1 FOR 3) AS POSTNO1,";  // カラム名を二重引用符で囲む
        $sql .= "   SUBSTRING(\"POSTNO\" FROM 4 FOR 4) AS POSTNO2,";  // カラム名を二重引用符で囲む
        $sql .= "   \"POSTNO\","; // 全体の郵便番号カラムも取得
        $sql .= "   \"ADDRESS1\","; // カラム名を二重引用符で囲む
        $sql .= "   \"ADDRESS2\","; // カラム名を二重引用符で囲む
        $sql .= "   \"BIKO\""; // カラム名を二重引用符で囲む
        $sql .= " FROM";
        $sql .= "  \"T_USER_INFO\""; // テーブル名を二重引用符で囲む
        $sql .= "  WHERE 1=1 ";

        $params = []; // バインドするパラメータを格納する配列

        // 検索条件の設定とバインドパラメータの追加
        if ($ErrChk->nullCheck($ID)) {
            $sql .= " AND \"ID\" = :ID"; // カラム名を二重引用符で囲む
            $params[':ID'] = $ID;
        }

        if ($ErrChk->nullCheck($NAME)) {
            $sql .= " AND \"NAME\" LIKE :NAME"; // カラム名を二重引用符で囲む
            $params[':NAME'] = '%' . $NAME . '%';
        }

        // SEXが"0"（未指定）でない場合のみ条件を追加
        if ($SEX !== "0") {
            $sql .= " AND \"SEX\" = :SEX"; // カラム名を二重引用符で囲む
            $params[':SEX'] = (int)$SEX; // SEXは数値型にキャスト
        }

        if ($ErrChk->nullCheck($POSTNO)) {
            $sql .= " AND \"POSTNO\" = :POSTNO"; // カラム名を二重引用符で囲む
            $params[':POSTNO'] = $POSTNO;
        }

        if ($ErrChk->nullCheck($ADDRESS1)) {
            $sql .= " AND \"ADDRESS1\" LIKE :ADDRESS1"; // カラム名を二重引用符で囲む
            $params[':ADDRESS1'] = '%' . $ADDRESS1 . '%';
        }

        if ($ErrChk->nullCheck($ADDRESS2)) {
            $sql .= " AND \"ADDRESS2\" LIKE :ADDRESS2"; // カラム名を二重引用符で囲む
            $params[':ADDRESS2'] = '%' . $ADDRESS2 . '%';
        }

        if ($ErrChk->nullCheck($BIKO)) {
            $sql .= " AND \"BIKO\" LIKE :BIKO"; // カラム名を二重引用符で囲む
            $params[':BIKO'] = '%' . $BIKO . '%';
        }

        // SQL実行準備
        $stmt = $conn->prepare($sql);

        // バインド設定の実行
        foreach ($params as $key => &$val) {
            // :SEX パラメータのみ整数型としてバインド
            $pdoParamType = ($key === ':SEX') ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($key, $val, $pdoParamType);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // 連想配列として結果を取得

        // データ件数をカウント
        $dbCnt = count($result);

        // 取得数をセッションに保持
        $_SESSION['dbCnt'] = $dbCnt;

    } catch (PDOException $e) {
        // エラー発生時はログに記録し、画面にエラーメッセージを表示して終了
        error_log('Database Error in Search.php: ' . $e->getMessage());
        $error = $e->getMessage();
        // エラー時は結果と件数をリセット
        $result = [];
        $dbCnt = 0;
        $_SESSION['dbCnt'] = 0;
        print('Error:' . $e->getMessage()); // 画面にもエラー表示
        die(); // スクリプトの実行を停止
    }
?>
