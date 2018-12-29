<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_kythuat extends MY_Model implements NenmauInterface {

    var $parent = 0;
    var $table = 'mau_kythuat';
    var $column = array('kythuat_id', 'kythuat_name', 'kythuat_describe');
    var $order = array('kythuat_id' => 'DESC');
    var $status = array('kythuat_status' => '1');


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
            'kythuat_name' => $values['name'],
            'kythuat_name_eng' => $values['name_eng'],
            'kythuat_status' => '1'
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
        if ($kiemtra == 0) {
            $insert = array(
                'kythuat_name' => $values['name'],
                'kythuat_describe' => $values['mota'],
                'kythuat_name_eng' => $values['name_eng'],
                'kythuat_status' => '1'
            );
            return $this->db->insert($this->table, $insert);
        } else {
            return false;
        }
    }

    function xoakythuat($id) {
        $mangxuly = array(
            'kythuat_status' => '2'
        );
        $this->db->where('kythuat_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }

    function suakythuat($data, $giongcu) {
        $kiemtra = $this->db->select('kythuat_id')->from($this->table)->where("kythuat_name_eng='" . $data['kythuat_name_eng'] . "' and kythuat_name='" . $data['kythuat_name'] . "' and kythuat_id!='" . $data['kythuat_id'] . "' and kythuat_status='1'")->get();
        if ($data['kythuat_name'] == $giongcu) {
            $kiemtra2 = 0;
        } else {
            $kiemtra2 = 1;
        };
        if ($kiemtra->num_rows() == 0 || $kiemtra2 == 0) {
            $this->db->where('kythuat_id', $data['kythuat_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('kythuat_status','0')
    }

}