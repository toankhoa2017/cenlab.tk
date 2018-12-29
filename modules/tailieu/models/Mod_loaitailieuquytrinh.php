<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');
class Mod_loaitailieuquytrinh extends MY_Model implements TailieuInterface {
    function _load(){
        $this->db->select("*");
        $this->db->from('tl_loai_tai_lieu_quy_trinh');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getDenghiByTailieu($tailieu_id){
        $this->db->select("ltl.*, qt.quy_trinh_name");
        $this->db->from('tl_loai_tai_lieu_quy_trinh as ltl');
        $this->db->join('tl_quy_trinh as qt', 'ltl.quy_trinh_id = qt.quy_trinh_id');
        $this->db->where('loai_tai_lieu_id', $tailieu_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
