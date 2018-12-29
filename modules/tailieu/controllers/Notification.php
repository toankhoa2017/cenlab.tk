<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notification extends ADMIN_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Mod_tldenghi');
    }
    private function filterAndNumberArray($danhSach){
        $result = array_filter($danhSach, function ($item) use ($like) {
            if ($item["denghi_tai_lieu"] == $item["de_nghi_id"]) {
                return true;
            }
            return false;
        });
        return count($result);
    } 
    function index(){
        $duyetDeNghi = $this->Mod_tldenghi->getListDuyetDenghi();
        $duyetDeNghi_number = $this->filterAndNumberArray($duyetDeNghi);
        $this->parser->assign('duyetDeNghi_number', $duyetDeNghi_number);
        $soanThao = $this->Mod_tldenghi->getListSoanThao();
        $soanThao_number = $this->filterAndNumberArray($soanThao);
        $this->parser->assign('soanThao_number', $soanThao_number);
        $xemXetSoanThao = $this->Mod_tldenghi->getListXemXetSoanThao();
        $xemXetSoanThao_number = $this->filterAndNumberArray($xemXetSoanThao);
        $this->parser->assign('xemXetSoanThao_number', $xemXetSoanThao_number);
        $pheDuyet = $this->Mod_tldenghi->getListPheDuyet();
        $pheDuyet_number = $this->filterAndNumberArray($pheDuyet);
        $this->parser->assign('pheDuyet_number', $pheDuyet_number);
        $banHanh = $this->Mod_tldenghi->getListBanHanh();
        $banHanh_number = $this->filterAndNumberArray($banHanh);
        $this->parser->assign('banHanh_number', $banHanh_number);
        $phanPhoi = $this->Mod_tldenghi->getListPhanPhoi();
        $phanPhoi_number = $this->filterAndNumberArray($phanPhoi);
        $this->parser->assign('phanPhoi_number', $phanPhoi_number);
        $this->parser->parse('notification/index');
    }
}