<?php

/********************************************
* PHP Newsletter 4.1.3
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

class Model_import extends Model
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
	
	public function getCategoryList()
	{
		$query =  "SELECT *,cat.id_cat as id FROM ".$this->data->getTableName('category')." cat 
					LEFT JOIN ".$this->data->getTableName('subscription')." subs ON cat.id_cat=subs.id_cat
					GROUP by id
					ORDER BY name";
					
		$result = $this->data->querySQL($query);
		return $this->data->getColumnArray($result);
	}

	public function checkExistEmail($email)
	{		
		$email = $this->data->escape($email);
		$query =  "SELECT * FROM ".$this->data->getTableName('users')." WHERE email LIKE '".$email."'";
		$result = $this->data->querySQL($query);		
				
		if($this->data->getRecordCount($result) == 0)
			return true;
		else
			return false;	
	}
	
	public function importFromExcel()
	{
		global $PNSL;
		
		require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPExcel/PHPExcel/IOFactory.php";
		
		$count = 0;
		
		if($_FILES['file']['tmp_name']){
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$num = count($sheetData);					
			
			foreach($sheetData as $d){
				$email = trim($d['A']);
				$name = trim($d['B']);
				
				$email = $this->data->escape($email);
				$name = $this->data->escape($name);				
					
				if(!check_email($email)){
					$query = "SELECT * FROM ".$this->data->getTableName('users')." WHERE email LIKE '".$email."'";
					$result = $this->data->querySQL($query);
					
					if($this->data->getRecordCount($result) > 0){
						$row = $this->data->getRow($result);
						$delete = "DELETE FROM ".$this->data->getTableName('subscription')." WHERE id_user=".$row['id_user'];
						
						$this->data->delete($this->data->getTableName('subscription'), "id_user=".$row['id_user'],'');
						if($_POST['id_cat']){
							foreach($_POST['id_cat'] as $id_cat){
								if(preg_match("|^[\d]+$|",$id_cat))	{
									$fields = array();
									$fields['id_sub'] = 0;
									$fields['id_user'] = $row['id_user'];
									$fields['id_cat'] = $id_cat;									
									
									$insert_id = $this->data->insert($fields, $this->data->getTableName('subscription'));				
								}
							}
						}
					}
					else{
						$fields = array();
						$fields['id_user'] = 0;
						$fields['name'] = $name;
						$fields['email'] = $email;
						$fields['ip'] = '';
						$fields['token'] = getRandomCode();
						$fields['time'] = date("Y-m-d H:i:s");
						$fields['status'] = 'active';
						$fields['time_send'] = '0000-00-00 00:00:00';
						
						$insert_id = $this->data->insert($fields, $this->data->getTableName('users'));
						
						if($insert_id) $count++;
						
						if($insert_id and $_POST['id_cat']){						
							foreach($_POST['id_cat'] as $id_cat){
								if(preg_match("|^[\d]+$|",$id_cat)){
								
									$subfields = array();
									$subfields['id_sub'] = 0;
									$subfields['id_user'] = $insert_id;
									$subfields['id_cat'] = $id_cat;										
									
									$insert_id2 = $this->data->insert($subfields, $this->data->getTableName('subscription'));																	
								}
							}						
						}					
					}
				}
			}
		}	

		return $count;	
	}
	
	public function importFromText()
	{
		global $PNSL;
	
		require $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."ConvertCharset/ConvertCharset.class.php";
	
		if(!($fp = @fopen($_FILES['file']['tmp_name'],"rb"))){
			return false;
		}
		else{
			$buffer = fread($fp,filesize($_FILES['file']['tmp_name']));
			fclose($fp);
			
			$tok = strtok($buffer,"\n");
			$strtmp[] = $tok;

			while ($tok)
			{
				$tok = strtok("\n");
				$strtmp[] = $tok;
			}

			$count = 0;
			
			for($i=0; $i<count($strtmp); $i++){
				$email = "";
				$name = "";
				
				if(!mb_check_encoding($strtmp[$i], 'utf-8') and $_POST['charset']) {
					$sh = new ConvertCharset($_POST['charset'],"utf-8");
					$strtmp[$i] = $sh->Convert($strtmp[$i]);
				}

				preg_match('/([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/uis', $strtmp[$i], $out);

				$email = $out[0];
				$name = str_replace($email,'',$strtmp[$i]);
				$email = strtolower($email);
				$name = trim($name);
				
				if(strlen($name)>250) { $name = ''; }
				
				if($email){
					$query = "SELECT * FROM ".$this->data->getTableName('users')." WHERE email LIKE '".$email."'";
					$result = $this->data->querySQL($query);
					
					if($this->data->getRecordCount($result) > 0) {
						$row = $this->data->getRow($result);
						
						$delete = "DELETE FROM ".$this->data->getTableName('subscription')." WHERE id_user=".$row['id_user'];
						$this->data->delete($this->data->getTableName('subscription'), "id_user=".$row['id_user'], '');
						
						if($_POST['id_cat']){
							foreach($_POST['id_cat'] as $id_cat)	{
								if(preg_match("|^[\d]+$|",$id_cat)){
									$fields = array();
									$fields['id_sub'] = 0;
									$fields['id_user'] = $row['id_user'];
									$fields['id_cat'] = $id_cat;									
									
									$insert_id = $this->data->insert($fields, $this->data->getTableName('subscription'));				
								}
							}
						}
					}else{				
						$email = $this->data->escape($email);
						$name = $this->data->escape($name);
				
						$fields = array();
						$fields['id_user'] = 0;
						$fields['name'] = $name;
						$fields['email'] = $email;
						$fields['ip'] = '';
						$fields['token'] = getRandomCode();
						$fields['time'] = date("Y-m-d H:i:s");
						$fields['status'] = 'active';
						$fields['time_send'] = '0000-00-00 00:00:00';
						
						$insert_id = $this->data->insert($fields, $this->data->getTableName('users'));
					
						if($insert_id) $count++;
					
						if($_POST['id_cat'] and $insert_id){
							foreach($_POST['id_cat'] as $id_cat){
								if(preg_match("|^[\d]+$|",$id_cat)){
									$fields = array();
									$fields['id_sub'] = 0;
									$fields['id_user'] = $insert_id;
									$fields['id_cat'] = $id_cat;									
									
									$insert_id2 = $this->data->insert($fields, $this->data->getTableName('subscription'));
								}
							}
						}					
					}					
				}				
			}			
		}
		
		return $count;
	}	
}

?>