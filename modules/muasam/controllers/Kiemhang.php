<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kiemhang extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_kiemhang');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_kiemhang');
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
        $this->parser->parse('kiemhang/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_kiemhang->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url('muasam/kiemhang/hang/' . $item->denghi_id) . '">' . $item->denghi_title . '</a>';
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
            while ($trangthai == false) {
                $dulieu = $this->mod_baogia->check($denghi_id);
                if ($dulieu == true) {
                    $vesion = $dulieu->denghi_vesion;
                    $denghi_id = $dulieu->denghi_id;
                } else {
                    $trangthai = true;
                }
            }
            if ($item->denghi_approve == 0 && $item->denghi_success == 1) {
                $row[] = '<span class="label label-sm label-primary">'.$ngonngu['status_moi'].'</span>';
            } elseif ($item->denghi_approve == 1 && $item->denghi_success == 2) {
                $row[] = '<span class="label label-sm label-success">'.$ngonngu['status_hoanthanh'].'</span>';
            } else {
                $row[] = '<span class="label label-sm label-danger">'.$ngonngu['status_thaydoi'].'</span>';
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_kiemhang->count_all(),
            "recordsFiltered" => $this->mod_kiemhang->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function hang($id) {
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

        $danhsach_sanpham = array();
        $loaisanpham = $this->mod_yeucau->danhsach_all_sanpham();
        foreach ($loaisanpham as $row) {
            $danhsach_sanpham[$row->sp_id] = $row->sp_name;
        }
        $this->parser->assign('sanpham', $danhsach_sanpham);

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
        $denghi_detail = $this->mod_yeucau->denghi_detail($id);
        $danhsach_file = $this->mod_yeucau->danhsach_file($denghi->denghi_id);
        $ds_file = array();
        foreach ($danhsach_file as $row) {
            $this->curl->create($this->_api['general'] . 'get_file');
            $this->curl->post(array(
                'file_id' => $row->file_id
            ));
            $file = json_decode($this->curl->execute(), TRUE);
            $link = $file['site_url'] . $file['file'][0]['file_path'];
            $ds_file[$row->file_id] = array(
                'name' => $file['file'][0]['file_name'],
                'url' => $link
            );
        }
        $this->parser->assign('danhsach_file', $ds_file);
        $this->parser->assign('denghi', $denghi);
        $this->parser->assign('denghi_detail', $denghi_detail);
        $this->parser->parse('kiemhang/detail');
    }

    function dongy() {
        $denghi_id = $this->input->post('denghi_id');
        $dieukien = true;
        while ($dieukien == true) {
            $data = $this->mod_kiemhang->denghi_success($denghi_id);
            if ($data == true) {
                $denghi_id = $data->denghi_idparent;
            } else {
                $dieukien = false;
            }
        }
        echo 1;
        $this->session->set_userdata('status', '1');
    }

    function thaydoi() {
        $data = $this->input->post();
        $soluong = $data['dn_detail_soluong'];
        $sanpham = $data['sp_id'];
        $hang = $data['hang_id'];
        $ncc = $data['ncc_id'];
        $dongia = $data['dn_detail_dongia'];
        $donvitinh = $data['donvitinh_id'];
        $denghi_id = $data['denghi_id'];
        $denghi_describe = $data['denghi_describe'];
        $this->mod_kiemhang->update_denghi($denghi_id,$denghi_describe);
        foreach ($soluong as $key => $value) {
            $data = array(
                'dn_detail_soluong' => $value,
                'dn_detail_dongia' => $dongia[$key],
                'sp_id' => $sanpham[$key],
                'hang_id' => $hang[$key],
                'ncc_id' => $ncc[$key],
                'donvitinh_id' => $donvitinh[$key],
                'denghi_id' => $denghi_id,
            );
            $this->mod_kiemhang->create_detail($data);
        }
        echo '1';
        $this->session->set_userdata('status', '2');
    }

}
