<?php
    require_once('ErrCheck.php');
    require_once('ConnectInfo.php');

    function performSearch(
        $ID = null,
        $NAME = null,
        $SEX = '0', // デフォルト値 '0' を設定
        $POSTNO = null,
        $ADDRESS1 = null,
        $ADDRESS2 = null,
        $BIKO = null
    ) {
        $ErrChk = new ErrCheck();
        $ConnectInfo = new ConnectInfo();
        $result = [];
        $dbCnt = 0;
        $error = null;

        try {
            $conn = new PDO(
                $ConnectInfo->getCon(),
                $ConnectInfo->getUser(),
                $ConnectInfo->getPassword(),
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );

            $sql = "SELECT ID, NAME, CASE SEX WHEN '1' THEN '男' WHEN '2' THEN '女' ELSE '未設定' END AS SEX, ";
            $sql .= "MID(POSTNO FROM 1 FOR 3) AS POSTNO1, MID(POSTNO FROM 4 FOR 4) AS POSTNO2, POSTNO, ADDRESS1, ADDRESS2, BIKO "; // POSTNOも取得するように修正
            $sql .= "FROM T_USER_INFO WHERE 1=1 ";

            $params = [];

            if ($ErrChk->nullCheck($ID)) {
                $sql .= " AND ID = :ID";
                $params[':ID'] = $ID;
            }
            if ($ErrChk->nullCheck($NAME)) {
                $sql .= " AND NAME LIKE :NAME";
                $params[':NAME'] = '%' . $NAME . '%';
            }
            if ($SEX !== "0") { // 文字列 '0' も考慮
                $sql .= " AND SEX = :SEX";
                $params[':SEX'] = (int)$SEX; // INTにキャスト
            }
            if ($ErrChk->nullCheck($POSTNO)) {
                $sql .= " AND POSTNO = :POSTNO";
                $params[':POSTNO'] = $POSTNO;
            }
            if ($ErrChk->nullCheck($ADDRESS1)) {
                $sql .= " AND ADDRESS1 LIKE :ADDRESS1";
                $params[':ADDRESS1'] = '%' . $ADDRESS1 . '%';
            }
            if ($ErrChk->nullCheck($ADDRESS2)) {
                $sql .= " AND ADDRESS2 LIKE :ADDRESS2";
                $params[':ADDRESS2'] = '%' . $ADDRESS2 . '%';
            }
            if ($ErrChk->nullCheck($BIKO)) {
                $sql .= " AND BIKO LIKE :BIKO";
                $params[':BIKO'] = '%' . $BIKO . '%';
            }

            $stmt = $conn->prepare($sql);
            foreach ($params as $key => &$val) {
                // PDO::PARAM_INT を適切に使うために実際の型をチェック
                $pdoParamType = PDO::PARAM_STR;
                if ($key === ':SEX') {
                    $pdoParamType = PDO::PARAM_INT;
                }
                $stmt->bindParam($key, $val, $pdoParamType);
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // 連想配列で取得
            $dbCnt = count($result);
        } catch (PDOException $e) {
            error_log('Database Error in performSearch: ' . $e->getMessage()); // サーバーログに出力
            $error = $e->getMessage();
        }
        return ['result' => $result, 'count' => $dbCnt, 'error' => $error];
    }
?>