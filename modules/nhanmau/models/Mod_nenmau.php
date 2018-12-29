<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_nenmau extends MY_Model implements NenmauInterface {
    
    // Get nenmau info
    public function getNenmauById($nenmau_id){
        $this->db->select('nm.nenmau_name');
        $this->db->from('mau_nenmau nm');
        $this->db->where('nm.nenmau_id', $nenmau_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get DVT nhanmau
    public function getDvtNhanmau(){
        $this->db->select('dvt.donvitinh_id, dvt.donvitinh_name');
        $this->db->from('mau_donvitinh dvt');
        $this->db->where('dvt.donvitinh_status', 1);
        $this->db->where('dvt.donvitinh_type', 0);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }

    // Get all nenmau
    public function getAllNenmau() {
        $this->db->select('nm.*');
        $this->db->from('mau_nenmau nm');
        $this->db->where('nm.nenmau_status', 1);
        $this->db->order_by('nm.nenmau_name', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get all chitieu
    public function getAllChitieu() {
        $this->db->select('ct.*');
        $this->db->from('mau_chitieu ct');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get all phuongphap
    public function getAllPhuongphap() {
        $this->db->select('pp.*');
        $this->db->from('mau_phuongphap pp');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get all phuongphap
    public function getAllPtn() {
        $this->db->select('ptn.*');
        $this->db->from('phongthinghiem ptn');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get all phuongphap
    public function getAllChat() {
        $this->db->select('chat.*');
        $this->db->from('mau_chat chat');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get all thitruong
    public function getAllThitruong() {
        $this->db->select('tt.*');
        $this->db->from('mau_thitruong tt');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get all dichvu
    public function getAllDichvu() {
        $this->db->select('dv.*');
        $this->db->from('nm_dichvu dv');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list package by package_code
    function getPackages($package_code, $nenmau_id = false){
        $this->db->select('dg.*');
        $this->db->from('mau_dongia dg');
        $this->db->join('mau_nenmau nm', 'dg.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_chitieu ct', 'dg.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'dg.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_kythuat kt', 'dg.kythuat_id = kt.kythuat_id');
        $this->db->like('package_code', $package_code);
        if($nenmau_id){
            $this->db->where('dg.nenmau_id', $nenmau_id);
        }
        $this->db->where('nm.nenmau_status', 1);
        $this->db->where('dg.package_status', 1);
        $this->db->where('ct.chitieu_status', 1);
        $this->db->where('pp.phuongphap_status', 1);
        $this->db->where('kt.kythuat_status', 1);
        $this->db->order_by('dg.package_code', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get package by all element
    function getPackage($nenmau_id, $chitieu_id, $phuongphap_id, $kythuat_id, $ptn_id){
        $this->db->select('dg.*, pp.phuongphap_name, ct.chitieu_name');
        $this->db->from('mau_dongia dg');
        $this->db->join('mau_phuongphap pp', 'dg.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_chitieu ct', 'dg.chitieu_id = ct.chitieu_id');
        $this->db->where('dg.nenmau_id', $nenmau_id);
        $this->db->where('dg.chitieu_id', $chitieu_id);
        $this->db->where('dg.phuongphap_id', $phuongphap_id);
        $this->db->where('dg.kythuat_id', $kythuat_id);
        $this->db->where('dg.donvi_id', $ptn_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list nenmau by package
    public function getNenmauPackage() {
        $this->db->select('nm.nenmau_id AS id, nm.nenmau_name AS name');
        $this->db->from('mau_nenmau nm');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list chitieu by package
    function getChitieuPackage($nenmau_id){
        $this->db->select('dg.chitieu_id AS id, ct.chitieu_name AS name');
        $this->db->from('mau_dongia dg');
        $this->db->join('mau_chitieu ct', 'dg.chitieu_id = ct.chitieu_id');
        $this->db->where('dg.nenmau_id', $nenmau_id);
        $this->db->group_by(array('dg.nenmau_id', 'dg.chitieu_id', 'ct.chitieu_name'));
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list phuongphap by package
    function getPhuongphapPackage($nenmau_id, $chitieu_id){
        $this->db->select('pp.phuongphap_id AS id, pp.phuongphap_name AS name');
        $this->db->from('mau_dongia dg');
        $this->db->join('mau_phuongphap pp', 'dg.phuongphap_id = pp.phuongphap_id');
        $this->db->where('dg.nenmau_id', $nenmau_id);
        $this->db->where('dg.chitieu_id', $chitieu_id);
        $this->db->where('pp.phuongphap_status', 1);
        $this->db->group_by(array('dg.nenmau_id', 'dg.chitieu_id', 'pp.phuongphap_id', 'pp.phuongphap_name'));
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list kythuat by package
    function getKythuatPackage($nenmau_id, $chitieu_id, $phuongphap_id){
        $this->db->select('dg.kythuat_id AS id, kt.kythuat_name AS name');
        $this->db->from('mau_dongia dg');
        $this->db->join('mau_kythuat kt', 'dg.kythuat_id = kt.kythuat_id');
        $this->db->where('dg.nenmau_id', $nenmau_id);
        $this->db->where('dg.chitieu_id', $chitieu_id);
        $this->db->where('dg.phuongphap_id', $phuongphap_id);
        $this->db->where('kt.kythuat_status', 1);
        $this->db->group_by(array('dg.nenmau_id', 'dg.chitieu_id', 'dg.phuongphap_id', 'dg.kythuat_id', 'kt.kythuat_name'));
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list ptn by package
    function getPtnPackage($nenmau_id, $chitieu_id, $phuongphap_id, $kythuat_id){
        $this->db->select('dg.donvi_id AS id');
        $this->db->from('mau_dongia dg');
        $this->db->where('dg.nenmau_id', $nenmau_id);
        $this->db->where('dg.chitieu_id', $chitieu_id);
        $this->db->where('dg.phuongphap_id', $phuongphap_id);
        $this->db->where('dg.kythuat_id', $kythuat_id);
        $this->db->group_by(array('dg.nenmau_id', 'dg.chitieu_id', 'dg.phuongphap_id', 'dg.kythuat_id', 'dg.donvi_id'));
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list chat by package
    function getChatPackage($dongia_id){
        $this->db->select('c.chat_id AS id, c.chat_name AS name, ctc.capacity, ctc.val_min, ctc.val_max');
        $this->db->from('mau_chitieu_chat ctc');
        $this->db->join('mau_dongia dg', 'ctc.dongia_id = dg.dongia_id');
        $this->db->join('mau_chat c', 'ctc.chat_id = c.chat_id');
        $this->db->where('dg.dongia_id', $dongia_id);
        $this->db->where('c.chat_status', 1);
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
	
	// Get list chat by chitieu
    function getChatChitieu($chitieu_id, $nenmau_id){
        $this->db->select('c.chat_id AS id, c.chat_name AS name, ctc.capacity, ctc.val_min, ctc.val_max');
        $this->db->from('mau_chitieu_chat ctc');
        $this->db->join('mau_dongia dg', 'ctc.dongia_id = dg.dongia_id');
        $this->db->join('mau_chat c', 'ctc.chat_id = c.chat_id');
        $this->db->where('dg.chitieu_id', $chitieu_id);
        $this->db->where('dg.nenmau_id', $nenmau_id);
        $this->db->where('c.chat_status', 1);
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get list chat by chitieu
    function getChatByChitieu($chitieu_id){
        $this->db->select('ctc.*');
        $this->db->from('mau_chitieu_chat ctc');
        $this->db->join('mau_dongia dg', 'ctc.dongia_id = dg.dongia_id');
        $this->db->where('dg.chitieu_id', $chitieu_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    // Get time save mau
    function getTimeSave($nenmau_id, $chitieu_id){
        $this->db->select('nmct.*');
        $this->db->from('mau_nenmau_chitieu nmct');
        $this->db->where('nmct.nenmau_id', $nenmau_id);
        $this->db->where('nmct.chitieu_id', $chitieu_id);
        $query = $this->db->get();
        $result = $query->row_array();
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
    
    // Get congnhan chat
    function get_congnhan_chat($chat_id){
        $this->db->select('cn.*');
        $this->db->from('mau_congnhan_chat cnc');
        $this->db->join('mau_congnhan cn', 'cnc.congnhan_id = cn.congnhan_id');
        $this->db->where('cnc.chat_id', $chat_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_chatgia($bo_id){
        $this->db->select('cg.*');
        $this->db->from('mau_chatgia cg');
        $this->db->where('cg.bo_id', $bo_id);
        $this->db->where('cg.khachhang_id IS NULL');
        $this->db->order_by('cg.gia_order', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_chat_by_id_list($chat_id_list){
        $this->db->select('c.*');
        $this->db->from('mau_chat c');
        $this->db->where_in('c.chat_id', $chat_id_list);
        $this->db->order_by('c.chat_name', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_dongia_khachhang($dongia_id, $khachhang_id){
        $this->db->select('dgkh.*');
        $this->db->from('mau_dongia_khachhang dgkh');
        $this->db->where('dgkh.dongia_id', $dongia_id);
        $this->db->where('dgkh.khachhang_id', $khachhang_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_chatgia_khachhang($bo_id, $khachhang_id){
        $this->db->select('cg.*');
        $this->db->from('mau_chatgia cg');
        $this->db->where('cg.bo_id', $bo_id);
        $this->db->where('cg.khachhang_id', $khachhang_id);
        $this->db->order_by('cg.gia_order', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_list_chat_info($list_chat_id){
        $this->db->select('c.*');
        $this->db->from('mau_chat c');
        $this->db->where_in('c.chat_id', $list_chat_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
/* End of file */