<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_dieukienluu extends MY_Model implements NenmauInterface {

    var $parent = 0;
    var $table = 'mau_dieukienluu';
    var $column = array('dieukienluu_id', 'dieukienluu_name');
    var $order = array('dieukienluu_id' => 'DESC');
    var $status = array('dieukienluu_status' => '1');


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
        $dieukien = array(
            'dieukienluu_name' => $values['name'],
            'dieukienluu_status' => '1'
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
        if ($kiemtra == 0) {
            $insert = array(
                'dieukienluu_name' => $values['name'],
                'dieukienluu_status' => '1'
            );
            return $this->db->insert($this->table, $insert);
        } else {
            return false;
        }
    }

    function xoadieukienluu($id) {
        $mangxuly = array(
            'dieukienluu_status' => '2'
        );
        $this->db->where('dieukienluu_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }

    function suadieukienluu($data, $giongcu) {
        $kiemtra = $this->db->select('dieukienluu_id')->from($this->table)->where("dieukienluu_name='" . $data['dieukienluu_name'] . "' and dieukienluu_id!='" . $data['dieukienluu_id'] . "' and dieukienluu_status='1'")->get();
        if ($data['dieukienluu_name'] == $giongcu) {
            $kiemtra2 = 0;
        } else {
            $kiemtra2 = 1;
        };
        if ($kiemtra->num_rows() == 0 || $kiemtra2 == 0) {
            $this->db->where('dieukienluu_id', $data['dieukienluu_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('dieukienluu_status','0')
    }

}