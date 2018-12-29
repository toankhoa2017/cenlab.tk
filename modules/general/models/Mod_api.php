<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');

class Mod_api extends MY_Model implements TailieuInterface {

    function get_file($file_id) {
        $dulieu = $this->db->select("*")->from("file")->where("file_id", $file_id)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }
    
    function get_forder($id_file){
        $dulieu = $this->db->select("*")->from("file_forder as a, file_type as b , file as c")->where("c.file_id='".$id_file."' and b.ftype_id=c.ftype_id and b.file_forder_id=a.file_forder_id")->get()->result();
        return $dulieu[0]->file_forder_path;
    }
    function _themthumuc($forder_name, $forder_path){
	$dulieu = $this->db->from('file_forder')->where(array('file_forder_name' => $forder_name, 'file_forder_path' => $forder_path))->get();
        if ($dulieu->num_rows() > 0) {
            return false;
        } else {
            return $this->db->insert('file_forder', array('file_forder_name' => $forder_name, 'file_forder_path' => $forder_path));
        }
    }

}
