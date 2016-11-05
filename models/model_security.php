<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_security extends Model
{
	private $data;

	public function __construct() {
		$this->get_data();
	}

	public function get_data()
	{
		global $PNSL;
		
		$this->data = new DBParser($PNSL);
		return $this->data;
	}
	
	public function changePassword()
	{
		$password = md5(trim($_POST["password"]));

		$query = "UPDATE ".$this->data->getTableName('aut')." SET passw='".$password."'";
		$result = $this->data->querySQL($query);
		
		return $result;
	}	
}

?>