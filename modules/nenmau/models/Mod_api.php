<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_api extends MY_Model implements NenmauInterface {
    
    var $table_phuongphap = "mau_phuongphap";
    
    function phuongphap_add($data){
        $kiemtra = $this->db->select("*")->from($this->table_phuongphap)->where($data)->get();
        if($kiemtra->num_rows()>0){
            return false;
        }else{
            return $this->db->insert($this->table_phuongphap,$data);
        }
    }
    
    function phuongphap_update($data, $dieukien){
        $this->db->where($dieukien);
        return $this->db->update($this->table_phuongphap, $data);
    }
            
    function danhsach_nenmau($key) {
        if ($key == "") {
            $dieukien = "a.nenmau_status='1'";
            return $this->db->select('*')->from("mau_nenmau as a")->where($dieukien)->order_by("nenmau_idparent", "desc")->get()->result();
        } else {
            $dieukien = "a.nenmau_status='1'";
            return $this->db->select('*')->from("mau_nenmau as a")->where($dieukien)->like('nenmau_name', $key)->order_by("nenmau_idparent", "asc")->get()->result();
        }
    }
}

/* End of file */