<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_pic extends Model
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
	
	public function countUser()
	{
		$_GET['id_template'] = $this->data->escape($_GET['id_template']);
		$_GET['id_user'] = $this->data->escape($_GET['id_user']);
		
		$query = "UPDATE ".$this->data->getTableName('ready_send')." SET readmail='yes' WHERE id_template=".$_GET['id_template']." AND id_user=".$_GET['id_user'];
		
		return $this->data->querySQL($query);
	}
}

?>