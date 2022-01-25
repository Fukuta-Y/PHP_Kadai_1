<?php
    $errMsg=null;

    // 初期表示時
    if($_SERVER["REQUEST_METHOD"] != "POST"){

        // 画面モードを取得する
        $mode= $_GET['mode'];

        session_start(); // セッション開始
        // 画面モードを取得する
        $_SESSION['mode'] = $mode;

        $ID = null; //ID
        $NAME = null; //名前
        $SEX = '0';  //性別
        $POSTNO = null; //郵便番号
        $POSTNO1 = null; //郵便番号1
        $POSTNO2 = null; //郵便番号2
        $ADDRESS1 = null; //住所1
        $ADDRESS2 = null; //住所2
        $BIKO = null; //備考

        // 選択画面の場合
        if($mode == "3")
        {
            $ID= $_GET['id'];
            // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }

    }else{
        session_start(); // セッション開始
        // 画面モードを取得する
        $mode= $_SESSION['mode'];

        // 検索ボタン
        if(isset($_POST['btnReSearch'])){

            $NAME  = $_POST["txtName"]; // 名前
            $SEX  = $_POST["rdoSex"]; // 性別
            $POSTNO1  = $_POST["txtPostNo1"]; // 郵便番号1
            $POSTNO2 = $_POST["txtPostNo2"]; // 郵便番号2
            $ADDRESS1  = $_POST["txtAddress1"]; // 住所１
            $ADDRESS2  = $_POST["txtAddress2"]; // 住所２
            $BIKO  = $_POST["txtBiko"]; // 備考

            // 入力チェック

            // エラーが０件の場合
            if($errMsg == null)
            {
                $_SESSION['txtName'] = $_POST["txtName"];// 名前
                $_SESSION['rdoSex'] = $_POST["rdoSex"]; // 性別
                $_SESSION['txtPostNo1'] = $_POST["txtPostNo1"]; // 郵便番号1
                $_SESSION['txtPostNo2'] = $_POST["txtPostNo2"]; // 郵便番号2
                $_SESSION['txtAddress1'] = $_POST["txtAddress1"];  // 住所1
                $_SESSION['txtAddress2'] = $_POST["txtAddress2"];  // 住所2
                $_SESSION['txtBiko'] = $_POST["txtBiko"];  // 備考
            }

        // 登録ボタン
        } else if(isset($_POST['btnInsertUpdate'])){

            $NAME  = $_POST["txtName"]; // 名前
            $SEX  = $_POST["rdoSex"]; // 性別
            $POS1  = $_POST["txtPostNo1"]; // 郵便番号1
            $POS2 = $_POST["txtPostNo2"]; // 郵便番号2
            $POSTNO = $_POST["txtPostNo1"]; // 郵便番号
            $POSTNO .= $_POST["txtPostNo2"]; // 郵便番号
            $ADDRESS1  = $_POST["txtAddress1"]; // 住所１
            $ADDRESS2  = $_POST["txtAddress2"]; // 住所２
            $BIKO  = $_POST["txtBiko"]; // 備考

            if($NAME==null)
            {
                $errMsg.= "名前が未入力です。";
            }
            else if($POS1==null)
            {

                $errMsg.= "郵便番号（前半３桁）が未入力です。";
            }
            else if($POS2==null)
            {

                $errMsg.= "郵便番号（後半４桁）が未入力です。";
            }
            else if($ADDRESS1==null)
            {

                $errMsg.= "住所１が未入力です。";
            }
            else if($ADDRESS2==null)
            {

                $errMsg.= "住所２が未入力です。";
            }

            // エラーが０件の場合
            if($errMsg == null)
            {
                // 検索処理のphpファイルを呼び出し
                include('Update.php');
            }
        }

        // エラーが０件の場合、この画面を終了させる
        if($errMsg == null)
        {
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
                            // エラーチェックの内容がある場合は前回の内容を設定する
                            if($errMsg == null)
                            {
                                echo "<input type='text' name='txtName' maxlength='20' size='20'>";
                            }
                            else
                            {
                                echo "<input type='text' name='txtName' maxlength='20' size='20' value=$NAME>";
                            }
                        }
                    ?>
                </td> 
                </tr> 
                <tr> 
                    <td width="100">性別</td>
                    <td width="280">
                <?php
                if($mode == "3") {
                    if($row['SEX'] == "男"){
                        echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                    }
                    else
                    {
                        echo "<input type='radio' name='rdoSex' value='1'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2' checked='checked'>女</input>";
                    }
                }
                else if($mode=="2")
                {
                    echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                    echo "<input type='radio' name='rdoSex' value='2'>女</input>";
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
                            // エラーチェックの内容がある場合は前回の内容を設定する
                            if($errMsg == null)
                            {
                                echo "<input type='text' name='txtPostNo1' maxlength='3' size='4'>";
                                echo "-";
                                echo "<input type='text' name='txtPostNo2' maxlength='4' size='8'>";
                            }
                            else
                            {
                                echo "<input type='text' name='txtPostNo1' maxlength='3' size='4' value=$POS1>";
                                echo "-";
                                echo "<input type='text' name='txtPostNo2' maxlength='4' size='8' value=$POS2>";
                            }
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
                            // エラーチェックの内容がある場合は前回の内容を設定する
                            if($errMsg == null)
                            {
                                echo "<input type='text' name='txtAddress1' maxlength='15' size='25'>";
                            }
                            else
                            {
                                echo "<input type='text' name='txtAddress1' maxlength='15' size='25' value=$ADDRESS1>";
                            }
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
                            // エラーチェックの内容がある場合は前回の内容を設定する
                            if($errMsg == null)
                            {
                                echo "<input type='text' name='txtAddress2' maxlength='15' size='25'>";
                            }
                            else
                            {
                                echo "<input type='text' name='txtAddress2' maxlength='15' size='25' value=$ADDRESS2>";
                            }
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
                            // エラーチェックの内容がある場合は前回の内容を設定する
                            if($errMsg == null)
                            {
                                echo "<input type='text' name='txtBiko' maxlength='15' size='25'>";
                            }
                            else
                            {
                                echo "<input type='text' name='txtBiko' maxlength='15' size='25' value=$BIKO>";
                            }
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