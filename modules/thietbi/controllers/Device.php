<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device extends ADMIN_Controller{
    function __construct() {
        parent::__construct();
		$this->load->model('mod_bothietbi');
		$this->load->model('mod_device');
    }
    
    function index(){
		$this->parser->assign('deviceset', $this->mod_bothietbi->_getAll());
        $this->parser->parse('device/list');
    }
  	function listset() {
		$list = $this->mod_bothietbi->_getAll();
		$output = '';
		foreach($list as $bo) {
			$output.= '<option value="'.$bo['id'].'">'.$bo['set_name'].'</option>';
		}
		echo json_encode(array('list' => $output));
	}
    function ajax_list() {
        $list = $this->mod_device->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $thietbi) {
            $no++;
            $row = array();
            $row[] = $thietbi->code;
            $row[] = $thietbi->device_name;
            $row[] = $thietbi->set_name;
            $row[] = "<button class='btn btn-minier btn-info' onclick=\"edit(".$thietbi->device_id.",'".$thietbi->code."','".$thietbi->device_name."',".$thietbi->device_set_id.")\"><i class='ace-icon fa fa-pencil smaller'></i></button>
                      <button class='btn btn-minier btn-danger' onclick=\"del(".$thietbi->device_id.")\"><i class='ace-icon fa fa-trash-o bigger-110'></i></button>";
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_device->count_all(),
            "recordsFiltered" => $this->mod_device->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function update() {
        $item = $this->input->post();
        $this->mod_device->_update($item['deviceid'], array('device_set_id' => $item['deviceset'],
                                                            'device_name' => $item['name'],
                                                            'code' => $item['code']));
        echo json_encode(array('err_code' => '100'));
    }

    function ajax_add() {
        $item = $this->input->post();
        $this->mod_device->_create(array(
                'device_set_id' => $item['deviceset'],
                'device_name' => $item['name'],
                'code' => $item['code']));
        echo json_encode(array('err_code' => '100'));
    }
    
    function delete() {
        $this->mod_device->_update($this->input->post('id'), array('status' => '2'));
        echo json_encode(array('err_code' => '100'));
    }
    function checkcode() {
        $code = $this->input->post('code');
        $kiemtra = $this->mod_device->_check_code($code);
        if($kiemtra == true) {
                echo json_encode(array('err_code' => '100', 'err_mess' => 'Mã này đã tồn tại'));
        }
    }
}