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
    private function checkChitieuNenmauExist($nenmau_id, $chitieu_id){
        $con = array(
            'nenmau_id' => $nenmau_id,
            'chitieu_id' => $chitieu_id,
        );
        $result = $this->db->select("*")->from($this->chitieu_nenmau)->where($con)->get();
        return $result->num_rows();
    }
    private function checkDongiaExist($data){
        $kiemtra1 = array(
            'nenmau_id' => $data["nenmau"],
            'chitieu_id' => $data["chitieu_id"],
            'phuongphap_id' => $data["phuongphap_id"],
            'kythuat_id' => $data["kythuat_id"],
        );
        $result = $this->db->select("*")->from($this->dongia)->where($kiemtra1)->get();
        return $result;
    }
            
    function index() {
        if (!$this->privchitieu['read'] && !$this->privtheodoichitieu['read']) redirect(site_url() . 'admin/denied?w=read');
        $nenmau_id = $this->input->get('nenmau');
        $this->parser->assign('user_info', $this->session->userdata());
        $info_nenmau = $this->mod_nenmau->info_nenmau($nenmau_id);
        $capbac_nenmau = $this->mod_nenmau->_capbac_nenmau($info_nenmau[0]->nenmau_ref, $info_nenmau[0]->nenmau_name);
        $this->parser->assign('capbac_nenmau', $capbac_nenmau);
        $this->parser->assign('info_nenmau', $info_nenmau);
        $donvi = array();
        $donvis = $this->mod_chitieu->donvi();
        foreach ($donvis as $row) {
            $donvi[$row->donvitinh_id] = $row->donvitinh_name;
        }
        $this->parser->assign('donvi', $donvi);
        $phuongphap = array();
        $phuongphapBN = array();
        $phuongphaps = $this->mod_chitieu->phuongphap();
        foreach ($phuongphaps as $row) {
            $phuongphap[$row->phuongphap_id] = array();
            if($row->phuongphap_code != ""){
                $phuongphap[$row->phuongphap_id]["code"] = $row->phuongphap_code;
                $phuongphap[$row->phuongphap_id]["loai"] = $row->phuongphap_loai;
            }
            if($row->phuongphap_shortname != "" && $row->phuongphap_loai == 2)
                $phuongphapBN[$row->phuongphap_id] = $row->phuongphap_shortname;
        }
        $phuongphap = array_filter($phuongphap, function($v) { return !empty($v['code']); });
        $this->parser->assign('phuongphap', $phuongphap);
        $this->parser->assign('phuongphapBN', $phuongphapBN);
        $kythuat = array();
        $kythuats = $this->mod_chitieu->kythuat();
        foreach ($kythuats as $row) {
            $kythuat[$row->kythuat_id] = $row->kythuat_name;
        }
        $this->parser->assign('kythuat', $kythuat);
        $this->parser->parse('chitieu/list');
    }
    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $nenmau = $this->input->get('nenmau');
        $url_action = "nenmau/khachhang";
        $getall = ($this->privdinhgia['read'] || $this->privtheodoichitieu['read']) ? TRUE : FALSE;
        $list = $this->mod_dongia->get_datatables($nenmau, $getall);
        $data = array();
        foreach ($list as $row) {
            $classDeactive = ($row->kythuat_status == 2) ? "class='chitieu-deactive'" : "";
            $classDeactivePP = ($row->phuongphap_status == 2) ? "class='chitieu-deactive'" : "";
            $hang = array();
            $hang[] = '<div>' . $row->chitieu_name.'<br />'.$row->chitieu_name_eng . '</div>';
            $hang[] = '<div>' . "<a href='".site_url()."nenmau/chat?package=".$row->dongia_id."'>".$row->package_code."</a>" . '</div>';
            $this->curl->create($this->_api['nhansu'] . 'get_donvi');
            $this->curl->post(array(
                'donvi_id' => $row->donvi_id,
            ));
            $donvi = json_decode($this->curl->execute());
            $hang[] = '<div>' . "<ul>
                            <li>".$ngonngu['mota_1'].": <strong><span class='label label-sm label-warning'>{$donvi->donvi_name}</span></strong></li>
                            <li>".$ngonngu['mota_2'].": <strong " . $classDeactivePP . ">{$row->phuongphap_name}</strong></li>
                            <li>".$ngonngu['mota_3'].": <strong " . $classDeactive . ">{$row->kythuat_name}</strong></li>
                            <li>".$ngonngu['mota_4'].": <strong>{$row->donvitinh_name}</strong></li>
                            <li>".$ngonngu['mota_5'].": <strong>{$row->thoigian}</strong> ".$ngonngu['donvi_thoigian']."</li>
                            <li>".$ngonngu['mota_6'].": <strong>{$row->thoigianluu}</strong> ".$ngonngu['donvi_thoigian']."</li>
			</ul>" . '</div>';
            if ($this->privdinhgia['read']) $hang[] = '<div>' . number_format($row->price) . '</div>';
            $button = '';
            $button .= ($this->privchitieu['update']) ? ' <button class="btn btn-xs btn-info" data-toggle="tooltip" title="'.$ngonngu['tooltip_suachitieu'].'" onclick="_sua_chitieu(' . $row->chitieu_id . ',' . $row->nenmau_id . ',\'' . $row->package_code . '\',' . $row->thoigianluu . ')"><i class="ace-icon fa fa-pencil bigger-110" ></i></button>' : '';
            $button .= ($this->privchitieu['delete']) ? ' <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoachitieu'].'" onclick="_xoa_chitieu(' . $row->chitieu_id . ',' . $row->nenmau_id . ')"><i class="ace-icon fa fa-trash-o bigger-110" ></i></button>' : '';
            if ($row->price > 0) {
                $button .= ($this->privdinhgia['update']) ? ' <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Định giá tổng" onclick="dinhgia(' . $row->price . ',' . $row->dongia_id . ')"><i class="ace-icon fa fa-dollar bigger-110"></i></button>' : '';
            } else {
                $button .= ($this->privdinhgia['write']) ? ' <button class="btn btn-xs btn-warning" data-toggle="tooltip" title="Định giá tổng" onclick="dinhgia(' . $row->price . ',' . $row->dongia_id . ')"><i class="ace-icon fa fa-dollar bigger-110"></i></button>' : '';
            }
            $button .= ' <a href="' . site_url() . $url_action . '?dongia_id=' . $row->dongia_id . '" class="btn btn-xs btn-warning">
                                <i class="ace-icon fa fa-pencil bigger-120"></i>
                            </a>';
            $hang[] = '<div>' . '<div style="text-align:center">' . $button . '</div>';
            $data[] = $hang;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_dongia->count_all($nenmau, $getall),
            "recordsFiltered" => $this->mod_dongia->count_filtered($nenmau, $getall),
            "data" => $data,
        );
        echo json_encode($output);
    }
    function themchitieu() {
        $giatri = $this->input->post();
        if ($giatri['nenmau'] != '' && $giatri['nenmau'] != NULL) {
            /*
             * Trường hợp là chỉ tiêu mới
             * 1. Thêm vào bảng mau_chitieu
             * 2. Thêm bộ (nenmau_id, chitieu_id) vào bảng mau_nenmau_chitieu
             */
            if ($giatri['chitieu_id'] == "" || $giatri['chitieu_id'] == "0") {
                $chitieu_id = $this->mod_chitieu->taochitieu($giatri);
                $this->mod_chitieu->themvaonenmau($chitieu_id, $giatri);
            }else{
                $chitieu_id = $giatri['chitieu_id'];
                if(!$this->checkChitieuNenmauExist($giatri['nenmau'], $giatri['chitieu_id']))
                    $this->mod_chitieu->themvaonenmau($chitieu_id, $giatri);
            }
            /*
             * Hồng làm trường hợp chọn chỉ tiêu đã có sẵn
             * Kiểm tra bảng mau_nenmau_chitieu xem có (nenmau_id, chitieu_id) hay chưa để thêm vào
             */
            $dongia = $this->checkDongiaExist($giatri)->row();
            if(!$dongia)
                $result = $this->mod_dongia->_create($chitieu_id, $giatri);
            else
                $result = $this->mod_dongia->_updateDonGia($dongia, $giatri["donvi"], $giatri["thoigian"]);
            if ($result == true) {
                echo "1";
            } else {
                echo "0";
            }
        }
        else echo 2;
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
    function get_chitieu() {
        $giatri = $this->input->post();
        $dulieu = $this->mod_chitieu->get_chitieu($giatri['chitieuid'], $giatri['nenmauid'], $giatri['packagecode']);
        echo json_encode($dulieu);
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
    function sua_chitieu() {
        $giatri = $this->input->post();
        //$kythuat = $this->mod_chitieu->kiemtra_kythuat($giatri['kythuat'], $giatri['kythuat_id']);
        $this->mod_chitieu->kiemtra_chitieu($giatri['id_chitieu_cu'], $giatri['chitieu'], $giatri['chitieu_eng'], $giatri['mota']);
        $data = array(
            'package_code' => $giatri['package_code'],
            'nenmau_id' => $giatri['nenmau'],
            'phuongphap_id' => $giatri['phuongphap_id'],
            'kythuat_id' => $giatri['kythuat_id'],
            'donvi_id' => $giatri['phongthinghiem'],
            'donvitinh_id' => $giatri['donvi'],
            'thoigian' => $giatri['thoigian'],
        );
        $this->mod_chitieu->update_thoigianluu($giatri['id_chitieu_cu'], $giatri['nenmau'], $giatri['thoigian_luu']);
        $kiemtra = $this->mod_chitieu->update_dongia($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function gia_chitieu() {
        if (!$this->privdinhgia['update'])
            redirect(site_url() . 'admin/denied?w=update');
        $giatri = $this->input->post();
        $this->mod_chitieu->capnhat_gia($giatri['gia'], $giatri['chitieu_id'], $giatri['nenmau_id']);
        echo "1";
    }
    function setgia() {
        if (!$this->privdinhgia['update']) redirect(site_url() . 'admin/denied?w=update');
        $giatri = $this->input->post();
        $this->mod_dongia->_setgia($giatri['gia'], $giatri['dongia']);
        echo "1";
    }
}
