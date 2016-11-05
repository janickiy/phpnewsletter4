<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_logstatxls extends Model
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
	
	public function getTimelog()
	{
		$id_log = $this->data->escape($_GET['id_log']);
		
		$query = "SELECT time FROM ".$this->data->getTableName('log')." WHERE id_log =".$id_log;
		$result = $this->data->querySQL($query);
		$row = $this->data->getRow($result);
		
		return $row['time'];
	}
	
	public function getTotalfaild()
	{
		$id_log = $this->data->escape($_GET['id_log']);
		
		$query = "SELECT COUNT(*) FROM ".$this->data->getTableName('ready_send')." WHERE id_log=".$id_log." AND success='no'";
		$result = $this->data->querySQL($query);
		$row = $this->data->getRow($result, 'assoc');
		
		return $total['COUNT(*)'];		
	}
	
	public function getTotaltime()
	{
		$id_log = $this->data->escape($_GET['id_log']);
		$query = "SELECT *,sec_to_time(UNIX_TIMESTAMP(max(time)) - UNIX_TIMESTAMP(min(time))) as totaltime FROM ".$this->data->getTableName('ready_send')." WHERE id_log=".$_GET['id_log']."";

		$result = $this->data->querySQL($query);
		$row = $this->data->getRow($result);
		
		return $row['totaltime'];	
	}
	
	public function getLogList()
	{
		$id_log = $this->data->escape($_GET['id_log']);	
		
		$query = "SELECT *, a.time as time FROM ".$this->data->getTableName('ready_send')." a 
		LEFT JOIN ".$this->data->getTableName('users')." b ON b.id_user=a.id_user 
		LEFT JOIN ".$this->data->getTableName('template')." s ON s.id_template=a.id_template
		WHERE id_log=".$id_log;
		
		$result = $this->data->querySQL($query);
		
		return $this->data->getColumnArray($result);		
	}

	public function getTotalread()
	{
		$id_log = $this->data->escape($_GET['id_log']);	
	
		$query = "SELECT COUNT(*) FROM ".$this->data->getTableName('ready_send')." WHERE id_log=".$id_log." AND readmail='yes'";
		$result = $this->data->querySQL($query);
		$total = $this->data->getRow($result, 'assoc');
		
		return $total['COUNT(*)'];
	}	
}