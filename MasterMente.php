<?php
    try{
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8", "root", "");
        $sql = 'select * from T_USER_INFO';
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();
    }catch (PDOException $e){ 
        print('Error:'.$e->getMessage());
        die();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Testt1.php</title>
    <link href="design.css" rel="stylesheet">
</head>
<body>
        <table border=’1’ class="main">
        <tr> 
            <td width="100">会員番号</td>
            <td width="100">名前</td> 
            <td width="100">性別</td> 
            <td width="100">郵便番号</td> 
            <td width="100">住所１</td> 
            <td width="100">住所２</td> 
            <td width="100">備考</td> 
        </tr> 
<?php 
    foreach($result as $row){
?> 
         <tr> 
            <td width="100"><?php echo $row['ID'];?></td> 
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
        <ttable>
    </body>
</html>