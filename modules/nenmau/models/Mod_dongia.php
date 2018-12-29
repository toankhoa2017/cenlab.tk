<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_dongia extends MY_Model implements NenmauInterface {

    var $table = 'mau_dongia';
    var $column = array('dongia_id', 'package_code'); //set column field database for order and search
    var $order = array('dongia_id' => 'DESC'); // default order 

    private function _get_datatables_query($nenmau, $getall = FALSE) {
        $this->db->select('
            dongia.*,
            chitieu.chitieu_name,
            chitieu.chitieu_name_eng,
            phuongphap.phuongphap_name,
            phuongphap.phuongphap_status,
            kythuat.kythuat_name,
            kythuat.kythuat_status,
            donvitinh.donvitinh_name,
            nenmau.thoigian as thoigianluu
        ');
        $this->db->from($this->table.' dongia');
        $this->db->join('mau_chitieu chitieu', 'dongia.chitieu_id = chitieu.chitieu_id');
        $this->db->join('mau_phuongphap phuongphap', 'dongia.phuongphap_id = phuongphap.phuongphap_id');
        $this->db->join('mau_kythuat kythuat', 'dongia.kythuat_id = kythuat.kythuat_id');
        $this->db->join('mau_donvitinh donvitinh', 'dongia.donvitinh_id = donvitinh.donvitinh_id');
        $this->db->join('mau_nenmau_chitieu nenmau', 'dongia.nenmau_id = nenmau.nenmau_id AND dongia.chitieu_id = nenmau.chitieu_id');
        $dieukien = array(
            'dongia.nenmau_id' => $nenmau
            //'donvi_status' => '0'
        );
        if (!$getall) $dieukien['dongia.donvi_id'] = $this->session->userdata('ssAdminDonvi');
        $this->db->where($dieukien);
        $i = 0;
        foreach ($this->column as $item) {//loop column 
            if (@$_POST['search']['value']) {//if datatable send POST for search
                if ($i === 0) {//first loop
                    $this->db->group_start(); //open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                //last loop
                if (count($this->column) - 1 == $i)
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; //set column array variable to order processing
            $i++;
        }
        if (isset($_POST['order'])) {//here order processing
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables($nenmau, $getall) {
        $this->_get_datatables_query($nenmau, $getall);
        if (@$_POST['length'] != -1) $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get(); //->where($this->status, "0")
        //echo $this->db->last_query();
        return $query->result();
    }
    function count_filtered($nenmau, $getall) {
        $this->_get_datatables_query($nenmau, $getall);
        $query = $this->db->get(); //->where($this->status, "0")
        return $query->num_rows();
    }
    function count_all($nenmau, $getall) {
        $this->db->from($this->table); //->where($this->status, "0")
        $dieukien = array(
            'nenmau_id' => $nenmau
            //'donvi_status' => '0'
        );
        if (!$getall) $dieukien['donvi_id'] = $this->session->userdata('ssAdminDonvi');
        $this->db->where($dieukien);
        return $this->db->count_all_results();
    }

    function _create($chitieu, $values) {
        $sodong = $this->db->select('max(dongia_id) as idcaonhat')->from('mau_dongia')->get()->result();
        $ma_code = str_pad(((int) $sodong[0]->idcaonhat + 1), 4, "0", STR_PAD_LEFT);
        $giatri = array(
            'package_code' => 'BO_' . $ma_code,
            'nenmau_id' => $values['nenmau'],
            'chitieu_id' => $chitieu,
            'phuongphap_id' => $values['phuongphap_id'],
            'phuongphap_ref' => $values['phuongphap_id_bn'] ? $values['phuongphap_id_bn'] : NULL,
            'kythuat_id' => $values['kythuat_id'],
            'donvi_id' => $values['phongthinghiem'],
            'donvitinh_id' => $values['donvi'],
            'price' => 0,
            'thoigian' => $values['thoigian'],
        );
        return $this->db->insert('mau_dongia', $giatri);        
    }
    function _updateDonGia($dongia){
        if ($dongia->package_status == '2') {
            $giatri = array(
                'donvitinh_id' => $donvi_id,
                'price' => '0',
                'thoigian' => $thoigian,
                'package_status' => '1'
            );
            $this->db->update("mau_dongia", $giatri);
            return $dongia->dongia_id;
        } else {
            return false;
        }
    }
    function _setgia($gia, $dongia) {
        $dieukien = array(
            "dongia_id" => $dongia
        );
        $update = array(
            "price" => $gia
        );
        $this->db->where($dieukien);
        $this->db->update($this->table, $update);
    }
    
    function getChiTieuGia($khachhang_id){
        $this->db->select('
            nenmau.nenmau_name,
            chitieu.chitieu_name,
            chat.chat_name,
            dongia_khachhang.price as giatong,
            chatgia.gia_price as giadon,
            dongia.dongia_id,
            dongia_khachhang.khachhang_id
        ');
        $this->db->from($this->table.' dongia');
        $this->db->join('mau_dongia_khachhang dongia_khachhang', 'dongia_khachhang.dongia_id = dongia.dongia_id', 'left');
        $this->db->join('mau_chatgia chatgia', 'chatgia.bo_id = dongia.dongia_id and dongia_khachhang.khachhang_id = chatgia.khachhang_id', 'left');
        $this->db->join('mau_chitieu chitieu', 'dongia.chitieu_id = chitieu.chitieu_id');
        $this->db->join('mau_nenmau nenmau', 'nenmau.nenmau_id = dongia.nenmau_id');
        $this->db->join('mau_chitieu_chat chitieu_chat', 'dongia.dongia_id = chitieu_chat.dongia_id');
        $this->db->join('mau_chat chat', 'chitieu_chat.chat_id = chat.chat_id');
        $this->db->where(array('chatgia.khachhang_id' => $khachhang_id));
        $this->db->or_where(array('dongia_khachhang.khachhang_id' => $khachhang_id));
        //echo $this->db->get_compiled_select();        die(1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function getGiaTongKH($khachhang_id){
        $this->db->select('dongia_id, price');
        $this->db->from('mau_dongia_khachhang');
        $this->db->where(array('khachhang_id' => $khachhang_id));
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function getGiaDonKH($khachhang_id){
        $this->db->select('bo_id as dongia_id, gia_price as price');
        $this->db->from('mau_chatgia');
        $this->db->where(array('khachhang_id' => $khachhang_id));
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
    
    function getInfoChitieuByDonGia($dongia_ids){
        $this->db->select('
            dongia.dongia_id,
            nenmau.nenmau_name,
            chitieu.chitieu_name,
            chat.chat_name,
        ');
        $this->db->from($this->table.' dongia');
        $this->db->join('mau_chitieu chitieu', 'dongia.chitieu_id = chitieu.chitieu_id');
        $this->db->join('mau_nenmau nenmau', 'nenmau.nenmau_id = dongia.nenmau_id');
        $this->db->join('mau_chitieu_chat chitieu_chat', 'dongia.dongia_id = chitieu_chat.dongia_id');
        $this->db->join('mau_chat chat', 'chitieu_chat.chat_id = chat.chat_id');
        $this->db->where_in('dongia.dongia_id', $dongia_ids);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}