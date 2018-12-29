<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') OR exit('No direct script access allowed');

class Bieumau extends MY_Controller{
    function __construct() {
        parent::__construct();
        //$this->load->library('PhpSpreadsheet');
    }
    
    function index(){
        $spreadsheet_test = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $arrayData = [
            [NULL, 2010, 2011, 2012],
            ['Q1',   12,   15,   21],
            ['Q2',   56,   73,   86],
            ['Q3',   52,   61,   69],
            ['Q4',   30,   32,    0],
        ];
        $spreadsheet_test->getActiveSheet()
            ->fromArray(
                $arrayData,  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
                             //    we want to set these values (default is A1)
            );
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet_test);
        $writer->save( _UPLOADS_PATH . 'tailieu/export.xlsx');
        
        //$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        /*$spreadsheet = $reader->load("./file/bieumau.xlsx");
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('./file/download.png');
        $drawing->setCoordinates('B10');
        $drawing->setHeight(36);
        $worksheet = $spreadsheet->getActiveSheet();
        $drawing->setWorksheet($worksheet);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('./file/hello_world.xlsx');
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        echo '<table>' . "\n";
        for ($row = 1; $row <= $highestRow; ++$row) {
            echo '<tr>' . PHP_EOL;
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                if (strpos($value, '$val.') !== false) {
                    echo '<td>' . $value . '</td>' . PHP_EOL;
                }
            }
            echo '</tr>' . PHP_EOL;
        }
        echo '</table>' . PHP_EOL;*/
        //$this->parser->parse('bieumau/form');
    }
}