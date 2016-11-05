<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_edit_category extends Model
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
	
	public function getCategoryRow()
	{
		$_GET['id_cat'] = $this->data->escape($_GET['id_cat']);
	
		$query = "SELECT * FROM ".$this->data->getTableName('category')." WHERE id_cat=".$_GET['id_cat'];
		$result = $this->data->querySQL($query);
		
		return $this->data->getRow($result);
	}
	
	public function editCategoryRow($fields)
	{
		$_POST['id_cat'] = $this->data->escape($_POST['id_cat']);
	
		$table = $this->data->getTableName('category');
		$where = "id_cat=".$_POST['id_cat'];
		
		return $this->data->update($fields, $table, $where); 
	}
}

?>