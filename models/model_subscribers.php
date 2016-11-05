<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_subscribers extends Model
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
	
	public function getSubersArr($strtmp)
	{
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		$settings = $this->data->getRow($result);	
	
		$this->data->tablename = $this->data->getTableName('users');
		
		if($_GET['search']){
			$temp = strtok($_GET['search']," ");
			$temp = "%".$temp."%";
			$logstr = "or";

			while ($temp)
			{
				if($is_query)
					$tmp1 .= " $logstr (name LIKE '".$temp."' OR email LIKE '".$temp."') "; 
				else 
					$tmp1 .= "(name LIKE '".$temp."' OR email LIKE '".$temp."') ";

				$is_query = true;
				$temp = strtok(" ");
			}
			
			$this->data->parameters = "*,DATE_FORMAT(time,'%d.%m.%y') as putdate_format";
			$this->data->where = "WHERE ".$tmp1."";
			$this->data->group = "GROUP BY id_user";
			$this->data->order = "ORDER BY name";
		}
		else{
			$this->data->parameters = "*,DATE_FORMAT(time,'%d.%m.%y') as putdate_format";
			$this->data->order = "ORDER BY ".$strtmp."";
		}	
		
		$this->data->pnumber = $settings['number_pos_users'];		
		
		return $this->data->get_page();
	}
	
	public function countSubscribers()
	{
		$this->data->tablename = $this->data->getTableName('users');
		
		if($_GET['search']){
			$temp = strtok($_GET['search']," ");
			$temp = "%".$temp."%";
			$logstr = "or";

			while ($temp)
			{
				if($is_query)
					$tmp1 .= " $logstr (name LIKE '".$temp."' OR email LIKE '".$temp."') "; 
				else 
					$tmp1 .= "(name LIKE '".$temp."' OR email LIKE '".$temp."') ";

				$is_query = true;
				$temp = strtok(" ");
			}
			
			$query = "SELECT *,DATE_FORMAT(time,'%d.%m.%y') as putdate_format FROM ".$this->data->getTableName('users')." WHERE ".$tmp1." GROUP BY id_user";
		}
		else{
			$query = "SELECT *,DATE_FORMAT(time,'%d.%m.%y') as putdate_format FROM ".$this->data->getTableName('users')."";
		}
		
		$result = $this->data->querySQL($query);
		
		return $this->data->getRecordCount($result);	
	}
	
	public function getTotal()
	{
		$query = "SELECT * FROM ".$this->data->getTableName('settings')."";
		$result = $this->data->querySQL($query);
		$settings = $this->data->getRow($result);	
	
		$table = $this->data->getTableName('users');
		$this->data->tablename = $table;		
		$this->data->pnumber = $settings['number_pos_users'];	
		$number = intval(($this->data->get_total() - 1) / $this->data->pnumber) + 1;
		
		return $number;
	}
	
	public function getPageNumber()
	{
		return $this->data->page;
	}
	
	public function updateUsers()
	{
		$temp = array();
	
		foreach($_POST['activate'] as $id_user){
			if(preg_match("|^[\d]+$|",$id_user)){
				$temp[] = $id_user;
			}
		}
	
		$fields = array();
		$fields['status'] = 'active';
		$where = "id_user IN (".implode(",",$temp).")";
		$table = $this->data->getTableName('users');
		$result = $this->data->update($fields, $table, $where);
		unset($temp);
		
		return $result;
	}
	
	public function deleteUsers()
	{
		$temp = array();
	
		foreach($_POST['activate'] as $id_user){
			if(preg_match("|^[\d]+$|",$id_user)){
				$temp[] = $id_user;
			}
		}	
	
		$result = $this->data->delete($this->data->getTableName('users'),"id_user IN (".implode(",",$temp).")");
		if($result){
			$result = $this->data->delete($this->data->getTableName('subscription'),"id_user IN (".implode(",",$temp).")");
			unset($temp);		
		
			return $result;
		}
		else
			return false;
			
	}
	
	public function removeAllUsers()
	{
		$delete1 = $this->data->delete($this->data->getTableName('users'));
		$delete2 = $this->data->delete($this->data->getTableName('subscription'));
	
		if($delete1 and $delete2)
			return true;
		else
			return false;
	}
	
	public function removeUser()
	{
		$id_user = $this->data->escape($_GET["remove"]);
		
		$delete1 = $this->data->delete($this->data->getTableName('users'), "id_user=".$id_user);
		$delete2 = $this->data->delete($this->data->getTableName('subscription'), "id_user=".$id_user);
		
		if($delete1 and $delete2)
			return true;
		else
			return false;
	}
}

?>