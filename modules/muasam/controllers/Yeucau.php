<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Yeucau extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_yeucau');
        $ngonngu = $this->lang->getLang();
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_yeucau');
    }

    function index() {
        if ($this->session->userdata('status') == 1) {
            $status = 1;
            $this->session->unset_userdata('status');
        } else if ($this->session->userdata('status') == 2) {
            $status = 2;
            $this->session->unset_userdata('status');
        } else {
            $status = 3;
        }
        $this->parser->assign('status', $status);
        $this->parser->parse('yeucau/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_yeucau->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url('muasam/yeucau/denghi/' . $item->denghi_id) . '">' . $item->denghi_title . '</a>';
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
            if($item->denghi_success == 2) {
                $item->denghi_approve = 1;
                $xong = $ngonngu['status_hoanthanh'];
            }else{
                $xong = $ngonngu['status_daduyet'];
            }
            switch ($item->denghi_approve) {
                case '0':
                    $row[] = '<span class="label label-sm label-primary">'.$ngonngu['status_choduyet'].'</span>';
                    break;
                case '1':
                    $row[] = '<span class="label label-sm label-success">'.$xong.'</span>';
                    break;
                case '2':
                    $row[] = '<span class="label label-sm label-danger">'.$ngonngu['status_xemxetlai'].'</span>';
                    break;
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_yeucau->count_all(),
            "recordsFiltered" => $this->mod_yeucau->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function denghi($id) {
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
        $denghi = $this->mod_yeucau->denghi($id, 0);
        $denghi_detail = $this->mod_yeucau->denghi_detail($id, 0);
        $this->parser->assign('denghi', $denghi);
        $this->parser->assign('denghi_detail', $denghi_detail);
        $denghi_approve = $this->mod_yeucau->denghi_approve($id);
        if(count($denghi_approve)==0){
            $denghi_approve="";
        }
        $this->parser->assign('denghi_approve', $denghi_approve);
        
        $donvitinh = array();
        $dvt = $this->mod_yeucau->danhsach_donvitinh();
        foreach ($dvt as $row) {
            $donvitinh[$row->donvitinh_id] = $row->donvitinh_name;
        }
        $this->parser->assign('donvitinh', $donvitinh);
        
        $this->parser->parse('yeucau/detail');
    }

    function themdenghi() {
        $this->curl->create($this->_api['nhansu'] . 'all_nhansu');
        $this->curl->post();
        $nhansu = json_decode($this->curl->execute());
        $danhsach_nhansu = array();
        foreach ($nhansu->list as $row) {
            $danhsach_nhansu[$row->nhansu_id] = $row->nhansu_lastname . ' ' . $row->nhansu_firstname;
        }
        $this->parser->assign('nhansu', $danhsach_nhansu);

        $danhsach_loaisanpham = array();
        $loaisanpham = $this->mod_yeucau->danhsach_loaisanpham();
        foreach ($loaisanpham as $row) {
            $danhsach_loaisanpham[$row->loai_id] = $row->loai_symbol . ' (' . $row->loai_name . ')';
        }
        $this->parser->assign('loaisanpham', $danhsach_loaisanpham);

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
        
        $this->parser->parse('yeucau/create');
    }

    function get_sanpham() {
        $ngonngu = $this->lang->getLang();
        $loai_id = $this->input->post('loai_id');
        $sanpham = $this->mod_yeucau->danhsach_sanpham($loai_id);
        echo '<option value="0">'.$ngonngu['chosen_sanpham'].'</option>';
        foreach ($sanpham as $row) {
            echo '<option value="' . $row->sp_id . '">' . $row->sp_name . '</option>';
        }
    }

    function create() {
        $data = $this->input->post();
        $soluong = $data['dn_detail_soluong'];
        $sanpham = $data['sp_id'];
        $hang = $data['hang_id'];
        $ncc = $data['ncc_id'];
        $ghichu = $data['ghichu'];
        $dongia = $data['dn_detail_dongia'];
        $quytrinh = $data['quytrinh_id'];
        $donvitinh = $data['donvitinh_id'];
        $denghi_idparent = 0;
        $denghi_ref = '-';
        $denghi_vesion = 1;
        $denghi_id = "";
        if (isset($data['denghi_id']) && isset($data['denghi_vesion']) && isset($data['denghi_ref'])) {
            $denghi_idparent = $data['denghi_id'];
            $denghi_ref = $data['denghi_ref'] . $data['denghi_id'] . '-';
            $denghi_vesion = (int) $data['denghi_vesion'] + 1;
            $denghi_id = $data['denghi_id'];
        }
        if (isset($data['denghi_id_update']) || ($data['denghi_vesion'] == 3 && $quytrinh == 3) || ($data['denghi_vesion'] == 2 && $quytrinh == 2)|| ($data['denghi_vesion'] == 4 && $quytrinh == 4) || $data['denghi_vesion']==5) {
            if (($data['denghi_vesion'] == 3 && $quytrinh == 3)) {
                $kiemtra = $this->mod_yeucau->kiemtra_update($denghi_id, $data['denghi_describe']);
            } elseif (($data['denghi_vesion'] == 2 && $quytrinh == 2)) {
                $kiemtra = $this->mod_yeucau->kiemtra_update($denghi_id, $data['denghi_describe']);
            } elseif (($data['denghi_vesion'] == 4 && $quytrinh == 4)) {
                $this->mod_yeucau->update_denghi($denghi_id);
                $kiemtra = $this->mod_yeucau->kiemtra_update($denghi_id, $data['denghi_describe']);
            } elseif($data['denghi_vesion']==5) {
                $this->mod_yeucau->kiemtra_update($denghi_id, $data['denghi_describe']);
                $kiemtra = true;
            }else {
                $kiemtra = $this->mod_yeucau->kiemtra_update($data['denghi_id_update'], $data['denghi_describe']);
                $denghi_id = $data['denghi_id_update'];
            }
            $check = 2;
        } else {
            $denghi_id = $this->mod_yeucau->create($denghi_id, $denghi_idparent, $denghi_ref, $denghi_vesion, $data['denghi_title'], $data['denghi_describe'], $data['quytrinh_id'], $data['nhansu_nhan']);
            $kiemtra = true;
            $check = 1;
        }
        
        if ($kiemtra == true) {
            foreach ($soluong as $key => $value) {
                if ((int) $quytrinh > 2) {
                    $data = array(
                        'dn_detail_soluong' => $value,
                        'dn_detail_dongia' => $dongia[$key],
                        'sp_id' => $sanpham[$key],
                        'hang_id' => $hang[$key],
                        'ncc_id' => $ncc[$key],
                        'donvitinh_id' => $donvitinh[$key],
                        'denghi_id' => $denghi_id,
                        'dn_detail_describe' => $ghichu[$key]
                    );
                } else {
                    $data = array(
                        'dn_detail_soluong' => $value,
                        'dn_detail_dongia' => NULL,
                        'sp_id' => $sanpham[$key],
                        'hang_id' => $hang[$key],
                        'ncc_id' => NULL,
                        'donvitinh_id' => $donvitinh[$key],
                        'denghi_id' => $denghi_id,
                        'dn_detail_describe' => $ghichu[$key]
                    );
                }
                $this->mod_yeucau->create_detail($data);
            }
            $this->session->set_userdata('status', $check);
            echo 1;
        } else {
            echo 2;
        }
    }

    function history() {
        $ngonngu = $this->lang->getLang();
        $data = "";
        $denghi_id = $this->input->post('denghi_id');
        $vesion = $this->input->post('denghi_vesion');
        $get_denghi = $this->mod_yeucau->get_denghi($denghi_id, $vesion);
        if ($get_denghi[0]->denghi_vesion == '1') {
            $denghi_id = $get_denghi[0]->denghi_id;
        } else {
            $laymax = explode("-", $get_denghi[0]->denghi_ref);
            $get_denghi = $this->mod_yeucau->get_denghi($laymax[1], 1);
            $denghi_id = $get_denghi[0]->denghi_id;
            $vesion = 1;
        }
        $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
        $this->curl->post(array(
            'nhansu_id' => $get_denghi[0]->nhansu_goi
        ));
        $nhansu = json_decode($this->curl->execute());
        $name = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
        $data .= '<tr><td colspan="5" style="background-color:#438eb9; color:#fff;">'.$ngonngu['quytrinh_1'].' (' . $name . ')</td></tr>';
        foreach ($get_denghi as $row) {
            $data .= '<tr>';
            $data .= '<td>' . $row->sp_name . '</td>';
            $data .= '<td>' . $row->dn_detail_soluong . '</td>';
            $data .= '<td>' . $row->hang_name . '</td>';
            $data .= '<td></td>';
            $data .= '<td>0</td>';
            $data .= '</tr>';
        }
        $vesion++;
        $trangthai = false;
        while ($trangthai == false) {
            $dulieu = $this->mod_yeucau->get_denghi_parent($denghi_id, $vesion);
            if ($dulieu == true) {
                if ($vesion == 2) {
                    $text = $ngonngu['quytrinh_2'];
                } else if ($vesion == 3) {
                    $text = $ngonngu['quytrinh_3'];
                } else if ($vesion == 4) {
                    $text = $ngonngu['quytrinh_4'];
                } else if ($vesion == 5) {
                    $text = $ngonngu['quytrinh_5'];
                }
                $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
                $this->curl->post(array(
                    'nhansu_id' => $dulieu[0]->nhansu_goi
                ));
                $nhansu = json_decode($this->curl->execute());
                $name = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
                $data .= '<tr><td colspan="5" style="background-color:#438eb9; color:#fff;">' . $text . ' (' . $name . ')</td></tr>';
                $vesion++;
                $denghi_id = $dulieu[0]->denghi_id;
                foreach ($dulieu as $row) {
                    $data .= '<tr>';
                    $data .= '<td>' . $row->sp_name . '</td>';
                    $data .= '<td>' . $row->dn_detail_soluong . '</td>';
                    $data .= '<td>' . $row->hang_name . '</td>';
                    $data .= '<td>' . $row->ncc_name . '</td>';
                    $data .= '<td>' . number_format($row->dn_detail_dongia) . '</td>';
                    $data .= '</tr>';
                }
            } else {
                $trangthai = true;
            }
        }
        echo $data;
    }
    
    function khongduyetyeucau(){
        $denghi_id = $this->input->post('denghi_id');
        $approve_comment = $this->input->post('approve_comment');
        $this->mod_yeucau->khongduyetyeucau($denghi_id,$approve_comment);
        $this->session->set_userdata('status', '3');
    }

}
