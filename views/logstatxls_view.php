<?php

/********************************************
* PHP Newsletter 4.0.16
* Copyright (c) 2006-2015 Alexander Yanitsky
* Website: http://janicky.com
* E-mail: janickiy@mail.ru
* Skype: janickiy
********************************************/

// authorization
Auth::authorization();

if(!preg_match("|^[\d]*$|",$_GET['id_log'])) exit();

require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPExcel/PHPExcel.php";
	
$pExcel = new PHPExcel();
$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();
$aSheet->setTitle($PNSL["lang"]["str"]["mailing_report"]);

$timelog = $data->getTimelog();
$totalfaild = $data->getTotalfaild();
$totaltime = $data->getTotaltime();
$readmail = $data->getTotalread();

$arr = $data->getLogList();

if(is_array($arr)){
	$success = count($arr) - $totalfaild;
	$count = 100*$success/count($arr);
	$total = count($arr);
}
else{
	$success = 0;
	$count = 0;
	$total = 0;
}

$aSheet->setCellValue('A1',"".$PNSL["lang"]["str"]["total"].": ".count($arr)." \n".$PNSL["lang"]["str"]["sent"].": ".intval($count)." %\n".$PNSL["lang"]["str"]["spent_time"].": ".$totaltime."\n".$PNSL["lang"]["str"]["read"].": ".$readmail."");
$aSheet->getStyle('A1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('A2',$PNSL["lang"]["str"]["email"]);
$aSheet->setCellValue('B2',$PNSL["lang"]["str"]["time"]);
$aSheet->setCellValue('C2',$PNSL["lang"]["str"]["status"]);

$aSheet->setCellValue('A2',$PNSL["lang"]["str"]["mailer"]);
$aSheet->setCellValue('B2',$PNSL["lang"]["str"]["email"]);
$aSheet->setCellValue('C2',$PNSL["lang"]["str"]["time"]);
$aSheet->setCellValue('D2',$PNSL["lang"]["str"]["status"]);
$aSheet->setCellValue('E2',$PNSL["lang"]["str"]["read"]);
$aSheet->setCellValue('F2',$PNSL["lang"]["str"]["error"]);
$aSheet->mergeCells('A1:F1');
$aSheet->getStyle('A2')->getFill()->getStartColor()->setRGB('E3DA62');	
$aSheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
$aSheet->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('A2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('B2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('C2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('D2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('E2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('F2')->getFill()->getStartColor()->setRGB('EE7171');
	
$aSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
if(is_array($arr)){
	$i=2;

	foreach($arr as $row){
		$i++;
		
		$status = $row['success'] == 'yes' ? $PNSL["lang"]["str"]["send_status_yes"] : $PNSL["lang"]["str"]["send_status_no"]; 
		$readmail = $row['readmail'] == 'yes' ? $PNSL["lang"]["str"]["yes"] : $PNSL["lang"]["str"]["no"]; 
		
		$aSheet->setCellValue('A'.$i,$row['email']);
		$aSheet->setCellValue('B'.$i,$row['time']);
		$aSheet->setCellValue('C'.$i,$status);		
		
		$aSheet->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$aSheet->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$aSheet->setCellValue('A'.$i,$row['name']);
		$aSheet->setCellValue('B'.$i,$row['email']);
		$aSheet->setCellValue('C'.$i,$row['time']);
		$aSheet->setCellValue('D'.$i,$status);
		$aSheet->setCellValue('E'.$i,$readmail);
		$aSheet->setCellValue('F'.$i,$row['errormsg']);
	}
}

$aSheet->getRowDimension(1)->setRowHeight(70);;
$aSheet->getColumnDimension('A')->setWidth(30);
$aSheet->getColumnDimension('B')->setWidth(25);
$aSheet->getColumnDimension('C')->setWidth(15);
$aSheet->getColumnDimension('D')->setWidth(15);
$aSheet->getColumnDimension('E')->setWidth(10);
$aSheet->getColumnDimension('F')->setWidth(35);
				
require_once $PNSL["system"]["dir_root"].$PNSL["system"]["dir_libs"]."PHPExcel/PHPExcel/Writer/Excel5.php";

$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="logstat_'.$timelog.'.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

?>