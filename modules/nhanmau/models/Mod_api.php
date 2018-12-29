<?php
get_instance()->load->iface('NenmauInterface');
class Mod_api extends MY_Model implements NenmauInterface {
    var $donvi = FALSE;
    var $post = FALSE;
    var $column = array('mau_code','mau_name','mau_datesave','mau_datesave_yeucau','mau_luu','dieukienluu_name');
    var $order = array('id' => 'DESC'); // default order

    //Datatable
    private function _get_datatables_query() {
        $post = $this->post;
        $this->db->select('
            mau.mau_id id,
            mau.mau_code code,
            mau.mau_name name,
            mau.mau_datesave ngayluu,
            mau.mau_datesave_yeucau ngayluuyeucau,
            mau.mau_luu luu,
            dieukien.dieukienluu_name dieukienluu
        ');
        $this->db->from('nm_mau AS mau');
	$this->db->join('nm_hopdong AS hopdong', 'mau.hopdong_id = hopdong.hopdong_id');
	$this->db->join('mau_nenmau AS nenmau', 'mau.nenmau_id = nenmau.nenmau_id');
        $this->db->join('mau_nenmau_dieukienluu AS luumau', 'mau.nenmau_id = luumau.nenmau_id');
        $this->db->join('mau_dieukienluu AS dieukien', 'luumau.dieukienluu_id = dieukien.dieukienluu_id');
        $this->db->where('nenmau.donvi_id', $this->donvi);
        $this->db->where('hopdong.hopdong_status', 1);
        $i = 0;
        foreach ($this->column as $item) {//loop column 
            if (@$post['search']['value']) {//if datatable send POST for search
                if ($i === 0 ) {//first loop
                    $this->db->group_start(); //open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, @$post['search']['value']);
                }
                else {
                    $this->db->or_like($item, @$post['search']['value']);
                }
                //last loop
                if (count($this->column) - 1 == $i) $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; //set column array variable to order processing
            $i++;
        }
        if (isset($post['order'])) {//here order processing
            $this->db->order_by($column[@$post['order']['0']['column']], @$post['order']['0']['dir']);
        } 
        elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables() {
    	       $post = $this->post;
        $this->_get_datatables_query();
        if (@$post['length'] != -1)
        $this->db->limit(@$post['length'], @$post['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from('nm_mau AS mau');
        $this->db->join('nm_hopdong AS hopdong', 'mau.hopdong_id = hopdong.hopdong_id');
        $this->db->join('mau_nenmau AS nenmau', 'mau.nenmau_id = nenmau.nenmau_id');
        $this->db->join('mau_nenmau_dieukienluu AS luumau', 'mau.nenmau_id = luumau.nenmau_id');
        $this->db->join('mau_dieukienluu AS dieukien', 'luumau.dieukienluu_id = dieukien.dieukienluu_id');
        $this->db->where('hopdong.hopdong_status', 1);
        return $this->db->count_all_results();
    }
    function _getMau($id = FALSE) {
        $this->db->select('
            mau.mau_id id,
            mau.mau_code code,
            mau.mau_name name,
            mau.mau_mass khoiluong,
            mau.mau_description mota,
            mau.mau_amount soluong,
            mau.mau_note ghichu,
            mau.mau_datesave ngayluu,
            mau.mau_datesave_yeucau ngayluuyeucau,
            mau.mau_luu luu,
            donvitinh.donvitinh_name donvi,
            dieukien.dieukienluu_name dieukienluu,
            nenmau.donvi_id phongthinghiem
        ');
        $this->db->from('nm_mau AS mau');
	$this->db->join('mau_nenmau AS nenmau', 'mau.nenmau_id = nenmau.nenmau_id');
        $this->db->join('mau_donvitinh AS donvitinh', 'mau.donvitinh_id = donvitinh.donvitinh_id');
        $this->db->join('mau_nenmau_dieukienluu AS luumau', 'mau.nenmau_id = luumau.nenmau_id');
        $this->db->join('mau_dieukienluu AS dieukien', 'luumau.dieukienluu_id = dieukien.dieukienluu_id');
        if ($id) $this->db->where('mau.mau_id', $id);
        $query = $this->db->get();
        $result = ($id) ? $query->row_array() : $query->result_array();
	return ($result) ? $result : FALSE;
    }
    function _luuMau($id) {
        $data = array(
            'mau_luu' => 'Y'
        );
        $this->db->where('mau_id', $id);
        $this->db->update('nm_mau', $data);
        if ($this->db->affected_rows()) return TRUE;
        return FALSE;
    }
}
