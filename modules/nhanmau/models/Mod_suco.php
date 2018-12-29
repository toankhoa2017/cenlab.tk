<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_suco extends MY_Model implements NenmauInterface {
    function get_suco_hopdong($hopdong_id){
        $this->db->select('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->where('sc.suco_type', 2);
        $this->db->where('sc.suco_approve', 1);
        $this->db->where('sc.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_suco_hopdong_mau($hopdong_id){
        $this->db->select('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate, m.mau_code, m.mau_name');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->join('nm_hopdong_suco_chitiet scct', 'sc.suco_id = scct.suco_id');
        $this->db->join('nm_mauchitiet mct', 'scct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->where('sc.suco_type', 2);
        $this->db->where('sc.suco_approve', 1);
        $this->db->where('sc.hopdong_id', $hopdong_id);
        $this->db->group_by('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate, m.mau_code, m.mau_name');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_suco_hopdong_khachhang($hopdong_id){
        $this->db->select('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->where('sc.suco_type', 1);
        $this->db->where('sc.suco_approve', 1);
        $this->db->where('sc.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_suco_chitieu($suco_id){
        $this->db->select('ct.chitieu_name, mct.mauchitiet_id, scct.list_chat');
        $this->db->from('nm_hopdong_suco_chitiet scct');
        $this->db->join('nm_mauchitiet mct', 'scct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->where('scct.suco_id', $suco_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
/* End of file */