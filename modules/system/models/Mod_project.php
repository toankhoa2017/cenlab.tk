<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('AccountInterface');
class Mod_project extends MY_Model implements AccountInterface {
    function _gets() {
        $this->db->select('*');
        $this->db->from('_projects');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
	return ($result) ? $result : FALSE;        
    }
}
/* End of file */