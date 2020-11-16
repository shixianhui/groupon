<?php
class User extends CI_Controller {
	private $_displayArr = array('0'=>'未激活', '1'=>'已激活', '2'=>'冻结');
	private $_sexArr = array('0'=>'未知', '1'=>'男', '2'=>'女');
	private $_typeArr = array('0'=>'普通会员', '1'=>'VIP会员', '2'=>'策略分析师');
	private $_title = '会员管理';
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
		$this->load->model('User_address_model', '', TRUE);
		$this->_tool = $this->load->view('element/user_tool', '', TRUE);
		$this->load->helper(array('url', 'my_fileoperate'));
	}

	public function index($page = 0) {
	    checkPermission("{$this->_template}_index");
	    if (! $this->uri->segment(2)) {
		    $this->session->unset_userdata("search");
		}
		$this->session->set_userdata(array("{$this->_template}RefUrl"=>base_url().'admincp.php/'.uri_string()));
        $strWhere = $this->session->userdata('search')?$this->session->userdata('search'):NULL;
		if ($_POST) {
			$strWhere = "{$this->_table}.id > 0";

			$id = $this->input->post('id', TRUE);
		    $username = trim($this->input->post('username', TRUE));
		    $nickname = trim($this->input->post('nick_name', TRUE));
		    $type = $this->input->post('type', TRUE);
		    $display = $this->input->post('display', TRUE);
		    $custom_attribute = $this->input->post('custom_attribute', TRUE);
		    $startTime = $this->input->post('inputdate_start');
		    $endTime = $this->input->post('inputdate_end');

		    if ($id) {
		        $strWhere .= " and {$this->_table}.id = {$id} ";
		    }
		    if ($username) {
		        $strWhere .= " and lower({$this->_table}.username) = '".strtolower($username)."' ";
		    }
		    if ($nickname) {
		        $strWhere .= " and {$this->_table}.nickname = '{$nickname}' ";
		    }
		    if (! empty($custom_attribute) ) {
		        $strWhere .= " and {$this->_table}.custom_attribute like '%".$custom_attribute."%'";
		    }
		    if ($type) {
		        $strWhere .= " and {$this->_table}.type = '{$type}' ";
		    }
		    if ($display != "") {
		        $strWhere .= " and {$this->_table}.display = '{$display}' ";
		    }
		    if (! empty($startTime) && ! empty($endTime)) {
		    	$strWhere .= " and {$this->_table}.add_time > ".strtotime($startTime.' 00:00:00')." and {$this->_table}.add_time < ".strtotime($endTime.' 23:59:59')." ";
		    }

		    $this->session->set_userdata('search', $strWhere);
            $page = 0;
		}

		//分页
		$this->config->load('pagination_config', TRUE);
		$paginationCount = $this->tableObject->rowCount($strWhere);
    	$paginationConfig = $this->config->item('pagination_config');
    	$paginationConfig['base_url'] = base_url()."admincp.php/{$this->_template}/index/";
    	$paginationConfig['total_rows'] = $paginationCount;
    	$paginationConfig['uri_segment'] = 3;
		$this->pagination->initialize($paginationConfig);
		$pagination = $this->pagination->create_links();

		$itemList = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
		if ($itemList){
		    foreach ($itemList as $key=>$value){
		        $user_address = $this->User_address_model->get('txt_address,address,buyer_name,mobile',array('is_default'=>1,'user_id'=>$value['id']));
		        if (!$user_address){
                    $user_address = $this->User_address_model->get('txt_address,address,buyer_name,mobile',array('user_id'=>$value['id']));
                }
                $itemList[$key]['user_address'] = $user_address;
            }
        }

		$data = array(
		              'tool'=>$this->_tool,
		              'displayArr'=>$this->_displayArr,
		              'sexArr'=>$this->_sexArr,
		              'typeArr'=>$this->_typeArr,
		              'template'=>$this->_template,
		              'itemList'=>$itemList,
		              'pagination'=>$pagination,
		              'paginationCount'=>$paginationCount,
		              'pageCount'=>ceil($paginationCount/$paginationConfig['per_page'])
		              );
	    $layout = array(
			      'title'=>$this->_title,
				  'content'=>$this->load->view($this->_template.'/index', $data, TRUE)
			      );
	    $this->load->view('layout/default', $layout);
	}

	public function save($id = NULL) {
	    if ($id) {
	        checkPermission("{$this->_template}_edit");
	    } else {
	        checkPermission("{$this->_template}_add");
	    }
		$prfUrl = $this->session->userdata("{$this->_template}RefUrl")?$this->session->userdata("{$this->_template}RefUrl"):base_url()."admincp.php/{$this->_template}/index";
		if ($_POST) {
			$username = $this->input->post('username', TRUE);

			$fields = array(
			          'username'=>      $username,
			          'real_name'=>     $this->input->post('real_name', TRUE),
			          'sex'=>           $this->input->post('sex', TRUE),
					  'qq'=>            $this->input->post('qq', TRUE),
					  'mobile'=>        $this->input->post('mobile', TRUE),
					  'email'=>         $this->input->post('email', TRUE),
					  'score'=>         $this->input->post('score'),
			          'login_time'=>    time(),
			          'ip_address'=>    '',
			          'type'=>          $this->input->post('type', TRUE),
			          'path'=>          $this->input->post('path', TRUE),
			          'real_name'=>     $this->input->post('real_name', TRUE)
			          );
			if (!$id) {
			    $fields['display'] = 1;
			}
		    $password = $this->input->post('password', TRUE);
			if ($password) {
				  $addTime = time();
				  $fields['add_time'] = $addTime;
			      $fields['password'] = $this->_createPasswordSALT($username, $addTime, $password);
			}
			if (empty($id)) {
			    if ($this->tableObject->validateUnique($this->input->post('username', TRUE))) {
			        printAjaxError('fail', "用户名已经存在，请换个用户名！");
			    }
			}

		    if ($this->tableObject->save($fields, $id?array('id'=>$id):$id)) {
		    	printAjaxSuccess($prfUrl);
			} else {
				printAjaxError('fail', "操作失败！");
			}
		}

		$itemInfo = $this->tableObject->get(array('id'=>$id));
        $user_address_list = array();
		if ($itemInfo){
            $user_address_list = $this->User_address_model->gets('txt_address,address,buyer_name,mobile',array('user_id'=>$itemInfo['id']));
        }

	    $data = array(
		        'tool'=>$this->_tool,
	            'itemInfo'=>$itemInfo,
	            'template'=>$this->_template,
	            'sexArr'=>$this->_sexArr,
	            'prfUrl'=>$prfUrl,
	            'user_address_list'=>$user_address_list,
		        );
		$layout = array(
		          'title'=>$this->_title,
				  'content'=>$this->load->view($this->_template.'/save', $data, TRUE)
		          );
		$this->load->view('layout/default', $layout);
	}

    public function delete() {
        checkPermissionAjax("{$this->_template}_delete");
	    $ids = $this->input->post('ids', TRUE);

	    if (! empty($ids)) {
	        if ($this->tableObject->delete('id in ('.$ids.')')) {
	            printAjaxData(array('ids'=>explode(',', $ids)));
	        }
	    }

	    printAjaxError('fail', '删除失败！');
	}

    public function display() {
        checkPermissionAjax("{$this->_template}_edit");

	    $ids = $this->input->post('ids');
		$display = $this->input->post('display');

		if (! empty($ids) && $display != "") {
			if($this->tableObject->save(array('display'=>$display), 'id in ('.$ids.')')) {
			    printAjaxSuccess('', '修改状态成功！');
			}
		}

		printAjaxError('fail', '修改状态失败！');
	}

    private function _createCsv($scoreList, &$headers)
    {
        $tmp_path = tempnam(TMP, 'rcmAttmnt');
        $fp = fopen($tmp_path, 'w');

        fputcsv($fp, array('{headers}'));
        if(!empty($scoreList)){
            $tmpArray = array();
            foreach($scoreList as $list){
                //add tmp array
                $tmpArray['Handbook'] = preg_replace(array('/\n|\r/', '/\s/'), array('','&nbsp;'), $list['Handbook']['name']);
            }
            fputcsv($fp, $tmpArray);
        }

        fclose($fp);
        return $tmp_path;
    }

	//加盐算法
	private function _createPasswordSALT($user, $salt, $password) {

	    return md5(strtolower($user).$salt.$password);
	}
}
/* End of file admin.php */
/* Location: ./application/admin/controllers/admin.php */