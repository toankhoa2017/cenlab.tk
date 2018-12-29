<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('KhachhangInterface');

class Mod_khachhang extends MY_Model implements KhachhangInterface {

    var $table = 'congty';
    var $congty_table = "congty";
    var $contact_table = "contact";
    var $congty_contact_table = "congty_contact";
    var $column = array('congty_id', 'congty_name', 'congty_address');
    var $order = array('congty_id' => 'DESC');
    var $status = array('congty_status' => '1');

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

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('congty_status','0')
    }

}

/* End of file */