<?php
class ErrCheck{
    // 引数で渡されたtxtがNULLかどうか
    public function nullCheck($txt){
        if($txt!=null) {
            return true;
        } else {
            return false;
        }
    }
    // 引数で渡されたtxtが数値かどうか
    public function numCheck($txt,$len){
        if(preg_match('/^([0-9]{' + $len +'})$/', $txt)) {
            return true;
        } else {
            return false;
        }
    }
    // 引数で渡されたtxtがlen文字と合致するかどうか
    public function lenSameCheck($txt,$len){
        if(mb_strlen($txt)==$len) {
            return true;
        } else {
            return false;
        }
    }
    // 引数で渡されたtxtがlen文字以内かどうか
    public function lenOverCheck($txt,$len){
        if(mb_strlen($txt)<=$len) {
            return true;
        } else {
            return false;
        }
    }
}
?>