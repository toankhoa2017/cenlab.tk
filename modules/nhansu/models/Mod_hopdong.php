<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');
class Mod_hopdong extends MY_Model implements NhansuInterface {
    var $parent = 0;
    var $table = 'hopdong';
    //Datatable
    function _create($data) {
        if (!$this->db->insert($this->table, $data)) return FALSE;
        return TRUE;
    }
	
    function _update($data) {
        $kiemtra = $this->db->select('chucvu_id')->from($this->table)->where("chucvu_ten='" . $data['chucvu_ten'] . "' and chucvu_id!='" . $data['chucvu_id'] . "'")->get();// and chucvu_status='0'
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('chucvu_id', $data['chucvu_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }
    
    function _delete($hopdong_id){
        $mangxuly = array(
            'hopdong_status' => 2 //deactive
        );
        $this->db->where('hopdong_id', $hopdong_id);
        return $this->db->update($this->table, $mangxuly);
    }
            
    function _getdanhsachhdong($id) {
        $this->db->select('hd.*, cv.chucvu_ten, dv.donvi_ten');
        $this->db->from('hopdong hd');
        $this->db->where('nhansu_id', $id);
        $this->db->where('hopdong_status', 1);// load ds dang active
        $this->db->join('chucvu cv', 'hd.chucvu_id = cv.chucvu_id');
        $this->db->join('donvi dv', 'hd.donvi_id = dv.donvi_id and dv.donvi_status=1');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : false;
    }
	
    function getHopDongById($id){
        $this->db->select('hd.*');
        $this->db->from('hopdong hd');
        $this->db->where('hopdong_id', $id);
        $this->db->where('hopdong_status', 1);// load ds dang active
        $query = $this->db->get();
        $result = $query->result();
        $query->free_result();
        return ($result) ? $result : false;
    }
}

/* End of file */