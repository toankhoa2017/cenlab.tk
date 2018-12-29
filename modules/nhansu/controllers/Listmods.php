<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Listmods extends ADMIN_Controller {
    private $privcheck;
    function __construct() {
        parent::__construct();
        $this->privcheck = $this->permarr[_TOCHUC_NHANSU];
        $this->parser->assign('privcheck', $this->privcheck);
    }
    function index() {
        $this->curl->create($this->_api['account']['listmods']);
        $this->curl->post(array(
            'uid' => $this->input->get('id')
        ));
        $mods = json_decode($this->curl->execute(), TRUE);
        $listmods = array();
        foreach ($mods['listmods'] as $key=>$mod) {
            $listmods[$mod['group']][$key] = $mod;
        }
        //print_r($listmods);
        $this->parser->assign('items', $listmods);
        $this->parser->parse('nhansu/listmods');
    }
    function setpermission() {
        if (!$this->privcheck['master']) redirect(site_url() . 'admin/denied?w=master');
        $value = array();
        $items = $this->input->post();
        $this->load->helper('array');
        $this->load->library('permission');
        $idMod = $items['idMod'];
        for ($msm = 0; $msm < sizeof($idMod); $msm++) {
            $this->permission->permissions['read'] = (isset($items['checkbox_read']) && element($msm, $items['checkbox_read'], '') != '') ? TRUE : 0;
            $this->permission->permissions['write'] = (isset($items['checkbox_write']) && element($msm, $items['checkbox_write'], '') != '') ? TRUE : 0;
            $this->permission->permissions['delete'] = (isset($items['checkbox_delete']) && element($msm, $items['checkbox_delete'], '') != '') ? TRUE : 0;
            $this->permission->permissions['update'] = (isset($items['checkbox_update']) && element($msm, $items['checkbox_update'], '') != '') ? TRUE : 0;
            $this->permission->permissions['master'] = (isset($items['checkbox_master']) && element($msm, $items['checkbox_master'], '') != '') ? TRUE : 0;
            $bitmask = $this->permission->toBitmask();
            if ($bitmask > 32) $bitmask = 32;
            $value[$msm]['idMod'] = $idMod[$msm];
            $value[$msm]['priv'] = $bitmask;
        }
        $items['value'] = $value;
        $this->curl->create($this->_api['account']['setpermission']);
        $this->curl->post(array(
            'value' => $items
        ));
        $this->curl->execute();
        echo json_encode(array("status" => TRUE, 'nhansu_code' => $items['aid']));
    }
}
