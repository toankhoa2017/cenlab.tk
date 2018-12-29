<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_cautructailieu extends MY_Model implements TailieuInterface {
    function getCauTrucTLByTangTL($id) {
        $this->db->select("ct.tang_tai_lieu_id, f.*");
        $this->db->from('tl_cau_truc_tai_lieu as ct');
        $this->db->join('file as f', 'ct.file_id = f.file_id');
        $this->db->where('tang_tai_lieu_id', $id); 
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    
    function getCauTrucTL() {
        $this->db->select("ct.tang_tai_lieu_id, f.*");
        $this->db->from('tl_cau_truc_tai_lieu as ct');
        $this->db->join('file as f', 'ct.file_id = f.file_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
