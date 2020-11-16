<?php
class Light_img extends CI_Controller {
    private $_title = '产品';
    private $_tool = '';
    private $_table = 'light_img';
    private $_template = 'light_img';

    public function __construct() {
        parent::__construct();
        //获取表名
        $this->_table = $this->uri->segment(1);
        //获取表对象
        $this->load->model(ucfirst($this->_table).'_model', 'tableObject', TRUE);
        $this->_tool = $this->load->view('element/save_list_tool', array('table'=>$this->_table, 'parent_title'=>'商品活动', 'title'=>'内容'), TRUE);
        $this->load->model('Menu_model', '', TRUE);
        $this->load->helper(array('url', 'my_fileoperate'));
        $this->load->library(array('Form_validation'));
    }

    public function index($clear = 1, $page = 0) {
//        checkPermission("{$this->_template}_index");
        clearSession(array('search_index'));
        if ($clear) {
        	$clear = 0;
        	$this->session->unset_userdata('search_index');
        }
        $uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index';
        $uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
        $this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

        $strWhere = $this->session->userdata('search_index')?$this->session->userdata('search_index'):NULL;

        if ($_POST) {
            $strWhere = "{$this->_table}.id > 0";

            $id = trim($this->input->post('id'));
            $title =  trim($this->input->post('title'));
            $light_img_num =  trim($this->input->post('light_img_num'));
            $display = $this->input->post('display');
            $category_id = $this->input->post('category_id');
            $custom_attribute = $this->input->post('custom_attribute');
            $startTime = $this->input->post('inputdate_start');
            $endTime = $this->input->post('inputdate_end');


            if ($id) {
            	$strWhere .= " and {$this->_table}.id = '{$id}' ";
            }
            if (!empty($title)) {
                $strWhere .= " and {$this->_table}.title  REGEXP '{$title}'";
            }
            if ($light_img_num) {
                $strWhere .= " and {$this->_table}.light_img_num = '{$light_img_num}' ";
            }
            if ($display != "") {
                $strWhere .= " and {$this->_table}.display={$display} ";
            }
            if ($category_id) {
                $ids = $this->Menu_model->getChildMenus($category_id);
                $strWhere .= " and {$this->_table}.category_id in ({$ids}) ";
            }
            if ($custom_attribute) {
                $strWhere .= " and {$this->_table}.custom_attribute REGEXP '^{$custom_attribute}' or {$this->_table}.custom_attribute REGEXP '{$custom_attribute}^' or {$this->_table}.custom_attribute REGEXP ',{$custom_attribute},' ";
            }
            if (!empty($startTime) && !empty($endTime)) {
                $strWhere .= " and {$this->_table}.add_time > " . strtotime($startTime . ' 00:00:00') . " and {$this->_table}.add_time < " . strtotime($endTime . ' 23:59:59') . " ";
            }

            $this->session->set_userdata('search_index', $strWhere);
        }
        //分页
        $this->config->load('pagination_config', TRUE);
        $paginationCount = $this->tableObject->rowCount($strWhere);
        $paginationConfig = $this->config->item('pagination_config');
        $paginationConfig['base_url'] = base_url() . "admincp.php/{$this->_table}/index/{$clear}/";
        $paginationConfig['total_rows'] = $paginationCount;
        $paginationConfig['uri_segment'] = 4;
        $this->pagination->initialize($paginationConfig);
        $pagination = $this->pagination->create_links();

        $item_list = $this->tableObject->gets('*', $strWhere, $paginationConfig['per_page'], $page);

        $data = array(
            'tool' => $this->_tool,
            'item_list' => $item_list,
            'pagination' => $pagination,
            'paginationCount' => $paginationCount,
            'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
        );

        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_table}/index", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function selector($clear = 0, $page = 0) {
        checkPermission("{$this->_template}_index");
        clearSession(array('search_index'));
        if ($clear) {
        	$clear = 0;
        	$this->session->unset_userdata('search_index');
        }
        $uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index';
        $uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
        $this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

        $strWhere = $this->session->userdata('search_index')?$this->session->userdata('search_index'):"{$this->_table}.display = 1";
        if ($_POST) {
            $strWhere = "{$this->_table}.id > 0 and {$this->_table}.display = 1";
            $title = $this->input->post('title');
            $light_img_num = $this->input->post('light_img_num');
            $display = $this->input->post('display');
            $category_id = $this->input->post('category_id');
            $custom_attribute = $this->input->post('custom_attribute');
            $pay_mode = $this->input->post('pay_mode');
            $startTime = $this->input->post('inputdate_start');
            $endTime = $this->input->post('inputdate_end');

            if (!empty($title)) {
                $strWhere .= " and {$this->_table}.title REGEXP '{$title}'";
            }
            if ($light_img_num) {
                $strWhere .= " and {$this->_table}.light_img_num = '{$light_img_num}' ";
            }
            if ($display != "") {
                $strWhere .= " and {$this->_table}.display={$display} ";
            }
            if ($category_id) {
                $ids = $this->Menu_model->getChildMenus($category_id);
                $strWhere .= " and {$this->_table}.category_id in ({$ids}) ";
            }
            if ($custom_attribute) {
                $strWhere .= " and {$this->_table}.custom_attribute REGEXP '^{$custom_attribute}' or {$this->_table}.custom_attribute REGEXP '{$custom_attribute}^' or {$this->_table}.custom_attribute REGEXP ',{$custom_attribute},' ";
            }
            if ($pay_mode != "") {
                $strWhere .= " and {$this->_table}.pay_mode={$pay_mode} ";
            }
            if (!empty($startTime) && !empty($endTime)) {
                $strWhere .= " and {$this->_table}.add_time > " . strtotime($startTime . ' 00:00:00') . " and {$this->_table}.add_time < " . strtotime($endTime . ' 23:59:59') . " ";
            }
            $this->session->set_userdata('search_index', $strWhere);
        }

        //分页
        $this->config->load('pagination_config', TRUE);
        $paginationCount = $this->tableObject->rowCount($strWhere);
        $paginationConfig = $this->config->item('pagination_config');
        $paginationConfig['base_url'] = base_url() . "admincp.php/{$this->_table}/selector/{$clear}/";
        $paginationConfig['total_rows'] = $paginationCount;
        $paginationConfig['uri_segment'] = 4;
        $paginationConfig['per_page'] = 20;
        $this->pagination->initialize($paginationConfig);
        $pagination = $this->pagination->create_links();

        $light_imgList = $this->tableObject->gets('*', $strWhere, $paginationConfig['per_page'], $page);
        foreach ($light_imgList as $key => $value) {
        }
        $data = array(
            'tool' => $this->_tool,
            'light_imgList' => $light_imgList,
            'pagination' => $pagination,
            'paginationCount' => $paginationCount,
            'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
            'display_arr' => $this->_display_arr
        );

        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view('light_img/selector', $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function save($id = NULL) {
//        if ($id) {
//            checkPermission("{$this->_template}_edit");
//        } else {
//            checkPermission("{$this->_template}_add");
//        }
        $prfUrl = $this->session->userdata("{$this->_table}RefUrl") ? $this->session->userdata("{$this->_table}RefUrl") : base_url() . 'admincp.php/light_img/index/';
        if ($_POST) {

            $name = $this->input->post('name', TRUE);
            $img_path = $this->input->post('path', TRUE);
            $light_img_path = $this->input->post('light_img_path', TRUE);
            $rate = $this->input->post('rate', TRUE);

            $fields = array(
                'name' => $name,
                'img_path' => $img_path,
                'light_img_path' => $light_img_path,
                'rate' => $rate,
            );
            $retId = $this->tableObject->save($fields, $id ? array('id' => $id) : $id);
            if ($retId) {
                printAjaxSuccess($prfUrl,'保存成功！');
            } else {
                printAjaxError('fail',"操作失败！");
            }
        }

        //详细
        $item_info = $this->tableObject->get('*', array('id' => $id));


        $data = array(
            'tool' => $this->_tool,
            'item_info' => $item_info,
        	'prfUrl' => $prfUrl
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view('light_img/save', $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function sort() {
//        checkPermissionAjax("{$this->_template}_edit");
        if ($_POST) {
        	$ids = $this->input->post('ids', TRUE);
        	$sorts = $this->input->post('sorts', TRUE);

        	if (!$ids) {
        		printAjaxError('fail', '请选定操作项');
        	}

        	if (!empty($ids) && $sorts) {
        		$ids = explode(',', $ids);
        		$sorts = explode(',', $sorts);

        		foreach ($ids as $key => $value) {
        			$this->tableObject->save(
        					array('sort' => $sorts[$key]), array('id' => $value));
        		}
        		printAjaxSuccess('success', '排序成功！');
        	}

        	printAjaxError('fail', '排序失败！');
        }
    }

    public function category() {
        checkPermissionAjax("{$this->_template}_edit");
        if ($_POST) {
        	$ids = $this->input->post('ids', TRUE);
        	$categoryId = $this->input->post('categoryId', TRUE);

        	if (!$ids) {
        		printAjaxError('fail', '请选定操作项');
        	}
        	if (!empty($ids) && !empty($categoryId)) {
        		if ($this->tableObject->save(array('category_id' => $categoryId), 'id in (' . $ids . ')')) {
        			printAjaxSuccess('success', '修改栏目成功！');
        		}
        	}

        	printAjaxError('fail', '修改栏目失败！');
        }
    }

    public function attribute() {
        checkPermissionAjax("{$this->_template}_edit");
        if ($_POST) {
        	$ids = $this->input->post('ids', TRUE);
        	$customAttribute = $this->input->post('custom_attribute', TRUE);

        	if (!$ids) {
        		printAjaxError('fail', '请选定操作项');
        	}
        	if (!empty($ids) && !empty($customAttribute)) {
        		if ($customAttribute == 'clear') {
        			$customAttribute = '';
        		}
        		if ($this->tableObject->save(array('custom_attribute' => $customAttribute), 'id in (' . $ids . ')')) {
        			printAjaxSuccess('success', '属性修改成功！');
        		}
        	}

        	printAjaxError('fail', '属性修改失败！');
        }
    }

    public function delete() {
//        checkPermissionAjax("{$this->_template}_delete");
        if ($_POST) {
        	$ids = $this->input->post('id', TRUE);

        	if (!$ids) {
        		printAjaxError('fail', '请选定操作项');
        	}

        	if (!empty($ids)) {
        		if ($this->tableObject->delete('id in (' . $ids . ')')) {
        			printAjaxData(array('ids' => explode(',', $ids)));
        		}
        	}

        	printAjaxError('fail', '删除失败！');
        }
    }
    

    public function display() {
        checkPermissionAjax($this->_template.'_edit');
        if ($_POST) {
        	$ids = $this->input->post('ids');
        	$display = $this->input->post('display');

        	if (!empty($ids) && $display != "") {
        		if ($this->tableObject->save(array('display' => $display, 'display_time' => time()), 'id in (' . $ids . ')')) {
        			printAjaxSuccess('success', '修改状态成功！');
        		}
        	}

        	printAjaxError('fail', '修改状态失败！');
        }
    }


}

/* End of file news.php */
/* Location: ./application/admin/controllers/news.php */