<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_export extends Model
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
	
	public function getUserList($id_cat){
		if(is_array($id_cat) and count($id_cat) > 0)
			$query = "SELECT name,email FROM ".$this->data->getTableName('users')." u 
						LEFT JOIN ".$this->data->getTableName('subscription')." s ON s.id_user=u.id_user 
						WHERE s.id_cat IN (".implode(",",$id_cat).") AND status='active'
						GROUP by u.id_user";
		else	
			$query = "SELECT name,email FROM ".$this->data->getTableName('users')." WHERE status='active'";
			
		$result = $this->data->querySQL($query);
		
		return $this->data->getColumnArray($result);
	}
	
	public function getCategoryList()
	{
		$query =  "SELECT *,cat.id_cat as id FROM ".$this->data->getTableName('category')." cat 
					LEFT JOIN ".$this->data->getTableName('subscription')." subs ON cat.id_cat=subs.id_cat
					GROUP by id
					ORDER BY name";
					
		$result = $this->data->querySQL($query);
		return $this->data->getColumnArray($result);
	}
}