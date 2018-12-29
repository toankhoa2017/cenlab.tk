<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_tldenghi extends MY_Model implements TailieuInterface {
    function _load(){
        $this->db->select("*");
        $this->db->from('tl_de_nghi');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getDeNghiByLoaiQuyTrinh($QuyTrinh, $condition, $userCondition, $trangthai = null, $option = null){
        if($trangthai == "chuaduyet")
            $trangthaiQuery = " and dn.de_nghi_id = tailieu.de_nghi_id";
        $this->db->select("dn.*, loaidn.loai_de_nghi_name, tailieu.tai_lieu_name, tailieu.de_nghi_id as denghi_tai_lieu");
        $this->db->from('tl_de_nghi as dn');
        $this->db->join('tl_quy_trinh_ket_qua as qtkq', 'qtkq.quy_trinh_id = dn.quy_trinh_id and qtkq.de_nghi_ket_qua_id = dn.de_nghi_ket_qua_id', 'left');
        $this->db->group_start();
        $this->db->group_start();
        $this->db->where($condition);
        if($QuyTrinh != 1 && $QuyTrinh != 3){  
            $this->db->where("qtkq.next_step", 1);
        }
        $this->db->group_end();
        if($option){
            $this->db->or_group_start();
            $this->db->where($option);
            if($option["tailieu.loai_tai_lieu_id"] == 2){
                $this->db->where("qtkq.next_step", 1);
            }else{
                $this->db->where("qtkq.next_step", 2);// for quay lai
            }
            $this->db->group_end();
        }
        $this->db->group_end();
        $this->db->where($userCondition);
        //$this->db->where('dn.status', 1);
        $this->db->join('tl_loai_de_nghi as loaidn', 'dn.loai_de_nghi_id = loaidn.loai_de_nghi_id');
        $this->db->join('tl_tai_lieu as tailieu', 'dn.tai_lieu_id = tailieu.tai_lieu_id' . $trangthaiQuery);
        //echo $this->db->get_compiled_select(); exit(1);
        $this->db->distinct();
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function _create($data, $banHanhMoi = NULL){
        $this->db->insert('tl_de_nghi', $data);
        $taiLieuIdReturn = $data["tai_lieu_id"];
        $update_data = array();
        $insert_id = $this->db->insert_id();
        if($data["quy_trinh_id"] != 6) { // xem xet de ban hanh tai lieu
            $update_data = array("de_nghi_id" => $insert_id);
        }else if($data["de_nghi_ket_qua_id"] == 7){ //chap nhan de nghi
            if($data["loai_de_nghi_id"] == 1){// them moi tai lieu
                $taiLieuCode = $this->genNextCodeTL($data);
                $banhanh_num = 1;
                $suadoi_num = 0;
                $update_data = array(
                    "de_nghi_id" => $insert_id, 
                    "tai_lieu_ban_hanh" => 1, 
                    "tai_lieu_lan_ban_hanh" => 1, 
                    "ngay_ban_hanh" => date("Y-m-d H:i:s"),
                    "user_ban_hanh" => $data["de_nghi_user_send"],
                    "tai_lieu_code" => $taiLieuCode);
            }else{ // sua tai lieu hien co
                $taiLieu = $this->getTailieuById($data["tai_lieu_id"]);
                if($taiLieu->tai_lieu_lan_sua_doi == 3 || $banHanhMoi == "on"){ // ban hành tài liệu mới sau 3 lần sửa đổi hoac force it
                    $banhanh_num = $taiLieu->tai_lieu_lan_ban_hanh + 1;
                    $suadoi_num = 0;
                    $tai_lieu = array(
                        "tai_lieu_name" => $taiLieu->tai_lieu_name,
                        "loai_tai_lieu_id" => $taiLieu->loai_tai_lieu_id,
                        "tai_lieu_code" => $taiLieu->tai_lieu_code, //$this->getNextCodeTL($taiLieu->tai_lieu_code),
                        "ngay_ban_hanh" => date("Y-m-d H:i:s"),
                        "tai_lieu_ban_hanh" => 1,
                        "tai_lieu_lan_ban_hanh" => $banhanh_num,
                        "tai_lieu_lan_sua_doi" => 0,
                        "tang_tai_lieu_id" => $taiLieu->tang_tai_lieu_id,
                        "de_nghi_id" => $insert_id,
                        "status" => 1
                    ); 
                    $id_tailieu = $this->Mod_tailieu->_create($tai_lieu);
                    $taiLieuIdReturn = $id_tailieu;
                    $maxId = $this->getMaxQuyTrinhOldTL($data["tai_lieu_id"])[0]['de_nghi_id'];
                    $this->db->update('tl_de_nghi', array('tai_lieu_id' => $id_tailieu), array('tai_lieu_id' => $data["tai_lieu_id"], 'de_nghi_id >=' => $maxId));// update all quy trinh cho tài liệu mới
                    $update_data = array(
                        "tai_lieu_ban_hanh" => 2,
                        "user_thu_hoi" => $data["de_nghi_user_send"],
                        "ngay_thu_hoi" => date("Y-m-d"),
                        "de_nghi_id" => $this->getLastQuytrinhByTL($data["tai_lieu_id"])[0]["de_nghi_id"]);// for thu hoi tai lieu
                }else{
                    $suadoi_num = $taiLieu->tai_lieu_lan_sua_doi + 1;
                    $banhanh_num = $taiLieu->tai_lieu_lan_ban_hanh;
                    $update_data = array(
                        "de_nghi_id" => $insert_id, 
                        "tai_lieu_lan_ban_hanh" => $banhanh_num, 
                        "tai_lieu_lan_sua_doi" => $suadoi_num,
                        "user_ban_hanh" => $data["de_nghi_user_send"],
                        "ngay_ban_hanh" => date("Y-m-d H:i:s"));
                }
            }
            //create version cua tai lieu
            $taiLieuVersion = array(
                "file_id" => $data["de_nghi_file_id"],
                "tai_lieu_id" => $taiLieuIdReturn,
                "tai_lieu_lan_ban_hanh" => $banhanh_num,
                "tai_lieu_lan_sua_doi" => $suadoi_num,
                "user_ban_hanh" => $data["de_nghi_user_send"],
                "user_thu_hoi" => $data["de_nghi_user_send"],
                "status" => 1
            );
            $this->db->insert('tl_tai_lieu_version', $taiLieuVersion);
        }else{
            $update_data = array("de_nghi_id" => $insert_id);
        }
        $this->db->update('tl_tai_lieu', $update_data, array('tai_lieu_id' => $data["tai_lieu_id"]));
        return $taiLieuIdReturn;
    }
    
    function getMaxQuyTrinhOldTL($taiLieuId){
        $this->db->select("de_nghi_id");
        $this->db->from('tl_de_nghi');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->where("de_nghi_before", NULL);
        $this->db->order_by('de_nghi_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
            
    function getLastQuytrinhByTL($taiLieuId){
        $this->db->select("dn.de_nghi_id, dn.de_nghi_file_id, dn.quy_trinh_id");
        $this->db->from('tl_de_nghi as dn');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->order_by('dn.de_nghi_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getLastKetQuaByTL($taiLieuId){
        $this->db->select("dn.de_nghi_ket_qua_id, kq.de_nghi_ket_qua_name");
        $this->db->from('tl_de_nghi as dn');
        $this->db->join('tl_de_nghi_ket_qua as kq', 'dn.de_nghi_ket_qua_id = kq.de_nghi_ket_qua_id');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->order_by('dn.de_nghi_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getLastDenghiOfThemTL($taiLieuId){
        $this->db->select("dn.de_nghi_id, dn.de_nghi_file_id");
        $this->db->from('tl_de_nghi as dn');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->where("loai_de_nghi_id", 1);// loai de nghi them tai lieu
        $this->db->order_by('dn.de_nghi_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
            
    function genNextCodeTL($data){
        $taiLieuCode = "";
        $tangTLId = $this->Mod_tailieu->getTangTLId($data['tai_lieu_id']);
        $TLCode = $this->Mod_tailieu->maxMaCodeTL($tangTLId->tang_tai_lieu_id);
        if(($TLCode->tai_lieu_id != $data['tai_lieu_id']) && $data["de_nghi_ket_qua_id"] == 7){
            if($TLCode->tai_lieu_code != ""){
                $taiLieuCode = $this->getNextCodeTL($TLCode->tai_lieu_code);
            }else{
                $tangTLCode = $this->Mod_tangtailieu->getCodeTangTL($tangTLId->tang_tai_lieu_id);
                $taiLieuCode = $tangTLCode->tai_lieu_code . ".1";
            }
        }
        return $taiLieuCode;
    }
            
    function getNextCodeTL($codeTL){
        $taiLieuCodes = explode(".", $codeTL);
        $numberCode = intval(array_pop($taiLieuCodes)) + 1;
        $taiLieuCode = implode(".", $taiLieuCodes) . "." . $numberCode;
        return $taiLieuCode;
    }
            
    function getTailieuById($id){
        $this->db->select("tai_lieu_lan_ban_hanh, tai_lieu_lan_sua_doi, tai_lieu_name, loai_tai_lieu_id, tang_tai_lieu_id, tai_lieu_code");
        $this->db->from('tl_tai_lieu');
        $this->db->where("tai_lieu_id", $id);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
            
    function getDeNghiById($id){
        $this->db->select("dn.*, tailieu.loai_tai_lieu_id, tailieu.tai_lieu_id");
        $this->db->from('tl_de_nghi as dn');
        $this->db->where("dn.de_nghi_id", $id);
        $this->db->join('tl_tai_lieu as tailieu', 'dn.tai_lieu_id = tailieu.tai_lieu_id');;
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    function getDeNghiByTaiLieu($tai_lieu_id) {
        $this->db->select("dn.*, qt.quy_trinh_name, qt.quy_trinh_id, dnkq.de_nghi_ket_qua_name");
        $this->db->from('tl_de_nghi as dn');
        $this->db->join('tl_quy_trinh as qt', 'dn.quy_trinh_id = qt.quy_trinh_id');
        $this->db->join('tl_de_nghi_ket_qua as dnkq', 'dnkq.de_nghi_ket_qua_id = dn.de_nghi_ket_qua_id', 'left');
        $this->db->where("tai_lieu_id", $tai_lieu_id);
        $this->db->order_by('dn.de_nghi_id', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getUserSend($tai_lieu_id, $quy_trinh_id){
        $this->db->select("de_nghi_user_send");
        $this->db->from('tl_de_nghi');
        $this->db->where("tai_lieu_id", $tai_lieu_id);
        $this->db->where("quy_trinh_id", $quy_trinh_id);
        $this->db->order_by('de_nghi_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getDenghiByTLId($TLId){
        $this->db->select("*");
        $this->db->from('tl_de_nghi');
        $this->db->where("tai_lieu_id", $TLId);
        $this->db->order_by('de_nghi_id', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getDuyetDeNghiTuChoi(){
        $this->db->select("tai_lieu_id");
        $this->db->from('tl_de_nghi');
        $this->db->where("quy_trinh_id", 2);// duyet de nghi
        $this->db->where("de_nghi_ket_qua_id", 2); // ket qua tu choi
        $this->db->distinct();
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getXemXetTuChoi(){
        $this->db->select("tai_lieu_id");
        $this->db->from('tl_de_nghi');
        $this->db->where("quy_trinh_id", 4);// xem xet soan thao
        $this->db->where("de_nghi_ket_qua_id", 4); // ket qua tu choi
        $this->db->distinct();
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getPheDuyetTuChoi(){
        $this->db->select("tai_lieu_id");
        $this->db->from('tl_de_nghi');
        $this->db->where("quy_trinh_id", 5);// phe duyet
        $this->db->where("de_nghi_ket_qua_id", 6); // ket qua tu choi
        $this->db->distinct();
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getBanHanhTuChoi(){
        $this->db->select("tai_lieu_id");
        $this->db->from('tl_de_nghi');
        $this->db->where("quy_trinh_id", 6);// phe duyet
        $this->db->where("de_nghi_ket_qua_id", 6); // ket qua tu choi
        $this->db->distinct();
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getListDuyetDenghi(){
        $quytrinh = 1;
        $condition = array(
            "dn.quy_trinh_id" => 1, // get danh sach da de nghi buoc 1
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $list_de_nghi = $this->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition);
        return $list_de_nghi;
    }
    function getListSoanThao(){
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
        $soan_thao = $this->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $option);
        return $soan_thao;
    }
    function getListXemXetSoanThao(){
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
        $xx_soan_thao = $this->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $option);
        return $xx_soan_thao;
    }
    function getListPheDuyet(){
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
        $dsPheDuyet = $this->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition, $option);
        return $dsPheDuyet;
    }
    function getListBanHanh(){
        $quytrinh = 5;
        $condition = array(
            "dn.quy_trinh_id" => 5, // get danh sach da da phe duyet buoc 5
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $dsBanHanh = $this->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition);
        return $dsBanHanh;
    }
    function getListPhanPhoi(){
        $quytrinh = 6;
        $condition = array(
            "dn.quy_trinh_id" => 6, // get danh sach da ban hanh buoc 6
        );
        $current_user = $this->session->userdata("ssAdminId");
        $userCondition = array(
            'de_nghi_user_receive' => trim($current_user)
        );
        $dsPhanPhoi = $this->getDeNghiByLoaiQuyTrinh($quytrinh, $condition, $userCondition);
        return $dsPhanPhoi;
    }
}
