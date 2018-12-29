<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_tangtailieu extends MY_Model implements TailieuInterface{
    function _load() {
        $this->db->select("*");
        $this->db->from('tl_tang_tai_lieu as tangtl');
        $this->db->join('tl_loai_tai_lieu as loaitl', 'tangtl.loai_tai_lieu = loaitl.loai_tai_lieu_id');
        $this->db->where('tangtl.status', 1);
        $this->db->where('tangtl.status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getTangTLCauTrucTL() {
        $this->db->select("*");
        $this->db->from('tl_tang_tai_lieu as tl');
        $this->db->join('tl_cau_truc_tai_lieu as ct', 'tl.tang_tai_lieu_id = ct.tang_tai_lieu_id');
        $this->db->where('tl.status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getTangTLByLoaiTLId($loaiTLId, $level){
        $this->db->select("*");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('loai_tai_lieu', $loaiTLId);
        $this->db->where('level', $level);
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getTangTLByParentId($parentId, $level){
        $this->db->select("*");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('parent_id', $parentId);
        $this->db->where('level', $level);
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getTangTLIdByParentId($parentId){
        $this->db->select("tang_tai_lieu_id");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('parent_id', $parentId);
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getTangTLIdByParentIds($parentIds){
        $tangTLs = array();
        foreach ($parentIds as $parentId){
            $this->db->select("tang_tai_lieu_id");
            $this->db->from('tl_tang_tai_lieu');
            $this->db->where('parent_id', $parentId);
            $this->db->where('status', 1);
            $query = $this->db->get();
            $result = $query->result_array();
            $query->free_result();
            $tangTLs = array_merge($tangTLs, array_column($result, 'tang_tai_lieu_id'));
        }
        return $tangTLs;
    }
    
    function getMaxLevel(){
        $this->db->select("Max(level) as max_level");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    
    function getCodeTangTL($tangTLId){
        $this->db->select("tai_lieu_code");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('tang_tai_lieu_id', $tangTLId);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    
    function getTangTLById($tangTLId){
        $this->db->select("*");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('tang_tai_lieu_id', $tangTLId);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getAllCodeTangTL(){
        $this->db->select("tai_lieu_code");
        $this->db->from('tl_tang_tai_lieu as tangtl');
        $this->db->where('tangtl.status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getTangTLByCode($TLCode){
        $taiLieuCodes = explode(".", $TLCode);
        array_pop($taiLieuCodes);
        $tangTLCode = implode(".", $taiLieuCodes);
        print_r($tangTLCode);
        $this->db->select("*");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('tai_lieu_code', $tangTLCode);
        $this->db->where('status', 1);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
}