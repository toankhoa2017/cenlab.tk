<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('TailieuInterface');

class Mod_file extends MY_Model implements TailieuInterface {

    var $table_file = 'file';
    var $column_file_search = array('file_name');
    var $order_file = array('file_id' => 'DESC');
    var $table_type = 'file_type';
    var $column_type_search = array('ftype_name');
    var $order_type = array('ftype_id' => 'DESC');
    var $table_forder = "file_forder";

    function danhsachftype() {
        $dulieu = $this->db->select("*")->from($this->table_type)->where('ftype_status', '0')->order_by("ftype_idparent", "asc")->get();
        if ($dulieu->num_rows() >= 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    var $column = array('file_name');

    private function get_datatables_query($id_file_type) {
        $dieukien = array(
            'file_status' => '0',
            'ftype_id' => $id_file_type
        );
        $this->db->from($this->table_file)->where($dieukien)->order_by("file_date", "desc");
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

    function danhsachfile($id_f_type) {
        $dieukien = array(
            'file_status' => '0',
            'ftype_id' => $id_f_type
        );
        $this->get_datatables_query($id_f_type);
        if (@$_POST['length'] != -1)
            $this->db->where($dieukien)->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id_f_type) {
        $dieukien = array(
            'file_status' => '0',
            'ftype_id' => $id_f_type
        );
        $this->get_datatables_query($id_f_type);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($id_f_type) {
        $dieukien = array(
            'file_status' => '0',
            'ftype_id' => $id_f_type
        );
        $this->db->from($this->table_file)->where($dieukien);
        return $this->db->count_all_results();
    }

    function themthumuc($data) {
        $dulieu = $this->db->from($this->table_type)->where($data)->get();
        if ($dulieu->num_rows() > 0) {
            return false;
        } else {
            return $this->db->insert($this->table_type, $data);
        }
    }

    function getlink($idparent) {
        $dulieu = $this->db->from($this->table_type)->where('ftype_id', $idparent)->get();
        if ($dulieu->num_rows() == 0) {
            return false;
        } else {
            return $dulieu->result();
        }
    }

    function xoathumuc($id) {
        $mangxuly = array(
            'ftype_status' => '1'
        );
        $this->db->where('ftype_id', $id);
        return $this->db->update($this->table_type, $mangxuly);
    }

    function get_suathumuc($id) {
        $dieukien = array(
            ftype_id => $id,
            ftype_status => '0'
        );
        $dulieu = $this->db->select("*")->from($this->table_type)->where($dieukien)->get();
        if ($dulieu->num_rows() == 1) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function suathumuc($data) {
        $kiemtra = $this->db->select('ftype_id')->from($this->table_type)->where("ftype_name='" . $data['ftype_name'] . "' and ftype_id!='" . $data['ftype_id'] . "' and ftype_status='0'")->get();
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('ftype_id', $data['ftype_id']);
            return $this->db->update($this->table_type, $data);
        } else {
            return false;
        }
    }

    function xoafile($id) {
        $mangxuly = array(
            'file_status' => '1'
        );
        $this->db->where('file_id', $id);
        return $this->db->update($this->table_file, $mangxuly);
    }

    function uploadfile($data) {
        $dulieu = $this->db->from($this->table_file)->where($data)->get();
        if ($dulieu->num_rows() > 0) {
            return false;
        } else {
            return $this->db->insert($this->table_file, $data);
        }
    }

    function kiemtraname($data) {
        $dulieu = $this->db->select("file_id")->from($this->table_file)->where($data)->get();
        if ($dulieu->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function get_file($id) {
        $dieukien = array(
            'file_status' => '0',
            'file_id' => $id
        );
        $dulieu = $this->db->select("*,date_format(file_date,'%H:%i:%s %d/%m/%Y') as datetao ")->from($this->table_file)->where($dieukien)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function rename($data) {
//        $kiemtra = $this->db->select('file_id')->from($this->table_file)->where("file_name='" . $data['file_name'] . "' and file_id!='" . $data['file_id'] . "' and file_status='0'")->get();
//        if ($kiemtra->num_rows() == 0) {
        $this->db->where('file_id', $data['file_id']);
        return $this->db->update($this->table_file, $data);
//        } else {
//            return false;
//        }
    }

    function thumuccha() {
        $dulieu = $this->db->select("*")->from($this->table_type)->where("ftype_status", "0")->get();
        return $dulieu->result();
    }
    
    function forder(){
        $dulieu = $this->db->select("*")->from($this->table_forder)->get();
        return $dulieu->result();
    }
    
    function get_forder($id_forder){
        $dulieu = $this->db->select("*")->from("file_forder as a, file_type as b")->where("b.ftype_id='".$id_forder."' and b.file_forder_id=a.file_forder_id")->get()->result();
        return $dulieu[0]->file_forder_path;
    }
    
    function get_forder1($id_forder){
        $dulieu = $this->db->select("*")->from("file_forder as a, file_type as b")->where("b.ftype_id='".$id_forder."' and b.file_forder_id=a.file_forder_id")->get()->result();
        return $dulieu[0]->file_forder_name;
    }
    
    function test(){
        $dulieu = $this->db->select("*")->from("file_type")->get()->result();
        return $dulieu;
    }

//    function get_token($data){
//        $dulieu = $this->db->select("*")->from($this->table_file)->where($data)->get();
//        if($dulieu->num_rows()>0){
//            return $dulieu->result();
//        }else{
//            return false;
//        }
//    }
//    function xoafiletamthoi($data){
//        $this->db->where($data);
//        $this->db->delete($this->table_file);
//    }
}
