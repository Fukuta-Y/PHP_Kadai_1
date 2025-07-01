<?php
    require_once('ConnectInfo.php');
    header('Content-Type: application/json'); // JSONレスポンスであることを指定

    $ConnectInfo = new ConnectInfo();
    $response = ['success' => false, 'message' => ''];

    try {
        $conn = new PDO(
            $ConnectInfo->getCon(),
            $ConnectInfo->getUser(),
            $ConnectInfo->getPassword(),
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
        $conn->beginTransaction();

        $sql = "DELETE FROM \"T_USER_INFO\" WHERE \"ID\" = :ID";
        $stmt = $conn->prepare($sql);

        if (isset($_GET['id'])) {
            $stmt->bindParam(':ID', $_GET['id'], PDO::PARAM_STR);
            $stmt->execute();
            $deleteCount = $stmt->rowCount();
            $conn->commit();

            if ($deleteCount > 0) {
                $response['success'] = true;
                $response['message'] = 'レコードが正常に削除されました。';
            } else {
                $response['message'] = '指定されたIDのレコードが見つかりませんでした。';
            }
        } else {
            $response['message'] = '削除するIDが指定されていません。';
        }
    } catch (PDOException $e) {
        $response['message'] = 'エラーが発生しました: ' . $e->getMessage();
        if (isset($conn)) {
            $conn->rollBack();
        }
    } finally {
        echo json_encode($response);
        exit();
    }
?>
