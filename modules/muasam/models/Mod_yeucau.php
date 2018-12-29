<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_yeucau extends MY_Model implements VattuInterface {

    var $table = 'denghi';
    var $column = array('denghi_id', 'denghi_title', 'denghi_date');
    var $order = array('denghi_id' => 'DESC');

    private function get_datatables_query() {
        $dieukien = array(
            "nhansu_goi" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '1'
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
            "nhansu_goi" => $this->session->userdata('ssAdminId'),
            "quytrinh_id" => '1'
        );
        $this->db->from($this->table)->where($dieukien);
        return $this->db->count_all_results();
    }

    function danhsach_loaisanpham() {
        $dulieu = $this->db->select("*")->from("loaisanpham")->get();
        return $dulieu->result();
    }

    function danhsach_nhacungcap() {
        $dulieu = $this->db->select("*")->from("nhacungcap")->get();
        return $dulieu->result();
    }

    function danhsach_sanpham($loai_id) {
        $dulieu = $this->db->select("*")->from("sanpham")->where('loai_id', $loai_id)->get();
        return $dulieu->result();
    }

    function danhsach_hang() {
        $dulieu = $this->db->select("*")->from("hang")->get();
        return $dulieu->result();
    }

    function create($denghi_id, $denghi_idparent, $denghi_ref, $denghi_vesion, $denghi_title, $denghi_describe, $quytrinh_id, $nhansu_nhan) {
        if ($denghi_id != "") {
            $update_status = array(
                'denghi_status' => '2',
                'denghi_approve' => '1',
            );
            $this->db->where('denghi_id', $denghi_id);
            $this->db->update('denghi', $update_status);
        }
        $data = array(
            'denghi_date' => date("Y-m-d H:i:s"),
            'denghi_title' => $denghi_title,
            'denghi_describe' => $denghi_describe,
            'quytrinh_id' => $quytrinh_id,
            'nhansu_goi' => $this->session->userdata('ssAdminId'),
            'nhansu_nhan' => $nhansu_nhan,
            'denghi_idparent' => $denghi_idparent,
            'denghi_ref' => $denghi_ref,
            'denghi_vesion' => $denghi_vesion
        );
        $this->db->insert('denghi', $data);
        return $this->db->insert_id();
    }

    function create_detail($data) {
        return $this->db->insert('denghi_detail', $data);
    }

    function denghi($denghi_id, $denghi) {
        $dieukien = array(
            'denghi_idparent' => $denghi_id,
        );
        $kiemtra = $this->db->select('*')->from("denghi")->where($dieukien)->get();
        if ($kiemtra->num_rows() > 0 && !isset($denghi)) {
            return $kiemtra->row();
        } else {
            $dulieu = $this->db->select('*')->from("denghi")->where("denghi_id", $denghi_id)->get();
            return $dulieu->row();
        }
    }

    function denghi_detail($denghi_id, $denghi, $cuoi) {
        if (isset($cuoi) && $cuoi == 5) {
            $dieukien = array(
                'denghi_idparent' => $denghi_id,
            );
        } else {
            $dieukien = array(
                'denghi_idparent' => $denghi_id,
                'denghi_approve' => 1
            );
        }
        $kiemtra = $this->db->select('*')->from("denghi")->where($dieukien)->get();
        if ($kiemtra->num_rows() > 0 && (!isset($denghi))) {
            $kiemtra_true = $kiemtra->row();
            $select = "denghi_detail as a, sanpham as b , hang as c , donvitinh as d";
            $dieukien = "a.denghi_id='" . $kiemtra_true->denghi_id . "' and a.sp_id=b.sp_id and a.hang_id=c.hang_id and a.donvitinh_id=d.donvitinh_id";
            $dulieu = $this->db->select('*')->from($select)->where($dieukien)->get();
            return $dulieu->result();
        } else {
            $dieukien = array(
                'denghi_idparent' => $denghi_id,
                'denghi_approve' => 0
            );
            $kiemtra = $this->db->select('*')->from("denghi")->where($dieukien)->get();
            if ($kiemtra->num_rows() > 0) {
                $kiemtra_true = $kiemtra->row();
                $select = "denghi_detail as a, sanpham as b , hang as c , donvitinh as d";
                $dieukien = "a.denghi_id='" . $kiemtra_true->denghi_id . "' and a.sp_id=b.sp_id and a.hang_id=c.hang_id and a.donvitinh_id=d.donvitinh_id";
                $dulieu = $this->db->select('*')->from($select)->where($dieukien)->get();
            } else {
                $select = "denghi_detail as a, sanpham as b , hang as c , donvitinh as d";
                $dieukien = "a.denghi_id='" . $denghi_id . "' and a.sp_id=b.sp_id and a.hang_id=c.hang_id and a.donvitinh_id=d.donvitinh_id";
                $dulieu = $this->db->select('*')->from($select)->where($dieukien)->get();
            }
            return $dulieu->result();
        }
    }

    function danhsach_all_sanpham() {
        $dulieu = $this->db->select("*")->from("sanpham")->get();
        return $dulieu->result();
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

    function get_denghi($denghi_id, $vesion) {
        if ($vesion < 3) {
            $from_table = "denghi as a,denghi_detail as b,sanpham as c,hang as d";
            $where_table = "a.denghi_id='" . $denghi_id . "' and a.denghi_id=b.denghi_id and b.sp_id=c.sp_id and b.hang_id=d.hang_id";
        } else {
            $from_table = "denghi as a,denghi_detail as b,sanpham as c,hang as d,nhacungcap as e";
            $where_table = "a.denghi_id='" . $denghi_id . "' and a.denghi_id=b.denghi_id and b.sp_id=c.sp_id and b.hang_id=d.hang_id and b.ncc_id=e.ncc_id";
        }
        $dulieu = $this->db->select("*")->from($from_table)->where($where_table)->get();
        if ($dulieu->num_rows() == 0) {
            return false;
        } else {
            return $dulieu->result();
        }
    }

    function get_denghi_parent($denghi_id, $vesion) {
        if ($vesion < 3) {
            $from_table = "denghi as a,denghi_detail as b,sanpham as c,hang as d";
            $where_table = "a.denghi_idparent='" . $denghi_id . "' and a.denghi_id=b.denghi_id and b.sp_id=c.sp_id and b.hang_id=d.hang_id";
        } else {
            $from_table = "denghi as a,denghi_detail as b,sanpham as c,hang as d,nhacungcap as e";
            $where_table = "a.denghi_idparent='" . $denghi_id . "' and a.denghi_id=b.denghi_id and b.sp_id=c.sp_id and b.hang_id=d.hang_id and b.ncc_id=e.ncc_id";
        }
        $dulieu = $this->db->select("*")->from($from_table)->where($where_table)->get();
        if ($dulieu->num_rows() == 0) {
            return false;
        } else {
            return $dulieu->result();
        }
    }

    function kiemtra_update($denghi_id, $ghichu) {
        $dieukien = array(
            'denghi_idparent' => $denghi_id,
            'denghi_approve' => 1
        );
        $dulieu = $this->db->select("*")->from("denghi")->where($dieukien)->get();
        if ($dulieu->num_rows() > 0) {
            return false;
        } else {
            $data = array(
                'denghi_approve' => '0',
                'denghi_describe' => $ghichu
            );
            $this->db->where("denghi_id", $denghi_id);
            $this->db->update("denghi", $data);
            $this->db->where("denghi_id", $denghi_id);
            $this->db->delete("denghi_detail");
            return true;
        }
    }

    function update_denghi($denghi_id) {
        $dulieu = $this->db->select("*")->from("denghi")->where('denghi_id', $denghi_id)->get()->row();
        $data = array(
            'denghi_approve' => '1'
        );
        $dieukien = array(
            'denghi_id' => $dulieu->denghi_idparent
        );
        $this->db->where($dieukien);
        $this->db->update("denghi", $data);
    }

    function xoa_file_baogia($denghi_id) {
        $this->db->where('denghi_id', $denghi_id);
        $this->db->delete('denghi_baogia');
    }

    function file_baogia($denghi_id, $file_id) {
        $data = array(
            'denghi_id' => $denghi_id,
            'file_id' => $file_id,
        );
        $this->db->insert("denghi_baogia", $data);
    }

    function danhsach_file($denghi_id) {
        $dulieu = $this->db->select("*")->from("denghi_baogia")->where("denghi_id", $denghi_id)->get();
        return $dulieu->result();
    }

    function khongduyetyeucau($denghi_id, $approve_comment) {
        $this->db->where('denghi_id', $denghi_id);
        $dulieu = array(
            'denghi_approve' => '2'
        );
        $this->db->update('denghi', $dulieu);
        $data = array(
            'denghi_id' => $denghi_id,
            'approve_comment' => $approve_comment
        );
        $this->db->insert('denghi_approve', $data);
    }

    function denghi_approve($denghi_id) {
        $dulieu = $this->db->select("*")->from("denghi_approve")->where('denghi_id', $denghi_id)->get();
        return $dulieu->result();
    }

    function danhsach_donvitinh() {
        $dulieu = $this->db->select("*")->from("donvitinh")->where('donvitinh_status', '1')->get();
        return $dulieu->result();
    }
    function getIDParent($id) {
        $this->db->select('denghi_idparent');
        $this->db->from('denghi');
        $this->db->where('denghi_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        if($result && count($result) > 0)   return $result['denghi_idparent'];
        return 0;
    }
}
