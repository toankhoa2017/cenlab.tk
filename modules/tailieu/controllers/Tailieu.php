<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Tailieu extends ADMIN_Controller {
    private $privtailieu;
    private $privthuhoi;
    private $privquanly;
    private $_api_nhansu_restful;
    function __construct() {
        parent::__construct();
        $this->privtailieu = $this->permarr[_TL_DANHSACH];
        $this->_api_nhansu_restful = $this->_api['nhansu_restful'];
        $this->_api_nenmau = $this->_api['nenmau'];
        $this->privthuhoi = $this->permarr[_TL_THUHOI];
        $this->privquanly = $this->permarr[_TL_QUANLY];
        $this->parser->assign('privtailieu', $this->privtailieu);
        $this->parser->assign('privthuhoi', $this->privthuhoi);
        $this->load->model('Mod_tailieu');
        $this->load->model('Mod_tldenghi');
        $this->load->model('Mod_file');
        $this->load->model('Mod_tailieuversion');
        $this->load->model('Mod_tlphongban');
        $this->load->model('Mod_cautructailieu');
        $this->load->model('Mod_loaitailieuquytrinh');
        $this->load->model('Mod_tangtailieu');
    }
    private function danhSachPhongBan(){
        $this->curl->create($this->_api_nhansu_restful.'getDonvi');
        $this->curl->post();
        $result = json_decode($this->curl->execute(), TRUE);
        return $result["donvi"];
    }
    private function danhSachNhanVien(){
        $current_user = $this->session->userdata("ssAdminId");
        $this->curl->create($this->_api_nhansu_restful.'getNhanvien');
        $this->curl->post();
        $all_user = NULL;
        $result = json_decode($this->curl->execute(), TRUE);
        foreach ($result["danhsach"] as $nv){
            $all_user[$nv["id"]] = $nv["lastname"] . " " . $nv["firstname"];
        }
        return $all_user;
    }
    private function getUserQuyTrinh($quyen, $tang = FALSE){
        $this->curl->create($this->_api_nhansu_restful.'getUsers');
        $this->curl->post(array(
            'quyen' => trim($quyen),
            'tang'  => trim($tang)
        ));
        $all_user = NULL;
        $result = json_decode($this->curl->execute(), TRUE);
        $all_user = $result["members"];
        return $all_user;
    }       
    private function updatePhuongPhap($phuongphap_code, $phuongphap_status){
        $this->curl->create($this->_api_nenmau . 'phuongphap_update');
        $this->curl->post(array(
            'phuongphap_code' => $phuongphap_code,
            'phuongphap_status' => $phuongphap_status
        ));
        $result = json_decode($this->curl->execute(), TRUE);
        return $result["err_code"];
    }
    function DanhSachTaiLieu() {
        if (!$this->privtailieu['read']) redirect(site_url() . 'admin/denied?w=read');
        $TLPhanPhoi = array();
        $phongBan = $this->session->userdata('ssAdminDonvi'); // phong ban duoc phan phoi tai lieu
        $user_id = $this->session->userdata("ssAdminId"); // User duoc phan phoi tai lieu
        if($this->privquanly['master']) // neu master thi xem all tai lieu
            $tailieus = $this->Mod_tailieu->loadAllTaiLieuPhanPhoi();
        else // nguoc lai chi xem phong ban cua no
            $tailieus = $this->Mod_tailieu->loadTaiLieuPhanPhoi($phongBan, $user_id);
        foreach ($tailieus as $tailieu){
            //$denghi = $this->Mod_tldenghi->getDeNghiById($tailieu["de_nghi_id"]);
            //if($denghi->quy_trinh_id == 7){
                $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($tailieu["tai_lieu_id"]);
                $interval = strtotime($tailieu["ngay_ban_hanh"]) > strtotime($tailieu["editDate"]);
                $file_ban_hanh_id = $fileTLVer[0]["file_id"];
                $tailieu['file'] = $file_ban_hanh_id;
                $tailieu['show_noti'] = $interval;
                $TLPhanPhoi[] = $tailieu;
            //}
        }
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('TLPhanPhoi', $TLPhanPhoi);
        $this->parser->assign('privquanly', $this->privquanly);
        $this->parser->parse('tailieu/danhsachtailieu');
    }
    
    function phanphoitailieu(){
        $item = $this->input->post();
        $dataPhanPhoi = array();
        if(count($item["phong_ban"]) > 0){
            foreach ($item["phong_ban"] as $pb){
                $dataPhanPhoi[] = array(
                    'tai_lieu_id' => $item["tai_lieu_id"],
                    'phong_ban_id' => $pb,
                    'user_id' => NULL,
                    'date_create' => date("Y-m-d"),
                    'status' => 0
                );
            }
        }
        if(count($item["nhan_vien"]) > 0){
            $dataPhanPhoi[] = array(
                'tai_lieu_id' => $item["tai_lieu_id"],
                'user_id' => implode(",", $item["nhan_vien"]),
                'phong_ban_id' => NULL,
                'date_create' => date("Y-m-d"),
                'status' => 0
            );
        }
        if(count($dataPhanPhoi) > 0){
            $this->db->delete("tl_phong_ban", array('tai_lieu_id' => $item["tai_lieu_id"], 'status' => 0));// delete active
            $this->db->insert_batch("tl_phong_ban", $dataPhanPhoi);
        }
        return redirect(site_url() . "tailieu/denghi/tailieudetail?id=" . $item["tai_lieu_id"]);
    }
            
    function thuhoitailieu(){
        $item = $this->input->post();
        if($item["thu_hoi_tai_lieu"] == "on"){
            $update_data = array(
                "tai_lieu_ban_hanh" => 2,
                "user_thu_hoi" => $this->session->userdata("ssAdminId"),
                "ngay_thu_hoi" => date("Y-m-d"),
            );// for thu hoi tai lieu
            $this->db->update('tl_tai_lieu', $update_data, array('tai_lieu_id' => $item["tai_lieu_id"]));
            //update status phuong phap ben luu mau
            $tangTL = $this->Mod_tangtailieu->getTangTLByCode($item['tai_lieu_code']);
            if($tangTL->type_use == 1){
                $deactive_pp = 2;
                $this->updatePhuongPhap($item['tai_lieu_code'], $deactive_pp);
            }
        }
        return redirect(site_url() . 'tailieu/tailieu/DanhSachTaiLieuThuHoi');
    }
            
    function DanhSachTaiLieuThuHoi() {
        if (!$this->privthuhoi['read']) redirect(site_url() . 'admin/denied?w=read');
        $TLThuHoi = array();
        $phongBan = $this->session->userdata('ssAdminDonvi'); // phong ban duoc phan phoi tai lieu
        $tailieus = $this->Mod_tailieu->loadTaiLieuThuHoi();
        $tailieu_ver = $this->Mod_tailieu->loadTaiLieuVersion();
        foreach ($tailieus as $tailieu){
            $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($tailieu["tai_lieu_id"]);
            $file_ban_hanh_id = $fileTLVer[0]["file_id"];
            $tailieu['file'] = $file_ban_hanh_id;
            $TLThuHoi[] = $tailieu;
        }
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('TLThuHoi', $TLThuHoi);
        $this->parser->assign('tailieu_ver', $tailieu_ver);
        $this->parser->parse('tailieu/danhsachtailieuthuhoi');
    }
    
    function taiLieuDetail() {
        $item = $this->input->get();
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($item["id"]);
        $phongbans = $this->Mod_tlphongban->getPhongBanByTLId($item["id"]);
        $keyUser = array_search('', array_column($phongbans, 'phong_ban_id'));
        $phanphoi_users = array();
        if($keyUser)
            $phanphoi_users = explode(",", $phongbans[$keyUser]["user_id"]);
        $all_phongBan = $this->danhSachPhongBan();
        $phongBan_info = array_column($all_phongBan, 'name', 'id');//column
        $de_nghis = $this->Mod_tldenghi->getDeNghiByTaiLieu($item["id"]);
        $all_quytrinh = $this->Mod_loaitailieuquytrinh->getDenghiByTailieu($tai_lieu->loai_tai_lieu_id);
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($item["id"]);
        $file_soan_thao_id = $fileTLVer[0]["file_id"];
        $taiLieuVersion = $this->Mod_tailieuversion->getTLVerByTLId($item["id"]);
        $quytrinh_denghi = array();
        $deNghiDone = array();
        $max_count = array_count_values(array_column($de_nghis, "quy_trinh_id"));
        foreach($de_nghis as $dn){
            $deNghiDone[$dn['quy_trinh_id']][] = $dn;
        }
        foreach($all_quytrinh as $quytrinh){
            if(isset($deNghiDone[$quytrinh["quy_trinh_id"]])){
                $quytrinh["denghi"] = $deNghiDone[$quytrinh["quy_trinh_id"]];
                $quytrinh_denghi[] = $quytrinh;
            }else{
                $quytrinh["denghi"] = array();
                $quytrinh_denghi[] = $quytrinh;
            }
        }
        $file_soan_thao = NULL;
        if($file_soan_thao_id != NULL){
            $file_soan_thao = $this->Mod_file->getFileById($file_soan_thao_id);
        }
        $thuhoi = false;
        $permission = $this->permarr;
        if($permission && isset($permission[_TL_THUHOI]) && ($permission[_TL_THUHOI]['write'] || $permission[_TL_THUHOI]['master'])){
            $thuhoi = true;
        }
        $user_phongban = $this->getUserQuyTrinh(FALSE, FALSE);
        $this->parser->assign('privquanly', $this->privquanly);
        $this->parser->assign('detailThuHoi', $item["thuhoi"]);
        $this->parser->assign('taiLieuVersion', $taiLieuVersion);
        $this->parser->assign('thuhoi', $thuhoi);
        $this->parser->assign('phongbans', $phongbans);
        $this->parser->assign('phongbans_id', array_column($phongbans, "phong_ban_id"));
        $this->parser->assign('all_phongBan', $all_phongBan);
        $this->parser->assign('phongBan_info', $phongBan_info);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('phanphoi_users', $phanphoi_users);
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('tai_lieu', $tai_lieu);
        $this->parser->assign('file_soan_thao', $file_soan_thao);
        $this->parser->assign('quytrinh_denghi', $quytrinh_denghi);
        $this->parser->parse('tailieu/tailieudetail');   
    }
    
    function exportTailieu(){
        if (!$this->privtailieu['read']) redirect(site_url() . 'admin/denied?w=read');
        $TLPhanPhoi = array();
        $header = ["Tên tài liệu", "Mã tài liệu", "Tầng tài liệu", "Loại tài liệu", "Sửa đổi", "Ban hành", "Ngày ban hành"];
        $phongBan = $this->session->userdata('ssAdminDonvi'); // phong ban duoc phan phoi tai lieu
        $user_id = $this->session->userdata("ssAdminId"); // User duoc phan phoi tai lieu
        if($this->privquanly['master']) // neu master thi xem all tai lieu
            $tailieus = $this->Mod_tailieu->loadAllTaiLieuPhanPhoi();
        else // nguoc lai chi xem phong ban cua no
            $tailieus = $this->Mod_tailieu->loadTaiLieuPhanPhoi($phongBan, $user_id);
        $TLPhanPhoi[] = $header;
        foreach ($tailieus as $tailieu){
            //$denghi = $this->Mod_tldenghi->getDeNghiById($tailieu["de_nghi_id"]);
            //if($denghi->quy_trinh_id == 7){
                $TLPhanPhoi[] = [$tailieu["tai_lieu_name"], $tailieu["tai_lieu_code"], $tailieu["tang_tai_lieu_ten"], $tailieu["loai_tai_lieu_name"], $tailieu["tai_lieu_lan_sua_doi"], $tailieu["tai_lieu_lan_ban_hanh"], $tailieu["ngay_ban_hanh"]];
            //}
        }
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getActiveSheet()
            ->fromArray(
                $TLPhanPhoi,  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            );
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $exportFile = 'tailieu/export-tailieu.xlsx';
        $writer->save( _UPLOADS_PATH . $exportFile);
        echo _UPLOADS_PATH . $exportFile;
        //print_r($TLPhanPhoi);exit(1);
    }
            
    function viewpdf(){
        $idFile = $this->input->get("id");
        $TLId = $this->Mod_tailieuversion->getTLIdByFileId($idFile);
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($TLId->tai_lieu_id);
        $parentTLId = $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id];
        $rootTangTL = $this->Mod_tangtailieu->getTangTLById($parentTLId);
        $file_soan_thao = NULL;
        $file_soan_thao = $this->Mod_file->getFileById($idFile);
        $path_file = $file_soan_thao->file_path;
        $partPathFile = explode(".", $path_file);
        $partPathFile[count($partPathFile) - 1] = "pdf";
        $pdf_path = implode(".", $partPathFile);
        $this->parser->assign('Url', _UPLOADS_PATH . $pdf_path);
        $this->parser->assign('type_use', $rootTangTL[0]["type_use"]);
        $this->parser->parse('denghi/viewpdf');
    }
    
    function getTaiLieuByTangTL(){
        $TangTLId = $this->input->get("TangTLId", true);
        $data = $this->Mod_tailieu->loadTLBHByTangTL($TangTLId);
        echo json_encode($data);
    }
}