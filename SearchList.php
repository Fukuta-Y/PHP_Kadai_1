<?php
// session_start() をファイルの**最も先頭**に移動
session_start();

require_once('MsgList.php');
require_once('ErrCheck.php');
require_once('ConnectInfo.php'); // performSearch 関数内で使用されますが、念のため
require_once('Search.php');      // performSearch 関数をインクルード

$dbCnt = 0;
$errMsg = null;

$MsgList = new MsgList();
$ErrChk = new ErrCheck();

// セッションから検索条件を取得
// 'search_' プレフィックスで統一することを推奨
$NAME = $_SESSION['search_name'] ?? null;
$SEX = $_SESSION['search_sex'] ?? '0';
$POSTNO = $_SESSION['search_postno'] ?? null;
$ADDRESS1 = $_SESSION['search_address1'] ?? null;
$ADDRESS2 = $_SESSION['search_address2'] ?? null;
$BIKO = $_SESSION['search_biko'] ?? null;

// performSearch 関数を呼び出して検索を実行
// IDは通常、リスト検索では使用しないのでnullを渡す
$searchResult = performSearch(null, $NAME, $SEX, $POSTNO, $ADDRESS1, $ADDRESS2, $BIKO);

if ($searchResult['error']) {
    $errMsg = 'データベースエラー: ' . $searchResult['error'];
    $result = []; // エラー時は結果を空に
    $dbCnt = 0;
} else {
    $result = $searchResult['result'];
    $dbCnt = $searchResult['count'];
    if ($dbCnt == 0) {
        $errMsg = $MsgList->getMsg('010'); // "検索結果はありませんでした。"
    }
}

// 検索条件のセッション変数は、もう使用しないならここでクリアしても良い
// 例: unset($_SESSION['search_name'], $_SESSION['search_sex'], ...);
// ただし、もし「検索条件に戻る」ボタンなどでMasterMente.phpに検索条件を引き継ぎたい場合は、unsetしない。
// session_destroy() は削除。
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SearchList.php</title>
    <link href="design.css" rel="stylesheet">
    <script type="text/javascript">
        // 選択ボタン
        function selectRow(id) {
            // 親ウィンドウにデータを返す（MasterMente.phpを想定）
            // window.opener.location.href = 'MasterMente.php?mode=3&id=' + id;
            window.opener.location.href = 'MasterMente.php?mode=3&id=' + id;
            window.close();
        }

        // 削除ボタン
        function deleteRow(id) {
            if (confirm("<?php echo $MsgList->getMsg('003'); ?>")) { // 'この行を削除しますか？'
                // Delete.php をポップアップで開く
                var deleteWindow = window.open('Delete.php?id=' + id, 'DeleteWindow', 'width=400,height=200,scrollbars=yes');
                if (deleteWindow) {
                    // ポップアップが閉じられたときに親ウィンドウをリロード
                    var interval = setInterval(function() {
                        if (deleteWindow.closed) {
                            clearInterval(interval);
                            window.location.reload(); // 削除後、現在の検索リストを再表示するためにリロード
                        }
                    }, 500); // 0.5秒ごとにチェック
                }
            }
        }

        // 新規登録ボタン
        function insertRow() {
            window.open('MasterMente.php?mode=2', 'InsertWindow', 'width=600,height=500,scrollbars=yes');
        }

        // 再検索ボタン
        function searchRow() {
            window.open('MasterMente.php?mode=1', 'SearchWindow', 'width=600,height=500,scrollbars=yes');
        }
    </script>
</head>

<body>
    <table style="width:500px;height:50px;">
        <tr>
            <td>
                <label style="width:100px;color:red;" id="errLabel"><?php echo htmlspecialchars($errMsg); ?></label>
            </td>
        </tr>
    </table>
    <table class="main">
        <tr>
            <td width="30"></td>
            <td width="30"></td>
            <td width="100" style="background-color: greenyellow;">会員番号</td>
            <td width="100" style="background-color: greenyellow;">名前</td>
            <td width="100" style="background-color: greenyellow;">性別</td>
            <td width="100" style="background-color: greenyellow;">郵便番号</td>
            <td width="100" style="background-color: greenyellow;">住所１</td>
            <td width="100" style="background-color: greenyellow;">住所２</td>
            <td width="100" style="background-color: greenyellow;">備考</td>
        </tr>
        <?php
        if (!empty($result)) {
            foreach ($result as $row) {
        ?>
                <tr class='chara'>
                    <td width='30'><button type='submit' style='width:100%;' name='btnSearch' onclick='selectRow(<?php echo htmlspecialchars($row['ID']); ?>)'>選択</button></td>
                    <td width='30'><button type='submit' style='width:100%;' name='btnDelete' onclick='deleteRow(<?php echo htmlspecialchars($row['ID']); ?>)'>削除</button></td>
                    <td width="100" id="id"><?php echo str_pad(htmlspecialchars($row['ID']), 6, '0', STR_PAD_LEFT); ?></td>
                    <td width="100" name="name"><?php echo htmlspecialchars($row['NAME']); ?></td>
                    <td width="100" name="sex"><?php echo htmlspecialchars($row['SEX']); ?></td>
                    <td width="100" name="postno"><?php echo htmlspecialchars($row['POSTNO1']); ?>-<?php echo htmlspecialchars($row['POSTNO2']); ?></td>
                    <td width="100" name="address1"><?php echo htmlspecialchars($row['ADDRESS1']); ?></td>
                    <td width="100" name="address2"><?php echo htmlspecialchars($row['ADDRESS2']); ?></td>
                    <td width="100" name="biko"><?php echo htmlspecialchars($row['BIKO']); ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </table>
    <table style="width:500px;height:50px;">
        <tr>
            <td>
                </br>
                <center>
                    <label style="width:100px;"><?php echo htmlspecialchars($dbCnt); ?>件ヒットしました。</label>
                    </br>
                    <button type="submit" style="width:100px;" name="btnInsert" onclick="insertRow()">新規登録</button>
                    <button type="submit" style="width:100px;" name="btnReSearch" onclick="searchRow()">再検索</button>
                </center>
            </td>
        </tr>
    </table>
</body>

</html>