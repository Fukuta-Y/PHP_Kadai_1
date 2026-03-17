<?php
class ConnectInfo
{
    /**
     * PostgreSQL 接続情報 (DSN)
     * 1. 「-pooler」を削除したホスト名を使う
     * 2. 「options」パラメータを削除する
     */
    public $conInfo = 'pgsql:host=ep-divine-truth-a1eqa5uj.ap-southeast-1.aws.neon.tech;port=5432;dbname=neondb;sslmode=require';

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
