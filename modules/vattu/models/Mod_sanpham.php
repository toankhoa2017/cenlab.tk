<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_sanpham extends MY_Model implements VattuInterface {
    
    var $table_loaisanpham = "loaisanpham";
    var $table_sanpham = "sanpham";
    
    function danhsach_loai(){
        $dulieu = $this->db->select("*")->from($this->table_loaisanpham)->get();
        return $dulieu->result();
    }
    
    var $table_sanpham_loaisanpham = 'sanpham as a , loaisanpham as b';
    var $column = array('a.sp_id', 'a.sp_code', 'a.sp_name', 'a.sp_mota');
    var $order = array('a.sp_id' => 'DESC');


    private function get_datatables_query($loai_id) {
        $dieukien = "a.loai_id = b.loai_id and a.loai_id='".$loai_id."' and a.sp_status='1'";
        $this->db->from($this->table_sanpham_loaisanpham)->where($dieukien);
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

    function get_datatables($loai_id) {
        $this->get_datatables_query($loai_id);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($loai_id) {
        $this->get_datatables_query($loai_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($loai_id) {
        $dieukien = "a.loai_id = b.loai_id and a.loai_id='".$loai_id."' and a.sp_status='1'";
        $this->db->from($this->table_sanpham_loaisanpham)->where($dieukien);
        return $this->db->count_all_results();
    }
    
    function _create($values) {
        $dieukien = array(
            'sp_code' => $values['sp_code'],
        );
        $kiemtra = $this->db->select("*")->from($this->table_sanpham)->where($dieukien)->get()->num_rows();
        if ($kiemtra == 0) {
            $count = $this->db->select("*")->from($this->table_sanpham)->where('loai_id',$values['loai_id'])->get();
            $count = $count->num_rows() + 1;
            if($values['sp_code']==""){
                $sp_code = $count;
            }else{
                $sp_code = $count.'_'.$values['sp_code'];
            }
            $insert = array(
                'sp_code' => $sp_code,
                'sp_name' => $values['sp_name'],
                'loai_id' => $values['loai_id'],
                'sp_mota' => $values['sp_mota'],
            );
            return $this->db->insert($this->table_sanpham, $insert);
        } else {
            return false;
        }
    }
    
    function suasanpham($data){
        $kiemtra = $this->db->select('sp_id')->from($this->table_sanpham)->where("sp_code ='" . $data['sp_code '] . "' and sp_id!='" . $data['sp_id'] . "' and sp_status='1'")->get();
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('sp_id', $data['sp_id']);
            return $this->db->update($this->table_sanpham, $data);
        } else {
            return false;
        }
    }
    
    function xoasanpham($sp_id){
        $data = array(
            'sp_status' => '2'
        );
        $this->db->where('sp_id', $sp_id);
        return $this->db->update($this->table_sanpham, $data);
    }
    
    function check_code($sp_id){
        $kiemtra = $this->db->select('sp_code')->from($this->table_sanpham)->where("sp_id",$sp_id)->get();
        return $kiemtra->row();
    }
    
}