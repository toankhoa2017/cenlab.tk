<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NhansuInterface');
class Mod_chucvu extends MY_Model implements NhansuInterface {
    var $parent = 0;
    var $table = 'chucvu';
    var $column = array('chucvu_id', 'chucvu_ten'); //set column field database for order and search
    var $order = array('chucvu_id' => 'ASC'); // default order 
    var $listUps = array();
    var $listCheck = array(); //Check dieu kien dung de quy
    var $status = '';//chucvu_status

    //Datatable
    private function _get_datatables_query() {
        $this->db->from($this->table);
        $this->db->where('chucvu_status', 1);
        $dieukien = array(
            'chucvu_idparent' => $this->parent,
            //'chucvu_status' => '0'
        );
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
    function get_datatables() {
        $this->_get_datatables_query();
        if (@$_POST['length'] != -1) $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();//->where($this->status, "0")
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from($this->table);
        $this->db->where('chucvu_idparent', $this->parent);
        $this->db->where('chucvu_status', 1);
        return $this->db->count_all_results();
    }
    //End datatable
    function _create($values) {
        $insert = array(
            'chucvu_idparent' => $values['parent'],
            'chucvu_ten' => $values['name'],
            //'chucvu_status' => '0'
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($insert)->where('chucvu_status', 1)->get();
        if ($kiemtra->num_rows() > 0) {
            return FALSE;
        } else {
            if ($values['ref'])
                $insert['chucvu_ref'] = $values['ref'];
                $insert['chucvu_level'] = $values['chucvu_level'];
            if (!$this->db->insert($this->table, $insert))
                return FALSE;
            return TRUE;
        }
    }	
    function _update($data) {
        $kiemtra = $this->db->select('chucvu_id')->from($this->table)->where("chucvu_ten='" . $data['chucvu_ten'] . "' and chucvu_id!='" . $data['chucvu_id'] . "' and chucvu_status=1")->get();// and chucvu_status='0'
        if ($kiemtra->num_rows() == 0) {
            $this->db->where('chucvu_id', $data['chucvu_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }
    function _delete($id) {
        $mangxuly = array(
            'chucvu_status' => 2
        );
        $this->db->where('chucvu_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }
    function _levelUps($id, $root = 0) {
        if (trim($id) != '') {
            $this->listCheck[] = $id; //Dua vao listCheck de kiem tra dieu kien dung
            $this->db->select('chucvu_idparent, chucvu_ten');
            $this->db->from($this->table);
            $this->db->where('chucvu_id', $id);
            $query = $this->db->get();
            $result = $query->row_array();
            $nenmau = array(
                'id' => $id,
                'name' => $result['chucvu_ten']
            );
            $query->free_result();
            array_unshift($this->listUps, $nenmau); //Them vao thu tu dau tien
            if (($result['chucvu_idparent'] != 0) && ($id != $root) && (!in_array($result['chucvu_idparent'], $this->listCheck))) {
                $this->_levelUps($result['chucvu_idparent'], $root);
            }
        }
        return FALSE;
    }
    function _getRef($id) {
        $this->db->select('chucvu_ref ref');
        $this->db->from($this->table);
        $this->db->where('chucvu_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
        ;
    }
    function _getdanhsachcvu($id) {
        $this->db->select('chucvu_id, chucvu_ten');
        $this->db->from('chucvu');
        $this->db->where('chucvu_status', 1);
        $this->db->where('chucvu_id NOT IN(select chucvu_id from donvi_chucvu where donvi_id = '.(int)$id.')', null, false);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : false;
    }	
    function _InsertDV_CV($values) {
        if(!$this->db->insert('donvi_chucvu',$values)) return false;
        return true;
    }
    function _UpdateDV_CV($iddv,$idcv,$values) {
        $this->db->where('donvi_id',$iddv);
        $this->db->where('chucvu_id',$idcv);
        $this->db->update('donvi_chucvu',$values);
    }
    function _DeleteDV_CV($iddv, $idcv) {
        $this->db->where('donvi_id',$iddv);
        $this->db->where('chucvu_id',$idcv);
        $this->db->delete('donvi_chucvu');
    }
}

/* End of file */