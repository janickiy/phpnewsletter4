<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_log extends Model
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
	
	public function getLogArr()
	{
		$table = $this->data->getTableName('log');
		$this->data->parameters = "*,DATE_FORMAT(time,'%d.%m.%Y %H:%i') as send_time";
		$this->data->tablename = "".$this->data->getTableName('log')."";
		$this->data->order = 'ORDER BY id_log desc';
		$this->data->pnumber = 20;
		
		return $this->data->get_page();
	}
	
	public function getTotal()
	{
		$this->data->order = '';
		$this->data->tablename = $this->data->getTableName('log');
		$this->data->pnumber = 20;
		$number = intval(($this->data->get_total() - 1) / $this->data->pnumber) + 1;
		
		return $number;		
	}
	
	public function getPageNumber()
	{
		return $this->data->page;
	}
	
	public function getDetaillog($strtmp)
	{
		$id_log = $this->data->escape($_GET['id_log']);
	
		$query = "SELECT *, a.time as time, c.name as catname, s.name as name FROM ".$this->data->getTableName('ready_send')." a 
					LEFT JOIN ".$this->data->getTableName('users')." b ON b.id_user=a.id_user 
					LEFT JOIN ".$this->data->getTableName('template')." s ON a.id_template=s.id_template
					LEFT JOIN ".$this->data->getTableName('category')." c ON s.id_cat=c.id_cat
					WHERE id_log=".$id_log."
					ORDER BY ".$strtmp."";
					
		$result = $this->data->querySQL($query);			
					
		return $this->data->getColumnArray($result);			
	}
	
	public function countLetters($id_log)
	{
		$id_log = $this->data->escape($id_log);
		
		$query = "SELECT * FROM ".$this->data->getTableName('ready_send')." WHERE id_log=".$id_log."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRecordCount($result);
	}
	
	public function countSent($id_log)
	{
		$id_log = $this->data->escape($id_log);
		
		$query = "SELECT * FROM ".$this->data->getTableName('ready_send')." WHERE success='yes' and id_log=".$id_log."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRecordCount($result);
	}
	
	public function countRead($id_log)
	{
		$id_log = $this->data->escape($id_log);
		
		$query = "SELECT * FROM ".$this->data->getTableName('ready_send')." WHERE readmail='yes' and id_log=".$id_log;
		$result = $this->data->querySQL($query);
		
		return $this->data->getRecordCount($result);		
	}
	
	public function clearLog()
	{
		$delete1 = $this->data->delete($this->data->getTableName('log'));
		$delete2 = $this->data->delete($this->data->getTableName('ready_send'));

		if($delete1 and $delete2)
			return true;
		else
			return false;
	}	
}

?>