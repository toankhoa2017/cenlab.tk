<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_phuongphap extends MY_Model implements NenmauInterface {

    var $parent = 0;
    var $table = 'mau_phuongphap';
    var $column = array('phuongphap_id', 'phuongphap_name', 'phuongphap_describe');
    var $order = array('phuongphap_id' => 'DESC');
    var $listUps = array();
    var $listCheck = array();

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
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->where('phuongphap_status', '1')->get();
        return $query->result();
    }

    function count_filtered() {
        $this->get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function _create($values) {
        $dieukien = array(
            'phuongphap_code' => $values['code'],
            'phuongphap_name' => $values['name'],
            'phuongphap_name_eng' => $values['name_eng'],
            'phuongphap_status' => '1'
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
        if ($kiemtra == 0) {
            $insert = array(
                'phuongphap_id' => $values['parent'],
                'phuongphap_code' => $values['code'],
                'phuongphap_name' => $values['name'],
                'phuongphap_loai' => $values['loai'],
                'phuongphap_name_eng' => $values['name_eng'],
                'phuongphap_describe' => $values['mota']
            );
            return $this->db->insert($this->table, $insert);
        } else {
            return false;
        }
    }

    function xoaphuongphap($id) {
        $update = array(
            'phuongphap_status' => '2'
        );
        $this->db->where('phuongphap_id', $id);
        return $this->db->update($this->table, $update);
    }

    function suaphuongphap($data) {
        $kiemtra = $this->db->select('phuongphap_id')->from($this->table)->where("phuongphap_name_eng='" . $data['phuongphap_name_eng'] . "' and phuongphap_name='" . $data['phuongphap_name'] . "' and phuongphap_id!='" . $data['phuongphap_id'] . "' and phuongphap_status='1'")->get();
        /*if ($data['phuongphap_code'] == $giongcu) {
            $kiemtra2 = 0;
        } else {
            $kiemtra2 = 1;
        };*/
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('phuongphap_id', $data['phuongphap_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('phuongphap_status','0')
    }

}