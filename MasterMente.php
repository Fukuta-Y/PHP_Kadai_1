<?php
    $dbCnt = 0;
    try{
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8", "root", "");
        $sql = "select";
        $sql .= "    ID";
        $sql .= "   ,NAME";
        $sql .= "   ,CASE WHEN SEX = '1' THEN '男' WHEN SEX ='2' THEN '女' END AS 'SEX'";
        $sql .= "   ,POSTNO";
        $sql .= "   ,ADDRESS1";
        $sql .= "   ,ADDRESS2";
        $sql .= "   ,BIKO";
        $sql .= " from";
        $sql .= "  T_USER_INFO";
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();

        foreach($result as $row){
            $dbCnt++;
        }

    }catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
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
        <table>
                <tr> 
                    <td width="100">会員番号</td>
                    <td width="200"><lable id="ID"></lable></td> 
                </tr> 
                <tr> 
                    <td width="100">名前</td>
                    <td width="200"><input type="text" id="NAME" size="15" maxlength="10" ></td> 
                </tr> 
                <tr> 
                    <td width="100">性別</td>
                    <td width="200"><input type="radio" id="rdoMen" name="sex" value="1" checked="checked">男</input><input type="radio" id="rdoWoman" name="sex"  value="2">女</input></td> 
                </tr>
                <tr> 
                    <td width="100">郵便番号</td>
                    <td width="200"><input type="text" id="txtPostNo1" maxlength="3" size="8">-<input type="text" id="txtPostNo2" maxlength="4"  size="9"></td> 
                </tr> 
                <tr> 
                    <td width="100">住所１</td>
                    <td width="200"><input type="text" id="txtAddress1" maxlength="15" size="20"></td> 
                </tr> 
                <tr> 
                    <td width="100">住所２</td>
                    <td width="200"><input type="text" id="txtAddress2" maxlength="15" size="20"></td> 
                </tr> 
                <tr> 
                    <td width="100">備考</td>
                    <td width="200"><input type="text" id="txtBiko" maxlength="15" size="20"></td> 
                </tr> 
        </table>
        </br>
        <button type="submit" style="width:100px;" name="btnSearch">検索</button>&nbsp;&nbsp;
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
            <td width="100"><?php echo $row['POSTNO'];?></td>
            <td width="100"><?php echo $row['ADDRESS1'];?></td> 
            <td width="100"><?php echo $row['ADDRESS2'];?></td> 
            <td width="100"><?php echo $row['BIKO'];?></td> 
        </tr> 
<?php 
}
?>
        <table>
    </body>
</html>