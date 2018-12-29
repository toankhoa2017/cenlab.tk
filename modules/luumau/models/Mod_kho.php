<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('LuumauInterface');

class Mod_kho extends MY_Model implements LuumauInterface {

    var $parent = 0;
    var $table = 'kho';
    var $column = array('kho_id', 'kho_name'); //set column field database for order and search
    var $order = array('kho_id' => 'DESC'); // default order 
    var $listUps = array();
    var $listCheck = array(); //Check dieu kien dung de quy

    //Datatable

    private function get_datatables_query($donvi_id) {
        $dieukien = "kho_idparent='" . $this->parent . "' and kho_status='1' and donvi_id='" . $donvi_id . "'";
        $this->db->from($this->table);
        $this->db->where($dieukien);
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

    function get_datatables($donvi_id) {
        $this->get_datatables_query($donvi_id);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->where('kho_status', '1')->get();
        return $query->result();
    }

    function count_filtered($donvi_id) {
        $this->get_datatables_query($donvi_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($donvi_id) {
        $dieukien = "kho_idparent='" . $this->parent . "' and kho_status='1' and donvi_id='" . $donvi_id . "'";
        $this->db->from($this->table)->where($dieukien);
        return $this->db->count_all_results();
    }

    function _getRef($id) {
        $this->db->select('kho_ref ref');
        $this->db->from($this->table);
        $this->db->where('kho_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }

    //End datatable
    function _create($items) {
        if (!isset($items['soluong'])) {
            $dieukien = array(
                'kho_name' => $items['kho_name'],
                'kho_status' => '1',
                'kho_idparent' => $items['parent']
            );
            $kiemtra = $this->db->select("*")->from($this->table)->where($dieukien)->get()->num_rows();
            if ($kiemtra == 0) {
                $insert = array(
                    'kho_name' => $items['kho_name'],
                    'kho_mota' => $items['kho_mota'],
                    'kho_loai' => $items['kho_loai'],
                    'thietbi_id' => $items['thietbi_id'] == "" ? NULL : $items['thietbi_id'],
                    'donvi_id' => $this->session->userdata('ssAdminDonvi'),
                    'kho_ref' => $items['ref'],
                    'kho_idparent' => $items['parent'],
                    'kho_level' => $items['kho_level'],
                    'kho_max_level' => $items['kho_max_level']
                );
                return $this->db->insert($this->table, $insert);
            } else {
                return false;
            }
        } else {
            for($i=1;$i<=(int)$items['soluong'];$i++){
                $insert = array(
                    'kho_name' => $items['kho_name']."_".$i,
                    'kho_mota' => $items['kho_mota'],
                    'kho_loai' => $items['kho_loai'],
                    'thietbi_id' => $items['thietbi_id'] == "" ? NULL : $items['thietbi_id'],
                    'donvi_id' => $this->session->userdata('ssAdminDonvi'),
                    'kho_ref' => $items['ref'],
                    'kho_idparent' => $items['parent'],
                    'kho_level' => $items['kho_level'],
                    'kho_max_level' => $items['kho_max_level']
                );
                $this->db->insert($this->table, $insert);
            }
            return true;
        }
    }

    function info_kho($id) {
        $dulieu = $this->db->select("*")->from($this->table)->where("kho_id", $id)->get();
        return $dulieu->result();
    }

    function xoakho($id) {
        $update = array(
            'kho_status' => '2'
        );
        $this->db->where('kho_id', $id);
        return $this->db->update($this->table, $update);
    }

    function update_donvi_kho($donvi_id, $kho_id) {
        $data = array(
            'donvi_id' => $donvi_id
        );
        $this->db->where('kho_id', $kho_id);
        $this->db->update($this->table, $data);
    }

    function suakho($data) {
        $kiemtra = $this->db->select('kho_id')->from($this->table)->where("kho_name='" . $data['kho_name'] . "' and kho_id!='" . $data['kho_id'] . "' and kho_status='1'")->get();
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('kho_id', $data['kho_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->where('kho_status', '1')->get()->result(); //->where('phuongphap_status','0')
    }

    function _capbac_kho($ref) {
        $chia = explode("-", $ref);
        $dulieu = "";
        for ($i = 0; $i < count($chia); $i++) {
            if ($chia[$i] != "") {
                $giatri = $this->db->select("kho_name,kho_id")->from($this->table)->where("kho_id", $chia[$i])->get()->result();
                if ($dulieu == "") {
                    $dulieu .= '<a href="' . base_url() . 'luumau/kho?id=' . $giatri[0]->kho_id . '">' . $giatri[0]->kho_name . '</a>';
                } else {
                    $dulieu .= ' &rightarrow; <a href="' . base_url() . 'luumau/kho?id=' . $giatri[0]->kho_id . '">' . $giatri[0]->kho_name . '</a>';
                }
            }
        }
        return $dulieu;
    }
    
    function get_kho_max_level($kho_id){
        $dulieu = $this->db->select("kho_max_level")->from("kho")->where("kho_id",$kho_id)->get()->result();
        return $dulieu[0]->kho_max_level;
    }

}

/* End of file */