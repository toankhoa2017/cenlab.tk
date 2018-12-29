<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');
class Mod_donvi extends MY_Model implements NhansuInterface {
    var $parent = 0;
    var $table = 'donvi';
    var $column = array('donvi_id', 'donvi_ten'); //set column field database for order and search
    var $order = array('donvi_id' => 'ASC'); // default order 
    var $status = ''; //donvi_status
    var $listUps = array();
    var $listCheck = array(); //Check dieu kien dung de quy

    //Datatable
    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->where('donvi_status', 1);
        $dieukien = array(
            'donvi_idparent' => $this->parent,
        );
        $this->db->where($dieukien);
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
        if (@$_POST['length'] != -1) $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get(); //->where($this->status, "0")
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get(); //->where($this->status, "0")
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from($this->table);
        $this->db->where('donvi_idparent', $this->parent);
        $this->db->where('donvi_status', 1);
        return $this->db->count_all_results();
    }
    //End datatable
    function _gets() {
        $dulieu = $this->db->select("*")->from("donvi")->where('donvi_status', 1)->get();
        return $dulieu->result();
    }
    function _create($values) {
        $insert = array(
            'donvi_idparent' => $values['parent'],
            'donvi_ten' => $values['name'],
            'donvi_type' => $values['loai_donvi']
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($insert)->where('donvi_status',1)->get();
        if ($kiemtra->num_rows() > 0) {
            return FALSE;
        } else {
            if ($values['ref'])
                $insert['donvi_ref'] = $values['ref'];
                $insert['donvi_level'] = $values['donvi_level'];
            if (!$this->db->insert($this->table, $insert))
                return FALSE;
            return TRUE;
        }
    }
    function _update($data) {
        $kiemtra = $this->db->select('donvi_id')->from($this->table)->where("donvi_ten='" . $data['donvi_ten'] . "' and donvi_id!='" . $data['donvi_id'] . "' and donvi_status=1")->get(); // and donvi_status='0'
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('donvi_id', $data['donvi_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }
    function _delete($id) {
        $mangxuly = array(
            'donvi_status' => 2
        );
        $this->db->where('donvi_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }
    function _levelUps($id, $root = 0) {
        if (trim($id) != '') {
            $this->listCheck[] = $id; //Dua vao listCheck de kiem tra dieu kien dung
            $this->db->select('donvi_idparent, donvi_ten');
            $this->db->from($this->table);
            $this->db->where('donvi_id', $id);
            $query = $this->db->get();
            $result = $query->row_array();
            $nenmau = array(
                'id' => $id,
                'name' => $result['donvi_ten']
            );
            $query->free_result();
            array_unshift($this->listUps, $nenmau); //Them vao thu tu dau tien
            if (($result['donvi_idparent'] != 0) && ($id != $root) && (!in_array($result['donvi_idparent'], $this->listCheck))) {
                $this->_levelUps($result['donvi_idparent'], $root);
            }
        }
        return FALSE;
    }
    function _getRef($id) {
        $this->db->select('donvi_ref ref');
        $this->db->from($this->table);
        $this->db->where('donvi_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function _getChucvus($donvi) {
        $this->db->select('chucvu.chucvu_id id, chucvu.chucvu_ten ten, donvi_chucvu.soluong soluong');
        $this->db->from('chucvu');
        $this->db->join('donvi_chucvu', 'chucvu.chucvu_id = donvi_chucvu.chucvu_id');
        $this->db->where('donvi_chucvu.donvi_id', $donvi);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}

/* End of file */