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

        if($mode == "3")
        {
            $ID= $_GET['id'];
            // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }

    }else{
        // 検索ボタン
        if(isset($_POST['btnReSearch'])){

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
            include('SearchList.php');
        }
        // 登録ボタン
        else if(isset($_POST['btnInsertUpdate'])){

            // モードを取得
            $mode= $_GET['mode'];

            // 更新時のみ
            if($mode == "3") {
                // 会員番号
                $ID  = $_POST["txtMember"];
            }

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
            
            echo "<script type='text/javascript'>window.close();</script>";

            // 検索処理のphpファイルを呼び出し
            include('SearchList.php');
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
                </tr> 
        </table>
        </br>
        <?php 
        if($mode == "1") {
            echo "<button type='submit' style='width:100px;' name='btnReSearch'>検索</button>";
         } else {
            echo "<button type='submit' style='width:100px;' name='btnInsertUpdate'>登録/更新</button>";
         }
         ?>
         &nbsp;&nbsp;
        </br>
</form>
    </body>
</html>