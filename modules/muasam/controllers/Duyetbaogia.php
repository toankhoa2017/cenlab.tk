<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Duyetbaogia extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_duyetbaogia');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_duyetbaogia');
        $this->load->model('mod_yeucau');
        $this->load->model('mod_baogia');
    }

    function index() {
        if ($this->session->userdata('status') == 1) {
            $status = 1;
            $this->session->unset_userdata('status');
        } else if($this->session->userdata('status') == 2){
            $status = 2;
            $this->session->unset_userdata('status');
        }else if($this->session->userdata('status') == 3){
            $status = 3;
            $this->session->unset_userdata('status');
        }else{
            $status = 4;
        }
        $this->parser->assign('status', $status);
        $this->parser->parse('duyetbaogia/list');
    }
    
    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_duyetbaogia->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="'. base_url('muasam/duyetbaogia/duyetgiatien/'.$item->denghi_id).'">'.$item->denghi_title.'</a>';
            $row[] = $item->denghi_date;
            $this->curl->create($this->_api['nhansu'].'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->nhansu_goi,
            ));
            $nhansu = json_decode($this->curl->execute());
            $row[] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $this->curl->create($this->_api['nhansu'].'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->nhansu_nhan,
            ));
            $nhansu = json_decode($this->curl->execute());
            $row[] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $trangthai = false;
            $hientai = $this->mod_baogia->hientai($item->denghi_id);
            $vesion = $hientai->denghi_vesion;
            $denghi_id =  $hientai->denghi_id;
            while ($trangthai == false) {
                $dulieu = $this->mod_baogia->check($denghi_id);
                if ($dulieu == true) {
                    $vesion = $dulieu->denghi_vesion;
                    $denghi_id = $dulieu->denghi_id;
                } else {
                    $trangthai = true;
                }
            }
            $denghi = $this->mod_yeucau->denghi($item->denghi_id);
            if ($denghi->denghi_vesion == '4') {
                $status = $denghi->denghi_approve;
                if($denghi->denghi_approve==2){
                    $check_cu = $this->mod_duyetbaogia->denghi($item->denghi_id);
                    if($check_cu>0){
                        $moi = $ngonngu['status_choduyet'];
                        $cu = $ngonngu['status_khongduyet'];
                    }else{
                        $moi = $ngonngu['status_choduyet'];
                        $cu = $ngonngu['status_xemxetlai'];
                    }
                }else{
                    $moi = $ngonngu['status_choduyet'];
                    $cu = $ngonngu['status_xemxetlai'];
                } 
            } else {
                $status = $item->denghi_approve;
                $moi = $ngonngu['status_moi'];
                $cu = $ngonngu['status_khongduyet'];
            }
            if($item->denghi_success == 2) {
                $status=1;
                $xong = $ngonngu['status_hoanthanh'];
            }else{
                $xong = $ngonngu['status_daduyet'];
            }
            switch ($status) {
                case '0':
                    $row[] = '<span class="label label-sm label-primary">' . $moi . '</span>';
                    break;
                case '1':
                    $row[] = '<span class="label label-sm label-success">'. $xong .'</span>';
                    break;
                case '2':
                    $row[] = '<span class="label label-sm label-danger">' . $cu . '</span>';
                    break;
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_duyetbaogia->count_all(),
            "recordsFiltered" => $this->mod_duyetbaogia->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function duyetgiatien($id) {
        $trangthai = false;
        $hientai = $this->mod_baogia->hientai($id);
        $vesion = $hientai->denghi_vesion;
        $denghi_id = $id;
        while ($trangthai == false) {
            $dulieu = $this->mod_baogia->check($denghi_id);
            if ($dulieu == true) {
                $vesion = $dulieu->denghi_vesion;
                $denghi_id = $dulieu->denghi_id;
            } else {
                $trangthai = true;
            }
        }
        $this->parser->assign('quytrinh', $vesion);
        
        $this->curl->create($this->_api['nhansu'].'all_nhansu');
        $this->curl->post();
        $nhansu = json_decode($this->curl->execute());
        $danhsach_nhansu = array();
        foreach($nhansu->list as $row){
            $danhsach_nhansu[$row->nhansu_id]= $row->nhansu_lastname.' '.$row->nhansu_firstname;
        }
        $this->parser->assign('nhansu',$danhsach_nhansu);
        
        $danhsach_nhacungcap = array();
        $nhacungcap = $this->mod_yeucau->danhsach_nhacungcap();
        foreach($nhacungcap as $row){
            $danhsach_nhacungcap[$row->ncc_id]= $row->ncc_name;
        }
        $this->parser->assign('nhacungcap',$danhsach_nhacungcap);
        
        $denghi = $this->mod_yeucau->denghi($id);
        $denghi_detail = $this->mod_yeucau->denghi_detail($id);
        $ds_file = $this->mod_baogia->_getFilebaogia($this->mod_yeucau->getIDParent($id));
        if($ds_file != '') {
            $this->parser->assign('danhsach_file', $ds_file);
        }
        $this->parser->assign('denghi',$denghi);
        $this->parser->assign('denghi_detail',$denghi_detail);
        $denghi_approve = $this->mod_yeucau->denghi_approve($denghi->denghi_id);
        if(count($denghi_approve)==0){
            $denghi_approve="";
        }
        $this->parser->assign('denghi_approve', $denghi_approve);
        $this->parser->assign('denghi_id_goc', $id);
        $this->parser->parse('duyetbaogia/detail');
    }

}
