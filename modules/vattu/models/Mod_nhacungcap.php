<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('VattuInterface');

class Mod_nhacungcap extends MY_Model implements VattuInterface {

    var $table = 'nhacungcap';
    var $column = array('ncc_id', 'ncc_name', 'ncc_address');
    var $order = array('ncc_id' => 'DESC');
    var $column_spncc = array('loai_name', 'sp_name');
    var $order_spncc = array('nhacungcap_sanpham.loai_id' => 'DESC');
    
    private function get_datatables_query() {
        $this->db->from($this->table);
		$this->db->where('ncc_status','1');
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
	
	function _update($id, $data){
		$this->db->where('ncc_id', $id);
		$this->db->update($this->table, $data);
	}

    function _create($values) {
        $insert = array(
            'ncc_name' => $values['ncc_name'],
            'ncc_address' => $values['ncc_address'],
            'ncc_hoso ' => $values['hopdong'],
        );
        return $this->db->insert($this->table, $insert);
    }

    function sualoaisanpham($data) {
        $this->db->where('ncc_id', $data['ncc_id']);
        return $this->db->update($this->table, $data);
    }

    function suanhacungcap($data) {
        $this->db->where('ncc_id', $data['ncc_id']);
        return $this->db->update($this->table, $data);
    }
    function _createfile($values) {
        if (!$this->db->insert('file', $values)) return FALSE;
        return $this->db->insert_id();
    }
    function _createprofile($values) {
        if (!$this->db->insert('profile_ncc', $values)) return FALSE;
        return TRUE;
    }
    function _getprofile($id) {
        $this->db->select('*');
        $this->db->join('file','file.id_file=profile_ncc.file_id','left');
        $this->db->from('profile_ncc');
        $this->db->where('id_ncc', $id);
        $this->db->where('profile_status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function _getFile($id) {
        $this->db->select('*');
        $this->db->from('file');
        $this->db->where('id_file', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    function _delFile($id) {
        $this->db->where('id_file', $id);
        $this->db->delete('file');
    }
    function _updateprofile($id, $values) {
        $this->db->where('profile_id', $id);
        $this->db->update('profile_ncc', $values);
    }
    function _InsSPNCC($values) {
        if (!$this->db->insert('nhacungcap_sanpham', $values)) return FALSE;
        return TRUE;
    }
    function _DelSPNCC($value) {
        $this->db->where($value);
        $this->db->delete('nhacungcap_sanpham');
    }
    function _Getlistsp($id_loai, $id_ncc) {
        $this->db->select('*');
        $this->db->from('sanpham');
        $this->db->where('loai_id', $id_loai);
        $this->db->where('sp_id NOT IN(select sp_id from nhacungcap_sanpham where ncc_id = '.(int)$id_ncc.')',null,false);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }

    private function get_datatables_query_spncc($id_ncc) {
        $this->db->from('nhacungcap_sanpham');
        $this->db->join('sanpham','sanpham.sp_id=nhacungcap_sanpham.sp_id','left');
        $this->db->join('loaisanpham', 'loaisanpham.loai_id=sanpham.loai_id','left');
	$this->db->where('ncc_id', $id_ncc);
        $i = 0;
        foreach ($this->column_spncc as $item) {
            $tukhoa = trim(@$_POST['search']['value']);
            if ($tukhoa) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $tukhoa);
                } else {
                    $this->db->or_like($item, $tukhoa);
                }
                if (count($this->column_spncc) - 1 == $i)
                    $this->db->group_end();
            }
            $column[$i] = $item;
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order_spncc)) {
            $order = $this->order_spncc;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_spncc($id_ncc) {
        $this->get_datatables_query_spncc($id_ncc);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_spncc($id_ncc) {
        $this->get_datatables_query_spncc($id_ncc);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_spncc($id_ncc) {
        $this->db->from('nhacungcap_sanpham');
        $this->db->where('ncc_id', $id_ncc);
        return $this->db->count_all_results();
    }

}
