<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_giabo extends MY_Model implements NenmauInterface {

    var $table_file = "mau_giabo_dongia as a , mau_dongia as b , mau_nenmau as c , mau_chitieu as d";
    var $table_select = "*";
    var $column = array('b.package_code', 'c.nenmau_name', 'd.chitieu_name', 'b.price');

    private function get_datatables_query($giabo_id) {
        $dieukien = "a.giabo_id='" . $giabo_id . "' and a.package_code=b.package_code and b.nenmau_id=c.nenmau_id and b.chitieu_id=d.chitieu_id";
        $this->db->select($this->table_select)->from($this->table_file)->where($dieukien);
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

    function danhsach_giabo($giabo_id) {
        $this->get_datatables_query($giabo_id);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($giabo_id) {
        $this->get_datatables_query($giabo_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($giabo_id) {
        $dieukien = "a.giabo_id='" . $giabo_id . "' and a.package_code=b.package_code and b.nenmau_id=c.nenmau_id and b.chitieu_id=d.chitieu_id";
        $this->db->select($this->table_select)->from($this->table_file)->where($dieukien);
        return $this->db->count_all_results();
    }

    function info_giabo($id) {
        $dulieu = $this->db->select("*")->from("mau_giabo")->where("giabo_id", $id)->get()->result();
        return $dulieu[0]->giabo_code;
    }

    function dongia($nenmau_id) {
        $dieukien = "a.nenmau_id='" . $nenmau_id . "' and a.chitieu_id=b.chitieu_id and b.chitieu_status='1'";
        $this->db->select('*');
        $this->db->from('mau_dongia as a , mau_chitieu as b');
        $this->db->where($dieukien);
        $dulieu = $this->db->get();
        return $dulieu->result();
    }

    function dachon($giabo_id) {
        $dulieu = $this->db->select("*")->from("mau_giabo_dongia")->where('giabo_id', $giabo_id)->get();
        return $dulieu->result();
    }

    function xoa_bogia($giabo_id, $package_code) {
        $dieukien = array(
            'giabo_id' => $giabo_id,
            'package_code' => $package_code,
        );
        $this->db->where($dieukien);
        return $this->db->delete('mau_giabo_dongia');
    }

    function xoahet($giabo_id) {
        $this->db->where('giabo_id', $giabo_id);
        $this->db->delete('mau_giabo_dongia');
    }

    function addgiabo($giabo_id, $package_code) {
        $dulieu = array(
            'giabo_id' => $giabo_id,
            'package_code' => $package_code,
        );
        return $this->db->insert('mau_giabo_dongia', $dulieu);
    }

    function info_nenmau($id) {
        $dulieu = $this->db->select("*")->from('mau_nenmau')->where("nenmau_id", $id)->get();
        return $dulieu->result();
    }

}