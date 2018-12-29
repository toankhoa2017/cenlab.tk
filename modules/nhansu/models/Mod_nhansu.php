<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');

class Mod_nhansu extends MY_Model implements NhansuInterface {

    var $table = 'nhansu';
    var $column = array('nhansu_id', 'nhansu_firstname', 'nhansu_lastname', 'nhansu_email', 'nhansu_phone'); //set column field database for order and search
    var $order = array('nhansu_id' => 'DESC'); // default order 

    //Datatable
    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->where('nhansu_status', 1);
        $i = 0;
        foreach ($this->column as $item) {//loop column 
            if (@$_POST['search']['value']) {//if datatable send POST for search
                if ($i === 0) {//first loop
                    $this->db->group_start(); //open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                //last loop
                if (count($this->column) - 1 == $i)
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; //set column array variable to order processing
            $i++;
        }
        if (isset($_POST['order'])) {//here order processing
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables() {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1) $this->db->limit(@$_POST['length'], @$_POST['start']); //->where('status', '0')
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        $this->db->where('nhansu_status', 1);
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from($this->table);
        $this->db->where('nhansu_status', 1);
        return $this->db->count_all_results();
    }
    //End datatable
    function _get($id) {
        $dieukien = "a.nhansu_id = {$id} and a.hopdong_id = b.hopdong_id and b.chucvu_id = c.chucvu_id and b.donvi_id = d.donvi_id";
        $dulieu = $this->db->select("a.*, date_format(a.nhansu_birthday,'%d-%m-%Y') nhansu_ngaysinh, b.loaihopdong_id, b.donvi_id, b.chucvu_id, c.chucvu_ten, d.donvi_ten")->from('nhansu a,hopdong b,chucvu c, donvi d')->where($dieukien)->get();
        return $dulieu->row_array();
    }
    function _create($data) {
        if (!$this->db->insert($this->table, $data)) return FALSE;
        return $this->db->insert_id();
    }
    function _update($data) {
        $this->db->where('nhansu_id', $data['nhansu_id']);
        return $this->db->update($this->table, $data);
    }
    function _delete($id) {
        $mangxuly = array(
            'nhansu_status' => 2
        );
        $this->db->where('nhansu_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }
    function _getsoluong_chucvu($donvi) {
        $kiemtra = $this->db
                ->select("COUNT(b.chucvu_id) as soluong, b.chucvu_id")
                ->from("donvi_chucvu as a,hopdong as b,nhansu as c")
                ->where("c.hopdong_id=b.hopdong_id and a.donvi_id='" . $donvi . "' and a.donvi_id=b.donvi_id and a.chucvu_id=b.chucvu_id and c.nhansu_status=1")
                ->group_by("b.chucvu_id")
                //echo $this->db->get_compiled_select(); exit(1);
                ->get();
        return $kiemtra->result();
    }
    function _review($id) {
        $dieukien = "a.nhansu_id = {$id} and b.loaihopdong_id = a.loaihopdong_id and c.donvi_id=a.donvi_id and d.chucvu_id=a.chucvu_id";
        $dulieu = $this->db
                ->select('a.*, b.loaihopdong_ten, c.donvi_ten, d.chucvu_ten')
                ->from('hopdong a, loaihopdong b, donvi c, chucvu d')
                ->where($dieukien)
                ->order_by('a.hopdong_id', 'desc')
                ->get();
        return $dulieu->result_array();
    }
    function _thaydoi($data) {
        if (!$this->db->insert("hopdong", $data)) {
            return FALSE;
        } else {
            $insert_id = $this->db->insert_id();
            $this->db->where('nhansu_id', $data['nhansu_id']);
            $dulieu = array(
                'hopdong_id' => $insert_id
            );
            $this->db->update($this->table, $dulieu);
            return TRUE;
        }
    }
    function _resetPwd($data) {
        $this->db->where('nhansu_id', $data['nhansu_id']);
        return $this->db->update($this->table, $data);
    }
    function _getQuyenTL() {
        $dulieu = $this->db->select("*")->from("quyen")->get();
        return $dulieu->result();
    }
    function _getQuyenTLbyUser($nhansu) {
        $this->db->select('*');
        $this->db->from('nhansu_quyen');
        $this->db->where('nhansu_id', $nhansu);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function _assignQuyenTL($data, $trangthai){
        if($trangthai == 1){
            $this->db->insert('nhansu_quyen',$data);
            
        } else {
            $this->db->where($data);
            $this->db->delete('nhansu_quyen');
        }
    }
    function _GetNSDV($id) {
        $this->db->select('nhansu_lastname,nhansu_firstname,nhansu_email,nhansu_phone,chucvu_ten');
        $this->db->join('hopdong','hopdong.hopdong_id=nhansu.hopdong_id','left');
        $this->db->join('chucvu','chucvu.chucvu_id=hopdong.chucvu_id','left');
        $this->db->from('nhansu');
        $this->db->where('hopdong.donvi_id',$id);
        $this->db->where('nhansu.nhansu_status',1);// for user active
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : false;
    }
    function _updatePBChinh($nhansu_id, $hopdong_id){
        $this->db->where('nhansu_id', $nhansu_id);
        return $this->db->update($this->table, array('hopdong_id' => $hopdong_id));
    }
    function getHopdongIdByNs($id){
        $this->db->select('hopdong_id');
        $this->db->from('nhansu');
        $this->db->where('nhansu_id', $id);
        $query = $this->db->get();
        $result = $query->result();
        $query->free_result();
        return ($result) ? $result : false;
    }
}

/* End of file */