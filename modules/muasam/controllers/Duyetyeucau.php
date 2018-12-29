<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Duyetyeucau extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_duyetyeucau');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_duyetyeucau');
        $this->load->model('mod_yeucau');
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
        $this->parser->parse('duyetyeucau/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_duyetyeucau->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url('muasam/duyetyeucau/duyetdenghi/' . $item->denghi_id) . '">' . $item->denghi_title . '</a>';
            $row[] = $item->denghi_date;
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->nhansu_goi,
            ));
            $nhansu = json_decode($this->curl->execute());
            $row[] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->nhansu_nhan,
            ));
            $nhansu = json_decode($this->curl->execute());
            $row[] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $denghi_id = $item->denghi_id;
            $vesion = 1;
            $trangthai = false;
            while ($trangthai == false) {
                $dulieu = $this->mod_yeucau->check($denghi_id);
                if ($dulieu == true) {
                    $vesion = $dulieu->denghi_vesion;
                    $denghi_id = $dulieu->denghi_id;
                } else {
                    $trangthai = true;
                }
            }
            if($item->denghi_success == 2) {
                $status=1;
                $xong = $ngonngu['status_hoanthanh'];
            }else{
                $xong = $ngonngu['status_daduyet'];
            }
            switch ($item->denghi_approve) {
                case '0':
                    $row[] = '<span class="label label-sm label-primary">'.$ngonngu['status_moi'].'</span>';
                    break;
                case '1':
                    $row[] = '<span class="label label-sm label-success">'.$xong.'</span>';
                    break;
                case '2':
                    $row[] = '<span class="label label-sm label-danger">'.$ngonngu['status_khongduyet'].'</span>';
                    break;
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_duyetyeucau->count_all(),
            "recordsFiltered" => $this->mod_duyetyeucau->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function duyetdenghi($id) {
        $denghi_id = $id;
        $vesion = 1;
        $trangthai = false;
        while ($trangthai == false) {
            $dulieu = $this->mod_yeucau->check($denghi_id);
            if ($dulieu == true) {
                $vesion = $dulieu->denghi_vesion;
                $denghi_id = $dulieu->denghi_id;
            } else {
                $trangthai = true;
            }
        }
        $this->parser->assign('quytrinh', $vesion);

        $this->curl->create($this->_api['nhansu'] . 'all_nhansu');
        $this->curl->post();
        $nhansu = json_decode($this->curl->execute());
        $danhsach_nhansu = array();
        foreach ($nhansu->list as $row) {
            $danhsach_nhansu[$row->nhansu_id] = $row->nhansu_lastname . ' ' . $row->nhansu_firstname;
        }
        $this->parser->assign('nhansu', $danhsach_nhansu);

        $danhsach_sanpham = array();
        $loaisanpham = $this->mod_yeucau->danhsach_all_sanpham();
        foreach ($loaisanpham as $row) {
            $danhsach_sanpham[$row->sp_id] = $row->sp_name;
        }
        $this->parser->assign('sanpham', $danhsach_sanpham);

        $danhsach_nhacungcap = array();
        $nhacungcap = $this->mod_yeucau->danhsach_nhacungcap();
        foreach ($nhacungcap as $row) {
            $danhsach_nhacungcap[$row->ncc_id] = $row->ncc_name;
        }
        $this->parser->assign('nhacungcap', $danhsach_nhacungcap);

        $danhsach_hang = array();
        $hang = $this->mod_yeucau->danhsach_hang();
        foreach ($hang as $row) {
            $danhsach_hang[$row->hang_id] = $row->hang_name;
        }
        $this->parser->assign('hang', $danhsach_hang);
        $denghi = $this->mod_yeucau->denghi($id);
        $denghi_detail = $this->mod_yeucau->denghi_detail($denghi->denghi_id);
        $this->parser->assign('denghi', $denghi);
        $this->parser->assign('denghi_detail', $denghi_detail);
        
        $donvitinh = array();
        $dvt = $this->mod_yeucau->danhsach_donvitinh();
        foreach ($dvt as $row) {
            $donvitinh[$row->donvitinh_id] = $row->donvitinh_name;
        }
        $this->parser->assign('donvitinh', $donvitinh);
        
        $this->parser->parse('duyetyeucau/detail');
    }
    function delspyeucau(){
        $this->mod_duyetyeucau->_delete_detaildn($this->input->post('id'));
        echo '1';
    }
}