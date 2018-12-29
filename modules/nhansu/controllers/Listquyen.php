<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Listquyen extends ADMIN_Controller {
    private $privcheck;
    function __construct() {
        parent::__construct();
        $this->privcheck = $this->permarr[_TOCHUC_NHANSU];
        $this->parser->assign('privcheck', $this->privcheck);
	$this->load->model('mod_nhansu');
    }
    function index() {
        $this->parser->assign('nhansu_id', $this->input->get('id'));

        $list_quyen = $this->mod_nhansu->_getQuyenTL();
        $this->parser->assign('list_quyen', $list_quyen);

        $list_quyen = $this->mod_nhansu->_getQuyenTLbyUser($this->input->get('id'));
        $quyen = array();
        foreach ($list_quyen as $q) {
            $quyen[$q['quyen_id']][] = $q['tang_id'];
        }
        $this->parser->assign('quyen', $quyen);
        $this->curl->create($this->_api['tailieu'].'getTang');
        $this->curl->post();
        $tang = json_decode($this->curl->execute(), TRUE);
        $this->parser->assign('tang', $tang['tang']);

        $this->parser->parse('nhansu/listquyen');
    }
    function assignquyen(){
        $input = $this->input->post();
        $data = array(
            'nhansu_id' => $input['nhansu_id'],
            'quyen_id' => $input['quyen_id'],
            'tang_id' => $input['tang_id'],
        );
        $dulieu = $this->mod_nhansu->_assignQuyenTL($data, $input['trangthai']);
    }

}
