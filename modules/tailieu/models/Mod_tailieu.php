<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_tailieu extends MY_Model implements TailieuInterface {
    function _load(){
        $this->db->select("*");
        $this->db->from('tl_tai_lieu');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function _create($data){
        $this->db->insert('tl_tai_lieu', $data);
        $insert_id = $this->db->insert_id();
        
        return  $insert_id;
    }
    
    function _update($data, $id){
        
    }
    function getTaiLieuById($id){
        $this->db->select("tl.*, tangtl.tang_tai_lieu_ten, loaitl.loai_tai_lieu_name");
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_de_nghi as dn', 'dn.tai_lieu_id = tl.tai_lieu_id');
        $this->db->join('tl_tang_tai_lieu as tangtl', 'tl.tang_tai_lieu_id = tangtl.tang_tai_lieu_id');
        $this->db->join('tl_loai_tai_lieu as loaitl', 'tl.loai_tai_lieu_id = loaitl.loai_tai_lieu_id');
        $this->db->where('tl.tai_lieu_id', $id);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
            
    function loadTaiLieuBanHanh(){
        $taiLieuBanHanh = 1;
        $this->db->select("tl.*, tangtl.tang_tai_lieu_ten, loaitl.loai_tai_lieu_name");
        $this->db->from('tl_tai_lieu as tl');
        //$this->db->join('tl_de_nghi as dn', 'dn.tai_lieu_id = tl.tai_lieu_id');
        $this->db->join('tl_tang_tai_lieu as tangtl', 'tl.tang_tai_lieu_id = tangtl.tang_tai_lieu_id');
        $this->db->join('tl_loai_tai_lieu as loaitl', 'tl.loai_tai_lieu_id = loaitl.loai_tai_lieu_id');
        $this->db->where("tai_lieu_ban_hanh", $taiLieuBanHanh);
        $this->db->where("tl.status", 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function loadTaiLieuNoiBo(){
        $taiLieuBanHanh = 1;
        $this->db->select("tl.*, tangtl.tang_tai_lieu_ten");
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_de_nghi as dn', 'dn.de_nghi_id = tl.de_nghi_id');
        $this->db->join('tl_tang_tai_lieu as tangtl', 'tl.tang_tai_lieu_id = tangtl.tang_tai_lieu_id');
        $this->db->where("tai_lieu_ban_hanh", $taiLieuBanHanh);
        $this->db->where("loai_tai_lieu_id", 1);
        $this->db->where("dn.quy_trinh_id", 7);// chỉ load tài đã phân phối khi sửa
        $this->db->where("tl.status", 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function loadTaiLieuBenNgoai(){
        $taiLieuBanHanh = 1;
        $this->db->select("tl.*, tangtl.tang_tai_lieu_ten"); 
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_de_nghi as dn', 'dn.de_nghi_id = tl.de_nghi_id');
        $this->db->join('tl_tang_tai_lieu as tangtl', 'tl.tang_tai_lieu_id = tangtl.tang_tai_lieu_id');
        $this->db->where("tai_lieu_ban_hanh", $taiLieuBanHanh);
        $this->db->where("loai_tai_lieu_id", 2);
        $this->db->where("dn.quy_trinh_id", 7);//chỉ load tài đã phân phối khi sửa
        $this->db->where("tl.status", 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    } 
    
    function loadTaiLieuPhanPhoi($phongBanId, $user_id){
        $taiLieuBanHanh = 1;
        $this->db->select("tl.*, tangtl.tang_tai_lieu_ten, tangtl.editDate, loaitl.loai_tai_lieu_name");
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_tang_tai_lieu as tangtl', 'tl.tang_tai_lieu_id = tangtl.tang_tai_lieu_id');
        $this->db->join('tl_loai_tai_lieu as loaitl', 'tl.loai_tai_lieu_id = loaitl.loai_tai_lieu_id');
        $this->db->join('tl_phong_ban as phongtl', 'tl.tai_lieu_id = phongtl.tai_lieu_id');
        $this->db->where("tai_lieu_ban_hanh", $taiLieuBanHanh);
        $this->db->group_start();
        $this->db->where("phongtl.phong_ban_id", $phongBanId);
        $this->db->or_where("phongtl.user_id", $user_id);
        $this->db->group_end();
        $this->db->where("tl.status", 1);
        $this->db->where("phongtl.status", 0); // active
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function loadAllTaiLieuPhanPhoi(){
        $taiLieuBanHanh = 1;
        $this->db->select("tl.*, tangtl.tang_tai_lieu_ten, tangtl.editDate, loaitl.loai_tai_lieu_name");
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_tang_tai_lieu as tangtl', 'tl.tang_tai_lieu_id = tangtl.tang_tai_lieu_id');
        $this->db->join('tl_loai_tai_lieu as loaitl', 'tl.loai_tai_lieu_id = loaitl.loai_tai_lieu_id');
        $this->db->where("tai_lieu_ban_hanh", $taiLieuBanHanh);
        $this->db->where("tl.status", 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function loadTaiLieuThuHoi(){
        $taiLieuBanHanh = 2; // tai lieu thu hoi
        $this->db->select("tl.*, tangtl.tang_tai_lieu_ten, loaitl.loai_tai_lieu_name");
        $this->db->from('tl_tai_lieu as tl');
        //$this->db->join('tl_de_nghi as dn', 'dn.tai_lieu_id = tl.tai_lieu_id');
        $this->db->join('tl_tang_tai_lieu as tangtl', 'tl.tang_tai_lieu_id = tangtl.tang_tai_lieu_id');
        $this->db->join('tl_loai_tai_lieu as loaitl', 'tl.loai_tai_lieu_id = loaitl.loai_tai_lieu_id');
        $this->db->where("tai_lieu_ban_hanh", $taiLieuBanHanh);
        $this->db->or_where("tl.status", 0); // for all tai lieu deactive
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function loadTaiLieuVersion(){
        $this->db->select("tl.*, ver.file_id");
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_tai_lieu_version as ver', 'tl.tai_lieu_id = ver.tai_lieu_id AND (ver.tai_lieu_lan_ban_hanh < tl.tai_lieu_lan_ban_hanh OR (ver.tai_lieu_lan_ban_hanh = tl.tai_lieu_lan_ban_hanh AND ver.tai_lieu_lan_sua_doi < tl.tai_lieu_lan_sua_doi))');
        $this->db->where("tai_lieu_ban_hanh", 1);
        $this->db->where("tl.status", 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
            
    function getAllDeNghiCurrent() {
        $this->db->select("tl.tai_lieu_id, qt.quy_trinh_id, qt.quy_trinh_name");
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_de_nghi as dn', 'tl.de_nghi_id = dn.de_nghi_id');
        $this->db->join('tl_quy_trinh as qt', 'qt.quy_trinh_id = dn.quy_trinh_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getTangTLId($tai_lieu_id){
        $this->db->select("tang_tai_lieu_id");
        $this->db->from('tl_tai_lieu');
        $this->db->where('tai_lieu_id', $tai_lieu_id);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    
    function maxMaCodeTL($tangTLId){
        $this->db->select("tai_lieu_code, tai_lieu_id");
        $this->db->from('tl_tai_lieu');
        $this->db->where('tang_tai_lieu_id', $tangTLId);
        $this->db->where('tai_lieu_code !=', "");
        $this->db->order_by('tai_lieu_id', 'DESC');
        $this->db->limit(1); 
        //echo $this->db->get_compiled_select(); exit(1);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    
    function updateTLCode($tai_lieu_id, $taiLieuCode){
        $this->db->update('tl_tai_lieu', array("tai_lieu_code" => $taiLieuCode), array("tai_lieu_id" => $tai_lieu_id));
    }
    
    function getTLByTangTL($tangTLId){
        $this->db->select("*");
        $this->db->from('tl_tai_lieu');
        $this->db->where('tang_tai_lieu_id', $tangTLId);
        $this->db->order_by('tai_lieu_id', 'DESC'); 
        //echo $this->db->get_compiled_select(); exit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function loadTLBHByTangTL($tangTLId){
        $taiLieuBanHanh = 1;
        $this->db->select("*");
        $this->db->from('tl_tai_lieu as tl');
        $this->db->join('tl_de_nghi as dn', 'dn.de_nghi_id = tl.de_nghi_id');
        $this->db->where('tl.tang_tai_lieu_id', $tangTLId);
        $this->db->where("tl.tai_lieu_ban_hanh", $taiLieuBanHanh);
        $this->db->where("dn.quy_trinh_id", 7);// chỉ load tài đã phân phối khi sửa
        $this->db->where("tl.status", 1);
        $this->db->order_by('tl.tai_lieu_id', 'DESC'); 
        //echo $this->db->get_compiled_select(); exit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getAllShortName(){
        $this->db->select("tai_lieu_shortname");
        $this->db->from('tl_tai_lieu');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
