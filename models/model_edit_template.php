<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_edit_template extends Model
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

	public function editTemplate($fields)
	{
		global $PNSL;
	
		$table = $this->data->getTableName('template');
		$id_template = $this->data->escape($_POST['id_template']);
		$where = "id_template = ".$id_template;
		$result = $this->data->update($fields, $table, $where); 
		
		if($result){
		
			for($i = 0; $i<count($_FILES["attachfile"]["name"]); $i++){
		
				if(!empty($_FILES["attachfile"]["name"][$i])) {
					$ext = strrchr($_FILES['attachfile']['name'][$i], ".");
					$attachfile = $PNSL["system"]["dir_attach"].date("YmdHis",time()).$i.$ext;
			
					if(@copy($_FILES['attachfile']['tmp_name'][$i], $attachfile)) { 
						@unlink($_FILES['attachfile']['tmp_name'][$i]); 
					}

					$attachfields = array();
					$attachfields['id_attachment'] = 0;
					$attachfields['name'] = $_FILES['attachfile']['name'][$i];
					$attachfields['path'] = $attachfile;
					$attachfields['id_template'] = $id_template;				
				
					$this->data->insert($attachfields, $this->data->getTableName('attach'));
				}		
			}
			
			return true;
		}
		else return false;
	}

	public function getAttachmentsList($id_template)
	{
		$id_template = $this->data->escape($id_template);
		
		$query = "SELECT * FROM ".$this->data->getTableName('attach')." WHERE id_template=".$id_template." ORDER by name";
		$result = $this->data->querySQL($query);

		return $this->data->getColumnArray($result);
	}

	public function getCategoryOptionList()
	{
		$from = $this->data->getTableName('category');
		$parameters = '*';
		$order = 'ORDER BY name';
		$result = $this->data->select($parameters, $from, '', '', $order, '');
		
		return $this->data->getColumnArray($result);
	}
	
	public function getTemplate($id_template)
	{
		$id_template = $this->data->escape($id_template);
		$parameters = '*';
		$from = $this->data->getTableName('template');
		$where = "WHERE id_template=".$id_template;
		
		$result = $this->data->select($parameters, $from, $where, '', '', '');
		
		return $this->data->getRow($result); 
	}
	
	public function removeAttach($id_attachment)
	{
		$id_attachment = $this->data->escape($id_attachment);
		
		$query = "SELECT * FROM ".$this->data->getTableName('attach')." WHERE id_attachment=".$id_attachment;
		$result = $this->data->querySQL($query);
		
		while($row = $this->data->getRow($result, 'array')){
			if(file_exists($row['path'])) @unlink($row['path']);		
		}		
		
		$where = "id_attachment=".$id_attachment;
		
		return $this->data->delete($this->data->getTableName('attach'),$where,'');
	}
	
	public function getSetting(){
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		
		return $this->data->getRow($result);	
	}
}