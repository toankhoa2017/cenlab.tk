<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_nhaphang extends MY_Model implements VattuInterface {

    var $table = 'denghi';
    var $column = array('denghi_id', 'denghi_title', 'denghi_date');
    var $order = array('denghi_id' => 'DESC');

    private function get_datatables_query() {
        $dieukien = array(
           // "nhansu_nhan" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '5'
        );
        $this->db->from($this->table)->where($dieukien);
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
        $dieukien = array(
            //"nhansu_nhan" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '5'
        );
        $this->db->from($this->table)->where($dieukien);
        return $this->db->count_all_results();
    }
    function hangdanhap($id) {
        $this->db->select('dn_detail_id, SUM(soluong_nhan) AS soluong');
        $this->db->from('nhanhang');
        $this->db->group_by('dn_detail_id');
        $this->db->where('denghi_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        if($result && count($result) > 0)  return $result;
        return 0;
    }
    function create($values) {
        if(!$this->db->insert('nhanhang', $values)) return FALSE;
        return TRUE;
    }
    function tonghangdanhap($id) {
        $this->db->select('SUM(soluong_nhan) AS tongsonhan');
        $this->db->from('nhanhang');
        $this->db->where('denghi_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        if($result && count($result) > 0) return $result['tongsonhan'];
        return 0;
    }
    function tonghangdenghi ($id) {
        $this->db->select('SUM(dn_detail_soluong) AS tongdenghi');
        $this->db->from('denghi_detail');
        $this->db->where('denghi_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        if($result && count($result) > 0)   return $result['tongdenghi'];
        return 0;
    }
    function updatedenghi($iddn, $data) {
        $this->db->where('denghi_id', $iddn);
        $this->db->update('denghi', $data);
    }
    function historynhaphang($id) {
        $this->db->select('*');
        $this->db->join('denghi_detail b', 'b.dn_detail_id = a.dn_detail_id', 'left');
        $this->db->join('sanpham c', 'c.sp_id = b.sp_id', 'left');
        $this->db->from('nhanhang a');
        $this->db->where('a.denghi_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}