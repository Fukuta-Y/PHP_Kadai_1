<?php
    class ColumnList{

        public $NAME="名前";
        public $SEX="性別";
        public $POS1="郵便番号１";
        public $POS2="郵便番号２";
        public $POSTNO="郵便番号";
        public $ADDRESS1="住所１";
        public $ADDRESS2="住所２";
        public $BIKO="備考";

        // 名前の項目名を返す
        public function getName(){
            return $this->NAME;
        }

        // 性別の項目名を返す
        public function getSex(){
            return $this->SEX;
        }

        // 郵便番号1の項目名を返す
        public function getPos1(){
            return $this->POS1;
        }

        // 郵便番号2の項目名を返す
        public function getPos2(){
            return $this->POS2;
        }

        // 郵便番号の項目名を返す
        public function getPostno(){
            return $this->POSTNO;
        }

        // 住所1の項目名を返す
        public function getAddress1(){
            return $this->ADDRESS1;
        }

        // 住所２の項目名を返す
        public function getAddress2(){
            return $this->ADDRESS2;
        }

        // 備考の項目名を返す
        public function getBiko(){
            return $this->BIKO;
        }
    }
?>