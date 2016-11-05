<?php

/********************************************
* PHP Newsletter 4.2.11
* Copyright (c) 2006-2016 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

if($_POST["action"]){	

	$arr_cat = array();
	
	foreach($_POST['id_cat'] as $id_cat){
		if(preg_match("|^[\d]+$|",$id_cat)){
			$arr_cat[] = $id_cat;
		}
	}
	
	$arr = $data->getUserList($arr_cat);	
	
	if($_POST['export_type'] == 1){ 
		$ext = '.txt';
		$filename = 'emailexport.txt';			
			
		if(is_array($arr)){
			$contents = '';	
			foreach ($arr as $row){
				$contents .= "".$row['email']." ".$row['name']."\r\n";
			}
		}
	}	
	else if($_POST['export_type'] == 2){
		$ext = '.xls';
		$filename = 'emailexport.xls';
			
		require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPExcel/PHPExcel.php";
			
		$pExcel = new PHPExcel();
		$pExcel->setActiveSheetIndex(0);
		$aSheet = $pExcel->getActiveSheet();
		$aSheet->setTitle($PNSL["lang"]["str"]["emals_db"]);

		$aSheet->setCellValue('A1', $PNSL["lang"]["str"]["user_email"]);
		$aSheet->setCellValue('B1', $PNSL["lang"]["str"]["name"]);
			
		$i = 1;

		foreach ($arr as $row){
			$i++;
			$aSheet->setCellValue('A'.$i, $row['email']);
			$aSheet->setCellValue('B'.$i, $row['name']);
		}
			
		$aSheet->getColumnDimension('A')->setWidth(20);
		$aSheet->getColumnDimension('B')->setWidth(30);
			
		require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPExcel/PHPExcel/Writer/Excel5.php";
			
		$objWriter = new PHPExcel_Writer_Excel5($pExcel);
			
		ob_start();
		$objWriter->save('php://output');
		$contents = ob_get_contents();
		ob_end_clean();
	}
		
	if($_POST['zip'] == 2){	
		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename=emailexport.zip');
		
		$fout = fopen("php://output", "wb");
	
		if($fout !== false){
			fwrite($fout, "\x1F\x8B\x08\x08".pack("V", '')."\0\xFF", 10);

			$oname = str_replace("\0", "", $filename);
			fwrite($fout, $oname."\0", 1+strlen($oname));
			
			$fltr = stream_filter_append($fout, "zlib.deflate", STREAM_FILTER_WRITE, -1);
			$hctx = hash_init("crc32b");
  
			if(!ini_get("safe_mode")) set_time_limit(0);
		 
			hash_update($hctx, $contents);
			$fsize = strlen($contents);
		
			fwrite($fout, $contents, $fsize);

			stream_filter_remove($fltr);

			$crc = hash_final($hctx, TRUE);

			fwrite($fout, $crc[3].$crc[2].$crc[1].$crc[0], 4);
			fwrite($fout, pack("V", $fsize), 4);
			fclose($fout);
		}		
		exit();		
	}
	else{
		header('Content-Type: '.get_mime_type($ext).'');
		header('Content-Disposition: attachment; filename='.$filename.'');
		header('Cache-Control: max-age=0');
		echo $contents;
		exit;		
	}
}

// require temlate class
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."html_template/SeparateTemplate.php";
$tpl = SeparateTemplate::instance()->loadSourceFromFile($PNSL["system"]["template"]."export.tpl");

$tpl->assign('TITLE_PAGE', $PNSL["lang"]["title_page"]["export"]);
$tpl->assign('TITLE', $PNSL["lang"]["title"]["export"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["export"]);

$tpl->assign('SCRIPT_VERSION', $PNSL["system"]["version"]);
$tpl->assign('STR_WARNING', $PNSL["lang"]["str"]["warning"]);
$tpl->assign('INFO_ALERT', $PNSL["lang"]["info"]["edit_user"]);
$tpl->assign('STR_ERROR', $PNSL["lang"]["str"]["error"]);
$tpl->assign('STR_LOGOUT', $PNSL["lang"]["str"]["logout"]);

//menu
include_once "menu.php";

$tpl->assign('MAILING_STATUS', getCurrentMailingStatus());
$tpl->assign('STR_LAUNCHEDMAILING', $PNSL["lang"]["str"]["launchedmailing"]);
$tpl->assign('STR_STOPMAILING', $PNSL["lang"]["str"]["stopmailing"]);

$tpl->assign('STR_BACK', $PNSL["lang"]["str"]["return_back"]);

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_EXPORT', $PNSL["lang"]["str"]["export"]);
$tpl->assign('STR_COMPRESSION', $PNSL["lang"]["str"]["compression"]);

$tpl->assign('STR_EXPORT_TEXT', $PNSL["lang"]["str"]["export_type_text"]);
$tpl->assign('STR_EXPORT_CSV', $PNSL["lang"]["str"]["export_type_cvs"]);
$tpl->assign('STR_EXPORT_EXCEL', $PNSL["lang"]["str"]["excel"]);
$tpl->assign('STR_COMPRESSION_OPTION_1', $PNSL["lang"]["str"]["compression_option_1"]);
$tpl->assign('STR_COMPRESSION_OPTION_2', $PNSL["lang"]["str"]["compression_option_2"]);
$tpl->assign('TABLE_CATEGORY', $PNSL["lang"]["table"]["category"]);

foreach ($data->getCategoryList() as $row){
	$rowBlock = $tpl->fetch('row');
	$rowBlock->assign('ID_CAT', $row['id']);
	$rowBlock->assign('NAME', $row['name']);
	$tpl->assign('row', $rowBlock);
}

$tpl->assign('BUTTON_APPLY', $PNSL["lang"]["button"]["apply"]);

//footer
include_once "footer.php";

// display content
$tpl->display();