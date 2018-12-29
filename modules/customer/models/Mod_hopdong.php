<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_hopdong extends MY_Model implements NenmauInterface {
    private $hopdong_mau_status = 3;
    private $hopdong_active_status = 1;
    /*
     * Get hopdong by id
     */
    function get_hopdong_id($hopdong_id){
        $this->db->select('hd.*, tt.thitruong_name, hdd.hopdong_approve as hopdong_approve, hdd.duyet_content, shd.suahopdong_approve');
        $this->db->from('nm_hopdong hd');
        $this->db->join('mau_thitruong tt', 'hd.thitruong_id = tt.thitruong_id', 'left');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->join('nm_suahopdong shd', 'hd.hopdong_id = shd.hopdong_id','left');
        $this->db->where('hd.hopdong_id', $hopdong_id);
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_mau_list_chitieu($list_chitieu){
        $this->db->select('m.*, nm.nenmau_name');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->where_in('mct.mauchitiet_id', $list_chitieu);
        $this->db->group_by('m.mau_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_chitieu_list_id($list_chitieu){
        $this->db->select('mct.*, ct.chitieu_name, dvt.donvitinh_name, pp.phuongphap_name, dg.dongia_id');
        $this->db->from('nm_mauchitiet mct');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_donvitinh dvt', 'dg.donvitinh_id = dvt.donvitinh_id');
        $this->db->where_in('mct.mauchitiet_id', $list_chitieu);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    /*
     * Get list chat by list id
     */
    function get_list_chat_id($list_chat_id, $dongia_id){
        $this->db->select('c.*, ctc.capacity, ctc.val_min, ctc.val_max');
        $this->db->from('mau_chat c');
        $this->db->join('mau_chitieu_chat ctc', 'c.chat_id = ctc.chat_id');
        $this->db->where('ctc.dongia_id', $dongia_id);
        $this->db->where_in('c.chat_id', $list_chat_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
/* End of file */