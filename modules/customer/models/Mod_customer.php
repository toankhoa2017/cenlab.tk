<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('KhachhangInterface');
class Mod_customer extends MY_Model implements KhachhangInterface {
    function _login($login) {
        $this->db->select('*');
        $this->db->from('contact');
        $this->db->where('contact_phone', $login['user']);
        $this->db->where('contact_password', $login['password']);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
		return $result;
    }
	function _getCongtyofContact($id) {
        $this->db->select('congty_id id');
        $this->db->from('congty_contact');
        $this->db->where('contact_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
	}
}
/* End of file */