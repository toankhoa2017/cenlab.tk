<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_upload extends MY_Model implements NenmauInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insertFile($values){
        $data_file = array(
            'nhansu_id' => trim($values['user_id']),
            'file_name' => trim($values['file_name']),
            'file_size' => trim($values['file_size']),
            'file_type' => trim($values['file_type']),
            'file_exts' => trim($values['file_exts']),
            'create_date' => date("Y-m-d H:i:s"),
            'update_date' => date("Y-m-d H:i:s")
        );
        $this->db->insert('nm_file', $data_file);
        $file_id = $this->db->insert_id();
        return ($file_id?$file_id:FALSE);
    }
    public function deleteFile($file_id, $user_id){
        $result = FALSE;
        if($file_id && is_numeric($file_id) && $user_id && is_numeric($user_id)){
            //  Delete file in profile
            //$this->db->where('file_id', $file_id);
            //$result = $this->db->delete('profile_file');
            //  Delete file of user
            //if($result){
                $this->db->where('file_id', $file_id);
                $this->db->where('nhansu_id', $user_id);
                $result = $this->db->delete('nm_file');
            //}
        }
        return ($result) ? $this->db->affected_rows() > 0 : FALSE;
    }
    public function countFileOfUser($user_id){
        $this->db->where('nhansu_id', $user_id);
        $this->db->from('nm_file');
        $result = $this->db->count_all_results();
        return ($result) ? $result : 0;
    }
}