<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_baogia extends MY_Model implements VattuInterface {

    var $table = 'denghi';
    var $column = array('denghi_id', 'denghi_title', 'denghi_date');
    var $order = array('denghi_id' => 'DESC');

    private function get_datatables_query() {
        $dieukien = array(
            "nhansu_nhan" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '2'
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
            "nhansu_nhan" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '2'
        );
        $this->db->from($this->table)->where($dieukien);
        return $this->db->count_all_results();
    }

    function check($denghi_id) {
        $dulieu = $this->db->select('*')->from("denghi")->where("denghi_idparent", $denghi_id)->get();
        if ($dulieu->num_rows() == 0) {
            return false;
        } else {
            $dulieu = $dulieu->row();
            return $dulieu;
        }
    }

    function hientai($denghi_id) {
        $dulieu = $this->db->select('*')->from("denghi")->where("denghi_id", $denghi_id)->get();
        $dulieu = $dulieu->row();
        return $dulieu;
    }

    function denghi_detail($denghi_id) {
        $dieukien = array(
            'denghi_idparent' => $denghi_id,
        );
        $kiemtra = $this->db->select('*')->from("denghi")->where($dieukien)->get();
        $kiemtra_true = $kiemtra->row();
        $select = "denghi_detail as a, sanpham as b , hang as c";
        $dieukien = "a.denghi_id='" . $kiemtra_true->denghi_id . "' and a.sp_id=b.sp_id and a.hang_id=c.hang_id";
        $dulieu = $this->db->select('*')->from($select)->where($dieukien)->get();
        return $dulieu->result();
    }
    function _insertbaogia($value) {
        if(!$this->db->insert('denghi_baogia', $value)) return FALSE;
        return TRUE;
    }
    function _getFilebaogia($id_denghi, $id_file) {
        $this->db->select('*');
        $this->db->from('denghi_baogia');
        $this->db->join('file','file.id_file=denghi_baogia.id_file', 'left');
        $this->db->where('denghi_baogia.denghi_id', $id_denghi);
        if(!empty($id_file)) {
            $this->db->where('denghi_baogia.id_file', $id_file);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function _deletebaogia($denghi_id, $file_id) {
        $this->db->where(array('denghi_id' => $denghi_id, 'id_file' => $file_id));
        $this->db->delete('denghi_baogia');
        $this->db->where('id_file', $file_id);
        $this->db->delete('file');
    }
    function _getNCCSanPham($id_sanpham) {
        $this->db->select('*');
        $this->db->from('nhacungcap');
        $this->db->join('nhacungcap_sanpham', 'nhacungcap_sanpham.ncc_id=nhacungcap.ncc_id', 'left');
        $this->db->where('nhacungcap_sanpham.sp_id', $id_sanpham);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
