<?php
require_once('ErrCheck.php');
require_once('ConnectInfo.php');

global $ID, $NAME, $SEX, $POSTNO, $ADDRESS1, $ADDRESS2, $BIKO, $rowsPerPage, $offset;

$result = [];
$dbCnt = 0;
$error = null;

$ErrChk = new ErrCheck();
$ConnectInfo = new ConnectInfo();

try {
    $conn = new PDO(
        $ConnectInfo->getCon(),
        $ConnectInfo->getUser(),
        $ConnectInfo->getPassword(),
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );

    // 1. 全件数取得
    $sqlCount = "SELECT COUNT(*) FROM \"T_USER_INFO\" WHERE 1=1 ";

    // 2. データ取得SQL
    $sqlData = "SELECT \"ID\", \"NAME\", 
                    CASE \"SEX\" WHEN '1' THEN '男' WHEN '2' THEN '女' ELSE '未設定' END AS \"SEX\",
                    \"SEX\" AS \"SEX_RAW\", 
                    SUBSTRING(\"POSTNO\" FROM 1 FOR 3) AS \"POSTNO1\",
                    SUBSTRING(\"POSTNO\" FROM 4 FOR 4) AS \"POSTNO2\",
                    \"POSTNO\", \"ADDRESS1\", \"ADDRESS2\", \"BIKO\"
                    FROM \"T_USER_INFO\" WHERE 1=1 ";

    $where = "";
    $params = [];

    if ($ErrChk->nullCheck($ID)) {
        $where .= " AND \"ID\" = :ID";
        $params[':ID'] = $ID;
    }
    if ($ErrChk->nullCheck($NAME)) {
        $where .= " AND \"NAME\" LIKE :NAME";
        $params[':NAME'] = '%' . $NAME . '%';
    }
    if ($SEX !== "0") {
        $where .= " AND \"SEX\" = :SEX";
        $params[':SEX'] = (int)$SEX;
    }
    if ($ErrChk->nullCheck($POSTNO)) {
        $where .= " AND \"POSTNO\" = :POSTNO";
        $params[':POSTNO'] = $POSTNO;
    }
    if ($ErrChk->nullCheck($ADDRESS1)) {
        $where .= " AND \"ADDRESS1\" LIKE :ADDRESS1";
        $params[':ADDRESS1'] = '%' . $ADDRESS1 . '%';
    }
    if ($ErrChk->nullCheck($ADDRESS2)) {
        $where .= " AND \"ADDRESS2\" LIKE :ADDRESS2";
        $params[':ADDRESS2'] = '%' . $ADDRESS2 . '%';
    }
    if ($ErrChk->nullCheck($BIKO)) {
        $where .= " AND \"BIKO\" LIKE :BIKO";
        $params[':BIKO'] = '%' . $BIKO . '%';
    }

    $stmtCount = $conn->prepare($sqlCount . $where);
    foreach ($params as $key => $val) {
        $stmtCount->bindValue($key, $val, ($key === ':SEX') ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmtCount->execute();
    $dbCnt = $stmtCount->fetchColumn();
    $_SESSION['dbCnt'] = $dbCnt;

    $sqlData .= $where . " ORDER BY CAST(\"ID\" AS INTEGER) ASC LIMIT :limit OFFSET :offset";
    $stmtData = $conn->prepare($sqlData);

    foreach ($params as $key => $val) {
        $stmtData->bindValue($key, $val, ($key === ':SEX') ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmtData->bindValue(':limit', (int)($rowsPerPage ?? 10), PDO::PARAM_INT);
    $stmtData->bindValue(':offset', (int)($offset ?? 0), PDO::PARAM_INT);

    $stmtData->execute();
    $result = $stmtData->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Database Error: ' . $e->getMessage());
    $_SESSION['dbCnt'] = 0;
    die('Error:' . $e->getMessage());
}
