<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_ketqua extends MY_Model implements NenmauInterface {
    function insert_ketqua($values){
        $data_insert = array(
            'ketqua_note' => trim($values['ketqua_note']),
            'create_date' => trim($values['date_export']),
            'user_id' => trim($values['user_id'])
        );
        $this->db->insert('nm_ketqua', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function insert_ketqua_chitiet($array_values){
        foreach ($array_values as &$values){
            $values = array(
                'ketqua_id' => trim($values['ketqua_id']),
                'mauchitiet_id' => trim($values['mauchitiet_id'])
            );
        }
        $this->db->insert_batch('nm_ketqua_chitiet', $array_values);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    function insert_ketqua_duyet($values){
        $data_insert = array(
            'ketqua_id' => trim($values['ketqua_id']),
            'user_send' => trim($values['user_send']),
            'user_receive' => trim($values['user_receive']),
            'parent_duyet_id' => trim($values['parent_duyet_id'])
        );
        $this->db->insert('nm_ketqua_duyet', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function get_ketqua_chitieu($list_chitieu){
        $this->db->select('kqct.*');
        $this->db->from('nm_ketqua_chitiet kqct');
        $this->db->join('nm_ketqua kq', 'kqct.ketqua_id = kq.ketqua_id');
        $this->db->where_in('kq.ketqua_approve', array(0,1));
        $this->db->where_in('kqct.mauchitiet_id', $list_chitieu);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_ketqua_id($ketqua_id){
        $this->db->select('kq.*');
        $this->db->from('nm_ketqua kq');
        $this->db->where_in('kq.ketqua_id', $ketqua_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_ketqua_chitiet($ketqua_id){
        $this->db->select('kqct.*, mct.*, mkq.*');
        $this->db->from('nm_ketqua_chitiet kqct');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1', 'left');
        $this->db->where('kqct.ketqua_id', $ketqua_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_hopdong_ketqua($ketqua_id){
        $this->db->select('hd.hopdong_id');
        $this->db->from('nm_ketqua_chitiet kqct');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('kqct.ketqua_id', $ketqua_id);
        $this->db->group_by('m.hopdong_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_ketqua_hopdong($hopdong_id){
        $this->db->select('kq.ketqua_id, kq.ketqua_approve, kq.create_date, kq.user_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.hopdong_id', $hopdong_id);
        $this->db->group_by('kq.ketqua_id, kq.ketqua_approve, kq.create_date, kq.user_id');
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function duyet_ketqua($data_ketqua){
        $data_update = array(
            'duyet_result' => $data_ketqua['duyet_result'],
            'duyet_note' => $data_ketqua['duyet_note'],
            'update_date' => date("Y-m-d H:i:s")
        );
        $this->db->where('ketqua_id', $data_ketqua['ketqua_id']);
        $this->db->where('user_receive', $data_ketqua['user_receive']);
        $this->db->where('duyet_id', $data_ketqua['duyet_id']);
        $this->db->update('nm_ketqua_duyet', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    
    function update_approve($data_ketqua){
        $data_update = array(
            'ketqua_approve' => $data_ketqua['duyet_result']
        );
        $this->db->where('ketqua_id', $data_ketqua['ketqua_id']);
        $this->db->update('nm_ketqua', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    
    /*
     * Count all ketqua
     */
    function count_all($user_login){
        $this->db->select('kq.ketqua_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.hopdong_status', 1);
        if($user_login){
            $this->db->where('kq.user_id', $user_login);
        }
        $this->db->group_by('kq.ketqua_id, hd.hopdong_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list ketqua
     */
    function count_list($user_login, $search){
        $this->db->select('kq.ketqua_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.hopdong_status', 1);
        if($user_login){
            $this->db->where('kq.user_id', $user_login);
        }
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->group_by('kq.ketqua_id, hd.hopdong_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list ketqua
     */
    function get_list($user_login, $search, $sort_column, $sort_direction, $start, $length){
        $this->db->select('kq.ketqua_id, kq.ketqua_note, kq.ketqua_approve, kq.create_date, kq.user_id, hd.hopdong_code');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        //  Search ketqua
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->where('hd.hopdong_status', 1);
        if($user_login){
            $this->db->where('kq.user_id', $user_login);
        }
        //  Sort ketqua
        switch ($sort_column){
            case 0:
                $this->db->order_by('hd.hopdong_code', $sort_direction);
                break;
            default :
                $this->db->order_by('hd.hopdong_id', $sort_direction);
                break;
        }
        $this->db->group_by('kq.ketqua_id, kq.ketqua_note, kq.ketqua_approve, kq.create_date, kq.user_id, hd.hopdong_id, hd.hopdong_code');
        if($start >= 0 && $length >= 0){
            $this->db->limit($length, $start);
        }
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    
    function count_all_approve($user_id, $approved){
        $this->db->select('MAX(kq.ketqua_id)');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_duyet kqd', 'kq.ketqua_id = kqd.ketqua_id');
        $this->db->where('kqd.user_receive', $user_id);
        $this->db->group_by('kq.ketqua_id');
        if($approved){
            $this->db->where('kqd.duyet_result != ', 0);
        }else{
            $this->db->where('kqd.duyet_result', 0);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list ketqua
     */
    function count_list_approve($user_id, $approved, $search){
        $this->db->select('kq.ketqua_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_duyet kqd', 'kq.ketqua_id = kqd.ketqua_id');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('kqd.user_receive', $user_id);
        if($approved){
            $this->db->where('kqd.duyet_result != ', 0);
        }else{
            $this->db->where('kqd.duyet_result', 0);
        }
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->group_by('kq.ketqua_id, hd.hopdong_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    function get_list_approve($user_login, $approved, $search, $sort_column, $sort_direction, $start, $length){
        $this->db->select('kq.ketqua_id, kq.ketqua_note, kq.ketqua_approve, kq.create_date, kq.user_id, hd.hopdong_code, MAX(kqd.duyet_id) AS duyet_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_duyet kqd', 'kq.ketqua_id = kqd.ketqua_id');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->group_by('kq.ketqua_id, kq.ketqua_note, kq.ketqua_approve, kq.create_date, kq.user_id, hd.hopdong_id, hd.hopdong_code');
        //  Search certificate
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->where('hd.hopdong_status', 1);
        $this->db->where('kqd.user_receive', $user_login);
        if($approved){
            $this->db->where('kqd.duyet_result != ', 0);
        }else{
            $this->db->where('kqd.duyet_result', 0);
        }
        //  Sort certificate
        switch ($sort_column){
            case 0:
                $this->db->order_by('hd.hopdong_code', $sort_direction);
                break;
            default :
                $this->db->order_by('hd.hopdong_id', $sort_direction);
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
    
    function get_ketqua_duyet($ketqua_id){
        $this->db->select('kqd.*');
        $this->db->from('nm_ketqua_duyet kqd');
        $this->db->where('kqd.ketqua_id', $ketqua_id);
        $this->db->order_by('kqd.duyet_id', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_ketqua_duyet_info($duyet_id){
        $this->db->select('kqd.*');
        $this->db->from('nm_ketqua_duyet kqd');
        $this->db->where('kqd.duyet_id', $duyet_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_ketqua_duyet_wating($ketqua_id){
        $this->db->select('kqd.*');
        $this->db->from('nm_ketqua_duyet kqd');
        $this->db->where('kqd.duyet_result', 0);
        $this->db->where('kqd.ketqua_id', $ketqua_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_ketqua_duyet_approved($ketqua_id){
        $this->db->select('kqd.*');
        $this->db->from('nm_ketqua_duyet kqd');
        $this->db->where('kqd.duyet_result !=', 0);
        $this->db->where('kqd.ketqua_id', $ketqua_id);
        $this->db->order_by('kqd.duyet_id', 'ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_ketqua_duyet_latest($ketqua_id){
        $this->db->select('kqd.*');
        $this->db->from('nm_ketqua_duyet kqd');
        $this->db->where('kqd.duyet_status', 1);
        $this->db->where('kqd.ketqua_id', $ketqua_id);
        $this->db->order_by('kqd.duyet_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function insert_mauketqua_duyet($values){
        $data_insert = array(
            'mauketqua_id' => trim($values['mauketqua_id']),
            'user_create' => trim($values['user_create']),
            'date_create' => trim($values['date_create']),
            'mauketqua_note' => trim($values['mauketqua_note'])
        );
        $this->db->insert('nm_mauketqua_duyet', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function update_mauketqua_approve($datas){
        $data_update = array(
            'mauketqua_duyet_id' => $datas['mauketqua_duyet_id']
        );
        $this->db->where('mauketqua_id', $datas['mauketqua_id']);
        $this->db->update('nm_mauketqua', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
}
/* End of file */