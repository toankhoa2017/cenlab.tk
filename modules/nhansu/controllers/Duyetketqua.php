<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Duyetketqua extends ADMIN_Controller {

    function __construct() {
        //Language
        $this->lang->load('duyetketqua');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        parent::__construct();
        $this->load->model('mod_duyetketqua');
    }

    function index() {
        $ds_ptn = array();
        $ds_ns_ptn = array();
        $phongthinghiem = $this->mod_duyetketqua->phongthinghiem();
        foreach ($phongthinghiem as $row) {
            $ds_ptn[$row->donvi_id] = $row->donvi_ten;
            $dulieu = $this->mod_duyetketqua->nhansu_donvi($row->donvi_id);
            $ds_ns_ptn[$row->donvi_id][0] = 'Chọn Nhân Sự';
            foreach ($dulieu as $row) {
                $ds_ns_ptn[$row->donvi_id][$row->nhansu_id] = $row->nhansu_lastname . " " . $row->nhansu_firstname;
            }
        }
        $ds_ptn1[] = $ds_ptn;
        $ds_ptn1[] = $ds_ns_ptn;
        $ds_ns = array();
        $nhansu = $this->mod_duyetketqua->nhansu();
        foreach ($nhansu as $row) {
            $ds_ns[$row->nhansu_id] = $row->nhansu_lastname . " " . $row->nhansu_firstname;
        }
        $this->parser->assign('phongthinghiem', $ds_ptn1);
        $this->parser->assign('nhansu', $ds_ns);
        $dachon = array();
        $dachon1 = $this->mod_duyetketqua->dachon();
        $dachon_caocap = array();
        foreach ($dachon1 as $row) {
            if ($row->duyet_level == 1) {
                $dachon[$row->duyet_level][$row->donvi_id] = $row->nhansu_id;
            } else {
                $dachon_caocap[$row->duyet_level] = $row->nhansu_id;
            }
        }
        $this->parser->assign('dachon', $dachon);
        $this->parser->assign('dachoncaocap', $dachon_caocap);
        $this->parser->parse('nhansu/duyetketqua');
    }

    function luu_duyetketqua() {
        $dv_level1 = $this->input->post('donvi_id_level1');
        $ns_level1 = $this->input->post('nhansu_id_level1');
        for ($i = 0; $i < count($ns_level1); $i++) {
            $this->mod_duyetketqua->luu_duyetketqua($dv_level1[$i], $ns_level1[$i], 1);
        }
        $ns_level2 = $this->input->post('nhansu_id_level2');
        $dv_level2 = $this->mod_duyetketqua->get_donvi($ns_level2);
        $this->mod_duyetketqua->luu_duyetketqua($dv_level2, $ns_level2, 2);
        $ns_level3 = $this->input->post('nhansu_id_level3');
        $dv_level3 = $this->mod_duyetketqua->get_donvi($ns_level3);
        $this->mod_duyetketqua->luu_duyetketqua($dv_level3, $ns_level3, 3);
    }

    function hinhanh() {
        $dachon1 = $this->mod_duyetketqua->dachon();
        $dachon_caocap = array();
        foreach ($dachon1 as $row) {
            if ($row->nhansu_id != 0) {
                $dulieu = $this->mod_duyetketqua->nhansu_info($row->nhansu_id);
                $this->curl->create($this->_api['general'].'get_file');
                $this->curl->post(array(
                    'file_id' => $row->file_id
                ));
                $file = json_decode($this->curl->execute(), TRUE);
                $dataa = array($dulieu[0]->nhansu_lastname . ' ' . $dulieu[0]->nhansu_firstname,base_url($file['site_url'] . $file['file'][0]['file_path']));
                $dachon_caocap[$row->nhansu_id] = $dataa;
            }
        }
        $this->parser->assign('danhsach', $dachon_caocap);
        $this->parser->parse('nhansu/hinhanh');
    }

}
