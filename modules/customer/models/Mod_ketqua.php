<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_ketqua extends MY_Model implements NenmauInterface {
    private $hopdong_active_status = 1;
    private $ketqua_approve_accept = [1,3];
    function get_ketqua_id($ketqua_id){
        $this->db->select('kq.ketqua_id, kq.ketqua_note, kq.create_date, hd.hopdong_code');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->group_by('kq.ketqua_id, kq.ketqua_note, kq.create_date, hd.hopdong_code');
        $this->db->where('kq.ketqua_id', $ketqua_id);
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_hopdong_ketqua($ketqua_id){
        $this->db->select('hd.hopdong_id, hd.hopdong_code');
        $this->db->from('nm_ketqua_chitiet kqct');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('kqct.ketqua_id', $ketqua_id);
        $this->db->group_by('m.hopdong_id, hd.hopdong_code');
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Count all ketqua
     */
    function count_all($khachhang_id){
        $this->db->select('kq.ketqua_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where_in('kq.ketqua_approve', $this->ketqua_approve_accept);
        $this->db->where_in('hd.congty_id', $khachhang_id);
        $this->db->group_by('kq.ketqua_id, hd.hopdong_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list ketqua
     */
    function count_list($khachhang_id, $search){
        $this->db->select('kq.ketqua_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where_in('kq.ketqua_approve', $this->ketqua_approve_accept);
        $this->db->where_in('hd.congty_id', $khachhang_id);
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
    function get_list($khachhang_id, $search, $sort_column, $sort_direction, $start, $length){
        $this->db->select('kq.ketqua_id, kq.ketqua_note, kq.ketqua_approve, kq.create_date, kq.user_id, hd.hopdong_code, hd.congty_id');
        $this->db->from('nm_ketqua kq');
        $this->db->join('nm_ketqua_chitiet kqct', 'kq.ketqua_id = kqct.ketqua_id');
        $this->db->join('nm_mauchitiet mct', 'kqct.mauchitiet_id = mct.mauchitiet_id');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->group_by('kq.ketqua_id, kq.ketqua_note, kq.ketqua_approve, kq.create_date, kq.user_id, hd.hopdong_id, hd.hopdong_code');
        //  Search certificate
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where_in('kq.ketqua_approve', $this->ketqua_approve_accept);
        $this->db->where_in('hd.congty_id', $khachhang_id);
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
    
}
/* End of file */