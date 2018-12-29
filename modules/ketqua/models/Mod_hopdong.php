<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_hopdong extends MY_Model implements NenmauInterface {
    private $hopdong_mau_status = 3;
    private $hopdong_active_status = 1;
    /*
     * Get hopdong by id
     */
    function get_hopdong_id($hopdong_id){
        $this->db->select('hd.*, tt.thitruong_name, hdd.hopdong_approve as hopdong_approve, hdd.duyet_content, shd.suahopdong_approve');
        $this->db->from('nm_hopdong hd');
        $this->db->join('mau_thitruong tt', 'hd.thitruong_id = tt.thitruong_id', 'left');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->join('nm_suahopdong shd', 'hd.hopdong_id = shd.hopdong_id','left');
        $this->db->where('hd.hopdong_id', $hopdong_id);
        $this->db->where('hd.hopdong_status', 1);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Get list chat by list id
     */
    function get_list_chat_id($list_chat_id, $dongia_id, $thitruong_id = false){
        if($thitruong_id){
            $this->db->select('c.*, ctc.capacity, ctc.val_min, ctc.val_max, ttc.mrl_min, ttc.mrl_max');
        }else{
            $this->db->select('c.*, ctc.capacity, ctc.val_min, ctc.val_max');
        }
        $this->db->from('mau_chat c');
        $this->db->join('mau_chitieu_chat ctc', 'c.chat_id = ctc.chat_id');
        if($thitruong_id){
            $this->db->join('mau_thitruong_chat ttc', 'c.chat_id = ttc.chat_id AND ttc.thitruong_id='.$thitruong_id, 'left');
        }
        $this->db->where('ctc.dongia_id', $dongia_id);
        $this->db->where_in('c.chat_id', $list_chat_id);
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_mau_list_chitieu($list_chitieu){
        $this->db->select('m.*, nm.nenmau_name');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->where_in('mct.mauchitiet_id', $list_chitieu);
        $this->db->group_by('m.mau_id');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_chitieu_list_id($list_chitieu){
        $this->db->select('mct.*, mkq.list_ketqua, mkqd.mauketqua_duyet_id, mkqd.user_approve, mkqd.mauketqua_approve, m.mau_code, m.mau_name, m.mau_description, '
                . 'nm.nenmau_name, ct.chitieu_name, dvt.donvitinh_name, pp.phuongphap_loai, pp.phuongphap_code, pp.phuongphap_shortname, nmct.thoigian, dg.dongia_id, dg.phuongphap_ref');
        $this->db->from('nm_mauchitiet mct');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1', 'left');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id', 'left');
        //$this->db->join('(SELECT MAX(mkqd_tmp.mauketqua_duyet_id) mauketqua_duyet_id, mkqd_tmp.mauketqua_id FROM nm_mauketqua_duyet mkqd_tmp GROUP BY mkqd_tmp.mauketqua_id) AS mkqd', 'mkq.mauketqua_id = mkqd.mauketqua_id', 'left');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_donvitinh dvt', 'dg.donvitinh_id = dvt.donvitinh_id');
        $this->db->join('mau_nenmau_chitieu nmct', 'm.nenmau_id = nmct.nenmau_id AND mct.chitieu_id = nmct.chitieu_id');
        $this->db->where_in('mct.mauchitiet_id', $list_chitieu);
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Count all hopdong
     */
    function count_all_hopdong(){
        $this->db->select('hd.hopdong_id');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->where('hd.duyet_id IS NOT NULL');
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_hopdong($search){
        $this->db->select('hd.hopdong_id');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->where('hd.duyet_id IS NOT NULL');
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        // Search
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list hopdong
     */
    function get_list_hopdong($search, $sort_column, $sort_direction, $start, $length){
        $this->db->select('hd.hopdong_id, hd.hopdong_code, hd.nhansu_id, hd.congty_id, hd.contact_id, hd.hopdong_pricetmp, hd.hopdong_price, hd.hopdong_status, '
                . 'DATE_FORMAT(hd.hopdong_createdate, "%d/%m/%Y") AS hopdong_createdate, '
                . 'DATE_FORMAT(hd.hopdong_dateend, "%d/%m/%Y") AS hopdong_dateend, '
                . 'IFNULL(hdd.hopdong_approve, 0) AS hopdong_approve');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->where('hd.duyet_id IS NOT NULL');
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        // Search certificate
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        // Sort certificate
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
    
    /*
     * Get hopdong by code
     */
    function get_hopdong_code($hopdong_code, $hopdong_id = false){
        $this->db->select('hd.*, tt.thitruong_name, IFNULL(hdd.hopdong_approve, 0) as hopdong_approve, hdd.duyet_content, '
                . 'shd.suahopdong_id, shd.suahopdong_name, suahopdong_content, suahopdong_approve');
        $this->db->from('nm_hopdong hd');
        $this->db->join('mau_thitruong tt', 'hd.thitruong_id = tt.thitruong_id', 'left');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->join('nm_suahopdong shd', 'hd.hopdong_id = shd.hopdong_id','left');
        $this->db->where('hd.hopdong_code', $hopdong_code);
        if($hopdong_id){
            $this->db->where('hd.hopdong_id !=', $hopdong_id);
        }
        $this->db->group_start();
        $this->db->where('hd.hopdong_status', 1);
        $this->db->or_where('hd.hopdong_status', 3);
        $this->db->group_end();
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Get mau by hopdong_id
     */
    function get_mau_hopdong($hopdong_id){
        $this->db->select('m.*, nm.nenmau_name, dvt.donvitinh_name');
        $this->db->from('nm_mau m');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_donvitinh dvt', 'm.donvitinh_id = dvt.donvitinh_id');
        $this->db->where('m.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Count mau by hopdong
     */
    function count_mau_hopdong($hopdong_id){
        $this->db->select('COUNT(*) AS total_mau');
        $this->db->from('nm_mau m');
        $this->db->where('m.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Count chitieu by hopdong
     */
    function count_chitieu_hopdong($hopdong_id){
        $this->db->select('COUNT(*) AS total_chitieu');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->where('m.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Count mau by hopdong
     */
    function count_chitieu_result($hopdong_id, $has_result){
        $this->db->select('COUNT(*) AS total_chitieu');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id', 'left');
        $this->db->where('m.hopdong_id', $hopdong_id);
        $this->db->where('mkqd.mauketqua_approve', 1);
        if($has_result){
            $this->db->where('list_ketqua IS NOT NULL');
        }else{
            $this->db->where('list_ketqua IS NULL');
        }
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Get chitieu by mau_id
     */
    function count_chitieu_export($hopdong_id){
        $this->db->select('COUNT(*) AS total_chitieu');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_ketqua_chitiet kqct', 'mct.mauchitiet_id = kqct.mauchitiet_id');
        $this->db->join('nm_ketqua kq', 'kqct.ketqua_id = kq.ketqua_id');
        $this->db->where('m.hopdong_id', $hopdong_id);
        $this->db->where('kq.ketqua_approve != 2');
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    /*
     * Get chitieu by mau_id
     */
    function get_chitieu_mau($mau_id){
        $this->db->select('mct.*, mkq.mauketqua_id, mkq.list_ketqua, mkqd.mauketqua_duyet_id, ct.chitieu_name, '
                . 'pp.phuongphap_loai, pp.phuongphap_code, pp.phuongphap_shortname, pp.phuongphap_name, kt.kythuat_name, dv.dichvu_name, kq.ketqua_approve, dg.dongia_id');
        $this->db->from('nm_mauchitiet mct');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_kythuat kt', 'mct.kythuat_id = kt.kythuat_id');
        $this->db->join('nm_dichvu dv', 'mct.dichvu_id = dv.dichvu_id');
        $this->db->join('(SELECT MAX(kqct_tmp.ketqua_id) ketqua_id, kqct_tmp.mauchitiet_id FROM `nm_ketqua_chitiet` kqct_tmp GROUP BY kqct_tmp.mauchitiet_id) AS kqct', 'mct.mauchitiet_id = kqct.mauchitiet_id', 'left');
        $this->db->join('nm_ketqua kq', 'kqct.ketqua_id = kq.ketqua_id AND kq.ketqua_approve != 2', 'left');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1', 'left');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id', 'left');
        $this->db->where('mct.mau_id', $mau_id);
        //$this->db->group_start();
        //$this->db->where('kq.ketqua_approve != 2');
        //$this->db->or_where('kq.ketqua_approve IS NULL');
        //$this->db->group_end();
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_chitieu_list_chitieu($list_chitieu){
        $this->db->select('mct.chitieu_id, ct.chitieu_name');
        $this->db->from('nm_mauchitiet mct');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->where_in('mct.mauchitiet_id', $list_chitieu);
        $this->db->group_by('mct.chitieu_id, ct.chitieu_name');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_mau_info($mau_id){
        $this->db->select('m.mau_code, m.mau_name');
        $this->db->from('nm_mau m');
        $this->db->where('m.mau_id', $mau_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_mau_ketqua_duyet($mauketqua_duyet_id){
        $this->db->select('mkqd.mauketqua_approve, mkqd.user_approve');
        $this->db->from('nm_mauketqua_duyet mkqd');
        $this->db->where('mkqd.mauketqua_duyet_id', $mauketqua_duyet_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
}
/* End of file */