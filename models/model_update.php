<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_update extends Model
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

	public function addLicenseKey($licensekey)
	{
		$licensekey = $this->data->escape($licensekey);
		$data = array();
		$data['licensekey'] = $licensekey;
	
		return $this->data->insert($data, $this->data->getTableName('licensekey'));
	}
	
	public function updateLicenseKey($licensekey)
	{
		$licensekey = $this->data->escape($licensekey);
		$fields = array();
		$fields['licensekey'] = $licensekey;
	
		return $this->data->update($fields, $this->data->getTableName('licensekey'), '');
	}
	
	public function getLicenseKey()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('licensekey')."";
		$result = $this->data->querySQL($query);
		$row = $this->data->getRow($result);
		
		return $row['licensekey'];
	}
	
	public function getVersionCode($version)
	{
		preg_match("/(\d+)\.(\d+)\./", $version, $out);
		$code = ($out[1] * 10000 + $out[2] * 100);
		
		return $code;
	}
	
	public function getSetting(){
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRow($result);	
	}
}