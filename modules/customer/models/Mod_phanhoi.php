<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_phanhoi extends MY_Model implements NenmauInterface {
    private $hopdong_active_status = 1;
    private $ketqua_approve_accept = 1;
    
    function insert_phanhoi($values){
        $data_insert = array(
            'ketqua_id' => trim($values['ketqua_id']),
            'phanhoi_content' => trim($values['phanhoi_content']),
            'contact_id' => trim($values['contact_id']),
            'phanhoi_date' => trim($values['phanhoi_date'])
        );
        $this->db->insert('nm_ketqua_phanhoi', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function update_phanhoi_file($datas){
        $data_update = array(
            'phanhoi_file' => trim($datas['phanhoi_file'])
        );
        $this->db->where('phanhoi_id', $datas['phanhoi_id']);
        $this->db->update('nm_ketqua_phanhoi', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    function get_phanhoi_ketqua($ketqua_id, $user_id){
        $this->db->select('ph.phanhoi_content, ph.phanhoi_date, ph.phanhoi_approve, ph.phanhoi_approve_note');
        $this->db->from('nm_ketqua_phanhoi ph');
        $this->db->where('ph.ketqua_id', $ketqua_id);
        $this->db->where('ph.contact_id', $user_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
/* End of file */