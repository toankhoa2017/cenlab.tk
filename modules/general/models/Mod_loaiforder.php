<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');

class Mod_loaiforder extends MY_Model implements TailieuInterface {

    var $parent = 0;
    var $table = 'file_forder';
    var $column = array('file_forder_id', 'file_forder_name'); //set column field database for order and search
    var $order = array('file_forder_id' => 'DESC'); // default order 

    //Datatable

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
        $query = $this->db->get();
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

    //End datatable
    function _create($values) {
        $dieukien = "file_forder_name = '".$values['name']."' or file_forder_path='".$values['path']."'";
        $kiemtra = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
        if ($kiemtra == 0) {
            $insert = array(
                'file_forder_name' => $values['name'],
                'file_forder_path' => $values['path']."/",
            );
//        $kiemtra = $this->db->select("*")->from($this->table)->where($insert)->get();
//        if ($kiemtra->num_rows() > 0) {
//            return FALSE;
//        } else {
            return $this->db->insert($this->table, $insert);
        } else {
            return false;
        }
//        }
    }

    function xoaloaiforder($id) {
        $this->db->where('file_forder_id', $id);
        return $this->db->delete($this->table);
    }

    function sualoaiforder($data) {
        $kiemtra = $this->db->select('file_forder_id')->from($this->table)->where("(file_forder_name='" . $data['file_forder_name'] . "'  or file_forder_path='".$data['file_forder_path']."')  and file_forder_id!='" . $data['file_forder_id'] . "'")->get();
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('file_forder_id', $data['file_forder_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }

}

/* End of file */