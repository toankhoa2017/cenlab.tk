<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_phanhoi extends MY_Model implements NenmauInterface {
    /*
     * Count all hopdong
     */
    function count_all_phanhoi(){
        $this->db->select('ph.phanhoi_id');
        $this->db->from('nm_ketqua_phanhoi ph');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_phanhoi($search){
        $this->db->select('ph.phanhoi_id');
        $this->db->from('nm_ketqua_phanhoi ph');
        $this->db->join('nm_ketqua kq', 'ph.ketqua_id = kq.ketqua_id');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        // Filter by search
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->group_by('ph.phanhoi_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list hopdong
     */
    function get_list_phanhoi($search, $sort_column, $sort_direction, $start, $length){
        $this->db->select('ph.phanhoi_id, ph.phanhoi_content, ph.contact_id, ph.phanhoi_date, ph.phanhoi_approve, ph.phanhoi_approve_note, hd.hopdong_code');
        $this->db->from('nm_ketqua_phanhoi ph');
        $this->db->join('nm_ketqua kq', 'ph.ketqua_id = kq.ketqua_id');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        // Filter by search
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->group_by('ph.phanhoi_id, ph.phanhoi_content, ph.contact_id, ph.phanhoi_date, ph.phanhoi_approve, hd.hopdong_code');
        // Sort list mau
        switch ($sort_column){
            case 0:
                $this->db->order_by('ph.phanhoi_id', $sort_direction);
                break;
            default :
                $this->db->order_by('ph.phanhoi_id', $sort_direction);
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
    function get_phanhoi_info($phanhoi_id){
        $this->db->select('ph.phanhoi_id, ph.phanhoi_content, ph.contact_id, ph.phanhoi_date, ph.phanhoi_approve, ph.phanhoi_file, ph.ketqua_id, hd.hopdong_id, hd.hopdong_code');
        $this->db->from('nm_ketqua_phanhoi ph');
        $this->db->join('nm_ketqua kq', 'ph.ketqua_id = kq.ketqua_id');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('ph.phanhoi_id', $phanhoi_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return $result;
    }
    function update_phanhoi_approve($datas){
        $data_update = array(
            'phanhoi_approve' => trim($datas['phanhoi_approve']),
            'phanhoi_approve_user' => trim($datas['phanhoi_approve_user']),
            'phanhoi_approve_note' => trim($datas['phanhoi_approve_note']),
            'phanhoi_approve_date' => trim($datas['phanhoi_approve_date'])
        );
        $this->db->where('phanhoi_id', $datas['phanhoi_id']);
        $this->db->update('nm_ketqua_phanhoi', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
}
/* End of file */