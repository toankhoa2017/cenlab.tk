<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Tangtailieu extends ADMIN_Controller{
    private $privcheck;
    function __construct() {
        parent::__construct();
        $this->privcheck = $this->permarr[_TL_TANG];        

        $this->load->model('mod_tangtailieu');
        $this->load->model('mod_cautructailieu');
        $this->load->model('Mod_file');
        $this->load->model('Mod_loaitailieu');
        $this->load->model('Mod_tailieu');
        $this->load->model('Mod_tldenghi');
    }
    function index() {
        if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $tangtls = $this->mod_tangtailieu->_load();
        $loai_tai_lieu = $this->Mod_loaitailieu->_load();
        $tangTLFiles = array();
        $cautructl = $this->mod_cautructailieu->getCauTrucTL();
        foreach ($cautructl as $key => $cautruc){
            $tangTLFiles[$cautruc["tang_tai_lieu_id"]] = $cautruc;
        }
        $this->parser->assign('privcheck', $this->privcheck);
        $this->parser->assign('loai_tai_lieu', $loai_tai_lieu);
        $this->parser->assign('tangtls', $tangtls);
        $this->parser->assign('_UPLOADS_URL', _UPLOADS_URL);
        $this->parser->assign('_UPLOADS_PATH', _UPLOADS_PATH);
        $this->parser->assign('tangTLFiles', $tangTLFiles);
        $this->parser->parse('tangtailieu/listall');
    }
    function ajax_add_tangtl() {
        if (!$this->privcheck['write']) redirect(site_url() . 'admin/denied?w=write');
        $item = $this->input->post();
        if(!$item['ten-tang-tl'] || !$item['ma-tang-tl'] || !$item["loai-id"]){
            $resp = array( 
                "error_code" => "EMPTY",
                "error_mess" => "Bạn chưa nhập đầy đủ thông tin"
            );
            echo json_encode($resp);
            return;
        }
        $isExist = $this->checkUniqueCode($item["ma-tang-tl"]);
        if($isExist){
            $resp = array( 
                "error_code" => "EXIST",
                "error_mess" => "Mã tầng tài liệu đã tồn tại trong hệ thống"
            );
            echo json_encode($resp);
            return;
        }
        if($item["loai-id"] == 1){
            $config['upload_path'] = _UPLOADS_PATH . 'tailieu/';
            $config['allowed_types'] = 'doc|docx|pdf';
            $this->load->library('upload', $config);
            $file_id = 0;
            if ( ! $this->upload->do_upload('cau_truc_file'))
            {
                $error = array('error' => $this->upload->display_errors());
                $resp = array( 
                    "error_code" => "NO_FILE",
                    "error_mess" => "Bạn chưa chọn file cấu trúc"
                );
                echo json_encode($resp);
                return;
            }
            else
            {
                $data = array('upload_data' => $this->upload->data());
                $file_data = array(
                    "file_name" => $data["upload_data"]["file_name"],
                    "file_path" => 'tailieu/' . $data["upload_data"]["file_name"]
                );
                $file_id = $this->Mod_file->_create($file_data);
            }
        }
        $type_use = NULL;
        if($item["parent-id"] != 0){
            $tangTL = $this->mod_tangtailieu->getTangTLById($item["parent-id"]);
            $type_use = $tangTL[0]["type_use"];
        }
        $data = array(
          'tang_tai_lieu_ten' => $item["ten-tang-tl"],
          'tai_lieu_code' => $item["ma-tang-tl"],
          'parent_id' => $item["parent-id"],
          'loai_tai_lieu' => $item["loai-id"],
          'level' => $item["level"],
          'type_use' => ($type_use == 2) ? $type_use : (isset($item["type_use"]) ? $item["type_use"] : 0),  
          'status' => "1"  
        );
        $this->db->insert('tl_tang_tai_lieu', $data);
        $insert_id = $this->db->insert_id();
        if($item["loai-id"] == 1)
            $this->db->insert('tl_cau_truc_tai_lieu', array('tang_tai_lieu_id' => $insert_id, 'file_id' => $file_id));
        echo $insert_id;
    }
    
    function ajax_delete_tangtl() {
        if (!$this->privcheck['delete']) redirect(site_url() . 'admin/denied?w=delete');
        $item = $this->input->post();
        $tangTLId_update = $this->getAllTangTLChild($item["tang-id"]);
        $data_tangtl = array(
          'status' => 0,
        );
        $this->db->where_in('tang_tai_lieu_id', $tangTLId_update);
        $this->db->update('tl_tang_tai_lieu', $data_tangtl);
        $data_tl = array(
          'status' => 0,
          'ngay_thu_hoi' => date("Y-m-d")
        );
        $this->db->where_in('tang_tai_lieu_id', $tangTLId_update);
        $this->db->update('tl_tai_lieu', $data_tl);
        echo TRUE;
    }
    function getAllTangTLChild($TangId){
        $maxLevel = $this->mod_tangtailieu->getMaxLevel();
        $tangTLs = $this->mod_tangtailieu->getTangTLIdByParentId($TangId);
        $tangTLIds = array_column($tangTLs, "tang_tai_lieu_id");
        $TangTLId_arr = $tangTLIds;
        $TangTLId_arr[] = $TangId;
        for($i = 1; $i < $maxLevel->max_level; $i++){
            if(count($tangTLIds) > 0){
                $result = $this->mod_tangtailieu->getTangTLIdByParentIds($tangTLIds);
                $tangTLIds = $result;
                $TangTLId_arr = array_merge($TangTLId_arr, $result);
            }    
        }
        return array_unique($TangTLId_arr);
    } 
    function ajax_update_tangtl() {
        if (!$this->privcheck['update']) redirect(site_url() . 'admin/denied?w=update');
        $item = $this->input->post();
        $file_id = 0;
        if($item["edit_file_ct"] == "on" ){
            $config['upload_path'] = _UPLOADS_PATH . 'tailieu/';
            $config['allowed_types'] = 'doc|docx|pdf';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('cau_truc_file'))
            {
                $error = array('error' => $this->upload->display_errors());
                var_dump($error);
                exit(1);
            }
            else
            {
                $data = array('upload_data' => $this->upload->data());
                $file_data = array(
                    "file_name" => $data["upload_data"]["file_name"],
                    "file_path" => 'tailieu/' . $data["upload_data"]["file_name"]
                );
                $file_id = $this->Mod_file->_create($file_data);
                if($file_id > 0){
                    $this->db->update('tl_cau_truc_tai_lieu', array("file_id" => $file_id), array("tang_tai_lieu_id" => $item["tang-id"]));
                    $this->db->update('tl_tang_tai_lieu', array("editDate" => date("Y-m-d H:i:s")), array("tang_tai_lieu_id" => $item["tang-id"]));
                }    
            }
        } 
        if($item["edit_ma_tl"] != "on"){
            $data = array(
              'tang_tai_lieu_ten' => $item["ten-tang-tl"],
              'type_use' => isset($item["type_use"]) ? $item["type_use"] : 0,
            );
            $this->db->update('tl_tang_tai_lieu', $data, array("tang_tai_lieu_id" => $item["tang-id"]));
        }else{
            $data = array(
              'tang_tai_lieu_ten' => $item["ten-tang-tl"],
              'type_use' => isset($item["type_use"]) ? $item["type_use"] : 0,
              'tai_lieu_code' => isset($item["ma-tang-tl"]) ? $item["ma-tang-tl"] : ""  
            );
            $this->db->update('tl_tang_tai_lieu', $data, array("tang_tai_lieu_id" => $item["tang-id"]));
            $this->thuHoiTaiLieuInTang($item["tang-id"]);
            //$IdNewTang = $this->updateMaTangTL($item["tang-id"], $data);
            //$this->suaMaTaiLieu($item["tang-id"], $data, $IdNewTang);
        }    
        echo TRUE;
    }
    
    function thuHoiTaiLieuInTang($tangTLId_update){
        $data = array(
          'status' => 0,
          'ngay_thu_hoi' => date("Y-m-d")  
        );
        $this->db->where('tang_tai_lieu_id', $tangTLId_update);
        $this->db->update('tl_tai_lieu', $data);
    }
    
    function getTangTaiLieuByLoaiTL(){
        $loaiTLId = $this->input->get("loaiTLId", true);
        $level = $this->input->get("level", true);
        $data = $this->mod_tangtailieu->getTangTLByLoaiTLId($loaiTLId, $level);
        echo json_encode($data);
    }
    function getTangTaiLieuByParentId(){
        $parentId = $this->input->get("parentId", true);
        $level = $this->input->get("level", true);
        $data = $this->mod_tangtailieu->getTangTLByParentId($parentId, $level);
        echo json_encode($data);
    }
    function checkUniqueCode($TLCode){
        $AllCode = array_column($this->mod_tangtailieu->getAllCodeTangTL(), "tai_lieu_code");
        return in_array($TLCode, $AllCode);
    }
    function updateMaTangTL($idOldTangTL, $data){
        //$OldTangTL = $this->mod_tangtailieu->getTangTLById($idOldTangTL);
        $this->db->update('tl_tang_tai_lieu', $data, array("tang_tai_lieu_id" => $idOldTangTL));
        /*$OldTangTL = $OldTangTL[0];
        $OldTangTL["tai_lieu_code"] = $data["tai_lieu_code"];
        $OldTangTL["tang_tai_lieu_ten"] = $data["tang_tai_lieu_ten"];
        $OldTangTL["type_use"] = $data["type_use"];
        unset($OldTangTL['tang_tai_lieu_id']);
        $this->db->insert('tl_tang_tai_lieu', $OldTangTL);
        $idNewTangTL = $this->db->insert_id();
        return $idNewTangTL;*/
    }
    function suaMaTaiLieu($idOldTangTL, $data, $IdNewTang){
        $taiLieus = $this->Mod_tailieu->getTLByTangTL($idOldTangTL);
        $maNewTangTL = $data["tai_lieu_code"];
        foreach ($taiLieus as $k => $taiLieu){
            $maTLParts = explode(".", $taiLieu["tai_lieu_code"]);
            $taiLieu["tai_lieu_code"] = $maNewTangTL . "." . end($maTLParts);
            $taiLieu["tang_tai_lieu_id"] = $IdNewTang;
            $data_tl = $taiLieu;
            unset($data_tl["tai_lieu_id"]);
            $idTLNew = $this->Mod_tailieu->_create($data_tl);
            $this->db->update('tl_tai_lieu', array("status" => 0), array("tai_lieu_id" => $taiLieu["tai_lieu_id"]));
            $deNghis = $this->Mod_tldenghi->getDenghiByTLId($taiLieu["tai_lieu_id"]);
            //$this->db->where_in("de_nghi_id", array_column($deNghis, "de_nghi_id"));
            //$this->db->update('tl_de_nghi', array("status" => 0));
            $deNghiBefore = NULL;
            foreach ($deNghis as $key => $deNghi){
                $this->db->update('tl_de_nghi', array("tai_lieu_id" => $idTLNew), array("de_nghi_id" => $deNghi["de_nghi_id"]));
            }
            if($key >3){ 
                $file_soan_thao = $this->Mod_file->getFileById($deNghi["de_nghi_file_id"]);
                $fileTLParts = explode(".", $file_soan_thao->file_path);
                $originFileName = $deNghi["de_nghi_file_id"] . "_origin" . "." . end($fileTLParts);
                $originFile = _UPLOADS_PATH . 'tailieu/' . $originFileName;
                $newFileName = uniqid() . "_" . time() . "." . end($fileTLParts);
                $newFilePath = _UPLOADS_PATH . 'tailieu/'. $newFileName;
                copy($originFile, $newFilePath); // copy new file from origin
                $dataNewFile = array(
                    "file_name" => $newFileName,
                    "file_path" => $newFilePath
                );
                $file_id = $this->Mod_file->_create($dataNewFile);
                $this->db->update('tl_de_nghi', array("de_nghi_file_id" => $file_id), array("de_nghi_id" => $deNghiBefore));
                copy($newFilePath, _UPLOADS_PATH . 'tailieu/' . $file_id . "_origin" . "." . end($fileTLParts)); // copy create new file origin base on new file
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($newFilePath);
                $templateProcessor->setValue('matl', $taiLieu["tai_lieu_code"]);
                $templateProcessor->setValue('ngaybh', date("d-m-Y", strtotime($taiLieu["ngay_ban_hanh"])));
                $templateProcessor->setValue('lanbanhanh', $taiLieu["tai_lieu_lan_ban_hanh"]);
                $templateProcessor->saveAs($newFilePath);
            }
            $this->db->update('tl_tai_lieu', array("de_nghi_id" => $deNghiBefore), array("tai_lieu_id" => $idTLNew));
        }
    }
}
