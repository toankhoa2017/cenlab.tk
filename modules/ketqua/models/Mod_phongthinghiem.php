<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_phongthinghiem extends MY_Model implements NenmauInterface {
    private $hopdong_active_status = 1;    
    function count_package_duyet_accept($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('mkqd.mauketqua_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->db->count_all_results();
        //var_dump($this->db->last_query());
        return $count;
    }
    function count_package_export($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_ketqua_chitiet kqct', 'mct.mauchitiet_id = kqct.mauchitiet_id');
        $this->db->join('nm_ketqua kq', 'kqct.ketqua_id = kq.ketqua_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('kq.ketqua_approve != 2');
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    function get_list_chat_info($list_chat_id){
        $this->db->select('c.*');
        $this->db->from('mau_chat c');
        $this->db->where_in('c.chat_id', $list_chat_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
}
/* End of file */