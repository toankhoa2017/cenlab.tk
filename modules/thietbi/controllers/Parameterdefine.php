<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameterdefine extends ADMIN_Controller{
    function __construct() {
        parent::__construct();
		$this->load->model('mod_kieuthietbi');
		$this->load->model('mod_device');
		$this->load->model('mod_parameterdefine');
    }
    
    function index(){
		/*$this->curl->create($this->_restful['nhansu']['gets']);
        $this->curl->post(array(
            'project' => _PROJECT_ID
        ));
        $groups = json_decode($this->curl->execute(), true);
		print_r($groups);*/
		$this->parser->assign('devicetype', $this->mod_kieuthietbi->get_datatables());
        $this->parser->parse('parameterdefine/list');
    }
  	
    function ajax_list() {
        $list = $this->mod_parameterdefine->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $define) {
            $no++;
            $row = array();
            $row[] = $define->type_name;
            $row[] = $define->name;
            $row[] = $define->unit;
			$row[] = $define->max_value;
			$row[] = $define->min_value;
			$row[] = $define->decimal_point;
			if($define->required == '0') {
				$row[] = 'Không';
			}
			else {
				$row[] = 'Có';
			}
            $row[] = "<button class='btn btn-minier btn-info' onclick=\"edit(".$define->id.",".$define->device_set_type_id.",'".$define->name."','".$define->unit."','".$define->max_value."','".$define->min_value."','".$define->decimal_point."','".$define->required."')\"><i class='ace-icon fa fa-pencil smaller'></i></button>
                      <button class='btn btn-minier btn-danger' onclick=\"del(".$define->id.")\"><i class='ace-icon fa fa-trash-o bigger-110'></i></button>";
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_parameterdefine->count_all(),
            "recordsFiltered" => $this->mod_parameterdefine->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function update() {
        $item = $this->input->post();
        $this->mod_parameterdefine->_update($item['paraid'], array('device_set_type_id' => $item['devicetype'],
                'name' => $item['name'],
				'unit' => $item['unit'],
				'max_value' => $item['maxvalue'],
				'min_value' => $item['minvalue'],
				'decimal_point' => $item['decimalpoint'],
				'required' => $item['req'],
				'update_date' => date('Y-m-d H:i:s') ));
        echo json_encode(array('err_code' => '100'));
    }

    function ajax_add() {
        $item = $this->input->post();
		$type_id_max = $this->mod_parameterdefine->_getMaxid($item['devicetype']);
        $this->mod_parameterdefine->_create(array(
                'device_set_type_id' => $item['devicetype'],
                'serial_no' => ++$type_id_max,
                'name' => $item['name'],
				'unit' => $item['unit'],
				'max_value' => $item['maxvalue'],
				'min_value' => $item['minvalue'],
				'decimal_point' => $item['decimalpoint'],
				'required' => $item['req'],
				'create_date' => date('Y-m-d H:i:s') ));
        echo json_encode(array('err_code' => '100'));
    }
    
    function delete() {
        $this->mod_parameterdefine->_update($this->input->post('id'), array('status' => '2'));
        echo json_encode(array('err_code' => '100'));
    }
}