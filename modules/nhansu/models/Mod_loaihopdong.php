<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');
class Mod_loaihopdong extends MY_Model implements NhansuInterface {
    var $table = 'loaihopdong';
    var $column = array('loaihopdong_ten');
    var $order = array('loaihopdong_id' => 'DESC');
    var $status = '';

    private function get_datatables_query() {
        $this->db->from($this->table);
        $this->db->where('loaihopdong_status', 1);
        $i = 0;
        foreach ($this->column as $item) {
            $tukhoa = trim(@$_POST['search']['value']);
            if ($tukhoa) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $tukhoa);
                } else {
                    $this->db->or_like($item, $tukhoa);
                }
                if (count($this->column) - 1 == $i)
                    $this->db->group_end();
            }
            $column[$i] = $item;
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables() {
        $this->get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);//->where($this->status, "0")
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered() {
        $this->get_datatables_query();
        $query = $this->db->get();//->where($this->status, "0")
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from($this->table);//->where($this->status, "0")
        return $this->db->count_all_results();
    }
    //End datatable
    function _gets() {
        $dulieu = $this->db->select("*")->from("loaihopdong")->where("loaihopdong_status", 1)->get();
        return $dulieu->result();
    }    
    function _create($values) {
        $insert = array(
            'loaihopdong_ten' => $values['name']
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($insert)->get();
        if ($kiemtra->num_rows() > 0) {
            return FALSE;
        } else {
            if (!$this->db->insert($this->table, $insert)) return FALSE;
            return $this->db->insert_id();
        }
    }
    function _update($data) {
        $kiemtra = $this->db->select('loaihopdong_id')->from($this->table)->where("loaihopdong_ten='" . $data['loaihopdong_ten'] . "' and loaihopdong_id!='" . $data['loaihopdong_id'] . "'")->get();//and status='0'
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('loaihopdong_id', $data['loaihopdong_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }
}

/* End of file */