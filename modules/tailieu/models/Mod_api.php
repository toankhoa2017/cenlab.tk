<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_api extends MY_Model implements TailieuInterface {
    function _getTang() {
        $this->db->select("*");
        $this->db->from('tl_tang_tai_lieu');
        $this->db->where('status', 1);
        $this->db->where('parent_id', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}