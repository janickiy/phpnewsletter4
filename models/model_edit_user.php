<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_edit_user extends Model
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
	
	public function getUserEdit($id_user)
	{
		if(preg_match("|^[\d]*$|",$id_user)){
			$query = "SELECT * FROM ".$this->data->getTableName('users')." WHERE id_user=".$id_user;
			$result = $this->data->querySQL($query);
			return $this->data->getRow($result);
		}	
	}

	public function getGategoryList()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('category')." ORDER BY name";
		$result = $this->data->querySQL($query);
		
		return $this->data->getColumnArray($result);
	}
	
	public function checkUserSub($id_cat,$id_user)
	{
		if(!preg_match("|^[\d]*$|",$id_user) OR !preg_match("|^[\d]*$|",$id_user)) {
			return false;
		}
		else{
			$query = "SELECT id_user FROM ".$this->data->getTableName('subscription')." WHERE id_cat=".$id_cat." AND id_user=".$id_user;
			$result = $this->data->querySQL($query);
			return $this->data->getRecordCount($result);
		}		
	}
	
	public function editUser($fields)
	{	
		$id_user = $this->data->escape($_POST['id_user']);
	
		$table = $this->data->getTableName('users');
		$where = "id_user=".$id_user;
		$result = $this->data->update($fields, $table, $where); 
		
		if($result){
			if($this->data->delete($this->data->getTableName('subscription'),"id_user=".$id_user,'')){
				if($_POST['id_cat']){
					foreach($_POST['id_cat'] as $id){
						if(preg_match("|^[\d]+$|",$id))	{
							$insert = "INSERT INTO ".$this->data->getTableName('subscription')." (`id_sub`,`id_user`,`id_cat`) VALUES (0,".$id_user.",".$id.")";
							$this->data->querySQL($insert);
						}
					}
				}	
			}
			
			return true;
		}
		else return false;
	}
}

?>