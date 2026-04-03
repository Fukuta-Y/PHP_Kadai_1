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

// --- ページング設定 ---
$rowsPerPage = 10; // 1ページあたりの件数
$currentPage = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($currentPage - 1) * $rowsPerPage;
// ---------------------

if (isset($_SESSION['rdoSex'])) {
    $NAME = $_SESSION['txtName'] ?? null;
    $SEX = $_SESSION['rdoSex'] ?? '0';
    $POSTNO = $_SESSION['txtPostNo'] ?? null;
    $ADDRESS1 =  $_SESSION['txtAddress1'] ?? null;
    $ADDRESS2 = $_SESSION['txtAddress2'] ?? null;
    $BIKO = $_SESSION['txtBiko'] ?? null;
}

include('Search.php');

$dbCnt = $_SESSION['dbCnt'] ?? 0;
$maxPage = (int)ceil($dbCnt / $rowsPerPage);

// 現在のページが最大ページ数を超えている場合（検索で件数が減った時など）、1ページ目として再読み込み
if ($currentPage > $maxPage && $maxPage > 0) {
    $currentPage = 1;
    $offset = 0;
    include('Search.php');
}

if (isset($_SESSION['rdoSex']) && $dbCnt == '0') {
    $errMsg = $MsgList->getMsg('002');
} else {
    $errMsg = null;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>index.php</title>
    <link href="design.css" rel="stylesheet">
    <style>
        /* ローディング画面 */
        #loader-container {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        body {
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form[name="form1"] {
            display: inline-block;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .pagination a,
        .pagination .current-page {
            text-decoration: none;
            color: #007bff;
            margin: 0 10px;
            font-family: sans-serif;
            font-size: 14px;
        }

        .pagination .current-page {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <div id="loader-container">
        <div class="spinner"></div>
        <div class="loading-text">読み込み中...</div>
    </div>

    <form name="form1">
        <table style="width:500px;height:10px;">
            <tr>
                <td><label style="width:100px;color:red;" id="errLabel"><?php echo htmlspecialchars($errMsg ?? ''); ?></label></td>
            </tr>
        </table>
        <br>
        <a href="" onclick="searchRow(); return false;">検索条件</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="" onclick="insertRow(); return false;">新規登録</a>
        <br>

        <table>
            <tr>
                <td width="550"></td>
                <td width="100">表示件数:</td>
                <td width="100">&nbsp;&nbsp;&nbsp;&nbsp;<label id="lblCount"><?php echo htmlspecialchars($dbCnt ?? 0); ?>件</label></td>
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
            <?php foreach ($result as $row): ?>
                <tr class='chara'>
                    <td width='50'><button type='button' style='width:100%;' onclick='selectRow(<?php echo htmlspecialchars($row['ID'] ?? ''); ?>)'>選択</button></td>
                    <td width='50'><button type='button' style='width:100%;' onclick='deleteRow(<?php echo htmlspecialchars($row['ID'] ?? ''); ?>)'>削除</button></td>
                    <td width="100"><?php echo htmlspecialchars(str_pad($row['ID'] ?? '', 6, '0', STR_PAD_LEFT)); ?></td>
                    <td width="100"><?php echo htmlspecialchars($row['NAME'] ?? ''); ?></td>
                    <td width="100"><?php echo htmlspecialchars($row['SEX'] ?? ''); ?></td>
                    <td width="100"><?php echo htmlspecialchars(($row['POSTNO1'] ?? '') . " - " . ($row['POSTNO2'] ?? '')); ?></td>
                    <td width="100"><?php echo htmlspecialchars($row['ADDRESS1'] ?? ''); ?></td>
                    <td width="100"><?php echo htmlspecialchars($row['ADDRESS2'] ?? ''); ?></td>
                    <td width="100"><?php echo htmlspecialchars($row['BIKO'] ?? ''); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($maxPage > 1): ?>
            <div class="pagination-container">
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?p=1" onclick="showLoader()">最初へ</a>
                        <a href="?p=<?php echo $currentPage - 1; ?>" onclick="showLoader()">前へ</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $maxPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="current-page"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?p=<?php echo $i; ?>" onclick="showLoader()"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($currentPage < $maxPage): ?>
                        <a href="?p=<?php echo $currentPage + 1; ?>" onclick="showLoader()">次へ</a>
                        <a href="?p=<?php echo $maxPage; ?>" onclick="showLoader()">最後へ</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </form>

    <script type="text/javascript">
        function showLoader() {
            document.getElementById('loader-container').style.display = 'flex';
        }
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader-container').style.display = 'none';
            }, 300);
        });

        // ★ウィンドウサイズを大きく修正 (幅650, 高さ500)
        var winW = 650;
        var winH = 500;
        var leftPos = (screen.width - winW) / 2;
        var topPos = (screen.height - winH) / 2;

        function selectRow(key) {
            window.open("MasterMente.php?mode=3&id=" + key, '', "width=" + winW + ",height=" + winH + ",left=" + leftPos + ",top=" + topPos);
        }

        function searchRow() {
            window.open('MasterMente.php?mode=1', '', "width=" + winW + ",height=" + winH + ",left=" + leftPos + ",top=" + topPos);
        }

        function insertRow() {
            window.open('MasterMente.php?mode=2', '', "width=" + winW + ",height=" + winH + ",left=" + leftPos + ",top=" + topPos);
        }

        function deleteRow(key) {
            if (!confirm("本当に削除しますか？")) return;
            showLoader();
            fetch("Delete.php?id=" + key).then(res => res.json()).then(data => {
                if (data.success) location.reload();
                else alert('失敗');
            });
        }
    </script>
</body>

</html>
