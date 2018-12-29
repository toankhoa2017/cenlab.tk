<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Tailieu_notification extends MY_Model {
    var $connTL;
    function __construct() {
        parent::__construct();
        $this->connTL = $this->load->database('tailieu', TRUE);
    }
    function _load() {
        $this->connTL->select("*");
        $this->connTL->from('tl_loai_tai_lieu');
        $query = $this->connTL->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getDeNghiByLoaiQuyTrinh($QuyTrinh, $condition, $userCondition, $trangthai = null, $option = null){
        if($trangthai == "chuaduyet")
            $trangthaiQuery = " and dn.de_nghi_id = tailieu.de_nghi_id";
        $this->connTL->select("dn.*, loaidn.loai_de_nghi_name, tailieu.tai_lieu_name, tailieu.de_nghi_id as denghi_tai_lieu");
        $this->connTL->from('tl_de_nghi as dn');
        $this->connTL->join('tl_quy_trinh_ket_qua as qtkq', 'qtkq.quy_trinh_id = dn.quy_trinh_id and qtkq.de_nghi_ket_qua_id = dn.de_nghi_ket_qua_id', 'left');
        $this->connTL->group_start();
        $this->connTL->group_start();
        $this->connTL->where($condition);
        if($QuyTrinh != 1 && $QuyTrinh != 3){  
            $this->connTL->where("qtkq.next_step", 1);
        }
        $this->connTL->group_end();
        if($option){
            $this->connTL->or_group_start();
            $this->connTL->where($option);
            if($option["tailieu.loai_tai_lieu_id"] == 2){
                $this->connTL->where("qtkq.next_step", 1);
            }else{
                $this->connTL->where("qtkq.next_step", 2);// for quay lai
            }
            $this->connTL->group_end();
        }
        $this->connTL->group_end();
        $this->connTL->where($userCondition);
        //$this->db->where('dn.status', 1);
        $this->connTL->join('tl_loai_de_nghi as loaidn', 'dn.loai_de_nghi_id = loaidn.loai_de_nghi_id');
        $this->connTL->join('tl_tai_lieu as tailieu', 'dn.tai_lieu_id = tailieu.tai_lieu_id' . $trangthaiQuery);
        //echo $this->db->get_compiled_select(); exit(1);
        $this->connTL->distinct();
        $query = $this->connTL->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function filterAndNumberArray($danhSach){
        $result = array_filter($danhSach, function ($item) use ($like) {
            if ($item["denghi_tai_lieu"] == $item["de_nghi_id"]) {
                return true;
            }
            return false;
        });
        return count($result);
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
    function CountTaiLieu() {
        $tlduyetdenghi = $this->filterAndNumberArray($this->getListDuyetDenghi());
        $tlsoanthao = $this->filterAndNumberArray($this->getListSoanThao());
        $tlxemxetsoanthao = $this->filterAndNumberArray($this->getListXemXetSoanThao());
        $tlpheduyet = $this->filterAndNumberArray($this->getListPheDuyet());
        $tlbanhanh = $this->filterAndNumberArray($this->getListBanHanh());
        $tlphanphoi = $this->filterAndNumberArray($this->getListPhanPhoi());
        return $tlduyetdenghi + $tlsoanthao + $tlxemxetsoanthao + $tlpheduyet + $tlbanhanh + $tlphanphoi;
    }
}
