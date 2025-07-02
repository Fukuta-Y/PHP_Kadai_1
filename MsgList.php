<?php
    class MsgList{
        private $errMsgList = array(
            '001' => '検索条件に一致するデータがありませんでした。',
            '002' => '会員情報が登録されていません。',
            '003' => 'この行を削除しますか？',
            '004' => '%sは片方のみ入力できません。',
            '005' => '%sが未入力です。',
            '006' => '%sは%s文字以内で入力してください。',
        );

        // 一致したIDの内容を表示する
        public function getMsg($msgId){
            return $this->errMsgList[$msgId];
        }
        
    }
?>