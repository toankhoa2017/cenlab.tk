<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chitieu extends ADMIN_Controller {

    private $privchitieu;
    private $privtheodoichitieu;
    private $privdinhgia;

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
        $this->load->model('mod_chitieu');
        $this->load->library('curl');
    }

    function index() {
        if (!$this->privchitieu['read'] && !$this->privtheodoichitieu['read']) redirect(site_url() . 'admin/denied?w=read');
        $nenmau_id = $this->input->get('nenmau');
        $this->parser->assign('user_info', $this->session->userdata());
        $info_nenmau = $this->mod_chitieu->info_nenmau($nenmau_id);
        $capbac_nenmau = $this->mod_chitieu->_capbac_nenmau($info_nenmau[0]->nenmau_ref, $info_nenmau[0]->nenmau_name);
        $this->parser->assign('capbac_nenmau', $capbac_nenmau);
        $this->parser->assign('info_nenmau', $info_nenmau);
        $dulieu = $this->mod_chitieu->danhsachnenmau();
        $danhsachnenmau = array();
        foreach ($dulieu as $row) {
            $danhsachnenmau[$row->nenmau_id] = $row->nenmau_name;
        }
        if ($nenmau_id == "") {
            $nenmau_id = $dulieu[0]->nenmau_id;
        }
        $this->parser->assign('nenmau_selected', $nenmau_id);
        $this->parser->assign('nenmau', $danhsachnenmau);
        $donvi = array();
        $donvi1 = $this->mod_chitieu->donvi();
        foreach ($donvi1 as $row) {
            $donvi[$row->donvitinh_id] = $row->donvitinh_name;
        }
        $this->parser->assign('donvi', $donvi);
        $noicongnhan = array();
        $noicongnhan1 = $this->mod_chitieu->congnhan();
        foreach ($noicongnhan1 as $row) {
            $noicongnhan[$row->congnhan_id] = $row->congnhan_name;
        }
        $this->parser->assign('noicongnhan', $noicongnhan);
        $phuongphap = array();
        $phuongphap1 = $this->mod_chitieu->phuongphap();
        foreach ($phuongphap1 as $row) {
            $phuongphap[$row->phuongphap_id] = $row->phuongphap_name;
        }
        $this->parser->assign('phuongphap', $phuongphap);
        $this->parser->parse('chitieu/list');
    }

    function goiy_kythuat() {
        $tukhoa = $this->input->post('key');
        $dulieu = $this->mod_chitieu->goiy_kythuat($tukhoa);
        foreach ($dulieu as $row) {
            $output[] = array(
                "label" => $row->kythuat_name,
                "info" => $row,
                "category" => ""
            );
        }
        echo json_encode($output);
    }

    function goiy_chitieu() {
        $tukhoa = $this->input->post('key');
        $dulieu = $this->mod_chitieu->goiy_chitieu($tukhoa);
        foreach ($dulieu as $row) {
            $output[] = array(
                "label" => $row->chitieu_name,
                "info" => $row,
                "category" => ""
            );
        }
        echo json_encode($output);
    }

    function them_chitieu() {
        $giatri = $this->input->post();
        if ($giatri['nenmau'] != '' && $giatri['nenmau'] != NULL) {
            if ($giatri['phuongphap_id'] == '0') {
                $phuongphap_id = 0;
            } else {
                $phuongphap_id = $giatri['phuongphap_id'];
            }
            if ($giatri['kythuat_id'] == '0') {
                $dulieu = $this->mod_chitieu->themkythuat($giatri['kythuat']);
                $kythuat_id = $dulieu;
            } else {
                $kythuat_id = $giatri['kythuat_id'];
            }
            if ($giatri['chitieu_id'] == "" || $giatri['chitieu_id'] == "0") {
                $chitieu_id = $this->mod_chitieu->themchitieu($giatri['chitieu'], $giatri['chitieu_eng'], $giatri['mota'], $giatri['nenmau'], $giatri['thoigian_luu']);
            } else {
                $chitieu_id = $giatri['chitieu_id'];
            }
            $dulieu = $this->mod_chitieu->them_dongia($giatri['nenmau'], $chitieu_id, $phuongphap_id, $kythuat_id, $giatri['phongthinghiem'], $giatri['donvi'], $giatri['thoigian']);
            if ($dulieu == true) {
                echo "1";
            } else {
                echo "0";
            }
        }
    }

    function danhsach_chitieu() {
        $ngonngu = $this->lang->getLang();
        $id_nenmau = $this->input->post('nenmau_id');
        $getall = ($this->privdinhgia['read'] || $this->privtheodoichitieu['read']) ? TRUE : FALSE;
        $list = $this->mod_chitieu->danhsach_chitieu($id_nenmau, $getall);
        //print_r($list);
        $data = array();
        foreach ($list as $row) {
            $hang = array();
            $hang[] = '<div class="action-buttons">
                        <a href="#" class="green bigger-140 show-details-btn" title="Show Details">
                            <i class="ace-icon fa fa-angle-double-down" onclick="hienthi_chat(' . $row->dongia_id . ')"></i>
                            <span class="sr-only"></span>
                        </a>
                    </div>';
            $hang[] = $row->chitieu_name . "<br />" . $row->chitieu_name_eng;
            $hang[] = $row->package_code;
            $this->curl->create($this->_api['nhansu'] . 'get_donvi');
            $this->curl->post(array(
                'donvi_id' => $row->donvi_nhansu,
            ));
            $donvi = json_decode($this->curl->execute());
            $hang[] = "<ul>
                            <li>".$ngonngu['mota_1'].": <strong><span class='label label-sm label-warning'>{$donvi->donvi_name}</span></strong></li>
                            <li>".$ngonngu['mota_2'].": <strong>{$row->phuongphap_name}</strong></li>
                            <li>".$ngonngu['mota_3'].": <strong>{$row->kythuat_name}</strong></li>
                            <li>".$ngonngu['mota_4'].": <strong>{$row->donvitinh_name}</strong></li>
                            <li>".$ngonngu['mota_5'].": <strong>{$row->thoigianthuchien}</strong> ".$ngonngu['donvi_thoigian']."</li>
                            <li>".$ngonngu['mota_6'].": <strong>{$row->thoigianluumau}</strong> ".$ngonngu['donvi_thoigian']."</li>
			</ul>";
            if ($this->privdinhgia['read'])
                $hang[] = number_format($row->price);
            $button = '';
            $button .= ($this->privchitieu['write']) ? ' <button type="button" class="btn btn-xs btn-purple" onclick="_add_chat(' . $row->dongia_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_themchat'].'"><i class="ace-icon fa fa-plus bigger-110"></i></button>' : '';
            $button .= ($this->privchitieu['update']) ? ' <button class="btn btn-xs btn-info" data-toggle="tooltip" title="'.$ngonngu['tooltip_suachitieu'].'" onclick="_sua_chitieu(' . $row->dongia_id . ',' . $row->nenmau_id . ',\'' . $row->package_code . '\',' . $row->thoigianluumau . ')"><i class="ace-icon fa fa-pencil bigger-110" ></i></button>' : '';
            $button .= ($this->privchitieu['delete']) ? ' <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoachitieu'].'" onclick="_xoa_chitieu(' . $row->dongia_id . ',' . $row->nenmau_id . ')"><i class="ace-icon fa fa-trash-o bigger-110" ></i></button>' : '';
            if ($row->price > 0) {
                $button .= ($this->privdinhgia['update']) ? ' <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="'.$ngonngu['tooltip_dinhgia'].'" onclick="_gia_chitieu(' . $row->price . ',' . $row->dongia_id . ',' . $row->nenmau_id . ',\'' . $row->chitieu_name . '\',\'' . $row->nenmau_name . '\')"><i class="ace-icon fa fa-dollar bigger-110"></i></button>' : '';
            } else {
                $button .= ($this->privdinhgia['write']) ? ' <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="'.$ngonngu['tooltip_dinhgia'].'" onclick="_gia_chitieu(' . $row->price . ',' . $row->dongia_id . ',' . $row->nenmau_id . ',\'' . $row->chitieu_name . '\',\'' . $row->nenmau_name . '\')"><i class="ace-icon fa fa-dollar bigger-110"></i></button>' : '';
            }
            $hang[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $hang;

            $hang1 = array();
            $hang1[] = $row->package_code;
            $hang1[] = $row->chitieu_name;
            $hang1[] = $row->dongia_id;
            $hang1[] = '3';
            $hang1[] = '4';
            $hang1[] = '5';
            $hang1[] = '6';
            $hang1[] = '7';
            $hang1[] = '8';
            $hang1[] = '9';
            $hang1[] = '10';
            $data[] = $hang1;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_chitieu->count_all($id_nenmau, $getall),
            "recordsFiltered" => $this->mod_chitieu->count_filtered($id_nenmau, $getall),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function xoa_chitieu() {
        $giatri = $this->input->post();
        $dieukien = array(
            'chitieu_id' => $giatri['chitieu_id'],
            'nenmau_id' => $giatri['nenmau_id']
        );
        $this->mod_chitieu->xoa_chitieu($dieukien);
        echo "1";
    }

    function gia_chitieu() {
        if (!$this->privdinhgia['update'])
            redirect(site_url() . 'admin/denied?w=update');
        $giatri = $this->input->post();
        $this->mod_chitieu->capnhat_gia($giatri['gia'], $giatri['chitieu_id'], $giatri['nenmau_id']);
        echo "1";
    }

    function get_chitieu() {
        $giatri = $this->input->post();
        $dulieu = $this->mod_chitieu->get_chitieu($giatri['chitieuid'], $giatri['nenmauid'], $giatri['packagecode']);
        echo json_encode($dulieu);
    }

    function sua_chitieu() {
        $giatri = $this->input->post();
        $kythuat = $this->mod_chitieu->kiemtra_kythuat($giatri['kythuat'], $giatri['kythuat_id']);
        $this->mod_chitieu->kiemtra_chitieu($giatri['id_chitieu_cu'], $giatri['chitieu'], $giatri['chitieu_eng'], $giatri['mota']);
        $data = array(
            'package_code' => $giatri['package_code'],
            'nenmau_id' => $giatri['nenmau'],
            'phuongphap_id' => $giatri['phuongphap_id'],
            'kythuat_id' => $kythuat,
            'donvi_id' => $giatri['phongthinghiem'],
            'donvitinh_id' => $giatri['donvi'],
            'thoigian' => $giatri['thoigian'],
        );
        $this->mod_chitieu->update_thoigianluu($giatri['id_chitieu_cu'], $giatri['nenmau'], $giatri['thoigian_luu']);
        $kiemtra = $this->mod_chitieu->update_chitieu($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
