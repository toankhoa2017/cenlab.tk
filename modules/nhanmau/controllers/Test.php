<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends ADMIN_Controller {
    private $upload_path;
    private $upload_url;
    function __construct() {
        parent::__construct();
        $this->upload_path = _UPLOADS_PATH;
        $this->upload_url = base_url()._UPLOADS_URL;
    }
    function index(){
        echo 'Test module';
    }
    function test_word_pdf(){
        $hopdong_doc = $this->upload_path.'hopdong/HD_TEST.docx';
        if(file_exists($this->upload_path.'hopdong_template.docx')){
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($this->upload_path.'hopdong_template.docx');
            $templateProcessor->setValue('hopdong_code', 'HD_TEST');
            $templateProcessor->setValue('date_print', date("d/m/Y H:i:s"));
            $templateProcessor->setValue('date_result', date("d/m/Y H:i:s"));
            $templateProcessor->setValue('hopdong_language', 'Language test');
            $templateProcessor->saveAs($hopdong_doc);
        }
        if(file_exists($hopdong_doc)){
            @shell_exec("sh /selinux/doc2pdf.sh ".$this->upload_path.'hopdong/'.' '.$hopdong_doc." 2>&1");
        }else{
            echo 'Không tạo được file word';
        }
        echo 'Link word: '.$hopdong_doc;
    }
    function test_qrcode(){
        $qr_url = site_url().'nhanmau/test/create_qrcode';
        $this->parser->assign('qr_url', $qr_url);
        $this->parser->parse('test/qrcode');
    }
    function create_qrcode(){
        $this->load->library('MY_QRcode');
     
        // we need to be sure ours script does not output anything!!! 
        // otherwise it will break up PNG binary! 

        ob_start("callback"); 

        // here DB request or some processing 
        $codeText = 'http://tamduc.jsc/nhanmau/test/test_qrcode'; 

        // end of processing here 
        $debugLog = ob_get_contents(); 
        ob_end_clean(); 

        // outputs image directly into browser, as PNG stream 
        QRcode::png($codeText);
    }
    
    function regex(){
        echo 3*pow(10, 6);
        // Chuỗi có phải trống hoặc có những chữ cái in thường 
        $pattern = '/^(.+)x(.+)\^(.+)$/';
        $subject = '2x©^(ax10^6)';
        if (preg_match($pattern, $subject, $match)){
            var_dump($match);
            echo 'Chuỗi regex so khớp';
        }
    }
}