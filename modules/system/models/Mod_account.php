<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('AccountInterface');
class Mod_account extends MY_Model implements AccountInterface {
    function _getSalt($id) {
        $this->db->select('ACC_ID, ACC_SALT');
        $this->db->from('_account');
        $this->db->or_where('ACC_EMAIL', $id);
        $this->db->or_where('ACC_PHONE', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
		return $result;
    }
    function _login($login) {
        $this->db->select('*');
        $this->db->from('_account');
        $this->db->where('ACC_ID', $login['id']);
        $this->db->where('ACC_PWD', md5(md5($login['password']).$login['salt']));
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
		return $result;
    }
}
/* End of file */