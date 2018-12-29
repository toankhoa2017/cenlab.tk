<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chatkhachhang extends ADMIN_Controller {

    private $privchitieu;
    private $privdinhgia;
    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('chitieu');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        //define('_DINHGIA_KHACHHANG', 193);
        $this->privdinhgia_kh = $this->permarr[_DINHGIA_KHACHHANG];
        $this->privchitieu = $this->permarr[_PTN_CHITIEU];
        $this->privdinhgia = $this->permarr[_PTN_DINHGIA];
        $this->parser->assign('privchitieu', $this->privchitieu);
        $this->parser->assign('privdinhgia_kh', $this->privdinhgia_kh);
        $this->parser->assign('privdinhgia', $this->privdinhgia);
        $this->load->model('mod_chat');
        $this->load->model('mod_chitieu');
    }
    
    function index(){
        $dongia = $this->input->get('dongia');
        $khachhang = $this->input->get('khachhang');
        $num_chat = $this->mod_chat->count_all($dongia);
        $list = $this->mod_chat->get_datatables($dongia);
        $dongiaChats = $this->mod_chat->getGiaByDongiaKhachhang($dongia, $khachhang);
        $chatGias = array();
        foreach ($dongiaChats as $dongiaChat){
            $chatGias[] = $dongiaChat["gia_price"];
        }
        $this->parser->assign('chatGias', $chatGias);
        $this->parser->assign('list_chat', $list);
        $this->parser->assign('khachhang', $khachhang);
        $this->parser->assign('dongia', $dongia);
        $this->parser->assign('num_chat', $num_chat);
        $this->parser->parse('chatkhachhang/list');
    }
}