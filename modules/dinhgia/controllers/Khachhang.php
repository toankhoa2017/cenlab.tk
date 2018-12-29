<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Khachhang extends ADMIN_Controller {
    private $_api_khachhang;
    function __construct() {
        parent::__construct();
        //Language
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privdinhgia = $this->permarr[_PTN_DINHGIA];
        define('_DINHGIA_KHACHHANG', 193);
        $this->privdinhgia_kh = $this->permarr[_DINHGIA_KHACHHANG];
        $this->parser->assign('privdinhgia', $this->privdinhgia);
        $this->_api_khachhang = $this->_api['khachhang'];
        $this->load->model('mod_khachhang');
    }    
    
    private function danhSachCty(){
        $this->curl->create($this->_api_khachhang.'list_all_cty');
        $this->curl->get();
        $all_cty = NULL;
        $result = json_decode($this->curl->execute(), TRUE);
        $all_cty = $result["list_congty"];
        return $all_cty;
    } 
    
    function index(){
        //$dongia_id = $this->input->get("dongia_id");
        //$num_chat = $this->mod_chat->count_all($dongia_id);
        $allCty = $this->danhSachCty();
        $this->parser->assign('allCty', $allCty);
        $this->parser->parse('khachhang/list');
    }
    
    function setgia_tong(){
        if (!$this->privdinhgia_kh['update']) redirect(site_url() . 'admin/denied?w=update');
        $giatri = $this->input->post();
        $this->mod_khachhang->_setgia($giatri);
        echo "1";
    }
    function setgia_don(){
        if (!$this->privdinhgia_kh['update']) redirect(site_url() . 'admin/denied?w=update');
        $giatri = $this->input->post();
        $datas = [];
        foreach ($giatri['gia'] as $key=>$gia){
            $giachat = array(
                'gia_order' => $key + 1,
                'gia_price' => $gia,
                'bo_id' => $giatri['dongia'],
                'khachhang_id' => $giatri['khachhang']
            );
            $datas[] = $giachat;
        }
        $this->mod_khachhang->_setgia_don($datas);
        echo "1";
    }
    function exportDinhgia(){
        //$chiTieuGia = array();
        $header = ["Nền mẫu", "Nhóm chỉ tiêu", "Chỉ tiêu", "Giá bộ", "Giá đơn"];
        $khachhang_id = $this->input->get('khachhang_id');
        //$chiTieuGia = $this->mod_dongia->getChiTieuGia($khachhang_id);
        $giaTong = $this->mod_dongia->getGiaTongKH($khachhang_id);
        $GTDonGiaKey = array();
        foreach ($giaTong as $giaT){
            $GTDonGiaKey[$giaT["dongia_id"]] = $giaT["price"];
        }
        $GTDongia = array_column($giaTong, "dongia_id");
        $giaDon = $this->mod_dongia->getGiaDonKH($khachhang_id);
        $GDDonGiaKey = array();
        foreach ($giaDon as $giaD){
            $GDDonGiaKey[$giaD["dongia_id"]][] = $giaD["price"];
        }
        $GDDongia = array_column($giaDon, "dongia_id");
        $DonGia_Id = array_unique (array_merge ($GTDongia, $GDDongia));
        
        $chitieuInfo = $this->mod_dongia->getInfoChitieuByDonGia($DonGia_Id);
        $nenmau = array(); 
        $nhomchitieu = array();
        $chitieu = array();
        foreach ($chitieuInfo as $key => $info){
            $nenmau[$info["dongia_id"]] = $info["nenmau_name"];
            $nhomchitieu[$info["dongia_id"]] = $info["chitieu_name"];
            $chitieu[$info["dongia_id"]][] = $info["chat_name"];
        }
        $KhachHangGias = array();
        foreach ($DonGia_Id as $val){
            $KhachHangGias[$val] = array(
                "giatong" => $GTDonGiaKey[$val], 
                "giadon" => $GDDonGiaKey[$val], 
                "nenmau" => $nenmau[$val], 
                "nhomchitieu" => $nhomchitieu[$val],
                "chitieu" => $chitieu[$val]
                );
        }
        
        $GiaKhachHang = array(); 
        $GiaKhachHang[] = $header;
        foreach ($KhachHangGias as $item){
            foreach ($item["chitieu"] as $index => $chitieu){
                if($item["giadon"] == NULL)
                    $GiaKhachHang[] = [$item["nenmau"], $item["nhomchitieu"], $chitieu, $item["giatong"], $item["giadon"]];
                else
                    $GiaKhachHang[] = [$item["nenmau"], $item["nhomchitieu"], $chitieu, $item["giatong"], $item["giadon"][$index]];
            }
        }
        //var_dump($KhachHangGias);exit(1);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getActiveSheet()
            ->fromArray( 
                $GiaKhachHang,  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            );
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $exportFile = 'tailieu/export-gia.xlsx';
        $writer->save( _UPLOADS_PATH . $exportFile);
        echo _UPLOADS_PATH . $exportFile;
    }
} 