<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_nenmau extends MY_Model implements NenmauInterface {
    // Get congnhan chat
    function get_congnhan_chat($chat_id){
        $this->db->select('cn.*, f.file_name');
        $this->db->from('mau_congnhan_chat cnc');
        $this->db->join('mau_congnhan cn', 'cnc.congnhan_id = cn.congnhan_id');
        $this->db->join('nm_file f', 'cn.congnhan_logo = f.file_id', 'left');
        $this->db->where('cnc.chat_id', $chat_id);
        $this->db->where('cn.congnhan_status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    // Get all thitruong of chat
    function getThiTruongChat($chat_id, $thitruong_id = false){
        $this->db->select('ttc.*');
        $this->db->from('mau_thitruong_chat ttc');
        $this->db->join('mau_thitruong tt', 'ttc.thitruong_id = tt.thitruong_id');
        $this->db->where('ttc.chat_id', $chat_id);
        if($thitruong_id){
            $this->db->where('ttc.thitruong_id', $thitruong_id);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    // Get phuongphap info
    function getPhuongphapInfo($phuongphap_id){
        $this->db->select('pp.*');
        $this->db->from('mau_phuongphap pp');
        $this->db->where('pp.phuongphap_id', $phuongphap_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
/* End of file */