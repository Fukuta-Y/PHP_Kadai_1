<?php
    class ConnectInfo{

        public $conInfo='mysql:host=127.0.0.1;port=3306;dbname=aspKadaiDB;charset=utf8';

        public $user='root';

        // 接続情報を返す
        public function getCon(){
            return $this->conInfo;
        }
        // 接続ユーザを返す
        public function getUser(){
            return $this->user;
        }
    }
?>