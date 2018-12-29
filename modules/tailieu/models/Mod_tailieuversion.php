<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_tailieuversion extends MY_Model implements TailieuInterface {
    function _load(){
        $this->db->select("*");
        $this->db->from('tl_tai_lieu_version');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getTLVersionPrevious($fileId, $taiLieuId){
        $this->db->select("file_id");
        $this->db->from('tl_tai_lieu_version');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->where("file_id <", $fileId);// loai de nghi them tai lieu
        $this->db->order_by('file_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getFileIdTLVersion($taiLieuId){
        $this->db->select("file_id");
        $this->db->from('tl_tai_lieu_version');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->order_by('file_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getTLIdByFileId($fileId){
        $this->db->select("tai_lieu_id");
        $this->db->from('tl_tai_lieu_version');
        $this->db->where("file_id", $fileId);
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    function getTLVerByTLId($taiLieuId){
        $this->db->select("*");
        $this->db->from('tl_tai_lieu_version');
        $this->db->where("tai_lieu_id", $taiLieuId);
        $this->db->order_by('file_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
