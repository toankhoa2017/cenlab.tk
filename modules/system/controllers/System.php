<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends ADMIN_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('mod_project');
        $this->load->model('mod_group');
		$this->load->model('mod_module');
    }
    function index() {
        $projectList = array();
        $projects = $this->mod_project->_gets();
        foreach ($projects as $project) {
            $projectList[$project['PROJECT_ID']] = $project['PROJECT_NAME'];
        }
        $this->parser->assign('project', $this->input->get('project'));
        $this->parser->assign('projects', $projectList);
        $this->parser->parse('group/list');
    }
    function ajax_list() {
        $this->mod_group->project = $this->input->get('project');
        $list = $this->mod_group->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $group) {
            $no++;
            $row = array();
            $row[] = "<div class='gname' data-id='".$group->GROUP_ID."' ><a href='".site_url()."system/detail?id=".$group->GROUP_ID."'>".$group->GROUP_NAME."</a></div>";
            $row[] = "<div class='glink' >".$group->GROUP_LINK."</div>";
            $row[] = "<div class='gicon' >".$group->GROUP_ICON."</div>";
			$row[] = "<div class='gorder' >".$group->GROUP_ORDER."</div>";
            $row[] = '<button class="btn btn-xs btn-info editgroup"><i class="ace-icon fa fa-pencil smaller"></i></button> '.
			'<button class="btn btn-warning btn-xs btnumail update"><i class="ace-icon fa fa-floppy-o smaller"></i></button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_group->count_all(),
            "recordsFiltered" => $this->mod_group->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    function ajax_add() {
        $items = $this->input->post();
        $this->mod_group->_create($items);
        echo json_encode(array("status" => TRUE));
    }
	function update() {
		$item = $this->input->post();
		$this->mod_group->_update($item);
		echo json_encode(array('code' => '100'));
	}
    function detail() {
        $id = $this->input->get('id');
        $this->parser->assign('group', $this->mod_group->_get($id));
        $this->parser->assign('listmod', $this->mod_group->_getmods($id));
        $this->parser->parse('group/detail');
    }
    function addmod() {
        $items = $this->input->post();
        $this->mod_module->_create($items);
        echo json_encode(array("status" => TRUE));
    }
    function updatemod() {
        $item = $this->input->post();
        $this->mod_module->_update($item);
        echo json_encode(array('code' => '100'));
    }
    function delmod() {
        $items = $this->input->post();
        $this->mod_group->_delmod($items);
        echo json_encode(array("status" => TRUE));
    }    
    function sethide(){
		$this->load->model('mod_module');
        $items = $this->input->post();
        $dulieu = $this->mod_module->_setHide($items);
        if ($dulieu==true) {
			echo "1";
		}
		else { 
			echo "2";
		}
    }
}
