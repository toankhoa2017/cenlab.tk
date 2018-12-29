<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_file extends MY_Model implements NenmauInterface {
    function _load() {
        $this->db->select("*");
        $this->db->from('nm_file');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function getFileById($id){
        $this->db->select("*");
        $this->db->from('nm_file');
        $this->db->where('file_id', $id);
        $query = $this->db->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
    
    function _create($data){
        $this->db->insert('nm_file', $data);
        $insert_id = $this->db->insert_id();
        
        return  $insert_id;
    }
}