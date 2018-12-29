<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_quytrinh extends MY_Model implements TailieuInterface {
    function _load() {
        $this->db->select("*");
        $this->db->from('tl_quy_trinh');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
}