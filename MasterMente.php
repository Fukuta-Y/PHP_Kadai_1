<?php
session_start();
require_once('MsgList.php');
require_once('ErrCheck.php');
require_once('ColumnList.php');

$errMsg = null;
$mode = null;
$MsgList = new MsgList();
$ErrChk = new ErrCheck();
$ColumnList = new ColumnList();

// 変数初期化
$ID = null;
$NAME = null;
$SEX = '0';

// 新規登録モード(2)の場合は「男(1)」を選択状態にする
if (isset($_GET['mode']) && $_GET['mode'] == '2') {
    $SEX = '1';
}

$POS1 = null;
$POS2 = null;
$POSTNO = null;
$ADDRESS1 = null;
$ADDRESS2 = null;
$BIKO = null;

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $mode = $_GET['mode'] ?? '1';
    $_SESSION['mode'] = $mode;

    if ($mode == "3") {
        $ID = $_GET['id'] ?? null;
        include('Search.php');
        if (!empty($result)) {
            $row = $result[0];
            $NAME = $row['NAME'];
            $SEX  = trim($row['SEX_RAW'] ?? '0');
            $POSTNO = $row['POSTNO'] ?? '';
            $POS1 = substr($POSTNO, 0, 3);
            $POS2 = substr($POSTNO, 3, 4);
            $ADDRESS1 = $row['ADDRESS1'];
            $ADDRESS2 = $row['ADDRESS2'];
            $BIKO = $row['BIKO'];
        } else {
            $errMsg = $MsgList->getMsg('001');
        }
    }
} else {
    $mode = $_SESSION['mode'] ?? '1';
    $ID = $_POST['lblId'] ?? null;
    $NAME = trim($_POST['txtName'] ?? "");
    $SEX = trim($_POST['rdoSex'] ?? "0");
    $POS1 = trim($_POST['txtPostNo1'] ?? "");
    $POS2 = trim($_POST['txtPostNo2'] ?? "");
    $POSTNO = $POS1 . $POS2;
    $ADDRESS1 = trim($_POST['txtAddress1'] ?? "");
    $ADDRESS2 = trim($_POST['txtAddress2'] ?? "");
    $BIKO = trim($_POST['txtBiko'] ?? "");

    if (isset($_POST['btnReSearch'])) {
        if (!$ErrChk->nullCheck($errMsg)) {
            $_SESSION['txtName'] = $NAME;
            $_SESSION['rdoSex'] = $SEX;
            $_SESSION['txtPostNo'] = $POSTNO;
            $_SESSION['txtAddress1'] = $ADDRESS1;
            $_SESSION['txtAddress2'] = $ADDRESS2;
            $_SESSION['txtBiko'] = $BIKO;

            // 検索時は親画面をindex.php（1ページ目）へ強制移動させて閉じる
            echo "<script>window.opener.location.href = 'index.php'; window.close();</script>";
            exit;
        }
    } else if (isset($_POST['btnInsert']) || isset($_POST['btnUpdate'])) {
        if (!$ErrChk->nullCheck($NAME)) {
            $errMsg = sprintf($MsgList->getMsg('005'), $ColumnList->getName());
        } else {
            include('Update.php');
        }
    }

    if (!$ErrChk->nullCheck($errMsg)) {
        echo "<script>window.opener.location.reload(); window.close();</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>MasterMente.php</title>
    <link href="design.css" rel="stylesheet">
</head>

<body>
    <form action="MasterMente.php" method="post">
        <table style="width:500px;height:50px;">
            <tr>
                <td><label style="width:100px;color:red;"><?php echo htmlspecialchars($errMsg ?? ''); ?></label></td>
            </tr>
        </table>
        <table>
            <?php if ($mode == "3"): ?>
                <tr>
                    <td width='100'>会員番号</td>
                    <td width='280'><?php echo str_pad($ID, 6, 0, STR_PAD_LEFT); ?></td>
                    <input type='hidden' name='lblId' value="<?php echo htmlspecialchars($ID); ?>">
                </tr>
            <?php endif; ?>
            <tr>
                <td width="100">名前</td>
                <td width="280"><input type='text' name='txtName' maxlength='10' size='20' value="<?php echo htmlspecialchars($NAME ?? ''); ?>"></td>
            </tr>
            <tr>
                <td>性別</td>
                <td>
                    <input type='radio' name='rdoSex' value='1' <?php if ($SEX == '1') echo 'checked'; ?>>男
                    <input type='radio' name='rdoSex' value='2' <?php if ($SEX == '2') echo 'checked'; ?>>女
                    <?php if ($mode == "1"): ?><input type='radio' name='rdoSex' value='0' <?php if ($SEX == '0') echo 'checked'; ?>>未指定<?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>郵便番号</td>
                <td>
                    <input type='text' name='txtPostNo1' maxlength='3' size='4' value="<?php echo htmlspecialchars($POS1 ?? ''); ?>"> -
                    <input type='text' name='txtPostNo2' maxlength='4' size='8' value="<?php echo htmlspecialchars($POS2 ?? ''); ?>">
                </td>
            </tr>
            <tr>
                <td>住所１</td>
                <td><input type='text' name='txtAddress1' maxlength='15' size='25' value="<?php echo htmlspecialchars($ADDRESS1 ?? ''); ?>"></td>
            </tr>
            <tr>
                <td>住所２</td>
                <td><input type='text' name='txtAddress2' maxlength='15' size='25' value="<?php echo htmlspecialchars($ADDRESS2 ?? ''); ?>"></td>
            </tr>
            <tr>
                <td>備考</td>
                <td><input type='text' name='txtBiko' maxlength='15' size='25' value="<?php echo htmlspecialchars($BIKO ?? ''); ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><br>
                    <center>
                        <?php
                        if ($mode == "1") echo "<button type='submit' style='width:100px;' name='btnReSearch'>検索</button>";
                        else if ($mode == "2") echo "<button type='submit' style='width:100px;' name='btnInsert'>新規登録</button>";
                        else echo "<button type='submit' style='width:100px;' name='btnUpdate'>更新</button>";
                        ?>
                    </center>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>
