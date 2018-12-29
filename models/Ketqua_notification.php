<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Ketqua_notification extends MY_Model {
    var $connTL;
    function __construct() {
        parent::__construct();
        $this->connTL = $this->load->database('nenmau', TRUE);
    }
    function _load() {
        echo "xxxxx";
    }    
    function count_package_export($ptn_id = false){
        $this->connTL->select('mct.mau_id');
        $this->connTL->from('nm_mau m');
        $this->connTL->join('nm_mauchitiet mct', 'm.mau_id = mct.mau_id');
        $this->connTL->join('nm_hopdong hd', 'm.hopdong_id = hd.hopdong_id');
        $this->connTL->join('nm_ketqua_chitiet kqct', 'mct.mauchitiet_id = kqct.mauchitiet_id');
        $this->connTL->join('nm_ketqua kq', 'kqct.ketqua_id = kq.ketqua_id');
        $this->connTL->where('hd.hopdong_status', 1);
        $this->connTL->where('kq.ketqua_approve != 2');
        // Filter by PTN
        if($ptn_id){
            $this->connTL->where('mct.donvi_id', $ptn_id);
        }
        $count = $this->connTL->count_all_results();
        return $count;
    }
    
    function count_all_approve($user_id, $approved){
        $this->connTL->select('MAX(kq.ketqua_id)');
        $this->connTL->from('nm_ketqua kq');
        $this->connTL->join('nm_ketqua_duyet kqd', 'kq.ketqua_id = kqd.ketqua_id');
        $this->connTL->where('kqd.user_receive', $user_id);
        $this->connTL->group_by('kq.ketqua_id');
        if($approved){
            $this->connTL->where('kqd.duyet_result != ', 0);
        }else{
            $this->connTL->where('kqd.duyet_result', 0);
        }
        $count = $this->connTL->count_all_results();
        return $count;
    }
}
