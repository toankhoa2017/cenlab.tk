<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends ADMIN_Controller {
    private $privcheck;
    function __construct() {
        parent::__construct();
        $this->privcheck = $this->permarr[_SYS_MODULE];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_nhansu');
        $this->lang->load('module');
        $this->parser->assign('languages', $this->lang->getLang());
    }
    function index() {
        if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $this->curl->create($this->_api['group']['gets']);
        $this->curl->post(array(
            'project' => _PROJECT_ID
        ));
        $groups = json_decode($this->curl->execute());
        $this->parser->assign('groups', $groups);
        $this->parser->parse('module/list');
    }
    function updategroup() {
        $data = $this->input->post();
        $this->curl->create($this->_api['group']['update']);
        $this->curl->post(array('id' => $data['id'], 'name' => $data['gname'], 'gorder' => $data['gorder'], 'project' => _PROJECT_ID));
        $response = json_decode($this->curl->execute());
        echo json_encode(array('code' => '100', 'status' => ''));
    }
    function updatemod() {
        $data = $this->input->post();
        $this->curl->create($this->_api['module']['update']);
        $this->curl->post(array('id' => $data['id'], 'mname' => $data['mname'], 'morder' => $data['morder'], 'gid' => $data['gid']));
        $response = json_decode($this->curl->execute());
        echo json_encode(array('code' => '100', 'status' => ''));
    }
    function detail() {
        if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $id = $this->input->get('id');
        //Get group
        $this->curl->create($this->_api['group']['gets']);
        $this->curl->post(array(
            'gid' => $id,
            'project' => _PROJECT_ID
        ));
        $this->parser->assign('group', json_decode($this->curl->execute()));
        //Get modules in group
        $this->curl->create($this->_api['module']['gets']);
        $this->curl->post(array(
            'group' => $id
        ));
        $this->parser->assign('modules', json_decode($this->curl->execute()));
        //Get account in group
        $this->curl->create($this->_api['group']['getaccs']);
        $this->curl->post(array(
            'gid' => $id
        ));
        $getaccs = json_decode($this->curl->execute());
        $listacc = array();
        $listacc[] = 0;
        foreach ($getaccs as $acc) {
            $listacc[] = $acc->id;
        }
        $this->parser->assign('accounts', $this->mod_nhansu->_getsin($listacc));
        $this->parser->assign('gid', $id);
        $this->parser->parse('module/detail');
    }
    function addaccs() {
        if (!$this->privcheck['update']) redirect(site_url() . 'admin/denied?w=update');
        $id = $this->input->get('id');
        if ($this->input->post('isSent') == 'OK') {
            $params = $this->input->post();
            //Add account to group
            $this->curl->create($this->_api['group']['addaccs']);
            $this->curl->post(array(
                'gid' => $id,
                'accounts' => $params['dualacc']
            ));
            $action = json_decode($this->curl->execute());
            redirect(site_url()."admin/module/detail?id={$id}");
        }
        //Get group
        $this->curl->create($this->_api['group']['gets']);
        $this->curl->post(array(
            'gid' => $id,
            'project' => _PROJECT_ID
        ));
        $this->parser->assign('group', json_decode($this->curl->execute()));
        //Get account out group
        $this->curl->create($this->_api['group']['getoutaccs']);
        $this->curl->post(array(
            'gid' => $id,
            'project' => _PROJECT_ID
        ));
        $getaccs = json_decode($this->curl->execute());
        $listacc = array();
        $listacc[] = 0;
        foreach ($getaccs as $acc) {
            $listacc[] = $acc->id;
        }
        $accounts = $this->mod_nhansu->_getsin($listacc);
        foreach ($accounts as $account) {
            $option_account[$account['nhansu_lastname'].' '.$account['nhansu_firstname']][$account['account_id']] = $account['nhansu_lastname'].' '.$account['nhansu_firstname'];
        }
        $this->parser->assign('option_account', $option_account);
        $this->parser->parse('module/addaccs');
    }
    function removeacc() {
        if (!$this->privcheck['delete']) {
            echo 'denied';
            exit;
        }
        $items = $this->input->post();
        $this->curl->create($this->_api['group']['removeacc']);
        $this->curl->post(array(
            'gid' => $items['gid'],
            'aid' => $items['aid']
        ));
        $this->curl->execute();
        echo json_encode(array("status" => TRUE));
    }
    function listmoduser() {
        $id_user = $this->input->post('id');
        $group_id = $this->input->post('gid');
        $this->curl->create($this->_api['group']['gets']);
        $this->curl->post(array(
            'gid' => $group_id,
            'project' => _PROJECT_ID
        ));
        $group = json_decode($this->curl->execute(), TRUE);
        $this->curl->create($this->_api['account']['listmods']);
        $this->curl->post(array(
            'uid' => $id_user
        ));
        $mods = json_decode($this->curl->execute(), TRUE);
        $listmods = array();
        foreach ($mods['listmods'] as $key=>$mod) {
            $listmods[$mod['group']][$key] = $mod;            
        }
        $output = '<table id="table" class="table table-bordered table-striped">';
        $output.= '<thead>';
        $output.= '<tr>';
        $output.= '<th>Tên Module</th>';
        $output.= '<th>Đọc</th>';
        $output.= '<th>Ghi</th>';
        $output.= '<th>Xóa</th>';
        $output.= '<th>Sửa</th>';
        $output.= '<th>Toàn Quyền</th>';
        $output.= '</tr>';
        $output.= '</thead>';
        $output.= '<tbody>';
        foreach ($listmods as $key1=>$i){
            if($key1 == $group['name']){
                foreach ($i as $a) {
                    $output.= '<tr>';
                    $output.= '<td>'.$a['module'].'</td>';
                    $output.= '<td><input type="checkbox" id="checkbox_read['.$a['stt'].']" name="checkbox_read['.$a['stt'].']" value="'.$a['value'].'" '. $a['read'] .' /></td>';
                    $output.= '<td><input type="checkbox" id="checkbox_write['.$a['stt'].']" name="checkbox_write['.$a['stt'].']" value="'.$a['value'].'" '. $a['write'] .' /></td>';
                    $output.= '<td><input type="checkbox" id="checkbox_delete['.$a['stt'].']" name="checkbox_delete['.$a['stt'].']" value="'.$a['value'].'" '. $a['delete'] .' /></td>';
                    $output.= '<td><input type="checkbox" id="checkbox_update['.$a['stt'].']" name="checkbox_update['.$a['stt'].']" value="'.$a['value'].'" '. $a['update'] .' /></td>';
                    $output.= '<td><input type="checkbox" id="checkbox_master['.$a['stt'].']" name="checkbox_master['.$a['stt'].']" value="'.$a['value'].'" '. $a['master'] .' /></td>';
                    $output.= '</tr>';
                    $output.= '<input type="hidden" id="idMod['.$a['stt'].']" name="idMod['.$a['stt'].']" value="'.$a['value'].'">';
                }
            }
        }
        $output.= '</tbody>';
        $output.= '</table>';
        echo $output;
    }
    function setpermission() {
       // echo 'a';
        //if (!$this->privcheck['master']) redirect(site_url() . 'admin/denied?w=master');
        $value = array();
        $items = $this->input->post();
		echo json_encode($items);
		/*
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
        //echo $value;
        $items['value'] = $value;
        $this->curl->create($this->_api['account']['setpermission']);
        $this->curl->post(array(
            'value' => $items
        ));
        $this->curl->execute();
        echo json_encode(array("code" => '200', 'nhansu_code' => $items['aid']));
		*/
    }
}
