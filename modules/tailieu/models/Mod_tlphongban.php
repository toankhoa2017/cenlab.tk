<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_tlphongban extends MY_Model implements TailieuInterface {
    function _load(){
        $this->db->select("*");
        $this->db->from('tl_phong_ban');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getPhongBanByTLId($taiLieuId){
        $this->db->select("*");
        $this->db->from('tl_phong_ban');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->where("status", 0); // active
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
