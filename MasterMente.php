<?php
    require_once('MsgList.php');
    require_once('ErrCheck.php');
    require_once('ColumnList.php');

    $errMsg=null;

    // インスタンス生成
    $MsgList = new MsgList();
    $ErrChk = new ErrCheck();
    $ColumnList = new ColumnList();

    // 初期表示時
    if($_SERVER["REQUEST_METHOD"] != "POST") {

        // 画面モードを取得する
        $mode= $_GET['mode'];
        session_start(); // セッション開始
        // 画面モードを取得する
        $_SESSION['mode'] = $mode;

        $ID = null; //ID
        $NAME = null; //名前
        $SEX = '0';  //性別
        $POSTNO = null; //郵便番号
        $POS1 = null; //郵便番号1
        $POS2 = null; //郵便番号2
        $ADDRESS1 = null; //住所1
        $ADDRESS2 = null; //住所2
        $BIKO = null; //備考

        // 選択画面の場合
        if($mode == "3") {
            $ID= $_GET['id'];
            // 検索処理のphpファイルを呼び出し
            include('Search.php');
        }

    } else {
        session_start(); // セッション開始
        // 画面モードを取得する
        $mode= $_SESSION['mode'];

        // 検索ボタン
        if(isset($_POST['btnReSearch'])) {

            $NAME  = trim($_POST["txtName"]); // 名前
            $SEX  = trim($_POST["rdoSex"]); // 性別
            $POS1  = trim($_POST["txtPostNo1"]); // 郵便番号1
            $POS2  = trim($_POST["txtPostNo2"]); // 郵便番号2
            $POSTNO = trim($_POST["txtPostNo1"]); // 郵便番号
            $POSTNO .= trim($_POST["txtPostNo2"]); // 郵便番号
            $ADDRESS1  = trim($_POST["txtAddress1"]); // 住所１
            $ADDRESS2  = trim($_POST["txtAddress2"]); // 住所２
            $BIKO  = trim($_POST["txtBiko"]); // 備考

            // 郵便番号１と郵便番号２がともに空でない場合
            if($ErrChk->nullCheck($POS1) && $ErrChk->nullCheck($POS2)) {
                // 郵便番号１が３桁の場合
                if($ErrChk->lenSameCheck($POS1,3)){
                    //数値かどうか
                    if($ErrChk->numCheck($POS1,3)){
                        // 郵便番号２が４桁の場合
                        if($ErrChk->lenSameCheck($POS2,4)){
                            //数値かどうか
                            if(!$ErrChk->numCheck($POS2,4)){
                                $errMsg = sprintf($MsgList->getMsg('004'), $ColumnList->getPos2());
                            }
                        } else {
                            $errMsg = sprintf($MsgList->getMsg('005'),$ColumnList->getPos2(),4);
                        }
                    } else {
                        $errMsg = sprintf($MsgList->getMsg('004'), $ColumnList->getPos1());
                    }
                } else {
                    $errMsg = sprintf($MsgList->getMsg('005'), $ColumnList->getPos1(),3);
                }
             // 郵便番号１と郵便番号２の片方のみ空の場合
            } else if($POS1!=null || $POS2 != null) {
                $errMsg = sprintf($MsgList->getMsg('006'), $ColumnList->getPostno());
            }

            // エラーが０件の場合
            if(!$ErrChk->nullCheck($errMsg)) {
                $_SESSION['txtName'] = $NAME;// 名前
                $_SESSION['rdoSex'] = $SEX; // 性別
                $_SESSION['txtPostNo'] = $POSTNO;
                $_SESSION['txtAddress1'] = $ADDRESS1;  // 住所1
                $_SESSION['txtAddress2'] = $ADDRESS2;  // 住所2
                $_SESSION['txtBiko'] = $BIKO;  // 備考
            }

        // 登録ボタン（登録モード・更新モード）
        } else if(isset($_POST['btnInsert'])|| isset($_POST['btnUpdate'])) {

            // 更新モードの場合のみ
            if(isset($_POST['btnUpdate'])) {
                $ID  = $_POST["lblId"]; // ID
            }

            $NAME  = trim($_POST["txtName"]); // 名前
            $SEX  = trim($_POST["rdoSex"]); // 性別
            $POS1  = trim($_POST["txtPostNo1"]); // 郵便番号1
            $POS2  = trim($_POST["txtPostNo2"]); // 郵便番号2
            $POSTNO = trim($_POST["txtPostNo1"]); // 郵便番号
            $POSTNO .= trim($_POST["txtPostNo2"]); // 郵便番号
            $ADDRESS1  = trim($_POST["txtAddress1"]); // 住所１
            $ADDRESS2  = trim($_POST["txtAddress2"]); // 住所２
            $BIKO  = trim($_POST["txtBiko"]); // 備考

            // 名前は入力必須
            if(!$ErrChk->nullCheck($NAME)) {
                $errMsg = sprintf($MsgList->getMsg('007'), $ColumnList->getName());
            }
            // 郵便番号1はともに入力必須
            else if(!$ErrChk->nullCheck($POS1)) {
                $errMsg = sprintf($MsgList->getMsg('007'), $ColumnList->getPos1());
            }
            // 郵便番号2はともに入力必須
            else if(!$ErrChk->nullCheck($POS2)) {
                $errMsg = sprintf($MsgList->getMsg('007'), $ColumnList->getPos2());
            }
            // 郵便番号1の書式チェック
            else if(!$ErrChk->numCheck($POS1,3)){
                $errMsg = sprintf($MsgList->getMsg('004'), $ColumnList->getPos1());
            }
            // 郵便番号2の書式チェック
            else if(!$ErrChk->numCheck($POS2,4)){
                $errMsg = sprintf($MsgList->getMsg('004'), $ColumnList->getPos2());
            }
            // 名前が1０桁以下かどうか（必須項目）
            else if(!$ErrChk->lenOverCheck($NAME,10)){
                $errMsg = sprintf($MsgList->getMsg('008'), $ColumnList->getName(),10);
            }
            // 郵便番号１が3桁かどうか（必須項目）
            else if(!$ErrChk->lenSameCheck($POS1,3)){
                $errMsg = sprintf($MsgList->getMsg('005'), $ColumnList->getPos1(),3);
            }
            // 郵便番号2が4桁かどうか（必須項目）
            else if(!$ErrChk->lenSameCheck($POS2,4)){
                $errMsg = sprintf($MsgList->getMsg('005'), $ColumnList->getPos2(),4);
            } else {
                // 住所1の文字数チェック（任意項目）
                if($ErrChk->nullCheck($ADDRESS1)) {
                    // 住所1が15桁以下かどうか
                    if(!$ErrChk->lenOverCheck($ADDRESS1,15)){
                        $errMsg = sprintf($MsgList->getMsg('008'), $ColumnList->getAddress1(),15);
                    }
                }
                // 住所２の文字数チェック（任意項目）
                if($ErrChk->nullCheck($ADDRESS2)) {
                    // 住所2が15桁以下かどうか
                    if(!$ErrChk->lenOverCheck($ADDRESS2,15)){
                        $errMsg = sprintf($MsgList->getMsg('008'), $ColumnList->getAddress2(),15);
                    }
                }
                // 備考の文字数チェック（任意項目）
                if($ErrChk->nullCheck($BIKO)) {
                    // 住所2が15桁以下かどうか
                    if(!$ErrChk->lenOverCheck($BIKO,15)){
                        $errMsg = sprintf($MsgList->getMsg('008'), $ColumnList->getBiko(),15);
                    }
                }
            }

            // エラーが０件の場合
            if(!$ErrChk->nullCheck($errMsg)) {
                // 検索処理のphpファイルを呼び出し
                include('Update.php');
            }
        }

        // エラーが０件の場合、この画面を終了させる
        if(!$ErrChk->nullCheck($errMsg)) {
            // 自画面を閉じる
            echo "<script type='text/javascript'>
            if (window.opener) {
                window.opener.location.reload();
            } else {
                alert('親ウィンドウが存在しません。手動で画面を更新してください。');
                window.close();
            }
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
                    echo "<td width='280'>"; echo str_pad($ID, 6, 0, STR_PAD_LEFT); echo"</td> ";
                    echo "<input type='hidden' name='lblId' maxlength='7' size='10' value=$ID>";
                    echo "</tr> ";
                }
                ?>
                <tr> 
                    <td width="100">名前</td>
                    <td width="280">
                    <?php
                        require_once('ErrCheck.php');
                        $ErrChk = new ErrCheck();
                        // エラーチェックの内容がある場合は前回の内容を設定する
                        if($ErrChk->nullCheck($NAME) || $ErrChk->nullCheck($errMsg)) {
                            echo "<input type='text' name='txtName' maxlength='10' size='20' value=$NAME>";
                        } else {
                            if($mode == "3") {
                                echo "<input type='text' name='txtName' maxlength='10' size='20' value=$row[NAME]>";
                            } else {
                                echo "<input type='text' name='txtName' maxlength='10' size='20'>";
                            }
                        }
                    ?>
                </td> 
                </tr> 
                <tr> 
                    <td width="100">性別</td>
                    <td width="280">
                <?php
                require_once('ErrCheck.php');
                $ErrChk = new ErrCheck();
                // 初期表示の場合
                if(!$ErrChk->nullCheck($errMsg)) {
                    // 登録モードの初期表示の場合
                    if($mode == "2") {
                        echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                    }
                    // 更新モードの初期表示の場合
                    else if($mode == "3") {
                        if($row['SEX'] == "男") {
                            echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                            echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                        } else {
                            echo "<input type='radio' name='rdoSex' value='1'>男</input>";
                            echo "<input type='radio' name='rdoSex' value='2' checked='checked'>女</input>";
                        }
                    // 検索モードの初期表示の場合
                    } else if($mode == "1") {
                        echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                        echo "<input type='radio' name='rdoSex' value='0'  checked='checked'>未指定</input>";
                    }
                // 入力チェックで再表示の場合
                } else {
                    // 未指定の場合
                    if ($SEX== "0") {
                        echo "<input type='radio' name='rdoSex' value='1'>男</input>";
                        echo "<input type='radio' name='rdoSex' value='2' checked='checked'>女</input>";
                        echo "<input type='radio' name='rdoSex' value='0'  checked='checked'>未指定</input>";
                    }
                    else
                    {
                        if($SEX == "1") {
                            echo "<input type='radio' name='rdoSex' value='1' checked='checked'>男</input>";
                            echo "<input type='radio' name='rdoSex' value='2'>女</input>";
                        } else if ($SEX== "2") {
                            echo "<input type='radio' name='rdoSex' value='1'>男</input>";
                            echo "<input type='radio' name='rdoSex' value='2' checked='checked'>女</input>";
                        }
                        // 検索モードの時は未指定を表示させる
                        if($mode == "1") {
                            echo "<input type='radio' name='rdoSex' value='0'>未指定</input>";
                        }
                    }
                }
                ?>
                </td> 
                </tr>
                <tr> 
                    <td width="100">郵便番号</td>
                    <td width="280">
                    <?php
                    require_once('ErrCheck.php');
                    $ErrChk = new ErrCheck();
                    // 完全な初期表示の場合
                    if(!$ErrChk->nullCheck($POS1) && !$ErrChk->nullCheck($POS2) && !$ErrChk->nullCheck($errMsg)) {
                        if($mode == "3") {
                            echo "<input type='text' name='txtPostNo1' maxlength='3' size='4' value=$row[POSTNO1]>";
                            echo "-";
                            echo "<input type='text' name='txtPostNo2' maxlength='4' size='8' value=$row[POSTNO2]>";
                        } else {
                            echo "<input type='text' name='txtPostNo1' maxlength='3' size='4'>";
                            echo "-";
                            echo "<input type='text' name='txtPostNo2' maxlength='4' size='8'>";
                        }
                    // 入力チェックで再表示の場合
                    } else {
                        // 中身の設定がされている場合は設定する
                        if($ErrChk->nullCheck($POS1)) {
                            echo "<input type='text' name='txtPostNo1' maxlength='3' size='4' value=$POS1>";
                        } else {
                            echo "<input type='text' name='txtPostNo1' maxlength='3' size='4'>";
                        }
                        echo "-";
                        if($ErrChk->nullCheck($POS2)) {
                            echo "<input type='text' name='txtPostNo2' maxlength='4' size='8' value=$POS2>";
                        } else {
                            echo "<input type='text' name='txtPostNo2' maxlength='4' size='8'>";
                        }
                    }
                    ?>
                    </td> 
                </tr> 
                <tr> 
                    <td width="100">住所１</td>
                    <td width="280">
                    <?php
                    require_once('ErrCheck.php');
                    $ErrChk = new ErrCheck();
                        // 入力チェックで再表示の場合
                        if($ErrChk->nullCheck($ADDRESS1) || $ErrChk->nullCheck($errMsg)) {
                            echo "<input type='text' name='txtAddress1' maxlength='15' size='25' value=$ADDRESS1>";
                        } else {
                            if($mode == "3") {
                                echo "<input type='text' name='txtAddress1' maxlength='15' size='25' value=$row[ADDRESS1]>";
                            } else {
                                echo "<input type='text' name='txtAddress1' maxlength='15' size='25'>";
                            }
                        }
                    ?>
                    </td> 
                </tr> 
                <tr> 
                    <td width="100">住所２</td>
                    <td width="280">
                    <?php
                    require_once('ErrCheck.php');
                    $ErrChk = new ErrCheck();
                        // 入力チェックで再表示の場合
                        if($ErrChk->nullCheck($ADDRESS2) || $ErrChk->nullCheck($errMsg)) {
                            echo "<input type='text' name='txtAddress2' maxlength='15' size='25' value=$ADDRESS2>";
                        } else {
                            if($mode == "3") {
                                echo "<input type='text' name='txtAddress2' maxlength='15' size='25' value=$row[ADDRESS2]>";
                            } else {
                                echo "<input type='text' name='txtAddress2' maxlength='15' size='25'>";
                            }
                        }
                    ?>
                </td> 
                </tr> 
                <tr> 
                    <td width="100">備考</td>
                    <td width="280">
                    <?php
                    require_once('ErrCheck.php');
                    $ErrChk = new ErrCheck();
                        // 入力チェックで再表示の場合
                        if($ErrChk->nullCheck($BIKO) || $ErrChk->nullCheck($errMsg)) {
                            echo "<input type='text' name='txtBiko' maxlength='15' size='25' value=$BIKO>";
                        } else {
                            if($mode == "3") {
                                echo "<input type='text' name='txtBiko' maxlength='15' size='25' value=$row[BIKO]>";
                            } else {
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
                            echo "<button type='submit' style='width:100px;' name='btnInsert'>新規登録</button>";
                        } else {
                            echo "<button type='submit' style='width:100px;' name='btnUpdate'>更新</button>";
                        }
                    ?>
                    </center>
                    </td>
                </tr> 
         </table>
</form>
    </body>
</html>