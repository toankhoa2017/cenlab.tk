<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chitieu extends ADMIN_Controller {

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
        $this->privchitieu = $this->permarr[_PTN_CHITIEU];
        $this->privtheodoichitieu = $this->permarr[_PTN_QLCHITIEU];
        $this->privdinhgia = $this->permarr[_PTN_DINHGIA];
        $this->parser->assign('privchitieu', $this->privchitieu);
        $this->parser->assign('privtheodoichitieu', $this->privtheodoichitieu);
        $this->parser->assign('privdinhgia', $this->privdinhgia);
        $this->load->model('mod_nenmau');
        $this->load->model('mod_chitieu');
        $this->load->model('mod_dongia');
        $this->chitieu_nenmau = "mau_nenmau_chitieu";
        $this->dongia = "mau_dongia";
    }
    
    function index(){
        
    }
}    