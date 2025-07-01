<?php
    require_once('MsgList.php');

    $dbCnt = 0;
    $errMsg = null;

    // インスタンス生成
    $MsgList = new MsgList();

    $ID = null; //ID
    $NAME = null; //名前
    $SEX = '0';  //性別
    $POSTNO = null; //郵便番号
    $ADDRESS1 = null; //住所１
    $ADDRESS2 = null; //住所２
    $BIKO = null; //備考
    session_start(); // セッション開始

    // 初期表示時でセッションが開始、存在している場合（セッションの性別が存在しないのは初回だけのため）
    if (isset($_SESSION['rdoSex'])) {
        $NAME = $_SESSION['txtName'] ?? null;  //名前
        $SEX = $_SESSION['rdoSex'] ?? '0'; //性別
        $POSTNO = $_SESSION['txtPostNo'] ?? null; //郵便番号
        $ADDRESS1 =  $_SESSION['txtAddress1'] ?? null;  //住所１
        $ADDRESS2 = $_SESSION['txtAddress2'] ?? null; //住所２
        $BIKO = $_SESSION['txtBiko'] ?? null; //備考
        session_destroy(); // セッション削除
    }
    // 検索処理のphpファイルを呼び出し
    include('Search.php');

    //結果取得件数を取得
    $dbCnt = $_SESSION['dbCnt'] ?? 0; // セッション変数も念のため存在チェック

    // 初期表示時でセッションが開始、存在している場合（セッションの性別が存在しないのは初回だけのため)
    if (isset($_SESSION['rdoSex']) && $dbCnt == '0') {
        $errMsg = $MsgList->getMsg('010');
    } else {
        $errMsg = null;
    }
?>
<script type="text/javascript">
    // 高さと幅を計算する
    var w = (screen.width - 500) / 2;
    var h = (screen.height - 400) / 2;

    // 選択ボタン
    function selectRow(key) {
        // 更新画面を呼び出す
        window.open("MasterMente.php?mode=3&id=" + key, '', "width=500,height=400,left=" + w + ",top=" + h);
    }
    // 削除ボタン
    function deleteRow(key) {
        // 確認
        if (!confirm("<?php echo htmlspecialchars($MsgList->getMsg('003')); ?>")) return;

        // 削除画面を呼び出す（開く小窓に変数を渡す）
        // 削除成功時に小窓が親ウィンドウをリロードするようにするため、open() の戻り値を保存する必要はありません。
        window.open("Delete.php?id=" + key, 'deleteWindow', "width=500,height=400,left=" + w + ",top=" + h);
    }
    // 検索条件リンク
    function searchRow() {
        // 検索条件画面を呼び出す
        window.open('MasterMente.php?mode=1', '', "width=500,height=400,left=" + w + ",top=" + h);
    }
    // 新規登録リンク
    function insertRow() {
        // 新規登録画面を呼び出す
        window.open('MasterMente.php?mode=2', '', "width=500,height=400,left=" + w + ",top=" + h);
    }
</script>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>index.php</title>
    <link href="design.css" rel="stylesheet">
</head>

<body>
    <form name="form1">
        <table style="width:500px;height:10px;">
            <tr>
                <td>
                    <label style="width:100px;color:red;" id="errLabel"><?php echo htmlspecialchars($errMsg ?? ''); ?></label>
                </td>
            </tr>
        </table>
        </br>
        <a href="" onclick="searchRow(); return false;">検索条件</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="" onclick="insertRow(); return false;">新規登録</a>
        </br>
        <table>
            <tr>
                <td width="50"></td>
                <td width="50"></td>
                <td width="100"></td>
                <td width="100"></td>
                <td width="100"></td>
                <td width="100"></td>
                <td width="100"></td>
                <td width="100">表示件数:</td>
                <td width="100">&nbsp;&nbsp;&nbsp;&nbsp;<lable id="lblCount"><?php echo htmlspecialchars($dbCnt ?? 0); ?>件</lable>
                </td>
            </tr>
        </table>
        <table border="1" class="main">
            <tr>
                <td width="50" style="background-color: greenyellow;"></td>
                <td width="50" style="background-color: greenyellow;"></td>
                <td width="100" style="background-color: greenyellow;">会員番号</td>
                <td width="100" style="background-color: greenyellow;">名前</td>
                <td width="100" style="background-color: greenyellow;">性別</td>
                <td width="100" style="background-color: greenyellow;">郵便番号</td>
                <td width="100" style="background-color: greenyellow;">住所１</td>
                <td width="100" style="background-color: greenyellow;">住所２</td>
                <td width="100" style="background-color: greenyellow;">備考</td>
            </tr>
            <?php
            foreach ($result as $row) {
            ?>
                <tr class='chara'>
                    <?php
                    // IDにNull合体演算子とhtmlspecialcharsを適用
                    echo "<td width='30'><button type='submit' style='width:100%;' name='btnSearch' onclick='selectRow(" . htmlspecialchars($row['ID'] ?? '') . ")'>選択</td> ";
                    ?>
                    <?php
                    // IDにNull合体演算子とhtmlspecialcharsを適用
                    echo "<td width='30'><button type='submit' style='width:100%;' name='btnDelete' onclick='deleteRow(" . htmlspecialchars($row['ID'] ?? '') . ")'>削除</td> ";
                    ?>
                    <td width="100" id="id"><?php echo htmlspecialchars(str_pad($row['ID'] ?? '', 6, '0', STR_PAD_LEFT)); ?></td>
                    <td width="100" name="name"><?php echo htmlspecialchars($row['NAME'] ?? ''); ?></td>
                    <td width="100" name="sex"><?php echo htmlspecialchars($row['SEX'] ?? ''); ?></td>
                    <td width="100" name="postno"><?php echo htmlspecialchars(($row['POSTNO1'] ?? '') . "-" . ($row['POSTNO2'] ?? '')); ?></td>
                    <td width="100" name="address1"><?php echo htmlspecialchars($row['ADDRESS1'] ?? ''); ?></td>
                    <td width="100" name="address2"><?php echo htmlspecialchars($row['ADDRESS2'] ?? ''); ?></td>
                    <td width="100" name="biko"><?php echo htmlspecialchars($row['BIKO'] ?? ''); ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </form>
</body>

</html>