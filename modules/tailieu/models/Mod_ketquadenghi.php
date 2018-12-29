<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_ketquadenghi extends MY_Controller implements TailieuInterface {
    function _load() {
        $this->db->select("*");
        $this->db->from('tl_de_nghi_ket_qua');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
}
