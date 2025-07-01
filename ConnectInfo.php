<?php
class ConnectInfo
{

    // PostgreSQL 接続情報
    public $conInfo = 'pgsql:host=aws-0-ap-northeast-1.pooler.supabase.com;port=5432;dbname=postgres';

    // ユーザー名とパスワード
    public $user = 'postgres.szdcftaezxmhxjxqdeyi';
    public $password = 'Yuki1008!!!!';

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
