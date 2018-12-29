<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_donvitinh extends MY_Model implements VattuInterface {

    var $parent = 0;
    var $table = 'donvitinh';
    var $column = array('donvitinh_id', 'donvitinh_name');
    var $order = array('donvitinh_id' => 'DESC');
    var $status = array('donvitinh_status' => '1');


    private function get_datatables_query() {
        $this->db->from($this->table);
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
            $this->db->where($this->status)->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->get_datatables_query();
        $query = $this->db->where($this->status)->get();
        return $query->num_rows();
    }

    function count_all() {
        $this->db->from($this->table);
        return $this->db->where($this->status)->count_all_results();
    }

    function _create($values) {
        $insert = array(
            'donvitinh_name' => $values['name'],
            'donvitinh_status' => '1'
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($insert)->get();
        if ($kiemtra->num_rows() > 0) {
            return FALSE;
        } else {
            return $this->db->insert($this->table, $insert);
        }
    }

    function xoadonvitinh($id) {
        $mangxuly = array(
            'donvitinh_status' => '2'
        );
        $this->db->where('donvitinh_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }

    function suadonvitinh($data, $giongcu) {
        $kiemtra = $this->db->select('donvitinh_id')->from($this->table)->where("donvitinh_name='" . $data['donvitinh_name'] . "' and donvitinh_id!='" . $data['donvitinh_id'] . "' and donvitinh_status='1'")->get();
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('donvitinh_id', $data['donvitinh_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->where('donvitinh_status', '1')->get()->result();
    }

}