<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chitieukhachhang extends ADMIN_Controller {

    private $privchitieu;
    private $privtheodoichitieu;
    private $privdinhgia;
    private $chitieu_nenmau;
    private $dongia;
    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('chitieu');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privdinhgia_kh = $this->permarr[_DINHGIA_KHACHHANG];
        $this->privchitieu = $this->permarr[_PTN_CHITIEU];
        $this->privtheodoichitieu = $this->permarr[_PTN_QLCHITIEU];
        $this->privdinhgia = $this->permarr[_PTN_DINHGIA];
        $this->parser->assign('privchitieu', $this->privchitieu);
        $this->parser->assign('privdinhgia_kh', $this->privdinhgia_kh);
        $this->parser->assign('privtheodoichitieu', $this->privtheodoichitieu);
        $this->parser->assign('privdinhgia', $this->privdinhgia);
        $this->load->model('mod_nenmau');
        $this->load->model('mod_chitieu');
        $this->load->model('mod_dongia');
        $this->load->model('mod_khachhang');
        $this->chitieu_nenmau = "mau_nenmau_chitieu";
        $this->dongia = "mau_dongia";
    }
    
    function index(){
        $nenmau = $this->input->get('nenmau');
        $khachhang = $this->input->get('khachhang');
        $chitieu_gia = $this->mod_khachhang->getGiaByKhachhang($khachhang);
        $data_price = array();
        foreach($chitieu_gia as $key => $item){
            $data_price[$item["dongia_id"]] = $item["price"];
        }
        $getall = ($this->privdinhgia['read'] || $this->privtheodoichitieu['read']) ? TRUE : FALSE;
        $list = $this->mod_dongia->get_datatables($nenmau, $getall);
        $this->parser->assign('khachhang', $khachhang);
        $this->parser->assign('data_price', $data_price);
        $this->parser->assign('list_chitieu', $list);
        $this->parser->parse('chitieukhachhang/list');
    }
}    