<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_template extends Model
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
	
	public function getListArr($pnumber)
	{
		$table = "".$this->data->getTableName('template')." tmpl LEFT JOIN ".$this->data->getTableName('category')." cat ON cat.id_cat=tmpl.id_cat";
		$this->data->parameters = "*,cat.name as catname, tmpl.name as tmplname";
		$this->data->tablename = $table;
		$this->data->order = 'ORDER BY pos DESC';
		$this->data->pnumber = $pnumber;
		
		return $this->data->get_page();
	}
	
	public function getTotal($pnumber)
	{
		$this->data->tablename = $this->data->getTableName('template');
		$this->data->pnumber = $pnumber;
		
		$number = intval(($this->data->get_total() - 1) / $this->data->pnumber) + 1;
		
		return $number;
	}
	
	public function getPageNumber()
	{
		return $this->data->page;
	}

	public function changeStatusNewsLetter($fields)
	{
		$temp = array();
		foreach($_POST['activate'] as $id){
			if(preg_match("|^[\d]+$|", $id)){
				$temp[] = $id;
			}
		}
		
		$table = $this->data->getTableName('template');
		$where = "id_template IN (".implode(",", $temp).")";
		$result = $this->data->update($fields, $table, $where);
		
		unset($temp);	
		
		return $result;
	}
	
	public function removeTemplate()
	{
		if($_POST['activate']){
			$temp = array();
			foreach($_POST['activate'] as $id){
				if(preg_match("|^[\d]+$|",$id)){
					$temp[] = $id;
				}
			}
	
			$query = "SELECT * FROM ".$this->data->getTableName('attach')." WHERE id_template IN (".implode(",",$temp).")";
			$result = $this->data->querySQL($query);
	
			$arr = $this->data->getColumnArray($result);
		
			if(is_array($arr)){
				for($i = 0; $i < count($arr); $i++){
					if(file_exists($arr[$i]['path'])) @unlink($arr[$i]['path']);
				}
			}
			
			$result = $this->data->delete($this->data->getTableName('template'), "id_template IN (".implode(",",$temp).")",'');	
			unset($temp);
			
			return $result;			
		}
		else return false;
	}
	
	public function upPosition($id_template)
	{
		$id_template = $this->data->escape($id_template);
		$parameters = '*';
		$order = 'ORDER BY pos DESC';
		$from = $this->data->getTableName('template');
		
		$result = $this->data->select($parameters, $from, '', '', $order, '');
		
		while($row = $this->data->getRow($result)){
			if($row["id_template"] == $_GET['id_template'])	{
				$pos = $row["pos"];
				$row = $this->data->getRow($result);
				$id_next = $row["id_template"];
				$posnext = $row["pos"];
			}
		}

		if($id_next){
			$query1 = "UPDATE ".$this->data->getTableName('template')." SET pos=".$pos." WHERE id_template=".$id_next;
			$query2 = "UPDATE ".$this->data->getTableName('template')." SET pos=".$posnext." WHERE id_template=".$_GET['id_template'];

			if($this->data->querySQL($query1) AND $this->data->querySQL($query2))
				return true;
			else
				return false;			
		}	
	}
	
	public function downPosition($id_template)
	{
		$id_template = $this->data->escape($id_template);
	
		$query = "SELECT * FROM ".$this->data->getTableName('template')." ORDER BY pos DESC";
		$result = $this->data->querySQL($query);
		
		while($row = $this->data->getRow($result))
		{
			if($row["id_template"] == $_GET['id_template']){
				$pos = $row["pos"];
				$row = $this->data->getRow($result);
				$id_next = $row["id_template"];
				$posnext = $row["pos"];
			}
		}
		
		if($id_next){		
			$query1 = "UPDATE ".$this->data->getTableName('template')." SET pos=".$pos." WHERE id_template=".$id_next;
			$query2 = "UPDATE ".$this->data->getTableName('template')." SET pos=".$posnext." WHERE id_template=".$_GET['id_template'];

			if($this->data->querySQL($query1) AND $this->data->querySQL($query2))
				return true;
			else
				return false;
		}	
	}
}

?>