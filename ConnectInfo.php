<?php
class ConnectInfo
{
    /**
     * PostgreSQL 接続情報 (DSN)
     * 1. 「-pooler」を削除したホスト名を使う
     * 2. 「options」パラメータを削除する
     */
    private $conInfo;
    private $user;
    private $password;

    public function __construct()
    {
        // 全て環境変数から取得。設定漏れがある場合は null または false が入ります
        $this->conInfo  = getenv('DB_DSN_PHP');
        $this->user     = getenv('DB_USER');
        $this->password = getenv('DB_PASSWORD');
    }

    // 接続情報を返す
    public function getCon()
    {
        return $this->conInfo;
    }

    // 接続ユーザを返す
    public function getUser()
    {
        return $this->user;
    }

    // 接続パスワードを返す
    public function getPassword()
    {
        return $this->password;
    }
}
