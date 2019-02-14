<?php
/**
 * Created by PhpStorm.
 * User: qianchao
 * Date: 2019-02-05
 * Time: 21:33
 */


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel
{
    public function __construct(){
    }

    public function ExportExcel($data){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', '姓名');
        $sheet->setCellValue('C1', '手机号码');
        $sheet->setCellValue('D1', '地址');
        $sheet->setCellValue('E1', '清单');
        $sheet->setCellValue('F1', '创建时间');

        $count = count($data);  //计算有多少条数据
        for ($i = 2; $i <= $count+1; $i++) {
            $sheet->setCellValue('A' . $i, $data[$i - 2]['id']);
            $sheet->setCellValue('B' . $i, $data[$i - 2]['name']);
            $sheet->setCellValue('C' . $i, $data[$i - 2]['mobile']);
            $sheet->setCellValue('D' . $i, $data[$i - 2]['address']);
            $sheet->setCellValue('E' . $i, $data[$i - 2]['goods']);
            $sheet->setCellValue('F' . $i, $data[$i - 2]['create_time']);
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'excel'.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}