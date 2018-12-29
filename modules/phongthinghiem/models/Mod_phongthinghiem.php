<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_phongthinghiem extends MY_Model implements NenmauInterface {
    private $hopdong_active_status = 1;
    /*
     * Count all hopdong
     */
    function count_all_package($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauptn mptn', 'm.mau_id = mptn.mau_id AND mct.donvi_id = mptn.donvi_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('mptn.mauptn_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_package($search, $ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauptn mptn', 'm.mau_id = mptn.mau_id AND mct.donvi_id = mptn.donvi_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('mptn.mauptn_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        // Filter by search
        if($search != ''){
            $this->db->like('m.mau_code', $search);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list hopdong
     */
    function get_chitieu_mau($search, $sort_column, $sort_direction, $start, $length, $ptn_id = false){
        $this->db->select('m.*, mct.*, nm.nenmau_name, ct.chitieu_name, pp.phuongphap_name, kt.kythuat_name, dv.dichvu_name, dg.dongia_id, mkqd.mauketqua_approve');
        $this->db->from('nm_mau m');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_kythuat kt', 'mct.kythuat_id = kt.kythuat_id');
        $this->db->join('nm_dichvu dv', 'mct.dichvu_id = dv.dichvu_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauptn mptn', 'm.mau_id = mptn.mau_id AND mct.donvi_id = mptn.donvi_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1', 'left');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id', 'left');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('mptn.mauptn_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        //  Search certificate
        if($search != ''){
            $this->db->like('m.mau_code', $search);
        }
        //  Sort certificate
        switch ($sort_column){
            case 0:
                $this->db->order_by('m.mau_code', $sort_direction);
                break;
            case 2:
                $this->db->order_by('m.mau_name', $sort_direction);
                break;
            default :
                $this->db->order_by('m.mau_id', $sort_direction);
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
     * Count all hopdong
     */
    function count_all_package_duyet($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_package_duyet($search, $ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        // Filter by search
        if($search != ''){
            $this->db->like('m.mau_code', $search);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list package for duyet
     */
    function get_chitieu_mau_duyet($search, $sort_column, $sort_direction, $start, $length, $ptn_id = false){
        $this->db->select('m.*, mct.*, mkqd.*, mkq.list_ketqua, nm.nenmau_name, ct.chitieu_name, pp.phuongphap_name, kt.kythuat_name, dv.dichvu_name, dg.dongia_id, dvt.donvitinh_name');
        $this->db->from('nm_mau m');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_dichvu dv', 'mct.dichvu_id = dv.dichvu_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_kythuat kt', 'mct.kythuat_id = kt.kythuat_id');
        $this->db->join('mau_donvitinh dvt', 'dg.donvitinh_id = dvt.donvitinh_id');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        //  Search certificate
        if($search != ''){
            $this->db->like('m.mau_code', $search);
        }
        //  Sort certificate
        switch ($sort_column){
            case 0:
                $this->db->order_by('m.mau_code', $sort_direction);
                break;
            case 2:
                $this->db->order_by('m.mau_name', $sort_direction);
                break;
            default :
                $this->db->order_by('m.mau_id', $sort_direction);
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
    
    // Get list chat of package
    function get_list_chat_package($mauchitiet_id, $ptn_id){
        $this->db->select('mct.*, m.*, nm.nenmau_name, ct.chitieu_name, pp.phuongphap_name, kt.kythuat_name, dv.dichvu_name, dvt.donvitinh_name, dvtct.donvitinh_name AS chitieu_donvitinh_name, dg.dongia_id, hd.hopdong_id');
        $this->db->from('nm_mauchitiet mct');
        $this->db->join('nm_mau m', 'mct.mau_id = m.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_dichvu dv', 'mct.dichvu_id = dv.dichvu_id');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_kythuat kt', 'mct.kythuat_id = kt.kythuat_id');
        $this->db->join('mau_donvitinh dvtct', 'dg.donvitinh_id = dvtct.donvitinh_id');
        $this->db->join('mau_donvitinh dvt', 'm.donvitinh_id = dvt.donvitinh_id');
        $this->db->where('mct.mauchitiet_id', $mauchitiet_id);
        $this->db->where('mct.donvi_id', $ptn_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    // Update result of package
    function update_result_package($mauchitiet_id, $ptn_id, $list_result){
        $data_update = array(
            'list_ketqua' => $list_result
        );
        $this->db->where('mauchitiet_id', $mauchitiet_id);
        $this->db->where('donvi_id', $ptn_id);
        $this->db->where('list_ketqua IS NULL');
        $this->db->update('nm_mauchitiet', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    
    function get_mauketqua_list($mauchitiet_id){
        $this->db->select('mkq.mauchitiet_id, mkq.list_ketqua, mkq.user_create, mkq.date_create, mkq.mauketqua_ghichu, mkqd.*');
        $this->db->from('nm_mauketqua mkq');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_id = mkqd.mauketqua_id', 'left');
        $this->db->where('mkq.mauchitiet_id', $mauchitiet_id);
        $this->db->order_by('mkqd.mauketqua_duyet_id', 'ASC');
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function insert_mauketqua($values){
        $data_insert = array(
            'mauchitiet_id' => trim($values['mauchitiet_id']),
            'list_ketqua' => trim($values['list_ketqua']),
            'user_create' => trim($values['user_create']),
            'date_create' => trim($values['date_create']),
            'mauketqua_ghichu' => trim($values['mauketqua_ghichu'])
        );
        $this->db->insert('nm_mauketqua', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function disable_current_mauketqua($mauchitiet_id){
        $data_update = array(
            'mauketqua_current' => 2
        );
        $this->db->where('mauchitiet_id', $mauchitiet_id);
        $this->db->where('mauketqua_current', 1);
        $this->db->update('nm_mauketqua', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    /*
    function mauketqua_approve($values){
        $data_update = array(
            'mauketqua_approve' => trim($values['mauketqua_approve']),
            'user_approve' => trim($values['user_approve']),
            'date_approve' => trim($values['date_approve'])
        );
        $this->db->where('mauketqua_id', $values['mauketqua_id']);
        $this->db->update('nm_mauketqua', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    */
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
    function update_mauketqua_duyet($datas){
        $data_update = array(
            'user_approve' => trim($datas['user_approve']),
            'date_approve' => trim($datas['date_approve']),
            'mauketqua_approve' => trim($datas['mauketqua_approve'])
        );
        $this->db->where('mauketqua_duyet_id', $datas['mauketqua_duyet_id']);
        $this->db->update('nm_mauketqua_duyet', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
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
    function count_package_ketqua($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1', 'left');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('mkq.list_ketqua IS NOT NULL');
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    function count_package_duyet($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('mkqd.mauketqua_approve !=', 0);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    function count_package_duyet_accept($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('mkqd.mauketqua_approve !=', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    
    /*
     * Count all hopdong
     */
    function count_all_mau($ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        $this->db->group_by('m.mau_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_mau($search, $ptn_id = false){
        $this->db->select('mct.mau_id');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        // Filter by search
        if($search != ''){
            $this->db->like('m.mau_code', $search);
        }
        $this->db->group_by('m.mau_id');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list hopdong
     */
    function get_list_mau($search, $sort_column, $sort_direction, $start, $length, $ptn_id = false){
        $this->db->select('m.mau_id, m.mau_code, m.mau_name, m.mau_description, m.date_create, nm.nenmau_name, mptn.mauptn_approve');
        $this->db->from('nm_mau m');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauptn mptn', 'm.mau_id = mptn.mau_id AND mct.donvi_id = mptn.donvi_id', 'left');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        // Filter by PTN
        if($ptn_id){
            $this->db->where('mct.donvi_id', $ptn_id);
        }
        // Search list mau
        if($search != ''){
            $this->db->like('m.mau_code', $search);
        }
        // Group by mau_id
        $this->db->group_by('m.mau_id, m.mau_code, m.mau_name, m.mau_description, m.date_create, nm.nenmau_name, mptn.mauptn_approve');
        // Sort list mau
        switch ($sort_column){
            case 0:
                $this->db->order_by('m.mau_code', $sort_direction);
                break;
            case 2:
                $this->db->order_by('m.mau_name', $sort_direction);
                break;
            default :
                $this->db->order_by('m.mau_id', $sort_direction);
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
    
    function get_mau_info($mau_id, $ptn_id){
        $this->db->select('hd.hopdong_id, m.mau_id, m.mau_code, m.mau_name, m.mau_description, m.mau_mass, m.mau_note, m.date_create, nm.nenmau_name, dvt.donvitinh_name, '
                . 'mptn.mauptn_approve, mptn.nhansu_id, mptn.mauptn_note, mptn.mauptn_createdate, COUNT(mct.mauchitiet_id) AS total_chitieu');
        $this->db->from('nm_mau m');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_donvitinh dvt', 'm.donvitinh_id = dvt.donvitinh_id');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauptn mptn', 'm.mau_id = mptn.mau_id AND mct.donvi_id = mptn.donvi_id', 'left');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('m.mau_id', $mau_id);
        $this->db->where('mct.donvi_id', $ptn_id);
        // Group by mau_id
        $this->db->group_by('hd.hopdong_id, m.mau_id, m.mau_code, m.mau_name, m.mau_description, m.mau_mass, m.mau_note, m.date_create, nm.nenmau_name, dvt.donvitinh_name, '
                . 'mptn.mauptn_approve, mptn.nhansu_id, mptn.mauptn_note, mptn.mauptn_createdate');
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_list_chitieu_mau($mau_id, $ptn_id){
        $this->db->select('mct.*, nm.nenmau_name, ct.chitieu_name, pp.phuongphap_name, kt.kythuat_name, dv.dichvu_name');
        $this->db->from('nm_mau m');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->db->join('mau_dongia dg', 'm.nenmau_id = dg.nenmau_id AND mct.chitieu_id = dg.chitieu_id AND mct.phuongphap_id = dg.phuongphap_id AND mct.kythuat_id = dg.kythuat_id');
        $this->db->join('mau_nenmau nm', 'm.nenmau_id = nm.nenmau_id');
        $this->db->join('mau_chitieu ct', 'mct.chitieu_id = ct.chitieu_id');
        $this->db->join('mau_phuongphap pp', 'mct.phuongphap_id = pp.phuongphap_id');
        $this->db->join('mau_kythuat kt', 'mct.kythuat_id = kt.kythuat_id');
        $this->db->join('nm_dichvu dv', 'mct.dichvu_id = dv.dichvu_id');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id');
        $this->db->join('nm_mauketqua mkq', 'mct.mauchitiet_id = mkq.mauchitiet_id AND mkq.mauketqua_current = 1', 'left');
        $this->db->join('nm_mauketqua_duyet mkqd', 'mkq.mauketqua_duyet_id = mkqd.mauketqua_duyet_id', 'left');
        $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        $this->db->where('hdd.hopdong_approve', 1);
        $this->db->where('m.mau_id', $mau_id);
        $this->db->where('mct.donvi_id', $ptn_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
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
    function insert_mauptn($values){
        $data_insert = array(
            'mau_id' => trim($values['mau_id']),
            'donvi_id' => trim($values['donvi_id']),
            'nhansu_id' => trim($values['nhansu_id']),
            'mauptn_approve' => trim($values['mauptn_approve']),
            'mauptn_note' => trim($values['mauptn_note']),
            'mauptn_createdate' => trim($values['mauptn_createdate'])
        );
        $this->db->insert('nm_mauptn', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function get_mau_id($mau_id){
        $this->db->select('*');
        $this->db->from('nm_mau m');
        $this->db->where('m.mau_id', $mau_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
/* End of file */