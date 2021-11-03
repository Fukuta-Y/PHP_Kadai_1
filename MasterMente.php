<?php
    $dbCnt = 0;
    $err = "";
    // 初期表示時
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $NAME = null;
        $SEX = '0';
        $POSTNO1 = null;
        $POSTNO2 = null;
        $ADDRESS1 = null;
        $ADDRESS2 = null;
        $BIKO = null;

        // 検索処理のphpファイルを呼び出し
        include('Search.php');
    }else{
        // 検索ボタン
        if(isset($_POST['btnSearch'])){

            //検索条件を保持する
            $NAME  = $_POST["txtName"];

            //検索条件を保持する
            $SEX  = $_POST["rdoSex"];

            //検索条件を保持する
            $POSTNO1  = $_POST["txtPostNo1"];

            //検索条件を保持する
            $POSTNO2  = $_POST["txtPostNo2"];

            //検索条件を保持する
            $ADDRESS1  = $_POST["txtAddress1"];

            //検索条件を保持する
            $ADDRESS2  = $_POST["txtAddress2"];

            //検索条件を保持する
            $BIKO  = $_POST["txtBiko"];

            // 検索処理のphpファイルを呼び出し
            include('Search.php');

        }
        // 選択ボタン
        else if(isset($_POST['btnSelect'])){

        }
        // クリアボタン
        else if(isset($_POST['btnClear'])){
            $NAME = null;
            $SEX = '0';
            $POSTNO1 = null;
            $POSTNO2 = null;
            $ADDRESS1 = null;
            $ADDRESS2 = null;
            $BIKO = null;
    
            // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }

    }
?>
<script type="text/javascript">
    // function search() {
    //     alert("Hello");
    // }
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
        <table style="width:500px;height:70px;">
            <tr> 
                <td>
                    <label style="width:100px;color:red;" id="errLabel"><?php echo $err;?></label>
                </td>
            </tr> 
        </table>
        <table>
                <tr> 
                    <td width="100">会員番号</td>
                    <td width="280"><lable id="ID"></lable></td> 
                </tr> 
                <tr> 
                    <td width="100">名前</td>
                    <td width="280"><input type="text" name="txtName" size="20" maxlength="10" ></td> 
                </tr> 
                <tr> 
                    <td width="100">性別</td>
                    <td width="280"><input type="radio" name="rdoSex" value="1" checked="checked">男</input><input type="radio" name="rdoSex" value="2">女</input><input type="radio" name="rdoSex" value="0">未指定</input></td> 
                </tr>
                <tr> 
                    <td width="100">郵便番号</td>
                    <td width="280"><input type="text" name="txtPostNo1" maxlength="3" size="4"> - <input type="text" name="txtPostNo2" maxlength="4" size="8"></td> 
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
        <button type="submit" style="width:100px;" name="btnSelect">選択</button>&nbsp;&nbsp;
        <button type="submit" style="width:100px;" name="btnInsert">登録</button>&nbsp;&nbsp;
        <button type="submit" style="width:100px;" name="btnDelete">削除</button>&nbsp;&nbsp;
        <button type="submit" style="width:100px;" name="btnClear">クリア</button>
        </br>
        <table>
        <tr> 
            <td width="30"></td>
            <td width="100"></td>
            <td width="100"></td>
            <td width="100"></td>
            <td width="100"></td>
            <td width="100"></td>
            <td width="100">表示件数：</td>
            <td width="100">&nbsp;&nbsp;&nbsp;&nbsp;<lable id="lblCount"><?php echo $dbCnt; ?>件</lable></td>
        </tr> 
        </table>
        <table border=’1’ class="main">
        <tr> 
            <td width="30" style="background-color: greenyellow;"><input type="checkbox" id="delFlg" value="0"/></td>
            <td width="100" style="background-color: greenyellow;">会員番号</td>
            <td width="100" style="background-color: greenyellow;">名前</td> 
            <td width="100" style="background-color: greenyellow;">性別</td> 
            <td width="100" style="background-color: greenyellow;">郵便番号</td> 
            <td width="100" style="background-color: greenyellow;">住所１</td> 
            <td width="100" style="background-color: greenyellow;">住所２</td> 
            <td width="100" style="background-color: greenyellow;">備考</td> 
        </tr> 
<?php 
    foreach($result as $row){
?> 
         <tr> 
            <td width="30"><input type="checkbox" id="delFlg" value="1"/></td> 
            <td width="100"><?php echo str_pad($row['ID'], 6, 0, STR_PAD_LEFT);?></td> 
            <td width="100"><?php echo $row['NAME'];?></td> 
            <td width="100"><?php echo $row['SEX'];?></td>  
            <td width="100"><?php echo $row['POSTNO1']; echo "-"; echo$row['POSTNO2'];?></td>
            <td width="100"><?php echo $row['ADDRESS1'];?></td> 
            <td width="100"><?php echo $row['ADDRESS2'];?></td> 
            <td width="100"><?php echo $row['BIKO'];?></td> 
        </tr> 
<?php 
}
?>
        <table>
</form>
    </body>
</html>