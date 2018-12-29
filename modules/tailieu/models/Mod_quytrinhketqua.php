<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_quytrinhketqua extends MY_Model implements TailieuInterface {
    function _load(){
        $this->db->select("*");
        $this->db->from('tl_quy_trinh_ket_qua');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function ketQuaByQuyTrinh($QuyTrinh){
        $this->db->select('qtkq.de_nghi_ket_qua_id, dnkq.de_nghi_ket_qua_name');
        $this->db->from('tl_quy_trinh_ket_qua as qtkq');
        $this->db->join('tl_de_nghi_ket_qua as dnkq', 'dnkq.de_nghi_ket_qua_id = qtkq.de_nghi_ket_qua_id');
        $this->db->where("qtkq.quy_trinh_id", $QuyTrinh);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
