<?php
    $errMsg = "";
    // 初期表示時
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $mode= $_GET['mode'];

        $ID = null;
        $NAME = null;
        $SEX = '0';
        $POSTNO = null;
        $ADDRESS1 = null;
        $ADDRESS2 = null;
        $BIKO = null;

        // 更新モードかどうか
        if($mode == "3")
        {
            $ID= $_GET['id'];
            // 検索処理のphpファイルを呼び出し
            include('Search.php');
            //$ID= $_GET['id'];
        }

    }else{
        // 検索ボタン
        if(isset($_POST['btnReSearch'])){
    echo "<script type='text/javascript'>
    window.opener.document.form1.hdnName.value = 'せいや';
        window.opener.location.reload();
        window.close();
        </script>";
            // alert($_POST[’txtName’]);
            // window.opener.document.getElementById('hdnName').value = $_POST[’txtName’];
            // alert(window.opener.document.getElementById('hdnName').value);

            // alert(test.value);
            // // alert(document.getElementById('rdoSex').value);
            // // alert(document.getElementById('txtPostNo1').value);
            // // alert(document.getElementById('txtPostNo2').value);
            // // alert(document.getElementById('txtAddress1').value);
            // // alert(document.getElementById('txtAddress2').value);
            // // alert(document.getElementById('txtBiko').value);

            // // window.opener.document.getElementById('hdnName').value = document.getElementById('txtName').value;
            // // window.opener.document.getElementById('hdnSex').value = document.getElementById('rdoSex').value;
            // // window.opener.document.getElementById('hdnPostno').value = document.getElementById('txtPostNo1').value + document.getElementById('txtPostNo2').value;
            // // window.opener.document.getElementById('hdnAddress1').value = document.getElementById('txtAddress1').value;
            // // window.opener.document.getElementById('hdnAddress2').value = document.getElementById('txtAddress2').value;
            // // window.opener.document.getElementById('hdnBiko').value = document.getElementById('txtBiko').value;

            // // alert(window.opener.document.getElementById('hdnName').value);
            // // alert(window.opener.document.getElementById('hdnSex').value);
            // // alert(window.opener.document.getElementById('hdnPostno').value);
            // // alert(window.opener.document.getElementById('hdnAddress1').value);
            // // alert(window.opener.document.getElementById('hdnAddress2').value);
            // // alert(window.opener.document.getElementById('hdnBiko').value);

            // // window.opener.location.reload();
            // window.close();
            // </script>";
        
        // 登録ボタン
        } else if(isset($_POST['btnInsertUpdate'])){

            // 会員番号
            $ID  = $_POST["txtId"];
            // 名前
            $NAME  = $_POST["txtName"];
            // 性別
            $SEX  = $_POST["rdoSex"];
            // 郵便番号
            $POSTNO  = $_POST["txtPostNo1"];
            $POSTNO .= $_POST["txtPostNo2"];
            // 住所１
            $ADDRESS1  = $_POST["txtAddress1"];
            // 住所２
            $ADDRESS2  = $_POST["txtAddress2"];
            // 備考
            $BIKO  = $_POST["txtBiko"];

            // 検索処理のphpファイルを呼び出し
            include('Update.php');

            // 自画面を閉じる
            echo "<script type='text/javascript'>
            window.opener.location.reload();
            window.close();
            </script>";
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
                <td>
                    <label style="width:100px;color:red;" id="errLabel"><?php echo $errMsg;?></label>
                </td>
            </tr> 
        </table>
        <table>
                <?php 
                if($mode == "3") {
                    echo "<tr> ";
                    echo "<td width='100'>会員番号</td>";
                    echo "<td width='280'>"; echo str_pad($row['ID'], 6, 0, STR_PAD_LEFT); echo"</td> ";
                    echo "<input type='hidden' name='txtId' maxlength='7' size='10' value=$row[ID]>";
                    echo "</tr> ";
                }
                ?>
                <tr> 
                    <td width="100">名前</td>
                    <td width="280">
                    <?php 
                        if($mode == "3") {
                            echo "<input type='text' name='txtName' maxlength='20' size='20' value=$row[NAME]>";
                        }else{
                            echo "<input type='text' name='txtName' maxlength='20' size='20'>";
                        }
                    ?>
                </td> 
                </tr> 
                <tr> 
                    <td width="100">性別</td>
                    <td width="280">
                <?php
                if($mode == "3") {
                    if($row['SEX'] == "1"){
                        echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                        echo "<input type='radio' name='rdoSex' value='0'>未指定</input>";
                    }
                    else if($row['SEX'] == "2"){
                        echo "<input type='radio' name='rdoSex' value='1'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2' checked='checked'>女</input>";
                        echo "<input type='radio' name='rdoSex' value='0'>未指定</input>";
                    }
                    else{
                        echo "<input type='radio' name='rdoSex' value='1'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                        echo "<input type='radio' name='rdoSex' value='0' checked='checked'>未指定</input>";
                    }
                }
                else
                {
                    echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                    echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                    echo "<input type='radio' name='rdoSex' value='0'>未指定</input>";
                }
                ?>
                </td> 
                </tr>
                <tr> 
                    <td width="100">郵便番号</td>
                    <td width="280">
                    <?php 
                        if($mode == "3") {
                            echo "<input type='text' name='txtPostNo1' maxlength='3' size='4' value=$row[POSTNO1]>";
                            echo "-";
                            echo "<input type='text' name='txtPostNo2' maxlength='4' size='8' value=$row[POSTNO2]>";
                        }else{
                            echo "<input type='text' name='txtPostNo1' maxlength='3' size='4'>";
                            echo "-";
                            echo "<input type='text' name='txtPostNo2' maxlength='4' size='8'>";
                        }
                    ?>
                    </td> 
                </tr> 
                <tr> 
                    <td width="100">住所１</td>
                    <td width="280">
                    <?php 
                        if($mode == "3") {
                            echo "<input type='text' name='txtAddress1' maxlength='15' size='25' value=$row[ADDRESS1]>";
                        }else{
                            echo "<input type='text' name='txtAddress1' maxlength='15' size='25'>";
                        }
                    ?>
                    </td> 
                </tr> 
                <tr> 
                    <td width="100">住所２</td>
                    <td width="280">
                    <?php 
                        if($mode == "3") {
                            echo "<input type='text' name='txtAddress2' maxlength='15' size='25' value=$row[ADDRESS2]>";
                        }else{
                            echo "<input type='text' name='txtAddress2' maxlength='15' size='25'>";
                        }
                    ?>
                </td> 
                </tr> 
                <tr> 
                    <td width="100">備考</td>
                    <td width="280">
                    <?php 
                        if($mode == "3") {
                            echo "<input type='text' name='txtBiko' maxlength='15' size='25' value=$row[BIKO]>";
                        }else{
                            echo "<input type='text' name='txtBiko' maxlength='15' size='25'>";
                        }
                    ?>
                    </td> 
                    <tr> 
                <tr> 
                    <td colspan="2">
                        </br>
                        <center>
                    <?php 
                        // 検索モードの場合
                        if($mode == "1") {
                            echo "<button type='submit' style='width:100px;' name='btnReSearch'>検索</button>";
                        } else if($mode == "2") {
                            echo "<button type='submit' style='width:100px;' name='btnInsertUpdate'>新規登録</button>";
                        } else {
                            echo "<button type='submit' style='width:100px;' name='btnInsertUpdate'>更新</button>";
                        }
                    ?>
                    </center>
                    </td>
                </tr> 
         </table>
</form>
    </body>
</html>
<?php
    // 検索ボタン
    if(isset($_POST['btnReSearch'])){
        // // 自画面を閉じる
        echo "<script type='text/javascript'>
        alert(document.getElementById('txtName').value);";
    }
?>