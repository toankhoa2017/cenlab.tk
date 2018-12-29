<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');

class Mod_restful extends MY_Model implements NhansuInterface {
    function _gets($id = FALSE) {
        $this->db->select('ns.nhansu_code id, ns.nhansu_lastname lastname, ns.nhansu_firstname firstname, hd.donvi_id donvi, hd.chucvu_id chucvu');
        $this->db->from('nhansu AS ns');
        $this->db->join('hopdong AS hd', 'ns.hopdong_id = hd.hopdong_id');
        if ($id) $this->db->where('ns.nhansu_code', $id);
        $query = $this->db->get();
        $result = ($id) ? $query->row_array() : $query->result_array();
		return ($result) ? $result : FALSE;
    }
	function _getUsers($info) {
		$quyen = FALSE;
		if (isset($info['quyen'])) {
			switch ($info['quyen']) {
				case 'duyetdenghi':
					$quyen = 1;
					break;
				case 'soanthao':
					$quyen = 6;
					break;
				case 'xemxetbanthao':
					$quyen = 2;
					break;
				case 'pheduyet':
					$quyen = 3;
					break;
				case 'banhanh':
					$quyen = 4;
					break;
				case 'phanphoi':
					$quyen = 5;
					break;
			}
		}
        $this->db->select('nhansu_id id, nhansu_lastname lastname, nhansu_firstname firstname, donvi_ten donvi');
        $this->db->from('quyen_tailieu');
        if ($quyen) $this->db->where('quyen_id', $quyen);
        if ($info['tang']) $this->db->where('tang_id', $info['tang']);
        
        $this->db->distinct();
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
	}
     function _getQuyens($id) {
        $this->db->select('q.quyen_id, q.quyen_name');
        $this->db->from('quyen AS q');
        $this->db->join('nhansu_quyen AS ns', 'q.quyen_id = ns.quyen_id');
        $this->db->where('ns.nhansu_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
    }
   function _getinDonvi($donvi) {
        $this->db->select('ns.nhansu_code id, ns.nhansu_lastname lastname, ns.nhansu_firstname firstname');
        $this->db->from('nhansu AS ns');
        $this->db->join('hopdong AS hd', 'ns.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.donvi_id', $donvi);
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
    }
    function _getDuyetinDonvi($donvi) {
        $this->db->select('ns.nhansu_code id, ns.nhansu_lastname lastname, ns.nhansu_firstname firstname');
        $this->db->from('nhansu AS ns');
        $this->db->join('hopdong AS hd', 'ns.hopdong_id = hd.hopdong_id');
        $this->db->join('nhansu_quyen AS q', 'ns.nhansu_id = q.nhansu_id');
        $this->db->where('hd.donvi_id', $donvi);
        $this->db->where('q.quyen_id', '1');
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
    }
    function _getXemxetinDonvi($donvi) {
        $this->db->select('ns.nhansu_code id, ns.nhansu_lastname lastname, ns.nhansu_firstname firstname');
        $this->db->from('nhansu AS ns');
        $this->db->join('hopdong AS hd', 'ns.hopdong_id = hd.hopdong_id');
        $this->db->join('nhansu_quyen AS q', 'ns.nhansu_id = q.nhansu_id');
        $this->db->where('hd.donvi_id', $donvi);
        $this->db->where('q.quyen_id', '2');
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
    }
    function _getQuyen($quyen) {
        switch ($quyen) {
            case 'pheduyet':
                $quyen_id = 3;
                break;
            case 'banhanh':
                $quyen_id = 4;
                break;
            case 'phanphoi':
                $quyen_id = 5;
                break;
        }
        $this->db->select('ns.nhansu_code id, ns.nhansu_lastname lastname, ns.nhansu_firstname firstname');
        $this->db->from('nhansu AS ns');
        $this->db->join('nhansu_quyen AS q', 'ns.nhansu_id = q.nhansu_id');
        $this->db->where('q.quyen_id', $quyen_id);
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
    }
    function _getPermission($id) {
        $this->db->select('q.quyen_id, q.quyen_name');
        $this->db->from('quyen AS q');
        $this->db->join('nhansu_quyen AS ns', 'q.quyen_id = ns.quyen_id');
        $this->db->where('ns.nhansu_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
    }
    function _getNhanvien() {
        $this->db->select('nhansu_id id, nhansu_lastname lastname, nhansu_firstname firstname');
        $this->db->from('nhansu');
        $this->db->where('nhansu_status', 1);
        $query = $this->db->get(); 
        $result = $query->result_array();
		return ($result) ? $result : FALSE;
    }
    function _getDonvi() {
        $this->db->select('donvi_id id, donvi_idparent parent, donvi_ten name');
        $this->db->from('donvi');
        $this->db->where('donvi_status', 1);
        $query = $this->db->get();
        $result = $query->result_array(); 
		return ($result) ? $result : FALSE;
    } 
    function _getPhongthinghiem() {
        $this->db->select('donvi_id id, donvi_idparent parent, donvi_ten name');
        $this->db->from('donvi');
        $this->db->where('donvi_type', 2); 
        $query = $this->db->get(); 
        $result = $query->result_array(); 
		return ($result) ? $result : FALSE;
    }
}

/* End of file */