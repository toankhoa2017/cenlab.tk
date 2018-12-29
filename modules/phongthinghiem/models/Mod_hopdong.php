<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_hopdong extends MY_Model implements NenmauInterface {
    private $hopdong_active_status = 1;
    /*
     * Get list chat by list id
     */
    function get_list_chat_id($list_chat_id, $dongia_id){
        $this->db->select('c.*, ctc.capacity, ctc.val_min, ctc.val_max');
        $this->db->from('mau_chat c');
        $this->db->join('mau_chitieu_chat ctc', 'c.chat_id = ctc.chat_id');
        $this->db->where('ctc.dongia_id', $dongia_id);
        $this->db->where_in('c.chat_id', $list_chat_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    /*
     * Count all hopdong
     */
    function count_all_hopdong($approved, $nhansu_id){
        $this->db->select('hd.hopdong_id');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        if($approved){
            $this->db->where('hdd.hopdong_approve', 1);
            $this->db->where('hdd.duyet_user_id', $nhansu_id);
        }else{
            $this->db->where('hdd.hopdong_approve', 4);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_hopdong($approved, $nhansu_id, $search){
        $this->db->select('hd.hopdong_id');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        // Search
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        if($approved){
            $this->db->where('hdd.hopdong_approve', 1);
            $this->db->where('hdd.duyet_user_id', $nhansu_id);
        }else{
            $this->db->where('hdd.hopdong_approve', 4);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list hopdong
     */
    function get_list_hopdong($approved, $nhansu_id, $search, $sort_column, $sort_direction, $start, $length){
        $this->db->select('hd.hopdong_id, hd.hopdong_code, hd.nhansu_id, hd.congty_id, hd.contact_id, hd.hopdong_pricetmp, hd.hopdong_price, hd.hopdong_status, '
                . 'DATE_FORMAT(hd.hopdong_createdate, "%d/%m/%Y") AS hopdong_createdate, '
                . 'DATE_FORMAT(hd.hopdong_dateend, "%d/%m/%Y") AS hopdong_dateend, '
                . 'IFNULL(hdd.hopdong_approve, 0) AS hopdong_approve');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        if($approved){
            $this->db->where('hdd.hopdong_approve', 1);
            $this->db->where('hdd.duyet_user_id', $nhansu_id);
        }else{
            $this->db->where('hdd.hopdong_approve', 4);
        }
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
    function get_edit_request($hopdong_id){
        $this->db->select('shd.*');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_suahopdong shd', 'hd.hopdong_id = shd.hopdong_id');
        $this->db->where('hd.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    /*
     * Get hopdong by code
     */
    function get_hopdong_info($hopdong_id){
        $this->db->select('hd.*, tt.thitruong_name, IFNULL(hdd.hopdong_approve, 0) as hopdong_approve, hdd.duyet_content, '
                . 'shd.suahopdong_id, shd.suahopdong_name, suahopdong_content, suahopdong_approve');
        $this->db->from('nm_hopdong hd');
        $this->db->join('mau_thitruong tt', 'hd.thitruong_id = tt.thitruong_id', 'left');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        $this->db->join('nm_suahopdong shd', 'hd.hopdong_id = shd.hopdong_id','left');
        $this->db->where('hd.hopdong_id', $hopdong_id);
        /*
        $this->db->group_start();
        $this->db->where('hd.hopdong_status', 1);
        $this->db->or_where('hd.hopdong_status', 3);
        $this->db->group_end();
         * 
         */
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
     * Get chitieu by mau_id
     */
    function get_chitieu_mau($mau_id){
        $this->db->select('mct.*, mptn.mauptn_approve, ct.chitieu_name, pp.phuongphap_name, kt.kythuat_name, dv.dichvu_name, dg.dongia_id, mkq.list_ketqua, mkqd.mauketqua_approve');
        $this->db->from('nm_mauchitiet mct');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_kythuat kt', 'mct.kythuat_id = kt.kythuat_id');
        $this->db->join('nm_dichvu dv', 'mct.dichvu_id = dv.dichvu_id');
        $this->db->join('nm_mauptn mptn', 'm.mau_id = mptn.mau_id AND mct.donvi_id = mptn.donvi_id', 'left');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1', 'left');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id', 'left');
        $this->db->where('mct.mau_id', $mau_id);
        $this->db->order_by('mct.mauchitiet_id', 'ASC');
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_hopdong_id($hopdong_id){
        $this->db->select('*');
        $this->db->from('nm_hopdong');
        $this->db->where('hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function insertApprove($values){
        $data_insert = array(
            'duyet_content' => trim($values['duyet_content']),
            'duyet_user_id' => trim($values['duyet_user_id']),
            'hopdong_approve' => trim($values['hopdong_approve']),
            'hopdong_id' => trim($values['hopdong_id'])
        );
        $this->db->insert('nm_hopdong_duyet', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    
    function updateApproveHopdong($hopdong_id, $approve_id){
        $data_update = array(
            'duyet_id' => $approve_id
        );
        $this->db->where('hopdong_id', $hopdong_id);
        $this->db->update('nm_hopdong', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    function disable_hopdong($hopdong_id){
        $data_update = array(
            'hopdong_status' => 2
        );
        $this->db->where('hopdong_id', $hopdong_id);
        $this->db->where('hopdong_status', 1);
        $this->db->update('nm_hopdong', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    function enable_hopdong($hopdong_id){
        $data_update = array(
            'hopdong_status' => 1
        );
        $this->db->where('hopdong_id', $hopdong_id);
        $this->db->where('hopdong_status', 2);
        $this->db->update('nm_hopdong', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
}
/* End of file */