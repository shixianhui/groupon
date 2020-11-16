<?php
class System extends CI_Controller {
	private $_title = '系统设置';
	private $_tool = '';
	private $_table = '';
	private $_template = '';
	
	public function __construct() {
		parent::__construct();
		//获取表名
		$this->_table = $this->uri->segment(1);
		//模型名
		$this->_template = $this->uri->segment(1);
		//获取表对象
		$this->load->model(ucfirst($this->_template).'_model', 'tableObject', TRUE);
		$this->_tool = $this->load->view('element/system_tool', '', TRUE);
	}


	public function save() {
	    checkPermission("{$this->_template}_save");
		if ($_POST) {
			$fields = array(
					'index_name'=>      $this->input->post('index_name', TRUE),
					'site_name'=>       $this->input->post('site_name', TRUE),
			        'index_site_name'=> $this->input->post('index_site_name', TRUE),
					'client_index'=>    $this->input->post('client_index', TRUE),			
					'site_copyright'=>  $this->input->post('site_copyright', TRUE),
					'site_keycode'=>    $this->input->post('site_keycode', TRUE),
					'site_description'=>$this->input->post('site_description', TRUE),
		            'icp_code'=>        $this->input->post('icp_code', TRUE),
					'cache'=>           $this->input->post('cache'),
					'cache_time'=>      $this->input->post('cache_time'),
			        'html_folder'=>     $this->input->post('html_folder', TRUE),
			        'html_level'=>     $this->input->post('html_level', TRUE),
					'html'=>      		$this->input->post('html')
			        );
		    if ($this->tableObject->save($fields, array('id'=>1))) {
		    	printAjaxSuccess(base_url()."admincp.php/{$this->_template}/save");
			} else {
				printAjaxError('fail', "修改失败！");
			}
		}
		$itemInfo = $this->tableObject->get('*', array('id'=>1));
		
		$data = array(
		        'tool'=>$this->_tool,
				'itemInfo'=>$itemInfo
		        );
	    $layout = array(
			      'title'=>$this->_title,
				  'content'=>$this->load->view($this->_template.'/save', $data, TRUE)
			      );
	    $this->load->view('layout/default', $layout);
	}
}
/* End of file news.php */
/* Location: ./application/admin/controllers/news.php */