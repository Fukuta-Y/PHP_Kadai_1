<?php
    $dbCnt = 0;
    $errMsg = "";
    $SEX_NAME ="";
    // 初期表示時
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $ID = null;
        $NAME = null;
        $SEX = '0';
        $POSTNO = null;
        $ADDRESS1 = null;
        $ADDRESS2 = null;
        $BIKO = null;

        // 検索処理のphpファイルを呼び出し
        include('Search.php');

    }else{
        // 選択ボタン
        if(isset($_POST['btnSearch'])){

        }
        // 削除ボタン
        else if(isset($_POST['btnDelete'])){

            $ID = $_POST["txtMember"];

            // 検索処理のphpファイルを呼び出し
            include('Delete.php');

            //パラメータ初期化
            $NAME  = null;
            $SEX = '0';
            $POSTNO = null;
            $ADDRESS1 = null;
            $ADDRESS2 = null;
            $BIKO = null;

            // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }
    }
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
function selectRow(obj,key)
{
    // URL
    var url = "MasterMente.php?mode=3&id=";
    url = url + key;

    window.open(url, '', 'width=500,height=400');
    
}
function deleteRow(obj,key)
{
    // 確認
    if (!confirm("この行を削除しますか？"))
        return;

    // URL
    var url = "Delete.php?id=";
    url = url + key;

    window.open(url, '', 'width=500,height=400');
    
}
</script>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SearchList.php</title>
    <link href="design.css" rel="stylesheet">
</head>
<body>
<form>
        </br>
        <a href="MasterMente.php?mode=1" onclick="window.open('MasterMente.php?mode=1', '', 'width=500,height=400'); return false;">検索条件</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="MasterMente.php?mode=2" onclick="window.open('MasterMente.php?mode=2', '', 'width=500,height=400'); return false;">新規登録</a>
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
            <td width="100">表示件数：</td>
            <td width="100">&nbsp;&nbsp;&nbsp;&nbsp;<lable id="lblCount"><?php echo $dbCnt; ?>件</lable></td>
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
    $rdoCnt = 0;
    foreach($result as $row){
        $rdoCnt++;

        // 性別判定
        if($row['SEX'] =='1'){
            $SEX_NAME ='男';
        } else if($row['SEX'] =='2'){
            $SEX_NAME ='女';
        } else {
            $SEX_NAME ='未指定';
        }
?>
        <tr class='chara'>
        <?php
        echo "<td width='30'><button type='submit' style='width:100%;' name='btnSearch' onclick='selectRow(this,$row[ID])'>選択</td> ";
        ?>
        <?php
        echo "<td width='30'><button type='submit' style='width:100%;' name='btnDelete' onclick='deleteRow(this,$row[ID])'>削除</td> ";
        ?>
        <td width="100" id="id"><?php echo str_pad($row['ID'], 6, 0, STR_PAD_LEFT);?></td> 
        <td width="100" name="name"><?php echo $row['NAME'];?></td> 
        <td width="100" name="sex"><?php echo $SEX_NAME;?></td>  
        <td width="100" name="postno"><?php echo $row['POSTNO1']; echo "-"; echo$row['POSTNO2'];?></td>
        <td width="100" name="address1"><?php echo $row['ADDRESS1'];?></td> 
        <td width="100" name="address2"><?php echo $row['ADDRESS2'];?></td> 
        <td width="100" name="biko"><?php echo $row['BIKO'];?></td>
    </tr> 
<?php 
}
?>
        <table>
</form>
    </body>
</html>