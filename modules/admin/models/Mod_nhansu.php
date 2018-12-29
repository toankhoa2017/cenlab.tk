<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');

class Mod_nhansu extends MY_Model implements NhansuInterface {
    function _gets($id = FALSE) {
        $this->db->select('ns.*, hd.donvi_id, hd.chucvu_id');
        $this->db->from('nhansu AS ns');
        $this->db->join('hopdong AS hd', 'ns.hopdong_id = hd.hopdong_id');
        if ($id) $this->db->where('ns.account_id', $id);
        $query = $this->db->get();
        $result = ($id) ? $query->row_array() : $query->result_array();
        //echo $this->db->last_query();
	return ($result) ? $result : FALSE;
    }    
    function _getsin($ids = array()) {
        $this->db->select('ns.*, dv.donvi_id, dv.donvi_ten, cv.chucvu_id, cv.chucvu_ten');
        $this->db->from('nhansu AS ns');
        $this->db->join('hopdong AS hd', 'ns.hopdong_id = hd.hopdong_id');
        $this->db->join('donvi AS dv', 'hd.donvi_id = dv.donvi_id');
        $this->db->join('chucvu AS cv', 'hd.chucvu_id = cv.chucvu_id');
        $this->db->where('ns.nhansu_status', 1);
        $this->db->where_in('ns.account_id', $ids);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
	return ($result) ? $result : FALSE;
    }    
    function doimatkhau($data) {
        $this->db->where('nhansu_id', $data['nhansu_id']);
        return $this->db->update('nhansu', $data);
    }
	function get_nhansu_info($nhansu_id){
		$this->db->select('ns.*');
        $this->db->from('nhansu ns');
        $this->db->where('ns.nhansu_id', $nhansu_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
	}
	function update_sign($data) {
        $this->db->where('nhansu_id', $data['nhansu_id']);
        return $this->db->update('nhansu', $data);
    }
}

/* End of file */