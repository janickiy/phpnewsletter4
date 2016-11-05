<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_start_update extends Model
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
	
	public function DownloadUpdate($path, $getfile)
	{
		global $PNSL;
		
		$result = true;
		
		if($getfile){
			$newUpdate = file_get_contents($getfile);
			if(!is_dir($PNSL["system"]["dir_tmp"])) mkdir($PNSL["system"]["dir_tmp"]);
			$dlHandler = fopen($path, 'w');
			
			if(!fwrite($dlHandler, $newUpdate)) { 
				$result = false;
			}
			
			fclose($dlHandler);
		}

		return $result;
	}
	
	public function updateDB($path)
	{
		global $PNSL;
		
		$result = true;	
		
		$queries = @file($path);

		foreach ($queries as $query){
			$query = str_replace('%prefix%', $PNSL["config"]["db"]["prefix"], $query);
			$query = trim($query);

			if(empty($query)){
				continue;
			}

			if(!$this->data->querySQL($query)){
				$result = false;
				break;
			}
		}
		
		return $result;	
	}
	
	public function version_code_detect()
	{	
		global $PNSL;
		
		$tables_list = array(
		'attach',
		'aut',
		'category',
		'charset',
		'licensekey',
		'log',
		'process',
		'ready_send',
		'settings',
		'subscription',
		'template',
		'users',
		);		
		
		$tables = array();
	
		if($res1 = $this->data->querySQL("SHOW TABLES FROM `".$PNSL["config"]["db"]["name"]."` LIKE '".$PNSL["config"]["db"]["prefix"]."%'")) {
			while ($row1 = $this->data->getRow($res1)){
				$res2 = $this->data->querySQL("DESCRIBE `".$row1[0]."`");
				$tables[substr($row1[0], strlen($PNSL["config"]["db"]["prefix"]))] = array();

				while($row2 = $this->data->getRow($res2)) {
					$tables[substr($row1[0], strlen($PNSL["config"]["db"]["prefix"]))][] = $row2[0];
				}
			}
		}
			
		$exists_tables = array();

		foreach($tables_list as $table){
			if(isset($tables[$table])) {
				$exists_tables[] = $table;
			}
		}
			
		$version_code_detect = null;
			
		if($exists_tables) {
			$version_code_detect = 40000;
				
			if (in_array('require_confirmation', $tables['settings'])) {
				$version_code_detect = 40100;
			}

			if (in_array('random', $tables['settings'])) {
				$version_code_detect = 40200;
			}
		}

		return $version_code_detect;	
	}
	

}