<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_add_user extends Model
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
	
	public function getGategoryList()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('category')." ORDER BY name";
		$result = $this->data->querySQL($query);
		
		return $this->data->getColumnArray($result);
	}

	public function checkExistEmail()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('users')." WHERE email LIKE '".$_POST['email']."'";
		$result = $this->data->querySQL($query);
		
		if($this->data->getRecordCount($result) == 0)
			return false;
		else
			return true;
	}
	
	public function checkSub($id_cat)
	{
		if($_POST['id_cat']){
			foreach($_POST['id_cat'] as $row){
				if($id_cat == $row){
					return true;
					break;
				}
			}
		}
	}
	
	public function addUser($fields)
	{
		$id_user = $this->data->insert($fields, $this->data->getTableName('users'));
		
		if($id_user){
			if($_POST['id_cat']){
				foreach($_POST['id_cat'] as $id){
					if(preg_match("|^[\d]+$|",$id))	{
						$insert = "INSERT INTO ".$this->data->getTableName('subscription')." (`id_sub`,`id_user`,`id_cat`) VALUES (0,".$id_user.",".$id.")";
						$this->data->querySQL($insert);
					}
				}
			}
			return true;
		}else return false;
	}	
}

?>