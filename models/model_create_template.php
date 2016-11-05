<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_create_template extends Model
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
	
	public function getCategoryOptionList()
	{
		$from = $this->data->getTableName('category');
		$parameters = '*';
		$order = 'ORDER BY name';
		$result = $this->data->select($parameters, $from, '', '', $order, '');
		
		return $this->data->getColumnArray($result);
	}

	public function addNewTemplate($fields)
	{
		global $PNSL;
		$parameters = 'MAX(pos)';
		$from = $this->data->getTableName('template');
		$result = $this->data->select($parameters, $from, '', '', '', '');
		
		$total = $this->data->getRow($result, 'assoc');
		
		if($total) 
			$pos = $total['MAX(pos)'] + 1; 
		else 
			$pos = 1;			
		
		$fields['pos'] = $pos;
		
		$id_insert = $this->data->insert($fields, $this->data->getTableName('template'));
		
		if($id_insert){
		
			for($i = 0; $i<count($_FILES["attachfile"]["name"]); $i++){
		
				if(!empty($_FILES["attachfile"]["name"][$i])){
					$ext = strrchr($_FILES['attachfile']['name'][$i], ".");
					$attachfile = $PNSL["system"]["dir_attach"].date("YmdHis",time()).$i.$ext;
			
					if(@copy($_FILES['attachfile']['tmp_name'][$i], $attachfile)) { 
						@unlink($_FILES['attachfile']['tmp_name'][$i]); 
					}

					$attachfields = array();
					$attachfields['id_attachment'] = 0;
					$attachfields['name'] = $_FILES['attachfile']['name'][$i];
					$attachfields['path'] = $attachfile;
					$attachfields['id_template'] = $id_insert;				
				
					$this->data->insert($attachfields, $this->data->getTableName('attach'));
				}		
			}
			
			return $id_insert;
		}
		else return false;
	}
	
	public function getSetting(){
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRow($result);	
	}
}