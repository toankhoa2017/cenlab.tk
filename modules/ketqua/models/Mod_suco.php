<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_suco extends MY_Model implements NenmauInterface {
    function insert_suco($values){
        $data_insert = array(
            'suco_type' => trim($values['suco_type']),
            'suco_content' => trim($values['suco_content']),
            'hopdong_id' => trim($values['hopdong_id']),
            'nhansu_id' => trim($values['nhansu_id']),
            'suco_createdate' => trim($values['suco_createdate']),
            'suco_approve' => 1,
            'user_approve_id' => trim($values['nhansu_id']),
            'approve_date' => date("Y-m-d H:i:s")
        );
        $this->db->insert('nm_hopdong_suco', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
}
/* End of file */