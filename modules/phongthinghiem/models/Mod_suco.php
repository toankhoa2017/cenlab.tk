<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_suco extends MY_Model implements NenmauInterface {
    /*
     * Count all hopdong
     */
    function count_all_suco($ptn_id = false){
        $this->db->select('sc.suco_id');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->join('nm_hopdong_suco_chitiet scct', 'sc.suco_id = scct.suco_id');
        $this->db->join('nm_mauchitiet mct', 'scct.mauchitiet_id = mct.mauchitiet_id');
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $this->db->group_by('sc.suco_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_suco($search, $ptn_id = false){
        $this->db->select('sc.suco_id');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->join('nm_hopdong_suco_chitiet scct', 'sc.suco_id = scct.suco_id');
        $this->db->join('nm_mauchitiet mct', 'scct.mauchitiet_id = mct.mauchitiet_id');
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        // Filter by search
        if($search != ''){
            $this->db->like('sc.suco_content', $search);
        }
        $this->db->group_by('sc.suco_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list hopdong
     */
    function get_list_suco($search, $sort_column, $sort_direction, $start, $length, $ptn_id = false){
        $this->db->select('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate, sc.suco_approve, m.mau_code, m.mau_name');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->join('nm_hopdong_suco_chitiet scct', 'sc.suco_id = scct.suco_id');
        $this->db->join('nm_mauchitiet mct', 'scct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        // Filter by search
        if($search != ''){
            $this->db->like('sc.suco_content', $search);
        }
        $this->db->group_by('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate, sc.suco_approve, m.mau_code, m.mau_name');
        // Sort list mau
        switch ($sort_column){
            case 0:
                $this->db->order_by('sc.suco_id', $sort_direction);
                break;
            default :
                $this->db->order_by('sc.suco_id', $sort_direction);
                break;
        }
        if($start >= 0 && $length >= 0){
            $this->db->limit($length, $start);
        }
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    
    function insert_suco($values){
        $data_insert = array(
            'suco_type' => trim($values['suco_type']),
            'suco_content' => trim($values['suco_content']),
            'hopdong_id' => trim($values['hopdong_id']),
            'nhansu_id' => trim($values['nhansu_id']),
            'suco_createdate' => trim($values['suco_createdate'])
        );
        $this->db->insert('nm_hopdong_suco', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function insert_suco_chitiet($array_values){
        $array_insert = array();
        foreach ($array_values as $values){
            $array_insert[] = array(
                'suco_id' => trim($values['suco_id']),
                'mauchitiet_id' => trim($values['mauchitiet_id']),
                'list_chat' => trim($values['list_chat'])
            );
        }
        return $this->db->insert_batch('nm_hopdong_suco_chitiet', $array_insert);
    }
    function update_suco($datas){
        $data_update = array(
            'suco_approve' => trim($datas['suco_approve']),
            'user_approve_id' => trim($datas['user_approve_id']),
            'approve_note' => trim($datas['approve_note']),
            'approve_date' => trim($datas['approve_date'])
        );
        $this->db->where('suco_id', $datas['suco_id']);
        $this->db->update('nm_hopdong_suco', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    function get_suco_chitieu($suco_id){
        $this->db->select('ct.chitieu_name, mct.mauchitiet_id, scct.list_chat');
        $this->db->from('nm_hopdong_suco_chitiet scct');
        $this->db->join('nm_mauchitiet mct', 'scct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->where('scct.suco_id', $suco_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_suco($suco_id){
        $this->db->select('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate, sc.suco_approve');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->where('sc.suco_id', $suco_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_suco_hopdong($hopdong_id){
        $this->db->select('sc.suco_id, sc.suco_content, sc.nhansu_id, sc.suco_createdate, sc.suco_approve');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->where('sc.suco_type', 2);
        $this->db->where('sc.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_suco_mau($mau_id){
        $this->db->select('sc.suco_content, sc.nhansu_id, sc.suco_createdate, sc.suco_approve, scct.mauchitiet_id, scct.list_chat');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->join('nm_hopdong_suco_chitiet scct', 'sc.suco_id = scct.suco_id');
        $this->db->join('nm_mauchitiet mct', 'scct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->where('sc.suco_type', 2);
        $this->db->where('mct.mau_id', $mau_id);
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_suco_mauchitiet($mauchitiet_id){
        $this->db->select('sc.suco_content, sc.nhansu_id, sc.suco_createdate, sc.suco_approve, scct.mauchitiet_id, scct.list_chat');
        $this->db->from('nm_hopdong_suco sc');
        $this->db->join('nm_hopdong_suco_chitiet scct', 'sc.suco_id = scct.suco_id');
        $this->db->where('sc.suco_type', 2);
        $this->db->where('scct.mauchitiet_id', $mauchitiet_id);
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
/* End of file */