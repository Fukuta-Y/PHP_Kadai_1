<?php
class ConnectInfo
{
    /**
     * PostgreSQL 接続情報 (DSN)
     * Neonの場合、hostの後ろに「-pooler」を付け、
     * optionsパラメータでプロジェクトIDを指定するのが確実です。
     */
    public $conInfo = 'pgsql:host=ep-divine-truth-a1eqa5uj-pooler.ap-southeast-1.aws.neon.tech;port=5432;dbname=neondb;sslmode=require;options=--project=ep-divine-truth-a1eqa5uj-pooler';

    // ユーザー名
    public $user = 'neondb_owner';

    // パスワード
    public $password = 'npg_POc5kym8noRC';

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
