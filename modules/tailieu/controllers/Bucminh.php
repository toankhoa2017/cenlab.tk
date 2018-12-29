<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bucminh extends MY_Controller {
    private $filename;
    function __construct() {
        parent::__construct();
        $folderPath = FCPATH . './_uploads/tamduc_dev/uploads/tailieu/';
        $fileNameOld = $folderPath . '5b7794dfab641_1534563551.docx';
        $fileNameNew = $folderPath . '5b7795ae2199e_1534563758.docx';
        $this->filenameold = $fileNameOld;
        $this->filenamenew = $fileNameNew;
    }
    function index() {
            echo "Cuc cu";
    }
    public static function diffArray($old, $new){
        $matrix = array();
        $maxlen = 0;
        var_dump($new);
        //var_dump($old);
        foreach($old as $oindex => $ovalue){
            $nkeys = array_keys($new, $ovalue);
            foreach($nkeys as $nindex){
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ? $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if($matrix[$oindex][$nindex] > $maxlen){
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }
        }
        //print_r($omax);
        /*echo "  ";
        print_r($maxlen);
        echo "  "; */
        if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
        return array_merge(
            self::diffArray(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxlen),
            self::diffArray(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
    }
 
    private function htmlDiff($old, $new){
        $ret = '';
        //print_r(explode(' ', $old));
        $diff = self::diffArray(explode(' ', $old), explode(' ', $new));
        //print_r($diff);
        foreach($diff as $key => $k){
            if(is_array($k) && (!empty(implode('',$k['d'])) || !empty(implode('',$k['i'])))){
                print_r($k);
                $ret .= (!empty($k['d'])?'<del>'.implode(' ',$k['d']).'</del> ':'').(!empty($k['i'])?'<ins>'.implode(' ',$k['i']).'</ins> ':'');
            }else{
                //print_r($k);
                //$ret .= $k . ' ';
            }
        }
        return $ret;
    }
    private function read_docx($file){

        $striped_content = "";
        $content = '';

        $zip = zip_open($file);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            
            $content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            $content = preg_replace('/<w:tbl>(.*?)<\/w:tbl>/s', " ", $content, 2);
            $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
            $content = str_replace('<w:tc>', " ", $content);
            $content = str_replace('</w:tc>', " ", $content);
            $content = str_replace('<w:p>', " ", $content);
            $content = str_replace('</w:p>', " ", $content);
            
            $striped_content = strip_tags($content);
            //$striped_content = explode('123', $content);
            //$content = '';
            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        //$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        //$content = str_replace('</w:r></w:p>', "\r\n", $content);
        //$striped_content = strip_tags($content);

        return $striped_content;
    }
    
    public function download()  {
        $cf = $this->read_docx($this->filenamenew);//replace(file_get_contents ($filemoi)); // Current Version
        $of = $this->read_docx($this->filenameold);//replace(file_get_contents ($filecu)); // Old Version
        //print_r($cf);
        echo '\n';
        //print_r($of);
        echo '\n';
        var_dump($this->htmlDiff($of, $cf));
    exit(1);
    
    //$phpWord = new \PhpOffice\PhpWord\PhpWord();
    $folderPath = '/var/local/laboratory/_uploads/tamduc_dev/uploads/tailieu/';
    $fileName = $folderPath . '5b4991b58c73f_1531548085.docx';
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($fileName);
    //$section = $phpWord->addSection();
    //$section = $phpWord->addSection(array('pageNumberingStart' => 1));
    $fontStyle = array(
        'bold' => true,
        'color' => '222222'
    );
    $sections = $phpWord->getSections();
    print_r($sections[0]->getHeaders());exit(1);
    foreach ($sections[0]->getElements() as $el)
    {
        print_r($el);
        //if ($el instanceof PhpOffice\PhpWord\Element\Table)
        {
            $rows = $el->getRows();
            foreach ($rows[count($rows) - 1]->getCells() as $cell)
            {
                //$cell->addText("tran phi hong");
                foreach ($cell->getElements() as $cEl)
                    if ($cEl instanceof PhpOffice\PhpWord\Element\Text)
                    {
                        echo $cEl->getText() .'<br>';
                    }
                    elseif ($cEl instanceof PhpOffice\PhpWord\Element\TextRun){
                        if (count($cEl->getElements())>0 and $cEl->getElements()[0] instanceof PhpOffice\PhpWord\Element\Text)
                        {
                            echo $cEl->getElements()[0]->getText();
                        }
                    }
            }
        }
    }
    /*$section = $sections[0]; // le document ne contient qu'une section
    $arrays = $section->getElements();
     foreach($section->getElements() as $element) {
        if($element instanceof PhpOffice\PhpWord\Element\Table) 
        {
            $table = $element;
            break;
       }
     }*/
    //$header = array('size' => 16, 'bold' => true);
    // 1. Basic table
    //$section->addText('Basic table', $header);
    //$table = $section->addTable($tableStyle);
    
    //for ($r = 1; $r <= 3; $r++) {
    //    $table->addRow();
    //    for ($c = 1; $c <= 3; $c++) {
    //        $table->addCell(3000, $cellRowStyle)->addText("Row {$r} test, Cell {$c}", $fontStyle);
    //    }
    //}*/
    // Saving the document as OOXML file...
    //$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    //$objWriter->save('./_uploads/tamduc_dev/uploads/tailieu/helloWorld.docx');
    // Saving the document as ODF file...
    }
}
