<?php
    $dbCnt = 0;
    $errMsg = "";
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
        // 検索ボタン
        if(isset($_POST['btnSearch'])){

            // 会員番号
            $ID  = $_POST["txtMember"];
            //名前
            $NAME  = $_POST["txtName"];
            //性別
            $SEX  = $_POST["rdoSex"];
            //郵便番号
            $POSTNO  = $_POST["txtPostNo1"];
            $POSTNO .= $_POST["txtPostNo2"];
            //住所１
            $ADDRESS1  = $_POST["txtAddress1"];
            //住所２
            $ADDRESS2  = $_POST["txtAddress2"];
            //備考
            $BIKO  = $_POST["txtBiko"];

            // エラーチェックの呼び出し
            include('ErrCheck.php');

            // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }
        // クリアボタン
        else if(isset($_POST['btnClear'])){
            $ID = null;
            $NAME = null;
            $SEX = '0';
            $POSTNO = null;
            $ADDRESS1 = null;
            $ADDRESS2 = null;
            $BIKO = null;
    
            // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }
        // 登録ボタン
        else if(isset($_POST['btnInsertUpdate'])){

            // 会員番号
            $ID  = $_POST["txtMember"];

            // 名前
            $NAME  = $_POST["txtName"];

            // 性別
            $SEX  = $_POST["rdoSex"];

            //郵便番号
            $POSTNO  = $_POST["txtPostNo1"];
            $POSTNO .= $_POST["txtPostNo2"];

            //住所１
            $ADDRESS1  = $_POST["txtAddress1"];

            //住所２
            $ADDRESS2  = $_POST["txtAddress2"];

            //備考
            $BIKO  = $_POST["txtBiko"];

            // 検索処理のphpファイルを呼び出し
            include('Update.php');

            //パラメータ初期化
            $NAME  = null;
            $SEX = '0';
            $POSTNO = null;
            $ADDRESS1 = null;
            $ADDRESS2 = null;
            $BIKO = null;

            // // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }
        // 選択ボタン
        else if(isset($_POST['btnSearch'])){

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
<script type="text/javascript">
//選択ボタンをクリック時
function cal() {
    alert('a');
        var name2 = $(this).closest('tr').children("td").find('id=id').val();
        alert('b');
        alert(name2);

        }
</script>
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
                <td>
                    <label style="width:100px;color:red;" id="errLabel"><?php echo $errMsg;?></label>
                </td>
            </tr> 
        </table>
        <table>
                <tr> 
                    <td width="100">会員番号</td>
                    <td width="280"><input type="text" name="txtMember" size="10" maxlength="7"></td> 
                </tr> 
                <tr> 
                    <td width="100">名前</td>
                    <td width="280"><input type="text" name="txtName" size="20" maxlength="10"></td> 
                </tr> 
                <tr> 
                    <td width="100">性別</td>
                    <td width="280"><input type="radio" name="rdoSex" value="1" checked="checked">男</input><input type="radio" name="rdoSex" value="2">女</input><input type="radio" name="rdoSex" value="0">未指定</input></td> 
                </tr>
                <tr> 
                    <td width="100">郵便番号</td>
                    <td width="280"><input type="text" name="txtPostNo1" maxlength="3" size="4"><?$POSTNO1?></input> - <input type="text" name="txtPostNo2" maxlength="4" size="8"><?$POSTNO2?></input></td> 
                </tr> 
                <tr> 
                    <td width="100">住所１</td>
                    <td width="280"><input type="text" name="txtAddress1" maxlength="15" size="25"></td> 
                </tr> 
                <tr> 
                    <td width="100">住所２</td>
                    <td width="280"><input type="text" name="txtAddress2" maxlength="15" size="25"></td> 
                </tr> 
                <tr> 
                    <td width="100">備考</td>
                    <td width="280"><input type="text" name="txtBiko" maxlength="15" size="25"></td> 
                </tr> 
        </table>
        </br>
        <button type="submit" style="width:100px;" name="btnSearch">検索</button>&nbsp;&nbsp;
        <button type="submit" style="width:100px;" name="btnInsertUpdate">登録</button>&nbsp;&nbsp;
        <button type="submit" style="width:100px;" name="btnClear">クリア</button>
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
?>
         <tr>
            <td width="30"><button type="submit" style="width:100%;" name="btnSearch" onclick="cal()">選択</td> 
            <td width="30"><button type="submit" style="width:100%;" name="btnDelete">削除</td> 
            <td width="100" id="id"><?php echo str_pad($row['ID'], 6, 0, STR_PAD_LEFT);?></td> 
            <td width="100" name="name"><?php echo $row['NAME'];?></td> 
            <td width="100" name="sex"><?php echo $row['SEX'];?></td>  
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