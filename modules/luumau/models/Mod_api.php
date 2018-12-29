<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('LuumauInterface');

class Mod_api extends MY_Model implements LuumauInterface {
    
    var $table_luumau = "luumau";
    var $history = 'history';
    var $luuho = 'luuho';
    function luu_mau($input){
        $data_luumau = array();
        foreach ($input["luumau"] as $key => $khoiluong){
            if($khoiluong > 0){
                $data = array(
                    'mau_id' => $input['mau_id'],
                    'luumau_name' => $input['luumau_name'],
                    'nhansu_id' => $input['nhansu_id'],
                    'luumau_status' => '1',
                    'luumau_loai' => $key,
                    'luumau_khoiluong' => $khoiluong,
                    'kho_id' => NULL,
                    'luumau_ngayvao' => date('Y-m-d H:i:s'),
                    'luumau_ngayra' => date('Y-m-d H:i:s'),
                    'luumau_goi' => 0
                );
                $this->db->insert($this->table_luumau, $data);
                $luumau_id = $this->db->insert_id();
                $history = array(
                    'history_date' => date('Y-m-d H:i:s'),
                    'history_action' => '1',
                    'history_khoiluong' => $khoiluong,
                    'luumau_id' => $luumau_id,
                    'kho_id' => NULL,
                    'user_action' => $input['nhansu_id'],
                    'user_request' => NULL
                );
                $this->db->insert($this->history, $history);
                //$data_luumau[] = $data;
            }    
        }
        return 1;
    }
    
    function getMaxLuumauMau($mau_id){
        $this->db->select("*");
        $this->db->from($this->table_luumau);
        $this->db->where("mau_id", $mau_id);
        $this->db->order_by('luumau_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
}

/* End of file */