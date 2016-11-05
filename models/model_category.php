<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_category extends Model
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
	
	public function getCategoryArr()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('category')." ORDER BY name";
		$result = $this->data->querySQL($query);
		
		return $this->data->getColumnArray($result);
	}
	
	public function getCountSubscription($id_cat)
	{
		if(preg_match("|^[\d]+$|",$id_cat)){
			$from = $this->data->getTableName('subscription');
			$parameters = 'COUNT(*)';
			$where = "WHERE id_cat = ".$id_cat;
			$group = '';
			$order = '';
			$limit = '';
	
			$result = $this->data->select($parameters, $from, $where, $group, $order, $limit);
			$count = $this->data->getRow($result, 'row');
			return $count[0];
		}
		else{
			return 0;
		}		
	}
	
	public function removeCategory($id_cat)
	{
		$id_cat = $this->data->escape($id_cat);
		$result = $this->data->delete($this->data->getTableName('category'), "id_cat=".$id_cat,'');
		
		if($result)
			return $this->data->delete($this->data->getTableName('subscription'), "id_cat=".$id_cat,'');
		else
			return false;		
	}
}