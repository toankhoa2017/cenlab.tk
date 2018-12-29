<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Duyetmua extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_duyetmua');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_duyetmua');
        $this->load->model('mod_yeucau');
        $this->load->model('mod_baogia');
    }

    function index() {
        if ($this->session->userdata('status') == 1) {
            $status = 1;
            $this->session->unset_userdata('status');
        } else if ($this->session->userdata('status') == 2) {
            $status = 2;
            $this->session->unset_userdata('status');
        } else if ($this->session->userdata('status') == 3) {
            $status = 3;
            $this->session->unset_userdata('status');
        } else {
            $status = 4;
        }
        $this->parser->assign('status', $status);
        $this->parser->parse('duyetmua/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_duyetmua->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url('muasam/duyetmua/mua/' . $item->denghi_id) . '">' . $item->denghi_title . '</a>';
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
            $trangthai = false;
            $hientai = $this->mod_baogia->hientai($item->denghi_id);
            $vesion = $hientai->denghi_vesion;
            $denghi_id = $hientai->denghi_id;
            $denghi_approve = $item->denghi_approve;
            $denghi_success = $item->denghi_success;
            while ($trangthai == false) {
                $dulieu = $this->mod_baogia->check($denghi_id);
                if ($dulieu == true) {
                    $vesion = $dulieu->denghi_vesion;
                    $denghi_id = $dulieu->denghi_id;
                    $denghi_approve = $dulieu->denghi_approve;
                    $denghi_success = $dulieu->denghi_success;
                } else {
                    $trangthai = true;
                }
            }
            if ($vesion == '4') {
                switch ($item->denghi_approve) {
                    case '0':
                        $row[] = '<span class="label label-sm label-primary">'.$ngonngu['status_moi'].'</span>';
                        break;
                    case '1':
                        $row[] = '<span class="label label-sm label-success">'.$ngonngu['status_daduyet'].'</span>';
                        break;
                    case '2':
                        $row[] = '<span class="label label-sm label-danger">'.$ngonngu['status_khongduyet'].'</span>';
                        break;
                }
            } else {
                if ($denghi_approve == 0 && $denghi_success == 1) {
                    $row[] = '<span class="label label-sm label-success">'.$ngonngu['status_daduyet'].'</span>';
                } elseif ($denghi_approve == 1 && $denghi_success == 2) {
                    $row[] = '<span class="label label-sm label-success">'.$ngonngu['status_hoanthanh'].'</span>';
                } else {
                    $row[] = '<span class="label label-sm label-danger">'.$ngonngu['status_thaydoi'].'</span>';
                }
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_duyetmua->count_all(),
            "recordsFiltered" => $this->mod_duyetmua->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function mua($id) {
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

        $this->curl->create($this->_api['nhansu'] . 'all_nhansu');
        $this->curl->post();
        $nhansu = json_decode($this->curl->execute());
        $danhsach_nhansu = array();
        foreach ($nhansu->list as $row) {
            $danhsach_nhansu[$row->nhansu_id] = $row->nhansu_lastname . ' ' . $row->nhansu_firstname;
        }
        $this->parser->assign('nhansu', $danhsach_nhansu);

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
        $donvitinh = array();
        $dvt = $this->mod_yeucau->danhsach_donvitinh();
        foreach ($dvt as $row) {
            $donvitinh[$row->donvitinh_id] = $row->donvitinh_name;
        }
        $this->parser->assign('donvitinh', $donvitinh);
        $denghi = $this->mod_yeucau->denghi($id);
        
        $denghi_detail = $this->mod_yeucau->denghi_detail($id,1,5);
        $denghi_parent = $this->mod_yeucau->getIDParent($id);
        $ds_file = $this->mod_baogia->_getFilebaogia($this->mod_yeucau->getIDParent($denghi_parent));
        if($ds_file != '') {
            $this->parser->assign('danhsach_file', $ds_file);
        }
        $this->parser->assign('denghi', $denghi);
        $this->parser->assign('denghi_detail', $denghi_detail);
        $this->parser->parse('duyetmua/detail');
    }

}
