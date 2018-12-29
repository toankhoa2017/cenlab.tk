<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nhaphang extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_duyetmua');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_duyetmua');
        $this->load->model('mod_yeucau');
        $this->load->model('mod_baogia');
        $this->load->model('mod_nhaphang');
    }

    function index() {
        /*if ($this->session->userdata('status') == 1) {
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
        }*/
        //$this->parser->assign('status', $status);
        $this->parser->parse('nhaphang/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_nhaphang->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            if($item->denghi_success == 1) {
                $row[] = '<a href="' . site_url(). 'muasam/nhaphang/nhap?id=' . $item->denghi_id . '">' . $item->denghi_title . '</a>';
            }
            else {
                $row[] = '<a href="'.site_url().'muasam/nhaphang/history?id='. $item->denghi_id . '">' . $item->denghi_title . '</a>';
            }
            $row[] = $item->denghi_date;
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->nhansu_goi,
            ));
            $nhansu = json_decode($this->curl->execute());
            $row[] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            if($item->denghi_approve == 0) {
                $row[] = '<span class="label label-sm label-primary">Mới</span>';
            } elseif ($item->denghi_approve == 1 && $item->denghi_success == 1) {
                $row[] = '<span class="label label-sm label-success">Đang nhập</span>';
            } else {
                $row[] = '<span class="label label-sm label-danger">Hoàn thành</span>';
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_nhaphang->count_all(),
            "recordsFiltered" => $this->mod_nhaphang->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    function history() {
        $id = $this->input->get('id');
        $data = $this->mod_nhaphang->historynhaphang($id);
        $history = array();
        foreach($data as $list) {
            $row = array();
            $row['sp_name'] = $list['sp_name'];
            $row['ngaynhan'] = $list['ngaynhan'];
            $row['soluong'] = $list['soluong_nhan'];
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $list['nhansu_nhan']
            ));
            $nhansu = json_decode($this->curl->execute());
            $row['nguoinhan'] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $row['ghichu'] = $list['ghichu'];
            $row['hang'] = $list['hang_name'];
            $history[] = $row;
        }
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
        $denghi = $this->mod_yeucau->denghi($id);
        $this->parser->assign('denghi', $denghi);
        $soluongdanhap = $this->mod_nhaphang->hangdanhap($id);      
        $denghi_detail = $this->mod_yeucau->denghi_detail($id);
        $this->parser->assign('soluongdanhap', $soluongdanhap);
        $this->parser->assign('denghi_detail', $denghi_detail);
        $this->parser->assign('history', $history);
        $this->parser->parse('nhaphang/history');
    }
    function nhap() {
        $id = $this->input->get('id');
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
        $denghi = $this->mod_yeucau->denghi($id);
        $this->parser->assign('denghi', $denghi);
        $soluongdanhap = $this->mod_nhaphang->hangdanhap($id);      
        $denghi_detail = $this->mod_yeucau->denghi_detail($id);
        $this->parser->assign('soluongdanhap', $soluongdanhap);
        $this->parser->assign('denghi_detail', $denghi_detail);
        $this->parser->parse('nhaphang/detail');
    }
    function insertnhaphang() {
        $data = $this->input->post();
        $de_detail_id = $data['de_detail_id'];
        $soluong_nhap = $data['soluong_nhap'];
        $ngaynhap = $data['ngaynhap'];
        $ghichu = $data['ghichu'];
        $hang = $data['hang'];
        for($i=0; $i < count($de_detail_id); $i++) {
            $input = array(
                'ngaynhan' => $ngaynhap[$i],
                'soluong_nhan' => $soluong_nhap[$i],
                'denghi_id' => $data['denghi_id'],
                'dn_detail_id' => $de_detail_id[$i],
                'hang_name' => $hang[$i],
                'nhansu_nhan' => $this->session->userdata('ssAccountId'),
                'ghichu' => $ghichu[$i]
            );
            $this->mod_nhaphang->create($input);
        }
        $soluongdenghi = $this->mod_nhaphang->tonghangdenghi($data['denghi_id']);
        $soluongdanhap = $this->mod_nhaphang->tonghangdanhap($data['denghi_id']);
        if($soluongdanhap == $soluongdenghi) {
            $this->mod_nhaphang->updatedenghi($data['denghi_id'], array('denghi_success' => '2', 'denghi_approve' => '1'));
        }
        else {
            $this->mod_nhaphang->updatedenghi($data['denghi_id'], array('denghi_approve' => '1'));
        }
        echo 1;
    }
}
