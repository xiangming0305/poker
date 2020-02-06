<?php
		require_once $_SERVER['DOCUMENT_ROOT']."/classes/Classes.php";
/**
 * Created by PhpStorm.
 * User: daudau
 * Date: 7/21/18
 * Time: 3:22 AM
 */

class Poker_PaymentMethod
{
    private $id;
    public static function all($type,$delete)
    {
	    $sql = new SQLConnection();

		if($delete == true){
			if($type != "all")
				return $sql->getAssocArray("select * from poker_payment_methods where type = '{$type}'");
			else
				return $sql->getAssocArray('select * from poker_payment_methods');
		}
		
		if($type != "all")
			return $sql->getAssocArray("select * from poker_payment_methods where status = 0 AND type = '{$type}'");
		else
			return $sql->getAssocArray('select * from poker_payment_methods where status = 0');
    }

    public static function add($name, $description, $payment_name, $country, $payment_address, $type)
    {
        $sql = new SQLConnection();
        return $sql->query("insert into poker_payment_methods (`name`, `description`, `status`, `payment_name`, `country`, `payment_address`, `type`) values ('{$name}', '{$description}', '0', '{$payment_name}', '{$country}', '{$payment_address}', '{$type}')");
    }

    public static function delete($id)
    {
        $sql = new SQLConnection();
        return $sql->query("UPDATE poker_payment_methods set status = '1' where id = {$id}");
    }

	public function __construct($id, $col = "id")
    {
        $sql = new SQLConnection();
        $temp = $sql->getArray("select * from poker_payment_methods where ".$col."= ".$id."");
		if(count($temp)){
            $temp = $temp[0];
            $this->id = $temp['id'];
            $this->name = $temp['name'];
            $this->description = $temp['description'];
            $this->status = $temp['status'];
            $this->payment_name = $temp['payment_name'];
            $this->country = $temp['country'];
            $this->payment_address = $temp['payment_address'];
            $this->type = $temp['type'];
        }
    }
}