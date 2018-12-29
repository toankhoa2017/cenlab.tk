<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');
class Mod_hopdong extends MY_Model implements NenmauInterface {
    private $hopdong_mau_status = 3;
    private $hopdong_active_status = 1;
    function countHopdongInDay(){
        $this->db->select('COUNT(hd.hopdong_id) AS hopdong_in_day');
        $this->db->from('nm_hopdong hd');
        $this->db->where('hd.hopdong_status', '1');
        $this->db->where('DATE(hd.hopdong_createdate)', date("Y-m-d"));
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function insertHopdong($values){
        $data_hopdong = array(
            'hopdong_idparent' => trim($values['hopdong_idparent']),
            'hopdong_code' => trim($values['hopdong_code']),
            'congty_id' => trim($values['congty_id']),
            'contact_id' => trim($values['contact_id']),
            'nhansu_id' => trim($values['nhansu_id']),
            'thitruong_id' => trim($values['thitruong_id']),
            'hopdong_resultlang' => trim($values['hopdong_resultlang']),
            'hopdong_resultvia' => trim($values['hopdong_resultvia']),
            'hopdong_quychuan' => trim($values['hopdong_quychuan']),
            'hopdong_yeucaukhac' => trim($values['hopdong_yeucaukhac']),
            'hopdong_datestart' => date("Y-m-d"),
            'hopdong_createdate' => date("Y-m-d H:i:s"),
            'hopdong_dateend' => trim($values['hopdong_dateend'])?DateTime::createFromFormat('d/m/Y', trim($values['hopdong_dateend']))->format('Y-m-d'):null,
            'hopdong_status' => trim($values['hopdong_mau'])?$this->hopdong_mau_status:$this->hopdong_active_status
        );
        $this->db->insert('nm_hopdong', $data_hopdong);
        $hopdong_id = $this->db->insert_id();
        return ($hopdong_id?$hopdong_id:FALSE);
    }
    function updatePriceHopdong($hopdong_id, $hopdong_pricetmp, $hopdong_price, $hopdong_deposit, $hopdong_remaining){
        $data_update = array(
            'hopdong_pricetmp' => $hopdong_pricetmp,
            'hopdong_price' => $hopdong_price,
            'hopdong_deposit' => $hopdong_deposit,
            'hopdong_remaining' => $hopdong_remaining
        );
        $this->db->where('hopdong_id', $hopdong_id);
        $this->db->update('nm_hopdong', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    function countHopdongInMonth($hopdong_id = false){
        $this->db->select('COUNT(hd.hopdong_id) AS hopdong_in_month');
        $this->db->from('nm_hopdong hd');
        $this->db->where('hd.hopdong_idparent', 0);
        $this->db->where('MONTH(hd.hopdong_createdate)', date("m"));
        $this->db->where('YEAR(hd.hopdong_createdate)', date("Y"));
        if($hopdong_id){
            $this->db->where('hd.hopdong_id <=', $hopdong_id);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function countMauInDay(){
        $this->db->select('COUNT(m.mau_id) AS mau_in_day');
        $this->db->from('nm_mau m');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.hopdong_status', '1');
        $this->db->where('DATE(hd.hopdong_createdate)', date("Y-m-d"));
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function countMauInHopDong($hopdong_code){
        $this->db->select('COUNT(DISTINCT(m.mau_code)) AS mau_in_hopdong');
        $this->db->from('nm_mau m');
        $this->db->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->db->where('hd.hopdong_code', $hopdong_code);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function insertMau($values){
        $data_mau = array(
            'mau_code' => trim($values['mau_code']),
            'mau_name' => trim($values['mau_name']),
            'mau_mass' => trim($values['mau_mass']),
            'donvitinh_id' => trim($values['donvitinh_id']),
            'mau_description' => trim($values['mau_description']),
            'mau_amount' => trim($values['mau_amount']),
            'mau_save' => trim($values['mau_save']),
            'mau_note' => trim($values['mau_note']),
            'mau_datesave' => trim($values['mau_datesave'])?DateTime::createFromFormat('d/m/Y', trim($values['mau_datesave']))->format('Y-m-d'):null,
            'mau_datesave_yeucau' => trim($values['mau_datesave_yeucau'])?DateTime::createFromFormat('d/m/Y', trim($values['mau_datesave_yeucau']))->format('Y-m-d'):null,
            'hopdong_id' => trim($values['hopdong_id']),
            'nenmau_id' => trim($values['nenmau_id']),
            'date_create' => trim($values['date_create'])
        );
        $this->db->insert('nm_mau', $data_mau);
        $mau_id = $this->db->insert_id();
        return ($mau_id?$mau_id:FALSE);
    }
    function insertMauChitiet($values){
        $data_insert = array(
            'mau_id' => trim($values['mau_id']),
            'chitieu_id' => trim($values['chitieu_id']),
            'phuongphap_id' => trim($values['phuongphap_id']),
            'kythuat_id' => trim($values['kythuat_id']),
            'donvi_id' => trim($values['donvi_id']),
            'list_chat' => trim($values['list_chat']),
            'lod_loq' => trim($values['lod_loq']),
            'chitieu_dateend' => trim($values['chitieu_dateend'])?DateTime::createFromFormat('d/m/Y', trim($values['chitieu_dateend']))->format('Y-m-d'):null,
            'dichvu_id' => trim($values['dichvu_id']),
            'price_tmp' => trim($values['price_tmp']),
            'price' => trim($values['price'])
        );
        $this->db->insert('nm_mauchitiet', $data_insert);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    
    function insertHopdongFile($array_values){
        foreach ($array_values as &$values){
            $values = array(
                'hopdong_id' => trim($values['hopdong_id']),
                'file_id' => trim($values['file_id'])
            );
        }
        return $this->db->insert_batch('nm_hopdong_file', $array_values);
    }
    
    /*
     * Count all hopdong
     */
    function count_all_hopdong($get_for, $hopdong_mau, $approved){
        $this->db->select('hd.hopdong_id');
        $this->db->from('nm_hopdong hd');
        if($hopdong_mau){
            $this->db->where('hd.hopdong_status', $this->hopdong_mau_status);
        }else{
            $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        }
        // Get all hopdong for duyet
        if($get_for != 'all'){
            $this->db->where('hd.nhansu_id', $get_for);
        }else{
            // Filter approved
            if($approved){
                $this->db->where('hd.duyet_id IS NOT NULL');
            }else{
                $this->db->where('hd.duyet_id IS NULL');
            }
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Count list hopdong
     */
    function count_list_hopdong($get_for, $hopdong_mau, $approved, $search){
        $this->db->select('hd.hopdong_id');
        $this->db->from('nm_hopdong hd');
        // Get all hopdong for duyet
        if($get_for != 'all'){
            $this->db->where('hd.nhansu_id', $get_for);
        }else{
            // Filter approved
            if($approved){
                $this->db->where('hd.duyet_id IS NOT NULL');
            }else{
                $this->db->where('hd.duyet_id IS NULL');
            }
        }
        // Search
        if($search != ''){
            $this->db->like('hd.hopdong_code', $search);
        }
        if($hopdong_mau){
            $this->db->where('hd.hopdong_status', $this->hopdong_mau_status);
        }else{
            $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
        }
        $count = $this->db->count_all_results();
        return $count;
    }
    
    /*
     * Get list hopdong
     */
    function get_list_hopdong($get_for, $hopdong_mau, $approved, $search, $sort_column, $sort_direction, $start, $length){
        $this->db->select('hd.hopdong_id, hd.hopdong_code, hd.nhansu_id, hd.congty_id, hd.contact_id, hd.hopdong_pricetmp, hd.hopdong_price, hd.hopdong_status, '
                . 'DATE_FORMAT(hd.hopdong_createdate, "%d/%m/%Y") AS hopdong_createdate, '
                . 'DATE_FORMAT(hd.hopdong_dateend, "%d/%m/%Y") AS hopdong_dateend, '
                . 'IFNULL(hdd.hopdong_approve, 0) AS hopdong_approve');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_hopdong_duyet hdd', 'hd.duyet_id = hdd.duyet_id', 'left');
        // Get all hopdong for duyet
        if($get_for != 'all'){
            $this->db->where('hd.nhansu_id', $get_for);
        }else{
            // Filter approved
            if($approved){
                $this->db->where('hd.duyet_id IS NOT NULL');
            }else{
                $this->db->where('hd.duyet_id IS NULL');
            }
        }
        // Get hopdong mau
        if($hopdong_mau){
            $this->db->where('hd.hopdong_status', $this->hopdong_mau_status);
        }else{
            $this->db->where('hd.hopdong_status', $this->hopdong_active_status);
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
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
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
     * Get hopdong original
     */
    function get_hopdong_original($hopdong_code){
        $this->db->select('hd.*');
        $this->db->from('nm_hopdong hd');
        $this->db->where('hd.hopdong_code', $hopdong_code);
        $this->db->where('hd.hopdong_idparent', 0);
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
     * Get file by hopdong_id
     */
    function get_file_hopdong($hopdong_id){
        $this->db->select('f.*');
        $this->db->from('nm_file f');
        $this->db->join('nm_hopdong_file hdf', 'f.file_id = hdf.file_id');
        $this->db->where('hdf.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function get_file_by_id($file_id){
        $this->db->select('f.*');
        $this->db->from('nm_file f');
        $this->db->where('f.file_id', $file_id);
        $query = $this->db->get();
        $result = $query->row_array();
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
    
    function get_approve_history($hopdong_id){
        $this->db->select('hdd.*');
        $this->db->from('nm_hopdong_duyet hdd');
        $this->db->where('hdd.hopdong_id', $hopdong_id);
        $query = $this->db->get();
        $result = $query->result_array();
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
    
    function insertRequestEdit($values){
        $data_edit = array(
            'suahopdong_name' => trim($values['suahopdong_name']),
            'suahopdong_content' => trim($values['suahopdong_content']),
            'suahopdong_approve' => trim($values['suahopdong_approve']),
            'nguoitao_id' => trim($values['nguoitao_id']),
            'hopdong_id' => trim($values['hopdong_id'])
        );
        if(trim($values['nguoiduyet_id'])){
            $data_edit['nguoiduyet_id'] = trim($values['nguoiduyet_id']);
        }
        if(trim($values['nguoisua_id'])){
            $data_edit['nguoisua_id'] = trim($values['nguoisua_id']);
        }
        $this->db->insert('nm_suahopdong', $data_edit);
        $request_edit_id = $this->db->insert_id();
        return ($request_edit_id?$request_edit_id:FALSE);
    }
    
    function updateStatusHopdong($hopdong_id, $hopdong_status){
        $data_update = array(
            'hopdong_status' => $hopdong_status
        );
        $this->db->where('hopdong_id', $hopdong_id);
        $this->db->update('nm_hopdong', $data_update);
        $result = $this->db->affected_rows();
        return ($result && $result > 0)?TRUE:FALSE;
    }
    function get_mau_chitiet($mau_chitiet_id){
        $this->db->select('mct.*');
        $this->db->from('nm_mauchitiet mct');
        $this->db->where('mct.mauchitiet_id', $mau_chitiet_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_mau_ketqua($mau_chitiet_id){
        $this->db->select('mkq.*');
        $this->db->from('nm_mauketqua mkq');
        $this->db->where('mkq.mauchitiet_id', $mau_chitiet_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_mau_ketqua_duyet($mauketqua_id){
        $this->db->select('mkqd.*');
        $this->db->from('nm_mauketqua_duyet mkqd');
        $this->db->where('mkqd.mauketqua_id', $mauketqua_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function get_mauptn($mau_id){
        $this->db->select('mptn.*');
        $this->db->from('nm_mauptn mptn');
        $this->db->where('mptn.mau_id', $mau_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function insert_mauptn($values){
        $data_insert = array(
            'mau_id' => trim($values['mau_id']),
            'donvi_id' => trim($values['donvi_id']),
            'nhansu_id' => trim($values['nhansu_id']),
            'mauptn_approve' => trim($values['mauptn_approve']),
            'mauptn_note' => trim($values['mauptn_note']),
            'mauptn_createdate' => trim($values['mauptn_createdate']),
            'mauptn_status' => trim($values['mauptn_status'])
        );
        $this->db->insert('nm_mauptn', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function insert_mauketqua($values){
        $data_insert = array(
            'mauchitiet_id' => trim($values['mauchitiet_id']),
            'list_ketqua' => trim($values['list_ketqua']),
            'user_create' => trim($values['user_create']),
            'date_create' => trim($values['date_create']),
            'mauketqua_ghichu' => trim($values['mauketqua_ghichu']),
            'mauketqua_current' => trim($values['mauketqua_current'])
        );
        $this->db->insert('nm_mauketqua', $data_insert);
        $insert_id = $this->db->insert_id();
        return ($insert_id?$insert_id:FALSE);
    }
    function insert_mauketqua_duyet($values){
        $data_insert = array(
            'mauketqua_id' => trim($values['mauketqua_id']),
            'user_create' => trim($values['user_create']),
            'date_create' => trim($values['date_create']),
            'user_approve' => trim($values['user_approve']),
            'date_approve' => trim($values['date_approve']),
            'mauketqua_approve' => trim($values['mauketqua_approve']),
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
    function check_mau_in_ptn($hopdong_id){
        $this->db->select('count(mptn.mauptn_id) AS total_mau_in_ptn');
        $this->db->from('nm_hopdong hd');
        $this->db->join('nm_mau m', 'hd.hopdong_id = m.hopdong_id', 'left');
        $this->db->join('nm_mauptn mptn', 'm.mau_id = mptn.mau_id', 'left');        
        $this->db->where('hd.hopdong_id', $hopdong_id);
        $this->db->where('mptn.mauptn_approve IS NOT NULL');
        $query = $this->db->get();
        //var_dump($this->db->last_query());
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
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