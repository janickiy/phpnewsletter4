<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_xmlcountsend extends Model
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
	
	public function getSetting(){
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRow($result);	
	}

	public function getTotalMails()
	{
		$settings = $this->getSetting();
	
		$query = "SELECT COUNT(*) FROM ".$this->data->getTableName('users')." WHERE status = 'active'";
	
		$result = $this->data->querySQL($query);		
		$count = $this->data->getRow($result, 'assoc');
		
		$total = $settings['make_limit_send'] == "yes" ? $settings['limit_number'] : $count['COUNT(*)'];
			
		return $total;	
	}
	
	public function getSuccessMails()
	{
		$_REQUEST['id_log'] = $this->data->escape($_REQUEST['id_log']);
		
		if(preg_match("|^[\d]+$|",$_REQUEST['id_log'])){
			$query = "SELECT COUNT(*) FROM ".$this->data->getTableName('ready_send')." WHERE id_log=".$_REQUEST['id_log']." AND success='yes'";
			$result = $this->data->querySQL($query);
			$count = $this->data->getRow($result, 'assoc');
		
			return $count['COUNT(*)'];		
		}
		else return 0;
	}
	
	public function getUnsuccessfulMails()
	{
		$_REQUEST['id_log'] = $this->data->escape($_REQUEST['id_log']);
	
		if(preg_match("|^[\d]+$|",$_REQUEST['id_log'])){
			$query = "SELECT COUNT(*) FROM ".$this->data->getTableName('ready_send')." WHERE id_log=".$_REQUEST['id_log']." AND success='no'";
			$result = $this->data->querySQL($query);
			$count = $this->data->getRow($result, 'assoc');
		
			return $count['COUNT(*)'];
		}
		else return 0;		
	}

	public function getSettings()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		$settings = $this->data->getRow($result);	
		
		return $settings;
	}	
}

?>