<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Denghi extends ADMIN_Controller {
    //private $_api_nhansu_url = "http://dev.tamducjsc.info/nhansu/api/";
    private $_api_nhansu;
    private $_api_nhansu_restful;
    private $privdenghi;
    function __construct() {
        parent::__construct();
        //define('_TL_THUHOI', 16);
        $this->privdenghi = $this->permarr['_TL_DENGHI'];
        $this->_api_nhansu_restful = $this->_api['nhansu_restful'];
        $this->_api_nenmau = $this->_api['nenmau'];
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->load->model('Mod_quytrinh');
        $this->load->model('Mod_tldenghi');
        $this->load->model('Mod_ketquadenghi');
        $this->load->model('Mod_quytrinhketqua');
        $this->load->model('Mod_loaitailieu');
        $this->load->model('Mod_tangtailieu');
        $this->load->model('Mod_tlphongban');
        $this->load->model('Mod_tailieu');
        $this->load->model('Mod_file');
        $this->load->model('Mod_loaitailieuquytrinh');
        $this->load->model('Mod_cautructailieu');
        $this->load->model('Mod_tailieuversion');
    }
    function index() {
        if (!$this->privdenghi['read']) redirect(site_url() . 'admin/denied?w=read');
        $quytrinh = 1;
        $current_user = $this->session->userdata("ssAdminId");
        $condition = array(
            "dn.quy_trinh_id" => 1 // get danh sach da de nghi buoc 1
        );
        $userCondition = array(
            'de_nghi_user_send' => trim($current_user)
        );
        $list_de_nghi = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition);
        $denghi_current_tailieu = $this->Mod_tailieu->getAllDeNghiCurrent();
        $denghi_current_array = array();
        $denghi_current_ketqua = array();
        foreach ($denghi_current_tailieu as $dn){
            $denghi_current_array[$dn['tai_lieu_id']] = $dn["quy_trinh_name"];
            $ketqua = $this->Mod_tldenghi->getLastKetQuaByTL($dn['tai_lieu_id']);
            if($ketqua){
                $denghi_current_ketqua[$dn['tai_lieu_id']] = $ketqua[0]['de_nghi_ket_qua_name'];
            }
            else {
                $denghi_current_ketqua[$dn['tai_lieu_id']] = 'chưa duyệt';
            }
        }
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('list_de_nghi', $list_de_nghi);
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->assign('denghi_current_array', $denghi_current_array);
        $this->parser->assign('denghi_current_ketqua', $denghi_current_ketqua);
        $this->parser->parse('denghi/listdenghi');
    }

    public static function diffArray($old, $new){
        $matrix = array();
        $maxlen = 0;
        foreach($old as $oindex => $ovalue){
            $nkeys = array_keys($new, $ovalue);
            foreach($nkeys as $nindex){
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ? $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if($matrix[$oindex][$nindex] > $maxlen){
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }
        }
        if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
        return array_merge(
            self::diffArray(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxlen),
            self::diffArray(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
    }
    private function htmlDiff($old, $new){
        $ret = array();
        $diff = self::diffArray(explode(' ', $old), explode(' ', $new));
        foreach($diff as $key => $k){
            if(is_array($k) && (!empty(implode('',$k['d'])) || !empty(implode('',$k['i'])))){
                $difference = array();
                $difference['d'] = (!empty($k['d']) ? implode(' ',$k['d']) : '');
                $difference['ins'] = (!empty($k['i'])? implode(' ',$k['i']) : '');
                $ret[] = $difference;
            }else{
                //$ret .= $k . ' ';
            }
        }
        return $ret;
    }
    private function read_docx($file){
        $striped_content = "";
        $content = '';
        $zip = zip_open($file);
        if (!$zip || is_numeric($zip)) return false;
        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            $content = preg_replace('/<w:tbl>(.*?)<\/w:tbl>/s', " ", $content, 2);
            $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
            $content = str_replace('<w:tc>', " ", $content);
            $content = str_replace('</w:tc>', " ", $content);
            $content = str_replace('<w:p>', " ", $content);
            $content = str_replace('</w:p>', " ", $content);
            $striped_content = strip_tags($content);
            zip_entry_close($zip_entry);
        }// end while
        zip_close($zip);
        return $striped_content;
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
    function getUserQuyTrinhAjax(){
        $quyen = "duyetdenghi";
        $tang = $this->input->get("tangId", true);
        $this->curl->create($this->_api_nhansu_restful.'getUsers');
        $this->curl->post(array(
            'quyen' => trim($quyen),
            'tang'  => trim($tang)
        ));
        $all_user = NULL;
        $result = json_decode($this->curl->execute(), TRUE);
        $all_user = $result["members"];
        echo json_encode($all_user);
        //return $all_user;
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
    
    private function addPhuongPhap($TLCode, $TLName, $TLLoai, $TLShortName){
        $this->curl->create($this->_api_nenmau . 'phuongphap_add');
        $this->curl->post(array(
            'phuongphap_code' => $TLCode,
            'phuongphap_name' => $TLName,
            'phuongphap_loai' => $TLLoai,
            'phuongphap_shortname' => $TLShortName
        ));
        $result = json_decode($this->curl->execute(), TRUE);
        return $result["err_code"];
    }

    public function arrayPermission(){
        $permissions = $this->getPermission();
        $deNgiPermissionClass = array();
        for($i = 1; $i <= 6; $i++){
            foreach ($permissions as $permission){
                if($permission["quyen_id"] == $i){
                    $deNgiPermissionClass[$i][0] = "allowed";
                    $deNgiPermissionClass[$i][1] = "label-info";
                    break;
                }
            }
            if(!isset($deNgiPermissionClass[$i])){
                $deNgiPermissionClass[$i][0] = "not-allowed";
                $deNgiPermissionClass[$i][1] = "";
            }
        }
        return $deNgiPermissionClass;
    }
    
    private function checkPerByIdDeNghi($DenghiId){
        $permissions = $this->getPermission();
        foreach ($permissions as $permission){
            if($permission["quyen_id"] == $DenghiId){
                return TRUE;
            }
        }
        return FALSE;
    }

    private function getPermission(){
        $current_user = $this->session->userdata("ssAdminId");
        $this->curl->create($this->_api_nhansu_restful.'getPermission');
        $this->curl->post(array(
            'id' => trim($current_user)
        ));
        $permission = NULL;
        $result = json_decode($this->curl->execute(), TRUE);
        $permission = $result["permission"];
        return $permission;
    }
    
    private function danhSachPhongBan(){
        $this->curl->create($this->_api_nhansu_restful.'getDonvi');
        $this->curl->post();
        $result = json_decode($this->curl->execute(), TRUE);
        return $result["donvi"];
    }
    
    private function checkUniqueShortName($shortName){
        if($shortName != ""){
            $AllShortName = array_column($this->mod_tailieu->getAllShortName(), "tai_lieu_shortname");
            return in_array($shortName, $AllShortName);
        }else
            return FALSE;
    }
    
    function danhSachDeNghi(){
        $quytrinh = 1;
        $current_user = $this->session->userdata("ssAdminId");
        $condition = array(
            "dn.quy_trinh_id" => 1 // get danh sach da de nghi buoc 1
        );
        $userCondition = array(
            'de_nghi_user_send' => trim($current_user)
        );
        $list_de_nghi = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition);
        $denghi_current_tailieu = $this->Mod_tailieu->getAllDeNghiCurrent();
        $denghi_current_array = array();
        $denghi_current_ketqua = array();
        foreach ($denghi_current_tailieu as $dn){
            $denghi_current_array[$dn['tai_lieu_id']] = $dn["quy_trinh_name"];
            $ketqua = $this->Mod_tldenghi->getLastKetQuaByTL($dn['tai_lieu_id']);
            if($ketqua){
                $denghi_current_ketqua[$dn['tai_lieu_id']] = $ketqua[0]['de_nghi_ket_qua_name'];
            }
            else {
                $denghi_current_ketqua[$dn['tai_lieu_id']] = 'chưa duyệt';
            }
        }
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('list_de_nghi', $list_de_nghi);
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->assign('denghi_current_array', $denghi_current_array);
        $this->parser->assign('denghi_current_ketqua', $denghi_current_ketqua);
        $this->parser->parse('denghi/listdenghi');
    }
    function themdenghi(){
        $validate = $this->input->get('validate');
        $quyen = "duyetdenghi";
        $loai_tai_lieu = $this->Mod_loaitailieu->_load();
        $tang_tai_lieu = $this->Mod_tangtailieu->_load();
        //$tai_lieus = $this->Mod_tailieu->loadTaiLieuBanHanh();
        $taiLieuNB = $this->Mod_tailieu->loadTaiLieuNoiBo();
        $taiLieuBN = $this->Mod_tailieu->loadTaiLieuBenNgoai();
        $maxLevel = $this->Mod_tangtailieu->getMaxLevel();
        $user_phongban = $this->getUserQuyTrinh($quyen);
        $this->parser->assign('loai_tai_lieu', $loai_tai_lieu);
        $this->parser->assign('maxLevel', $maxLevel->max_level);
        $this->parser->assign('tang_tai_lieu', $tang_tai_lieu);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('validate', $validate);
        $this->parser->assign('taiLieuNB', $taiLieuNB);
        $this->parser->assign('taiLieuBN', $taiLieuBN);
        $this->parser->parse('denghi/themdenghi');
    }
    private function validation($item){
        if(!$item['loai_tai_lieu_id'] || !$item['tang_tai_lieu_id'] || !$item['de_nghi_name'] || !$item['de_nghi_date_end'] || !$item['loai_de_nghi_id'] || !$item["de_nghi_user_receive"]){
            return FALSE;
        }
        if($item['loai_de_nghi_id'] == 1 && !$item['tai_lieu_name']){
            return FALSE;
        }
        if($item['loai_de_nghi_id'] == 2 && !$item['tai_lieu_id']){
            return FALSE;
        }
        if(strtotime($item['de_nghi_date_end']) < strtotime($item['de_nghi_date_start'])){
            return FALSE;
        }
        return TRUE;
    }
    function createDenghi(){
        $item = $this->input->post();
        $current_user = $this->session->userdata("ssAdminId");
        $Validate = TRUE;
        /*$isExist = $this->checkUniqueShortName($item["tai_lieu_shortname"]);
        if($isExist){
            $resp = array( 
                "error_code" => "EXIST",
                "error_mess" => "Tên viết tắt đã tồn tại trong hệ thống"
            );
            echo json_encode($resp);
            return;
        }*/
        //Validate data user input
        $Validate = $this->validation($item);
        if(!$Validate){
            return redirect(site_url() . 'tailieu/denghi/themdenghi?validate=1');
        }
        if($item["loai_de_nghi_id"] == 1) { // tao tai lieu moi khi them moi hoac nguoc lai
            $tai_lieu = array(
                "tai_lieu_name" => $item["tai_lieu_name"],
                "loai_tai_lieu_id" => $item["loai_tai_lieu_id"],
                "tai_lieu_ban_hanh" => 0,
                "tai_lieu_lan_ban_hanh" => 0,
                "tai_lieu_lan_sua_doi" => 0,
                "tang_tai_lieu_id" => $item["tang_tai_lieu_id"],
                "tai_lieu_shortname" => $item["tai_lieu_shortname"],
                "status" => 1
            );
            $id_tailieu = $this->Mod_tailieu->_create($tai_lieu);
        }else {
            $id_tailieu = $item["tai_lieu_id"];
        }
        $file_id = null;
        if($item["loai_tai_lieu_id"] == 2) { // upload file for loai de nghi ben ngoai
            $name_file = $_FILES["de_nghi_file"]["name"];
            $ext = end((explode(".", $name_file)));
            $fileNameTable = uniqid() . "_" . time() . "." . $ext;
            $config['upload_path'] = _UPLOADS_PATH . 'tailieu/';
            $config['file_name'] = $fileNameTable;
            $config['allowed_types'] = 'doc|docx|pdf';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('de_nghi_file'))
            {
                $error = array('error' => $this->upload->display_errors());
                $Validate = FALSE;
                return redirect(site_url() . 'tailieu/denghi/themdenghi?validate=1');
                //$this->load->view('upload_form', $error);
            }else{
                $data = array('upload_data' => $this->upload->data());
                $file_data = array(
                    "file_name" => $fileNameTable,
                    "file_path" => 'tailieu/' . $fileNameTable
                );
                $file_id = $this->Mod_file->_create($file_data);
            }
        }   
        $denghi = array(
            "de_nghi_name" => $item["de_nghi_name"],
            "de_nghi_content" => $item["de_nghi_content"],
            "de_nghi_date_start" => date("Y-m-d", strtotime($item["de_nghi_date_start"])),
            "de_nghi_date_end" => date("Y-m-d", strtotime($item["de_nghi_date_end"])),
            "ngay_thuc_hien" => date('Y-m-d'),
            "loai_de_nghi_id" => $item["loai_de_nghi_id"],
            "tai_lieu_id" => $id_tailieu,
            "quy_trinh_id" => 1,
            "de_nghi_user_send" => $current_user,
            "de_nghi_file_id" => $file_id,
            "de_nghi_user_receive" => $item["de_nghi_user_receive"],
            "status" => 1
        );
        $this->Mod_tldenghi->_create($denghi);
        return redirect(site_url() . 'tailieu/denghi/danhsachdenghi');
    }
    function duyetDeNghi() {
        $trangthai = $this->input->get("trangthai");
        if(!$this->checkPerByIdDeNghi(1))
            redirect(site_url());
        $quytrinh = 1;
        $condition = array(
            "dn.quy_trinh_id" => 1, // get danh sach da de nghi buoc 1
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $duyetdn_tuchoi = $this->Mod_tldenghi->getDuyetDeNghiTuChoi();
        $duyetdn_tuchoi = array_column($duyetdn_tuchoi, "tai_lieu_id");
        $list_de_nghi = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $trangthai);
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('duyetdn_tuchoi', $duyetdn_tuchoi);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->assign('list_de_nghi', $list_de_nghi);
        $this->parser->parse('denghi/duyetdenghi');
    }
    function createDuyetDeNghi() {
        $item = $this->input->post();
        $Validate = TRUE;
        if(!$item["de_nghi_ket_qua_id"] || (($item["de_nghi_ket_qua_id"] == 1) && (!$item['de_nghi_date_end'] || !$item["de_nghi_user_receive"] || (strtotime($item['de_nghi_date_end']) < strtotime($item['de_nghi_date_start']))))){
            $Validate = FALSE;
            return redirect(site_url() . 'tailieu/denghi/formxetduyetdenghi?validate=1&id=' . $item["de_nghi_id"]);
        }
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item["de_nghi_id"]);
        $denghi = array(
            "de_nghi_name" => $item["de_nghi_name"],
            "de_nghi_content" => $item["de_nghi_content"],
            "de_nghi_date_start" => date("Y-m-d", strtotime($item["de_nghi_date_start"])),
            "de_nghi_date_end" => date("Y-m-d", strtotime($item["de_nghi_date_end"])),
            "ngay_thuc_hien" => date('Y-m-d'),
            "loai_de_nghi_id" => $de_nghi->loai_de_nghi_id,
            "tai_lieu_id" => $de_nghi->tai_lieu_id,
            "quy_trinh_id" => 2,
            "de_nghi_before" => $item["de_nghi_id"],
            "de_nghi_ket_qua_id" => $item["de_nghi_ket_qua_id"], 
            "de_nghi_file_id" => $de_nghi->de_nghi_file_id ? $de_nghi->de_nghi_file_id : NULL,
            "de_nghi_user_send" => $this->session->userdata("ssAdminId"),
            "de_nghi_user_receive" => $item["de_nghi_user_receive"],
            "status" => 1
        );
        $this->Mod_tldenghi->_create($denghi);
        return redirect(site_url() . 'tailieu/denghi/duyetDeNghi');
    }
    function formXetDuyetDeNghi(){
        $item = $this->input->get();
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item['id']);
        $file_soan_thao = NULL;
        $ket_qua = $this->Mod_quytrinhketqua->ketQuaByQuyTrinh(2);
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($de_nghi->tai_lieu_id);
        if($tai_lieu->loai_tai_lieu_id == 2)// for tai lieu ben ngoai
            $file_soan_thao = $this->Mod_file->getFileById($de_nghi->de_nghi_file_id);
        $fileCauTruc = $this->Mod_cautructailieu->getCauTrucTLByTangTL($tai_lieu->tang_tai_lieu_id);
        $label_date_start = ($de_nghi->loai_tai_lieu_id == 1) ? "Ngày bắt đầu soạn thảo" : "Ngày bắt đầu phê duyệt";
        $label_date_end = ($de_nghi->loai_tai_lieu_id == 1) ? "Ngày kết thúc soạn thảo" : "Ngày kết thúc phê duyệt";
        $label_user_assign = ($de_nghi->loai_tai_lieu_id == 1) ? "Người soạn thảo" : "Người phê duyệt";
        if($de_nghi->loai_tai_lieu_id == 1){ // for tài liệu bên ngoài
            $quyen = "soanthao";
            $user_phongban = $this->getUserQuyTrinh($quyen, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);    
        }else{
            $quyen = "pheduyet";
            $user_phongban = $this->getUserQuyTrinh($quyen, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);
        }
        
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($de_nghi->tai_lieu_id);
        $file_ban_hanh_id = -1;
        $file_ban_hanh = NULL;
        $file_ban_hanh_id = $fileTLVer[0]["file_id"];
        if($file_ban_hanh_id > -1){
            $file_ban_hanh = $this->Mod_file->getFileById($file_ban_hanh_id);
            $file_ban_hanh_goc_path = _UPLOADS_PATH . 'tailieu/' . $file_ban_hanh->file_id . '_origin' . '.' . end(explode('.', _UPLOADS_PATH . $file_ban_hanh->file_path));
        }
        $this->parser->assign('validate', $item['validate']);
        $this->parser->assign('de_nghi', $de_nghi);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('label_date_start', $label_date_start);
        $this->parser->assign('label_date_end', $label_date_end);
        $this->parser->assign('label_user_assign', $label_user_assign);
        $this->parser->assign('file_soan_thao', $file_soan_thao);
        $this->parser->assign('ket_qua', $ket_qua);
        $this->parser->assign('tai_lieu', $tai_lieu);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('fileCauTruc', $fileCauTruc);
        $this->parser->assign('file_ban_hanh', $file_ban_hanh);
        $this->parser->assign('file_ban_hanh_goc_path', $file_ban_hanh_goc_path);
        $this->parser->parse('denghi/xetduyetdenghi');
    }
    
    function danhSachSoanThao(){
        $trangthai = $this->input->get("trangthai");
        $quytrinh = 2;
        $condition = array(
            "dn.quy_trinh_id" => 2, // get danh sach da duyet buoc 2
            "tailieu.loai_tai_lieu_id" => 1 // tai lieu noi bo
        );
        $option = array(
            "dn.quy_trinh_id" => 4, // for soan thao lai
            "dn.de_nghi_ket_qua_id" => 5
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $soan_thao = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $trangthai, $option);
        $xemxet_tuchoi = $this->Mod_tldenghi->getXemXetTuChoi();
        $xemxet_tuchoi = array_column($xemxet_tuchoi, "tai_lieu_id");
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->assign('soan_thao', $soan_thao);
        $this->parser->assign('xemxet_tuchoi', $xemxet_tuchoi);
        $this->parser->parse('denghi/danhsachsoanthao');
    }
    
    function formSoanThao(){
        $item = $this->input->get();
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item['id']);
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($de_nghi->tai_lieu_id);
        $fileCauTruc = $this->Mod_cautructailieu->getCauTrucTLByTangTL($tai_lieu->tang_tai_lieu_id);
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($de_nghi->tai_lieu_id);
        $file_ban_hanh_id = -1;
        $file_ban_hanh = NULL;
        $file_ban_hanh_id = $fileTLVer[0]["file_id"];
        if($file_ban_hanh_id > -1){
            $file_ban_hanh = $this->Mod_file->getFileById($file_ban_hanh_id);
            $file_ban_hanh_goc_path = _UPLOADS_PATH . 'tailieu/' . $file_ban_hanh->file_id . '_origin' . '.' . end(explode('.', _UPLOADS_PATH . $file_ban_hanh->file_path));
        }
        $quyen = "xemxetbanthao";
        $user_phongban = $this->getUserQuyTrinh($quyen, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('validate', $item['validate']);
        $this->parser->assign('tai_lieu', $tai_lieu);
        $this->parser->assign('fileCauTruc', $fileCauTruc);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('file_ban_hanh_goc_path', $file_ban_hanh_goc_path);
        $this->parser->assign('de_nghi', $de_nghi);
        $this->parser->parse('denghi/soanthao');
    }
    
    function createSoanThao(){
        $item = $this->input->post();
        $validate = TRUE;
        if(!$item['de_nghi_date_end'] || !$item["de_nghi_user_receive"] || (strtotime($item['de_nghi_date_end']) < strtotime($item['de_nghi_date_start']))){
            $validate = FALSE;
            return redirect(site_url() . 'tailieu/denghi/formsoanthao?validate=1&id=' . $item["de_nghi_id"]);
        }
        $name_file = $_FILES["de_nghi_file"]["name"];
        $ext = end((explode(".", $name_file)));
        $fileNameTable = uniqid() . "_" . time() . "." . $ext;
        $config['upload_path'] = _UPLOADS_PATH . 'tailieu/';
        $config['allowed_types'] = 'doc|docx|pdf';
        $config['file_name'] = $fileNameTable;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('de_nghi_file'))
        {
            $error = array('error' => $this->upload->display_errors());
            $validate = FALSE;
            return redirect(site_url() . 'tailieu/denghi/formsoanthao?validate=1&id=' . $item["de_nghi_id"]);
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $file_data = array(
                "file_name" => $fileNameTable,
                "file_path" => 'tailieu/' . $fileNameTable
            );
            $file_id = $this->Mod_file->_create($file_data);
            $de_nghi = $this->Mod_tldenghi->getDeNghiById($item["de_nghi_id"]);
            $denghi = array(
                "de_nghi_name" => $item["de_nghi_name"],
                "de_nghi_content" => $item["de_nghi_content"],
                "de_nghi_date_start" => date("Y-m-d", strtotime($item["de_nghi_date_start"])),
                "de_nghi_date_end" => date("Y-m-d", strtotime($item["de_nghi_date_end"])),
                "ngay_thuc_hien" => date('Y-m-d'),
                "loai_de_nghi_id" => $de_nghi->loai_de_nghi_id,
                "tai_lieu_id" => $de_nghi->tai_lieu_id,
                "quy_trinh_id" => 3,
                "de_nghi_before" => $item["de_nghi_id"],
                "de_nghi_file_id" => $file_id,
                "de_nghi_user_send" => $this->session->userdata("ssAdminId"),
                "de_nghi_user_receive" => $item["de_nghi_user_receive"],
                "status" => 1
            );
            $this->Mod_tldenghi->_create($denghi);
        }
        return redirect(site_url() . 'tailieu/denghi/danhsachsoanthao');
    }
    
    function danhSachXemXetSoanThao(){
        $trangthai = $this->input->get("trangthai");
        if(!$this->checkPerByIdDeNghi(2))
            redirect(site_url());
        $quytrinh = 3;
        $condition = array(
            "dn.quy_trinh_id" => 3, // get danh sach da soan thao buoc 3
            "tailieu.loai_tai_lieu_id" => 1 // tai lieu noi bo
        );
        $option = array(
            "dn.quy_trinh_id" => 5, // for soan thao lai tai phe duyet
            "dn.de_nghi_ket_qua_id" => 5
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $xx_soan_thao = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $trangthai, $option);
        $pheduyet_tuchoi = $this->Mod_tldenghi->getPheDuyetTuChoi();
        $pheduyet_tuchoi = array_column($pheduyet_tuchoi, "tai_lieu_id");
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->assign('xx_soan_thao', $xx_soan_thao);
        $this->parser->assign('pheduyet_tuchoi', $pheduyet_tuchoi);
        $this->parser->parse('denghi/danhsachxemxetsoanthao');
    }
    
    function formXemXetSoanThao(){
        $item = $this->input->get();
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item['id']);
        $ket_qua = $this->Mod_quytrinhketqua->ketQuaByQuyTrinh(4);
        $file_soan_thao = $this->Mod_file->getFileById($de_nghi->de_nghi_file_id);
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($de_nghi->tai_lieu_id);
        $file_ban_hanh_id = -1;
        $file_ban_hanh = NULL;
        $file_ban_hanh_id = $fileTLVer[0]["file_id"];
        if($file_ban_hanh_id > -1){
            $file_ban_hanh = $this->Mod_file->getFileById($file_ban_hanh_id);
            $file_ban_hanh_goc_path = _UPLOADS_PATH . 'tailieu/' . $file_ban_hanh->file_id . '_origin' . '.' . end(explode('.', $file_ban_hanh->file_path));
        }
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($de_nghi->tai_lieu_id);
        $fileCauTruc = $this->Mod_cautructailieu->getCauTrucTLByTangTL($tai_lieu->tang_tai_lieu_id);
        $quyen = "pheduyet";
        $user_phongban = $this->getUserQuyTrinh($quyen, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);
        $quyenSoanThao = "soanthao";
        $soanthao_user = $this->getUserQuyTrinh($quyenSoanThao, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('validate', $item['validate']);
        $this->parser->assign('soanthao_user', $soanthao_user);
        $this->parser->assign('tai_lieu', $tai_lieu);
        $this->parser->assign('de_nghi', $de_nghi);
        $this->parser->assign('fileCauTruc', $fileCauTruc);
        $this->parser->assign('ket_qua', $ket_qua);
        $this->parser->assign('file_soan_thao', $file_soan_thao);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('file_ban_hanh_goc_path', $file_ban_hanh_goc_path);
        $this->parser->parse('denghi/formxemxetsoanthao');
    }
    
    function createXemXetSoanThao(){
        $item = $this->input->post();
        $validate = TRUE;
        if(!$item["de_nghi_ket_qua_id"] || (($item["de_nghi_ket_qua_id"] != 4) && (!$item['de_nghi_date_end'] || !$item["de_nghi_user_receive"] || (strtotime($item['de_nghi_date_end']) < strtotime($item['de_nghi_date_start']))))){
            $validate = FALSE;
            return redirect(site_url() . 'tailieu/denghi/formxemxetsoanthao?validate=1&id=' . $item["de_nghi_id"]);
        }
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item["de_nghi_id"]);
        $denghi = array(
            "de_nghi_name" => $item["de_nghi_name"],
            "de_nghi_content" => $item["de_nghi_content"],
            "de_nghi_date_start" => date("Y-m-d", strtotime($item["de_nghi_date_start"])),
            "de_nghi_date_end" => date("Y-m-d", strtotime($item["de_nghi_date_end"])),
            "ngay_thuc_hien" => date('Y-m-d'),
            "loai_de_nghi_id" => $de_nghi->loai_de_nghi_id,
            "tai_lieu_id" => $de_nghi->tai_lieu_id,
            "quy_trinh_id" => 4,
            "de_nghi_before" => $item["de_nghi_id"],
            "de_nghi_ket_qua_id" => $item["de_nghi_ket_qua_id"], 
            "de_nghi_file_id" => $de_nghi->de_nghi_file_id,
            "de_nghi_user_send" => $this->session->userdata("ssAdminId"),
            "de_nghi_user_receive" => $item["de_nghi_user_receive"],
            "status" => 1
        );
        $this->Mod_tldenghi->_create($denghi);
        return redirect(site_url() . 'tailieu/denghi/danhsachxemxetsoanthao');
    }
    
    function danhSachPheDuyet(){
        $trangthai = $this->input->get("trangthai");
        if(!$this->checkPerByIdDeNghi(3))
            redirect(site_url());
        $quytrinh = 4;
        $condition = array(
            "dn.quy_trinh_id" => 4, // get danh sach da xem xet soan thao buoc 4
            "tailieu.loai_tai_lieu_id" => 1 // tai lieu noi bo
        );
        $option = array(
            "dn.quy_trinh_id" => 2, // get danh sach da duyet buoc 2
            "tailieu.loai_tai_lieu_id" => 2 // tai lieu ben ngoai
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $dsPheDuyet = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $trangthai, $option);
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->assign('dsPheDuyet', $dsPheDuyet);
        $this->parser->parse('denghi/danhsachpheduyet');
    }
    
    function formPheDuyet(){
        $item = $this->input->get();
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item['id']);
        $ket_qua = $this->Mod_quytrinhketqua->ketQuaByQuyTrinh(5);
        $file_soan_thao = $this->Mod_file->getFileById($de_nghi->de_nghi_file_id);
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($de_nghi->tai_lieu_id);
        $file_ban_hanh_id = -1;
        $file_ban_hanh = NULL;
        $file_ban_hanh_id = $fileTLVer[0]["file_id"];
        if($file_ban_hanh_id > -1){
            $file_ban_hanh = $this->Mod_file->getFileById($file_ban_hanh_id);
            $file_ban_hanh_goc_path = _UPLOADS_PATH . 'tailieu/' . $file_ban_hanh->file_id . '_origin' . '.' . end(explode('.', _UPLOADS_PATH . $file_ban_hanh->file_path));
        }
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($de_nghi->tai_lieu_id);
        if($tai_lieu->loai_tai_lieu_id == 2){ // loai TL ben ngoai
            foreach ($ket_qua as $key => $val){
                if($val["de_nghi_ket_qua_id"] == 5){
                    unset($ket_qua[$key]);
                }
            }
        }
        $fileCauTruc = $this->Mod_cautructailieu->getCauTrucTLByTangTL($tai_lieu->tang_tai_lieu_id);
        $quyen = "banhanh";
        $user_phongban = $this->getUserQuyTrinh($quyen, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);
        $quyen_xemxet = "xemxetbanthao";
        $xemxet_user = $this->getUserQuyTrinh($quyen_xemxet, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('validate', $item['validate']);
        $this->parser->assign('xemxet_user', $xemxet_user);
        $this->parser->assign('tai_lieu', $tai_lieu);
        $this->parser->assign('fileCauTruc', $fileCauTruc);
        $this->parser->assign('file_soan_thao', $file_soan_thao);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('file_ban_hanh_goc_path', $file_ban_hanh_goc_path);
        $this->parser->assign('de_nghi', $de_nghi);
        $this->parser->assign('ket_qua', $ket_qua);
        $this->parser->parse('denghi/formpheduyet');
    }
    
    function createPheDuyet(){
        $item = $this->input->post();
        $validate = TRUE;
        if(!$item["de_nghi_ket_qua_id"] || (($item["de_nghi_ket_qua_id"] != 6) && (!$item['de_nghi_date_end'] || !$item["de_nghi_user_receive"] || (strtotime($item['de_nghi_date_end']) < strtotime($item['de_nghi_date_start']))))){
            $validate = FALSE;
            return redirect(site_url() . 'tailieu/denghi/formpheduyet?validate=1&id=' . $item["de_nghi_id"]);
        }
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item["de_nghi_id"]);
        $denghi = array(
            "de_nghi_name" => $item["de_nghi_name"],
            "de_nghi_content" => $item["de_nghi_content"],
            "de_nghi_date_start" => date("Y-m-d", strtotime($item["de_nghi_date_start"])),
            "de_nghi_date_end" => date("Y-m-d", strtotime($item["de_nghi_date_end"])),
            "ngay_thuc_hien" => date('Y-m-d'),
            "loai_de_nghi_id" => $de_nghi->loai_de_nghi_id,
            "tai_lieu_id" => $de_nghi->tai_lieu_id,
            "quy_trinh_id" => 5,
            "de_nghi_before" => $item["de_nghi_id"],
            "de_nghi_ket_qua_id" => $item["de_nghi_ket_qua_id"], 
            "de_nghi_file_id" => $de_nghi->de_nghi_file_id,
            "de_nghi_user_send" => $this->session->userdata("ssAdminId"),
            "de_nghi_user_receive" => $item["de_nghi_user_receive"],
            "status" => 1
        );
        $this->Mod_tldenghi->_create($denghi);
        return redirect(site_url() . 'tailieu/denghi/danhSachPheDuyet');
    }
    
    function danhSachBanHanh(){
        $trangthai = $this->input->get("trangthai");
        if(!$this->checkPerByIdDeNghi(4))
            redirect(site_url());
        $quytrinh = 5;
        $condition = array(
            "dn.quy_trinh_id" => 5, // get danh sach da da phe duyet buoc 5
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $banhanh_tuchoi = $this->Mod_tldenghi->getBanHanhTuChoi();
        $banhanh_tuchoi = array_column($banhanh_tuchoi, "tai_lieu_id");
        $dsBanHanh = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $trangthai);
        $userInfo = $this->danhSachNhanVien(); 
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('banhanh_tuchoi', $banhanh_tuchoi);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->assign('dsBanHanh', $dsBanHanh);
        $this->parser->parse('denghi/danhsachbanhanh'); 
    }
    
    function formBanHanh(){
        $item = $this->input->get();
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item['id']);
        $ket_qua = $this->Mod_quytrinhketqua->ketQuaByQuyTrinh(6);
        $file_soan_thao = $this->Mod_file->getFileById($de_nghi->de_nghi_file_id);
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($de_nghi->tai_lieu_id);
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($de_nghi->tai_lieu_id);
        $file_ban_hanh_id = -1;
        $file_ban_hanh = NULL;
        $file_ban_hanh_id = $fileTLVer[0]["file_id"];
        if($file_ban_hanh_id > -1){
            $file_ban_hanh = $this->Mod_file->getFileById($file_ban_hanh_id);
            $file_ban_hanh_goc_path = _UPLOADS_PATH . 'tailieu/' . $file_ban_hanh->file_id . '_origin' . '.' . end(explode('.', _UPLOADS_PATH . $file_ban_hanh->file_path));
        }
        $fileCauTruc = $this->Mod_cautructailieu->getCauTrucTLByTangTL($tai_lieu->tang_tai_lieu_id);
        $quyen = "phanphoi";
        $user_phongban = $this->getUserQuyTrinh($quyen, $this->Mod_tangtailieu->_levelUps($tai_lieu->tang_tai_lieu_id, 0)[id]);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('validate', $item['validate']);
        $this->parser->assign('file_ban_hanh', $file_ban_hanh);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('file_soan_thao', $file_soan_thao);
        $this->parser->assign('tai_lieu', $tai_lieu);
        //$this->parser->assign('fileCauTruc', $fileCauTruc); // không hiển thị file cấu trúc cho ban hành
        $this->parser->assign('de_nghi', $de_nghi);
        $this->parser->assign('ket_qua', $ket_qua);
        $this->parser->parse('denghi/formbanhanh');
    }
    
    function createBanHanh(){
        $item = $this->input->post();
        $validate = TRUE;
        if(!$item["de_nghi_ket_qua_id"] || (($item["de_nghi_ket_qua_id"] == 7) && (!$item['de_nghi_date_end'] || !$item["de_nghi_user_receive"] || (strtotime($item['de_nghi_date_end']) < strtotime($item['de_nghi_date_start']))))){
            $validate = FALSE;
            return redirect(site_url() . 'tailieu/denghi/formbanhanh?validate=1&id=' . $item["de_nghi_id"]);
        }
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item["de_nghi_id"]);
        $denghi = array(
            "de_nghi_name" => $item["de_nghi_name"],
            "de_nghi_content" => $item["de_nghi_content"],
            "de_nghi_date_start" => date("Y-m-d", strtotime($item["de_nghi_date_start"])),
            "de_nghi_date_end" => date("Y-m-d", strtotime($item["de_nghi_date_end"])),
            "ngay_thuc_hien" => date('Y-m-d'),
            "loai_de_nghi_id" => $de_nghi->loai_de_nghi_id,
            "tai_lieu_id" => $de_nghi->tai_lieu_id,
            "quy_trinh_id" => 6,
            "de_nghi_before" => $item["de_nghi_id"],
            "de_nghi_ket_qua_id" => $item["de_nghi_ket_qua_id"], 
            "de_nghi_file_id" => $de_nghi->de_nghi_file_id,
            "de_nghi_user_send" => $this->session->userdata("ssAdminId"),
            "de_nghi_user_receive" => $item["de_nghi_user_receive"],
            "status" => 1
        );
        
        $tai_lieu_id = $this->Mod_tldenghi->_create($denghi, $item["ban_hanh_moi"]);
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($tai_lieu_id);
        if($denghi["de_nghi_ket_qua_id"] == 7){ // chap nhan de nghi
            if($tai_lieu->loai_tai_lieu_id == 1){ //Loại tài liệu nội bộ
                $soanthao = $this->Mod_tldenghi->getUserSend($tai_lieu_id, 3); //Nguoi soan thao
                $xemxet = $this->Mod_tldenghi->getUserSend($tai_lieu_id, 4); //Nguoi phe duyet
                $pheduyet= $this->Mod_tldenghi->getUserSend($tai_lieu_id, 5); //Nguoi phe duyet
                $userInfo = $this->danhSachNhanVien();
                $file_soan_thao = $this->Mod_file->getFileById($de_nghi->de_nghi_file_id);
                $fileName = _UPLOADS_PATH . $file_soan_thao->file_path;
                // Create original file tai lieu
                $fileTLParts = explode(".", $fileName);
                $originFileName = $de_nghi->de_nghi_file_id . "_origin" . "." . end($fileTLParts);
                $originFilePath = _UPLOADS_PATH . 'tailieu/' . $originFileName;
                copy($fileName, $originFilePath);
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($fileName);
                if($denghi["loai_de_nghi_id"] == 2){
                    if($item["ban_hanh_moi"] == 'on')
                        $this->soSanhFileTaiLieu($de_nghi->tai_lieu_id, $fileName, $de_nghi->de_nghi_file_id, $templateProcessor);
                    else    
                        $this->soSanhFileTaiLieu($tai_lieu_id, $fileName, $de_nghi->de_nghi_file_id, $templateProcessor);
                }else{
                    $templateProcessor->setValue('stt', "01");
                    $templateProcessor->setValue('oldsua', "");
                    $templateProcessor->setValue('newsua', "");
                    $templateProcessor->setValue('dateedit', "");
                }//so sanh file khi Sua tai lieu
                $templateProcessor->setValue('soanthao', $userInfo[$soanthao[0]["de_nghi_user_send"]]);
                $templateProcessor->setValue('xemxet', $userInfo[$xemxet[0]["de_nghi_user_send"]]);
                $templateProcessor->setValue('pheduyet', $userInfo[$pheduyet[0]["de_nghi_user_send"]]);
                $templateProcessor->saveAs($fileName);
                $templateProcessorNew = new \PhpOffice\PhpWord\TemplateProcessor($fileName);
                $templateProcessorNew->setValue('matl', $tai_lieu->tai_lieu_code);
                $templateProcessorNew->setValue('ngaybh', date("d-m-Y", strtotime($tai_lieu->ngay_ban_hanh)));
                $templateProcessorNew->setValue('lanbanhanh', $tai_lieu->tai_lieu_lan_ban_hanh);
                $templateProcessorNew->setValue('lansuadoi', $tai_lieu->tai_lieu_lan_sua_doi);
                $templateProcessorNew->saveAs($fileName);

                $folderPath = _UPLOADS_PATH . 'tailieu';
                $output = shell_exec("sh /selinux/doc2pdf.sh " . $folderPath . " " . $folderPath . "/" . $file_soan_thao->file_name);
            }

            if($de_nghi->loai_de_nghi_id == 1 || ($tai_lieu_id != $de_nghi->tai_lieu_id)){
                $tangTL = $this->Mod_tangtailieu->getTangTLByCode($tai_lieu->tai_lieu_code);
                if($tangTL->type_use == 1)
                    $this->addPhuongPhap($tai_lieu->tai_lieu_code, $tai_lieu->tai_lieu_name, $tai_lieu->loai_tai_lieu_id, $tai_lieu->tai_lieu_shortname);
            }
        }
        return redirect(site_url() . 'tailieu/denghi/danhSachBanHanh');
    }
    
    private function soSanhFileTaiLieu($tai_lieu_id, $fileNewName, $fileNewId, &$templateProcessor){
        $filenamenew = FCPATH . $fileNewName;
        $TLVersion = $this->Mod_tailieuversion->getTLVersionPrevious($fileNewId, $tai_lieu_id);
        $file_before_edit = $this->Mod_file->getFileById($TLVersion[0]["file_id"]);
        $filenameold = FCPATH . $file_before_edit->file_path;
        $cf = $this->read_docx($filenamenew);// Current Version
        $of = $this->read_docx($filenameold);// Old Version
        $diffs = $this->htmlDiff($of, $cf);
        $templateProcessor->cloneRow('oldsua', count($diffs));
        foreach ($diffs as $index => $diff){
            $templateProcessor->setValue('stt#'.($index+1), str_pad(($index+1), 2, '0', STR_PAD_LEFT));
            $templateProcessor->setValue('oldsua#'.($index+1), $diff['d']);
            $templateProcessor->setValue('newsua#'.($index+1), $diff['ins']);
            $templateProcessor->setValue('dateedit#'.($index+1), date("d-m-Y"));
        }
    }
            
    function danhSachPhanPhoi(){
        $trangthai = $this->input->get("trangthai");
        if(!$this->checkPerByIdDeNghi(5))
            redirect(site_url());
        $quytrinh = 6;
        $condition = array(
            "dn.quy_trinh_id" => 6, // get danh sach da ban hanh buoc 6
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $dsPhanPhoi = $this->Mod_tldenghi->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $trangthai);
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('dsPhanPhoi', $dsPhanPhoi);
        $this->parser->assign('permissionClass', $this->arrayPermission());
        $this->parser->parse('denghi/danhsachphanphoi');
    }
    
    function formPhanPhoi(){
        $item = $this->input->get();
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item['id']);
        $ket_qua = $this->Mod_quytrinhketqua->ketQuaByQuyTrinh(7);
        $file_soan_thao = $this->Mod_file->getFileById($de_nghi->de_nghi_file_id);
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($de_nghi->tai_lieu_id);
        $fileCauTruc = $this->Mod_cautructailieu->getCauTrucTLByTangTL($tai_lieu->tang_tai_lieu_id);
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($de_nghi->tai_lieu_id);
        $file_ban_hanh_id = -1;
        $file_ban_hanh = NULL;
        $file_ban_hanh_id = $fileTLVer[0]["file_id"];
        if($file_ban_hanh_id > -1){
            $file_ban_hanh = $this->Mod_file->getFileById($file_ban_hanh_id);
            $file_ban_hanh_goc_path = _UPLOADS_PATH . 'tailieu/' . $file_ban_hanh->file_id . '_origin' . '.' . end(explode('.', _UPLOADS_PATH . $file_ban_hanh->file_path));
        }
        $quyen = FALSE;
        $user_phongban = $this->getUserQuyTrinh($quyen);
        $this->parser->assign('user_phongban', $user_phongban);
        $this->parser->assign('validate', $item['validate']);
        $phongBans = $this->danhSachPhongBan();
        $this->parser->assign('tai_lieu', $tai_lieu);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('file_soan_thao', $file_soan_thao);
        //$this->parser->assign('file_ban_hanh', $file_ban_hanh);
        //$this->parser->assign('file_ban_hanh_goc_path', $file_ban_hanh_goc_path);
        $this->parser->assign('de_nghi', $de_nghi);
        $this->parser->assign('ket_qua', $ket_qua);
        $this->parser->assign('phongBans', $phongBans);
        $this->parser->parse('denghi/formphanphoi');
    }
    
    function createPhanPhoi(){
        $item = $this->input->post();
        $validate = TRUE;
        if(!$item["phong_ban"]){
            $validate = FALSE;
            return redirect(site_url() . 'tailieu/denghi/formphanphoi?validate=1&id=' . $item["de_nghi_id"]);
        }
        $de_nghi = $this->Mod_tldenghi->getDeNghiById($item["de_nghi_id"]);
        $denghi = array(
            "de_nghi_name" => $item["de_nghi_name"],
            "de_nghi_content" => $item["de_nghi_content"],
            "loai_de_nghi_id" => $de_nghi->loai_de_nghi_id,
            "tai_lieu_id" => $de_nghi->tai_lieu_id,
            "ngay_thuc_hien" => date('Y-m-d'),
            "quy_trinh_id" => 7,
            "de_nghi_before" => $item["de_nghi_id"],
            "de_nghi_file_id" => $de_nghi->de_nghi_file_id,
            "de_nghi_user_send" => $this->session->userdata("ssAdminId"),
            "status" => 1
        );
        $this->Mod_tldenghi->_create($denghi);
        $dataPhanPhoi = array();
        if(count($item["phong_ban"]) > 0){
            foreach ($item["phong_ban"] as $pb){
                $dataPhanPhoi[] = array(
                    'tai_lieu_id' => $de_nghi->tai_lieu_id,
                    'phong_ban_id' => $pb,
                    'user_id' => NULL,
                    'date_create' => date("Y-m-d"),
                    'status' => 0
                );
            }
        }
        if(count($item["nhan_vien"]) > 0){
            $dataPhanPhoi[] = array(
                'tai_lieu_id' => $de_nghi->tai_lieu_id,
                'user_id' => implode(",", $item["nhan_vien"]),
                'phong_ban_id' => NULL,
                'date_create' => date("Y-m-d"),
                'status' => 0
            );
        }
        $this->db->update("tl_phong_ban", array('status' => 1), array('tai_lieu_id' => $de_nghi->tai_lieu_id));
        $this->db->insert_batch("tl_phong_ban", $dataPhanPhoi);
        return redirect(site_url() . 'tailieu/denghi/danhSachPhanPhoi');
    }
    
    function taiLieuDetail() {
        $item = $this->input->get();
        $tai_lieu = $this->Mod_tailieu->getTaiLieuById($item["id"]);
        $phongbans = $this->Mod_tlphongban->getPhongBanByTLId($item["id"]);
        $all_phongBan = $this->danhSachPhongBan();
        $phongBan_info = array_column($all_phongBan, 'name', 'id');//column
        $de_nghis = $this->Mod_tldenghi->getDeNghiByTaiLieu($item["id"]);
        $last_de_nghi = $this->Mod_tldenghi->getLastQuytrinhByTL($item["id"]);
        $file_soan_thao_id = $last_de_nghi[0]["de_nghi_file_id"];
        $lastDeNghiQuyTrinh = $last_de_nghi[0]["quy_trinh_id"];
        $fileCauTruc = $this->Mod_cautructailieu->getCauTrucTLByTangTL($tai_lieu->tang_tai_lieu_id);
        $all_quytrinh = $this->Mod_loaitailieuquytrinh->getDenghiByTailieu($tai_lieu->loai_tai_lieu_id);
        $fileTLVer = $this->Mod_tailieuversion->getFileIdTLVersion($item["id"]);
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
        $file_ban_hanh_id = -1;
        $file_ban_hanh = NULL;
        $file_ban_hanh_id = $fileTLVer[0]["file_id"];
        if($file_ban_hanh_id > -1){
            $file_ban_hanh = $this->Mod_file->getFileById($file_ban_hanh_id);
            $file_ban_hanh_goc_path = _UPLOADS_PATH . 'tailieu/' . $file_ban_hanh->file_id . '_origin' . '.' . end(explode('.', $file_ban_hanh->file_path));
        }
        $file_soan_thao = NULL;
        if($file_soan_thao_id != NULL){
            $file_soan_thao = $this->Mod_file->getFileById($file_soan_thao_id);
        }
        $thuhoi = false;
        $permission = $this->permarr;
        if($permission && isset($permission[_TL_THUHOI]) && ($permission[_TL_THUHOI]['write'] || $permission[_TL_THUHOI]['master']) && $lastDeNghiQuyTrinh >= 6){
            $thuhoi = true;
        }
        $userInfo = $this->danhSachNhanVien();
        $this->parser->assign('taiLieuVersion', $taiLieuVersion);
        $this->parser->assign('thuhoi', $thuhoi);
        $this->parser->assign('phongbans', $phongbans);
        $this->parser->assign('phongbans_id', array_column($phongbans, "phong_ban_id"));
        $this->parser->assign('all_phongBan', $all_phongBan);
        $this->parser->assign('phongBan_info', $phongBan_info);
        $this->parser->assign('userInfo', $userInfo);
        $this->parser->assign('tai_lieu', $tai_lieu);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('fileCauTruc', $fileCauTruc);
        $this->parser->assign('file_ban_hanh', $file_ban_hanh);
        $this->parser->assign('file_ban_hanh_goc_path', $file_ban_hanh_goc_path);
        $this->parser->assign('file_soan_thao', $file_soan_thao);
        $this->parser->assign('quytrinh_denghi', $quytrinh_denghi);
        $this->parser->parse('denghi/tailieudetail');   
    }
}
