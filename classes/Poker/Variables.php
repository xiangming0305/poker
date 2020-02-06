<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
    
class Poker_Variables{
    
    public static function set($var, $value){
        $sql = new SQLConnection();
        $sql->query("UPDATE poker_variables SET value='$value', changed=NOW() WHERE name='$var'");
        
        if(!mysqli_error()){
            return true;
        }
        
        return false;
    }
    
    public static function get($var){
        $sql = new SQLConnection;
        $temp = $sql->getAssocArray("SELECT * FROM poker_variables WHERE name='$var'");
        
        if(!count($temp)){
            return null;
        }else{
            return $temp[0]['value'];
        }
    }

    public static function getList($listVars) {
        $lstName = implode(', ', array_map(function ($item) {
            return "'" . $item . "'";
        }, $listVars));
        $sql = new SQLConnection;
        $temp = $sql->getAssocArray("SELECT * FROM poker_variables WHERE name in ({$lstName}) ");
        $expected = [];
        foreach ($temp as $item) {
            $expected[$item['name']] = $item;
        }

        return $expected;
    }

    public static function out($var){
        echo self::get($var);
    }
    
    
}