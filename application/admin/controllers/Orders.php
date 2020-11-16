<?php

class Orders extends CI_Controller {
    private $_title = '订单管理';
    private $_tool = '';
    private $_status_arr = array(
        '0' => '<font color="#ff4200">待付款</font>',
        '1' => '<font color="#cc3333">待发货</font>',
        '2' => '<font color="#ff811f">待收货</font>',
        '3' => '<font color="#066601">交易成功</font>',
        '4' => '<font color="#a0a0a0">交易关闭</font>'
    );
    private $_status_arr2 = array(
        '0' => '待付款',
        '1' => '待发货',
        '2' => '待收货',
        '3' => '交易成功',
        '4' => '交易关闭'
    );
    private $_sex_arr = array(
    		'0' => '保密',
    		'1' => '男',
    		'2' => '女'
    );
    private $_deliveryTime = array('1' => '工作日、双休日均可(周一至周日)', '2' => '工作日(周一至周五)', '3' => '双休日(周六周日)');
    private $_order_type_arr = array(
        '0' => '普通订单',
        '1' => '拼团砍价订单'
    );
    private $_table = '';

    public function __construct() {
        parent::__construct();
        //获取表名
        $this->_table = $this->uri->segment(1);
        //获取表对象
        $this->load->model(ucfirst($this->_table) . '_model', 'tableObject', TRUE);
        $this->_tool = $this->load->view('element/orders_tool', '', TRUE);
        $this->load->model('Area_model', '', TRUE);
        $this->load->model('Menu_model', '', TRUE);
        $this->load->model('User_model', '', TRUE);
        $this->load->model('Orders_detail_model', '', TRUE);
        $this->load->model('Orders_process_model', '', TRUE);
        $this->load->model('Product_model', '', TRUE);
        $this->load->model('Financial_model', '', TRUE);
        $this->load->model('Groupon_model', '', TRUE);
        $this->load->model('Groupon_record_model', '', TRUE);
        $this->load->model('System_model', '', TRUE);

        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('session');
    }

    public function index($clear = 0, $page = 0) {
        checkPermission("{$this->_table}_index");
        if ($clear) {
	    	$clear = 0;
		    $this->session->unset_userdata('search');
		}
        $uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index';
		$uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
		$this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

		$condition = "{$this->_table}.id > 0";
        $strWhere = $this->session->userdata('search')?$this->session->userdata('search'):$condition;
        if ($_POST) {
            $strWhere = $condition;
            $order_number = trim($this->input->post('order_number', TRUE));
            $buyer_name = trim($this->input->post('buyer_name', TRUE));
            $status = trim($this->input->post('status', TRUE));
            $mobile = trim($this->input->post('mobile', TRUE));
            $startTime = $this->input->post('inputdate_start', TRUE);
            $endTime = $this->input->post('inputdate_end', TRUE);

            if (!empty($order_number)) {
                $strWhere .= " and orders.order_number = '{$order_number}' ";
            }
            if (!empty($buyer_name)) {
                $strWhere .= " and orders.buyer_name regexp '{$buyer_name}' ";
            }
            if ($status != ''){
                $strWhere .= " and orders.status = '{$status}' ";
            }
            if (!empty($mobile)) {
                $strWhere .= " and orders.mobile regexp '{$mobile}' ";
            }
            if (!empty($startTime) && !empty($endTime)) {
                $strWhere .= ' and orders.add_time > ' . strtotime($startTime . ' 00:00:00') . ' and orders.add_time < ' . strtotime($endTime . ' 23:59:59') . ' ';
            }
            $this->session->set_userdata('search', $strWhere);
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

        $item_list = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
        foreach ($item_list as $key => $order) {
            $orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
            $item_list[$key]['orderdetailList'] = $orderdetailList;
            //推荐人
            $user_info = $this->User_model->getInfo('parent_id',array('id'=>$order['user_id']));
            $parent_info = '无';
            if ($user_info && $user_info['parent_id']){
                $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                $parent_info = $parent_user_info ? '<a href="'.base_url().'admincp.php/user/save/'.$user_info['parent_id'].'">'.$parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']</a>' : '无';
            }
            $item_list[$key]['parent_info'] = $parent_info;
            //团购人数
            $groupon_info = $this->Groupon_model->get('*',array('id' => $order['groupon_id']));
            $item_list[$key]['join_people'] = $groupon_info['join_people'];
            //城市地址
            $city_info = $this->Area_model->get('name', array('id' => $order['city_id']));
            $area_info = $this->Area_model->get('name', array('id' => $order['area_id']));
            $item_list[$key]['address'] = $area_info['name'].$order['address'];
            $item_list[$key]['city'] = $city_info['name'];
        }
        $data = array(
            'tool' => $this->_tool,
            'item_list' => $item_list,
            'pagination' => $pagination,
            'paginationCount' => $paginationCount,
            'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
        	'table'=>$this->_table,
            'status_arr' => $this->_status_arr
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_table}/index", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function index_0($clear = 0, $page = 0) {
    	checkPermission("{$this->_table}_index");
    	if ($clear) {
    		$clear = 0;
    		$this->session->unset_userdata('search');
    	}
    	$uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index_0';
    	$uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
    	$this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

    	$condition = "{$this->_table}.status = 0 ";
    	$strWhere = $this->session->userdata('search')?$this->session->userdata('search'):$condition;
    	if ($_POST) {
    		$strWhere = $condition;
    		$order_number = trim($this->input->post('order_number', TRUE));
    		$buyer_name = trim($this->input->post('buyer_name', TRUE));
            $status = trim($this->input->post('status', TRUE));
            $mobile = trim($this->input->post('mobile', TRUE));
            $startTime = $this->input->post('inputdate_start', TRUE);
            $endTime = $this->input->post('inputdate_end', TRUE);

            if (!empty($order_number)) {
                $strWhere .= " and orders.order_number = '{$order_number}' ";
            }
            if (!empty($buyer_name)) {
                $strWhere .= " and orders.buyer_name regexp '{$buyer_name}' ";
            }
            if ($status != ''){
                $strWhere .= " and orders.status = '{$status}' ";
            }
            if (!empty($mobile)) {
                $strWhere .= " and orders.mobile regexp '{$mobile}' ";
            }
    		if (!empty($startTime) && !empty($endTime)) {
    			$strWhere .= ' and orders.add_time > ' . strtotime($startTime . ' 00:00:00') . ' and orders.add_time < ' . strtotime($endTime . ' 23:59:59') . ' ';
    		}
    		$this->session->set_userdata('search', $strWhere);
    	}

    	//分页
    	$this->config->load('pagination_config', TRUE);
    	$paginationCount = $this->tableObject->rowCount($strWhere);
    	$paginationConfig = $this->config->item('pagination_config');
    	$paginationConfig['base_url'] = base_url() . "admincp.php/{$this->_table}/index_0/{$clear}/";
    	$paginationConfig['total_rows'] = $paginationCount;
    	$paginationConfig['uri_segment'] = 4;
    	$this->pagination->initialize($paginationConfig);
    	$pagination = $this->pagination->create_links();

    	$item_list = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
    	foreach ($item_list as $key => $order) {
    		$orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
    		$item_list[$key]['orderdetailList'] = $orderdetailList;
            //推荐人
            $user_info = $this->User_model->getInfo('parent_id',array('id'=>$order['user_id']));
            $parent_info = '无';
            if ($user_info['parent_id']){
                $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                $parent_info = $parent_user_info ? '<a href="'.base_url().'admincp.php/user/save/'.$user_info['parent_id'].'">'.$parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']</a>' : '无';
            }
            $item_list[$key]['parent_info'] = $parent_info;
            //团购人数
            $groupon_info = $this->Groupon_model->get('*',array('id' => $order['groupon_id']));
            $item_list[$key]['join_people'] = $groupon_info['join_people'];
            //城市地址
            $city_info = $this->Area_model->get('name', array('id' => $order['city_id']));
            $area_info = $this->Area_model->get('name', array('id' => $order['area_id']));
            $item_list[$key]['address'] = $area_info['name'].$order['address'];
            $item_list[$key]['city'] = $city_info['name'];
    	}
    	$data = array(
    			'tool' => $this->_tool,
    			'item_list' => $item_list,
    			'pagination' => $pagination,
    			'paginationCount' => $paginationCount,
    			'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
    			'table'=>$this->_table,
    			'status_arr' => $this->_status_arr
    	);
    	$layout = array(
    			'title' => $this->_title,
    			'content' => $this->load->view("{$this->_table}/index_0", $data, TRUE)
    	);
    	$this->load->view('layout/default', $layout);
    }

    public function index_1($clear = 0, $page = 0) {
    	checkPermission("{$this->_table}_index");
    	if ($clear) {
    		$clear = 0;
    		$this->session->unset_userdata('search');
    	}
    	$uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index_1';
    	$uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
    	$this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

    	$condition = "{$this->_table}.status = 1 ";
    	$strWhere = $this->session->userdata('search')?$this->session->userdata('search'):$condition;
    	if ($_POST) {
    		$strWhere = $condition;
    		$order_number = trim($this->input->post('order_number', TRUE));
    		$buyer_name = trim($this->input->post('buyer_name', TRUE));
            $status = trim($this->input->post('status', TRUE));
            $mobile = trim($this->input->post('mobile', TRUE));
            $startTime = $this->input->post('inputdate_start', TRUE);
            $endTime = $this->input->post('inputdate_end', TRUE);

            if (!empty($order_number)) {
                $strWhere .= " and orders.order_number = '{$order_number}' ";
            }
            if (!empty($buyer_name)) {
                $strWhere .= " and orders.buyer_name regexp '{$buyer_name}' ";
            }
            if ($status != ''){
                $strWhere .= " and orders.status = '{$status}' ";
            }
            if (!empty($mobile)) {
                $strWhere .= " and orders.mobile regexp '{$mobile}' ";
            }
    		if (!empty($startTime) && !empty($endTime)) {
    			$strWhere .= ' and orders.add_time > ' . strtotime($startTime . ' 00:00:00') . ' and orders.add_time < ' . strtotime($endTime . ' 23:59:59') . ' ';
    		}
    		$this->session->set_userdata('search', $strWhere);
    	}

    	//分页
    	$this->config->load('pagination_config', TRUE);
    	$paginationCount = $this->tableObject->rowCount($strWhere);
    	$paginationConfig = $this->config->item('pagination_config');
    	$paginationConfig['base_url'] = base_url() . "admincp.php/{$this->_table}/index_1/{$clear}/";
    	$paginationConfig['total_rows'] = $paginationCount;
    	$paginationConfig['uri_segment'] = 4;
    	$this->pagination->initialize($paginationConfig);
    	$pagination = $this->pagination->create_links();

    	$item_list = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
    	foreach ($item_list as $key => $order) {
    		$orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
    		$item_list[$key]['orderdetailList'] = $orderdetailList;
            //推荐人
            $user_info = $this->User_model->getInfo('parent_id',array('id'=>$order['user_id']));
            $parent_info = '无';
            if (!empty($user_info) && $user_info['parent_id']){
                $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                $parent_info = $parent_user_info ? '<a href="'.base_url().'admincp.php/user/save/'.$user_info['parent_id'].'">'.$parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']</a>' : '无';
            }
            $item_list[$key]['parent_info'] = $parent_info;
            //团购人数
            $groupon_info = $this->Groupon_model->get('*',array('id' => $order['groupon_id']));
            $item_list[$key]['join_people'] = $groupon_info['join_people'];
            //城市地址
            $city_info = $this->Area_model->get('name', array('id' => $order['city_id']));
            $area_info = $this->Area_model->get('name', array('id' => $order['area_id']));
            $item_list[$key]['address'] = $area_info['name'].$order['address'];
            $item_list[$key]['city'] = $city_info['name'];
    	}
    	$data = array(
    			'tool' => $this->_tool,
    			'item_list' => $item_list,
    			'pagination' => $pagination,
    			'paginationCount' => $paginationCount,
    			'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
    			'table'=>$this->_table,
    			'status_arr' => $this->_status_arr
    	);
    	$layout = array(
    			'title' => $this->_title,
    			'content' => $this->load->view("{$this->_table}/index_1", $data, TRUE)
    	);
    	$this->load->view('layout/default', $layout);
    }

    public function index_2($clear = 0, $page = 0) {
    	checkPermission("{$this->_table}_index");
    	if ($clear) {
    		$clear = 0;
    		$this->session->unset_userdata('search');
    	}
    	$uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index_2';
    	$uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
    	$this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

    	$condition = "{$this->_table}.status = 2 ";
    	$strWhere = $this->session->userdata('search')?$this->session->userdata('search'):$condition;
    	if ($_POST) {
    		$strWhere = $condition;
    		$order_number = trim($this->input->post('order_number', TRUE));
    		$buyer_name = trim($this->input->post('buyer_name', TRUE));
            $status = trim($this->input->post('status', TRUE));
            $mobile = trim($this->input->post('mobile', TRUE));
            $startTime = $this->input->post('inputdate_start', TRUE);
            $endTime = $this->input->post('inputdate_end', TRUE);

            if (!empty($order_number)) {
                $strWhere .= " and orders.order_number = '{$order_number}' ";
            }
            if (!empty($buyer_name)) {
                $strWhere .= " and orders.buyer_name regexp '{$buyer_name}' ";
            }
            if ($status != ''){
                $strWhere .= " and orders.status = '{$status}' ";
            }
            if (!empty($mobile)) {
                $strWhere .= " and orders.mobile regexp '{$mobile}' ";
            }
    		if (!empty($startTime) && !empty($endTime)) {
    			$strWhere .= ' and orders.add_time > ' . strtotime($startTime . ' 00:00:00') . ' and orders.add_time < ' . strtotime($endTime . ' 23:59:59') . ' ';
    		}
    		$this->session->set_userdata('search', $strWhere);
    	}

    	//分页
    	$this->config->load('pagination_config', TRUE);
    	$paginationCount = $this->tableObject->rowCount($strWhere);
    	$paginationConfig = $this->config->item('pagination_config');
    	$paginationConfig['base_url'] = base_url() . "admincp.php/{$this->_table}/index_2/{$clear}/";
    	$paginationConfig['total_rows'] = $paginationCount;
    	$paginationConfig['uri_segment'] = 4;
    	$this->pagination->initialize($paginationConfig);
    	$pagination = $this->pagination->create_links();

    	$item_list = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
    	foreach ($item_list as $key => $order) {
    		$orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
    		$item_list[$key]['orderdetailList'] = $orderdetailList;
            //推荐人
            $user_info = $this->User_model->getInfo('parent_id',array('id'=>$order['user_id']));
            $parent_info = '无';
            if ($user_info['parent_id']){
                $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                $parent_info = $parent_user_info ? '<a href="'.base_url().'admincp.php/user/save/'.$user_info['parent_id'].'">'.$parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']</a>' : '无';
            }
            $item_list[$key]['parent_info'] = $parent_info;
            //团购人数
            $groupon_info = $this->Groupon_model->get('*',array('id' => $order['groupon_id']));
            $item_list[$key]['join_people'] = $groupon_info['join_people'];
            //城市地址
            $city_info = $this->Area_model->get('name', array('id' => $order['city_id']));
            $area_info = $this->Area_model->get('name', array('id' => $order['area_id']));
            $item_list[$key]['address'] = $area_info['name'].$order['address'];
            $item_list[$key]['city'] = $city_info['name'];
    	}
    	$data = array(
    			'tool' => $this->_tool,
    			'item_list' => $item_list,
    			'pagination' => $pagination,
    			'paginationCount' => $paginationCount,
    			'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
    			'table'=>$this->_table,
    			'status_arr' => $this->_status_arr
    	);
    	$layout = array(
    			'title' => $this->_title,
    			'content' => $this->load->view("{$this->_table}/index_2", $data, TRUE)
    	);
    	$this->load->view('layout/default', $layout);
    }

    public function index_3($clear = 0, $page = 0) {
    	checkPermission("{$this->_table}_index");
    	if ($clear) {
    		$clear = 0;
    		$this->session->unset_userdata('search');
    	}
    	$uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index_3';
    	$uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
    	$this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

    	$condition = "{$this->_table}.status = 3 ";
    	$strWhere = $this->session->userdata('search')?$this->session->userdata('search'):$condition;
    	if ($_POST) {
    		$strWhere = $condition;
    		$order_number = trim($this->input->post('order_number', TRUE));
    		$buyer_name = trim($this->input->post('buyer_name', TRUE));
            $status = trim($this->input->post('status', TRUE));
            $mobile = trim($this->input->post('mobile', TRUE));
            $startTime = $this->input->post('inputdate_start', TRUE);
            $endTime = $this->input->post('inputdate_end', TRUE);

            if (!empty($order_number)) {
                $strWhere .= " and orders.order_number = '{$order_number}' ";
            }
            if (!empty($buyer_name)) {
                $strWhere .= " and orders.buyer_name regexp '{$buyer_name}' ";
            }
            if ($status != ''){
                $strWhere .= " and orders.status = '{$status}' ";
            }
            if (!empty($mobile)) {
                $strWhere .= " and orders.mobile regexp '{$mobile}' ";
            }
    		if (!empty($startTime) && !empty($endTime)) {
    			$strWhere .= ' and orders.add_time > ' . strtotime($startTime . ' 00:00:00') . ' and orders.add_time < ' . strtotime($endTime . ' 23:59:59') . ' ';
    		}
    		$this->session->set_userdata('search', $strWhere);
    	}

    	//分页
    	$this->config->load('pagination_config', TRUE);
    	$paginationCount = $this->tableObject->rowCount($strWhere);
    	$paginationConfig = $this->config->item('pagination_config');
    	$paginationConfig['base_url'] = base_url() . "admincp.php/{$this->_table}/index_3/{$clear}/";
    	$paginationConfig['total_rows'] = $paginationCount;
    	$paginationConfig['uri_segment'] = 4;
    	$this->pagination->initialize($paginationConfig);
    	$pagination = $this->pagination->create_links();

    	$item_list = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
    	foreach ($item_list as $key => $order) {
    		$orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
    		$item_list[$key]['orderdetailList'] = $orderdetailList;
            //推荐人
            $user_info = $this->User_model->getInfo('parent_id',array('id'=>$order['user_id']));
            $parent_info = '无';
            if ($user_info['parent_id']){
                $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                $parent_info = $parent_user_info ? '<a href="'.base_url().'admincp.php/user/save/'.$user_info['parent_id'].'">'.$parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']</a>' : '无';
            }
            $item_list[$key]['parent_info'] = $parent_info;
            //团购人数
            $groupon_info = $this->Groupon_model->get('*',array('id' => $order['groupon_id']));
            $item_list[$key]['join_people'] = $groupon_info['join_people'];
            //城市地址
            $city_info = $this->Area_model->get('name', array('id' => $order['city_id']));
            $area_info = $this->Area_model->get('name', array('id' => $order['area_id']));
            $item_list[$key]['address'] = $area_info['name'].$order['address'];
            $item_list[$key]['city'] = $city_info['name'];
    	}
    	$data = array(
    			'tool' => $this->_tool,
    			'item_list' => $item_list,
    			'pagination' => $pagination,
    			'paginationCount' => $paginationCount,
    			'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
    			'table'=>$this->_table,
    			'status_arr' => $this->_status_arr
    	);
    	$layout = array(
    			'title' => $this->_title,
    			'content' => $this->load->view("{$this->_table}/index_3", $data, TRUE)
    	);
    	$this->load->view('layout/default', $layout);
    }

    public function index_4($clear = 0, $page = 0) {
    	checkPermission("{$this->_table}_index");
    	if ($clear) {
    		$clear = 0;
    		$this->session->unset_userdata('search');
    	}
    	$uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index_4';
    	$uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
    	$this->session->set_userdata(array("{$this->_table}RefUrl"=>$uri_sg));

    	$condition = "{$this->_table}.status = 4 ";
    	$strWhere = $this->session->userdata('search')?$this->session->userdata('search'):$condition;
    	if ($_POST) {
    		$strWhere = $condition;
    		$order_number = trim($this->input->post('order_number', TRUE));
    		$buyer_name = trim($this->input->post('buyer_name', TRUE));
            $status = trim($this->input->post('status', TRUE));
            $mobile = trim($this->input->post('mobile', TRUE));
            $startTime = $this->input->post('inputdate_start', TRUE);
            $endTime = $this->input->post('inputdate_end', TRUE);

            if (!empty($order_number)) {
                $strWhere .= " and orders.order_number = '{$order_number}' ";
            }
            if (!empty($buyer_name)) {
                $strWhere .= " and orders.buyer_name regexp '{$buyer_name}' ";
            }
            if ($status != ''){
                $strWhere .= " and orders.status = '{$status}' ";
            }
            if (!empty($mobile)) {
                $strWhere .= " and orders.mobile regexp '{$mobile}' ";
            }
    		if (!empty($startTime) && !empty($endTime)) {
    			$strWhere .= ' and orders.add_time > ' . strtotime($startTime . ' 00:00:00') . ' and orders.add_time < ' . strtotime($endTime . ' 23:59:59') . ' ';
    		}
    		$this->session->set_userdata('search', $strWhere);
    	}

    	//分页
    	$this->config->load('pagination_config', TRUE);
    	$paginationCount = $this->tableObject->rowCount($strWhere);
    	$paginationConfig = $this->config->item('pagination_config');
    	$paginationConfig['base_url'] = base_url() . "admincp.php/{$this->_table}/index_4/{$clear}/";
    	$paginationConfig['total_rows'] = $paginationCount;
    	$paginationConfig['uri_segment'] = 4;
    	$this->pagination->initialize($paginationConfig);
    	$pagination = $this->pagination->create_links();

    	$item_list = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
    	foreach ($item_list as $key => $order) {
    		$orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
    		$item_list[$key]['orderdetailList'] = $orderdetailList;
            //推荐人
            $user_info = $this->User_model->getInfo('parent_id',array('id'=>$order['user_id']));
            $parent_info = '无';
            if ($user_info['parent_id']){
                $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                $parent_info = $parent_user_info ? '<a href="'.base_url().'admincp.php/user/save/'.$user_info['parent_id'].'">'.$parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']</a>' : '无';
            }
            $item_list[$key]['parent_info'] = $parent_info;
            //团购人数
            $groupon_info = $this->Groupon_model->get('*',array('id' => $order['groupon_id']));
            $item_list[$key]['join_people'] = $groupon_info['join_people'];
            //城市地址
            $city_info = $this->Area_model->get('name', array('id' => $order['city_id']));
            $area_info = $this->Area_model->get('name', array('id' => $order['area_id']));
            $item_list[$key]['address'] = $area_info['name'].$order['address'];
            $item_list[$key]['city'] = $city_info['name'];
    	}
    	$data = array(
    			'tool' => $this->_tool,
    			'item_list' => $item_list,
    			'pagination' => $pagination,
    			'paginationCount' => $paginationCount,
    			'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
    			'table'=>$this->_table,
    			'status_arr' => $this->_status_arr
    	);
    	$layout = array(
    			'title' => $this->_title,
    			'content' => $this->load->view("{$this->_table}/index_4", $data, TRUE)
    	);
    	$this->load->view('layout/default', $layout);
    }

    public function view($id = NULL) {
       checkPermission("{$this->_table}_view");
        $prfUrl = $this->session->userdata("{$this->_table}RefUrl") ? $this->session->userdata("{$this->_table}RefUrl") : base_url() . "admincp.php/{$this->_table}/index/1";
        $item_info = $this->tableObject->get('*', array('id' => $id));
        $orders_detail_list = NULL;
        $user_info = NULL;
        $orders_process_list = NULL;
        if ($item_info) {
            $user_info = $this->User_model->get(array('user.id' => $item_info['user_id']));
            $orders_detail_list = $this->Orders_detail_model->gets('*', array('order_id' => $id));
            $orders_process_list = $this->Orders_process_model->gets('*', array('order_id' => $id));
        }

        $data = array(
            'tool' => $this->_tool,
            'table'=>$this->_table,
        	'status_arr' => $this->_status_arr,
        	'sex_arr'=>$this->_sex_arr,
        	'item_info' => $item_info,
            'user_info' => $user_info,
        	'orders_detail_list'=>$orders_detail_list,
        	'orders_process_list'=>$orders_process_list,
            'prfUrl' => $prfUrl
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_table}/view", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function print_order($id = NULL) {
       checkPermission("{$this->_table}_view");
        $item_info = $this->tableObject->get('*', array('id' => $id));
        $orders_detail_list = NULL;
        $user_info = NULL;
        $orders_process_list = NULL;
        if ($item_info) {
            $user_info = $this->User_model->get(array('user.id' => $item_info['user_id']));
            $orders_detail_list = $this->Orders_detail_model->gets('*', array('order_id' => $id));
            $orders_process_list = $this->Orders_process_model->gets('*', array('order_id' => $id));
        }

        $data = array(
            'tool' => '',
            'table'=>$this->_table,
        	'status_arr' => $this->_status_arr,
        	'sex_arr'=>$this->_sex_arr,
        	'item_info' => $item_info,
            'user_info' => $user_info,
        	'orders_detail_list'=>$orders_detail_list,
        	'orders_process_list'=>$orders_process_list
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_table}/print_order", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function delete() {
        checkPermissionAjax("{$this->_table}_delete");
        $ids = $this->input->post('ids', TRUE);
        if (!empty($ids)) {
            if ($this->tableObject->delete('id in (' . $ids . ')')) {
                //删除订单详细
                $this->Orders_detail_model->delete("order_id in ({$ids})");
                //删除跟踪记录
                $this->Orders_process_model->delete("order_id in ({$ids})");
                printAjaxData(array('ids' => explode(',', $ids)));
            }
        }

        printAjaxError('fail', '删除失败！');
    }

    //修改价格
    public function change_price() {
    	checkPermissionAjax("{$this->_table}_edit");
    	if ($_POST) {
    		$id = $this->input->post('id', TRUE);
    		$order_total = $this->input->post('order_total', TRUE);

    		if (!$id) {
    			printAjaxError('fail', '操作异常');
    		}
    		if (!$this->form_validation->required($order_total)) {
    			printAjaxError('order_total', '修改金额不能为空');
    		}
    		if (!$this->form_validation->numeric($order_total)) {
    			printAjaxError('order_total', '请输入正确的订单金额');
    		}
    		$item_info = $this->tableObject->get('id, total, status', array('id' => $id));
    		if (!$item_info) {
    			printAjaxError('fail', '此订单信息不存在');
    		}
    		if ($item_info['status'] != 0) {
    			printAjaxError('fail', '当前状态不能修改订单金额');
    		}

    		$fields = array(
    				'total' => $order_total
    		);
    		if ($this->tableObject->save($fields, array('id' => $item_info['id'], 'status'=>0))) {
    			$fields = array(
    					'add_time' => time(),
    					'content' => "修改订单金额成功-[订单金额由“{$item_info['total']}”元修改为“{$order_total}”元]",
    					'order_id' => $item_info['id'],
    					'order_status'=>0,
    					'change_status'=>0
    			);
    			$this->Orders_process_model->save($fields);
    			printAjaxSuccess('success', '订单金额修改成功');
    		} else {
    			printAjaxError('fail', "订单金额修改失败");
    		}
    	}
    }

    //交易关闭
    public function close_order() {
    	checkPermissionAjax("{$this->_table}_edit");
    	if ($_POST) {
    		$id = $this->input->post('id', TRUE);
    		$cancel_cause = $this->input->post('cancel_cause', TRUE);

    		if (!$id) {
    			printAjaxError('fail', '操作异常');
    		}
    		if (!$this->form_validation->required($cancel_cause)) {
    			printAjaxError('cancel_cause', '请填写交易关闭的原因');
    		}
    		$item_info = $this->tableObject->get('id, user_id, order_number, status', array('id' => $id));
    		if (!$item_info) {
    			printAjaxError('fail', '此订单信息不存在');
    		}
    		if ($item_info['status'] != 0) {
    			printAjaxError('fail', '当前状态不能关闭交易');
    		}
			$user_info = $this->User_model->get(array('id' => $item_info['user_id']));
            if (!$user_info) {
            	printAjaxError('fail', "用户账号不存在或被管理员删除");
            }

    		$fields = array(
    				'cancel_cause'=> $cancel_cause,
    				'status'=> 4
    		);
    		if ($this->tableObject->save($fields, array('id' => $id, 'status'=>0))) {
    			$fields = array(
    					'add_time' => time(),
    					'content' => "交易关闭-[卖家关闭订单]",
    					'order_id' => $item_info['id'],
    					'order_status'=>$item_info['status'],
    					'change_status'=>4
    			);
    			$this->Orders_process_model->save($fields);

				//加库存-下单减库存

                $orders_detail_list = $this->Orders_detail_model->gets('*', array('order_id' => $id));
                if ($orders_detail_list) {
                    foreach ($orders_detail_list as $item) {
                        if ($item['color_size_open'] == 1) {
                            $product_stock_info = $this->Product_model->getProductStock($item['product_id'], $item['color_id'], $item['size_id']);
                            if ($product_stock_info) {
                                $stock = $product_stock_info['stock'] + $item['buy_number'];
                                $this->Product_model->changeStock(array('stock' => $stock), array('product_id' => $item['product_id'], 'color_id' => $item['color_id'], 'size_id' => $item['size_id']));
                            }
                        } else {
                            $product_info = $this->Product_model->get('stock', array('id' => $item['product_id']));
                            if ($product_info) {
                                $stock = $product_info['stock'] + $item['buy_number'];
                                $this->Product_model->save(array('stock' =>$stock), array('id' => $item['product_id']));
                            }
                        }
                    }
                }
    			printAjaxSuccess('success', '交易关闭成功');
    		}else {
    			printAjaxError('fail', "交易关闭失败");
    		}
    	}
    }

    /**
	 * 自动取消订单
	 */
    public function auto_close_order() {
    	$this->System_model->save(array('auto_close_time'=>time()), array('id'=>1));
    	$systemInfo = $this->System_model->get('stock_reduce_type, close_order_time', array('id' => 1));
		$space_time = $systemInfo['close_order_time']*3600;
    	$item_list = $this->tableObject->gets("status = 0 and unix_timestamp(now()) - add_time > {$space_time}", 100, 0);
		if ($item_list) {
			foreach($item_list as $key=>$value) {
				$fields = array(
	    				'cancel_cause'=> '超过系统付款时间，系统自动取消订单',
	    				'status'=> 4
	    		);
	    		if ($this->tableObject->save($fields, array('id'=>$value['id'], 'status'=>0))) {
	    			$fields = array(
	    					'add_time' => time(),
	    					'content' => "交易关闭-[超过系统付款时间，系统自动取消订单]",
	    					'order_id' => $value['id'],
	    					'order_status'=>$value['status'],
	    					'change_status'=>4
	    			);
	    			$this->Orders_process_model->save($fields);
					//退换购积分
					$user_info = $this->User_model->getInfo('*', array('id' => $value['user_id']));
		            if ($user_info) {
		            	//判断是不是积分换购
						if ($value['pay_mode'] > 0) {
							//退还金象积分部分
							if ($value['deductible_score_gold'] > 0) {
								if (!$this->Score_model->rowCount(array('score_type'=>'gold', 'user_id' => $user_info['id'], 'ret_id' => $value['id'], 'type' => 'orders_buy_in'))) {
		                			$balance = $user_info['score_gold'] + $value['deductible_score_gold'];
		                			if ($this->User_model->save(array('score_gold'=>$balance), array('id' => $user_info['id']))) {
		                				$sFields = array(
		                						'score_type'=>'gold',
		                						'cause' => "交易关闭，退还积分-[订单号：{$value['order_number']}]",
		                						'score' => $value['deductible_score_gold'],
		                						'balance' => $balance,
		                						'type' => 'orders_buy_in',
		                						'add_time' => time(),
		                						'username' => $user_info['username'],
		                						'user_id' => $user_info['id'],
		                						'ret_id' => $value['id']
		                				);
		                				$this->Score_model->save($sFields);
		                			}
		                		}
							}
							//退还银象积分部分
							if ($value['deductible_score_silver'] > 0) {
								if (!$this->Score_model->rowCount(array('score_type'=>'silver', 'user_id' => $user_info['id'], 'ret_id' => $value['id'], 'type' => 'orders_buy_in'))) {
		                			$balance = $user_info['score_silver'] + $value['deductible_score_silver'];
		                			if ($this->User_model->save(array('score_silver'=>$balance), array('id' => $user_info['id']))) {
		                				$sFields = array(
		                						'score_type'=>'silver',
		                						'cause' => "交易关闭，退还积分-[订单号：{$value['order_number']}]",
		                						'score' => $value['deductible_score_silver'],
		                						'balance' => $balance,
		                						'type' => 'orders_buy_in',
		                						'add_time' => time(),
		                						'username' => $user_info['username'],
		                						'user_id' => $user_info['id'],
		                						'ret_id' => $value['id']
		                				);
		                				$this->Score_model->save($sFields);
		                			}
		                		}
							}
						}
		            }
					//加库存-下单减库存
					if ($systemInfo['stock_reduce_type'] == 0) {
			            $orders_detail_list = $this->Orders_detail_model->gets('*', array('order_id' => $value['id']));
			            if ($orders_detail_list) {
			            	foreach ($orders_detail_list as $item) {
			            		if ($item['color_size_open'] == 1) {
			            			$product_stock_info = $this->Product_model->getProductStock($item['product_id'], $item['color_id'], $item['size_id']);
			            			if ($product_stock_info) {
			            				$stock = $product_stock_info['stock'] + $item['buy_number'];
			            				$this->Product_model->changeStock(array('stock' => $stock), array('product_id' => $item['product_id'], 'color_id' => $item['color_id'], 'size_id' => $item['size_id']));
			            			}
			            		} else {
			            			$product_info = $this->Product_model->get('stock', array('id' => $item['product_id']));
			            			if ($product_info) {
			            				$stock = $product_info['stock'] + $item['buy_number'];
			            				$this->Product_model->save(array('stock' =>$stock), array('id' => $item['product_id']));
			            			}
			            		}
			            	}
			            }
		            }
	    		}
			}
		}
    }

    //修改状态(已付款)
    public function change_pay() {
    	checkPermissionAjax("{$this->_table}_edit");
    	if ($_POST) {
    		$id = $this->input->post('id', TRUE);
    		$remark = $this->input->post('remark', TRUE);

    		if (!$id) {
    			printAjaxError('fail', '操作异常');
    		}
    		if (!$remark) {
    			printAjaxError('remark', '备注不能为空');
    		}
    		$item_info = $this->tableObject->get('*', array('id' => $id));
    		if (!$item_info) {
    			printAjaxError('fail', '此订单信息不存在');
    		}
    		if ($item_info['status'] != 0) {
    			printAjaxError('fail', '当前状态不能将订单状态修改为已付款');
    		}

    		$fields = array(
                'status' => 1,
                'is_free'=>1
    		);
    		if ($this->tableObject->save($fields, array('id' => $id, 'status'=>0))) {

                if ($item_info['order_type']){
                    //参团记录
                    $this->Groupon_record_model->save(array('order_id'=>$item_info['id']), array('id' => $item_info['record_id']));
                }else{
                    $groupon_info = $this->Groupon_model->get('*',array('id' => $item_info['groupon_id']));
                    if ($groupon_info){
                        $this->Groupon_model->save(array('join_people'=>$groupon_info['join_people']+1),array('id'=>$item_info['groupon_id']));
                    }
                }

    			//订单跟踪记录
    			$fields = array(
    					'add_time' => time(),
    					'content' => "已付款状态修改成功[{$remark}]",
    					'order_id' => $item_info['id'],
    					'order_status'=>$item_info['status'],
    					'change_status'=>1
    			);
    			$this->Orders_process_model->save($fields);

				//付款减库存 加销量

                $orders_detail_list = $this->Orders_detail_model->gets('*', array('order_id' => $id));
                if ($orders_detail_list) {
                    foreach ($orders_detail_list as $item) {
                        if ($item['color_size_open'] == 1) {
                            $product_stock_info = $this->Product_model->getProductStock($item['product_id'], $item['color_id'], $item['size_id']);
                            if ($product_stock_info) {
                                $stock = $product_stock_info['stock'] - $item['buy_number'];
                                $this->Product_model->changeStock(array('stock' => $stock), array('product_id' => $item['product_id'], 'color_id' => $item['color_id'], 'size_id' => $item['size_id']));
                            }
                        } else {
                            $product_info = $this->Product_model->get('stock,sales', array('id' => $item['product_id']));
                            if ($product_info) {
                                $stock = $product_info['stock'] - $item['buy_number'];
                                $this->Product_model->save(array('stock' =>$stock>0?$stock:0,'sales'=>$product_info['sales'] + 1), array('id' => $item['product_id']));
                            }
                        }
                    }
                }

    			printAjaxSuccess('success', '订单状态设置成功');
    		}else {
    			printAjaxError('fail', "订单状态设置失败");
    		}
    	}
    }

    //发货
    public function delivery() {
    	checkPermissionAjax("{$this->_table}_edit");
    	if ($_POST) {
    		$id = $this->input->post('id', TRUE);
    		$delivery_name = $this->input->post('delivery_name', TRUE);
    		$express_number = $this->input->post('express_number', TRUE);
    		$remark = $this->input->post('remark', TRUE);

    		if (!$id) {
    			printAjaxError('fail', '操作异常');
    		}
    		if (!$this->form_validation->required($delivery_name)) {
    			printAjaxError('delivery_name', '快递名称不能为空');
    		}
    		if (!$this->form_validation->required($express_number)) {
    			printAjaxError('express_number', '快递单号不能为空');
    		}
    		$item_info = $this->tableObject->get('id, order_number, status, user_id', array('id' => $id));
    		if (!$item_info) {
    			printAjaxError('fail', '此订单信息不存在');
    		}
    		if ($item_info['status'] != 1) {
    			printAjaxError('fail', '当前状态不能发货');
    		}
//     		$exchange_info = $this->Exchange_model->get('*', array('orders_id'=>$id, 'user_id'=>$item_info['user_id']));
//     		if ($exchange_info) {
//     			if ($exchange_info['status'] >= 3) {
//     				printAjaxError('fail', "此订单退款申请成功，不能完成下面的操作");
//     			} else {
//     				if ($exchange_info['status'] != 1) {
//     					printAjaxError('fail', "此订单退款申请审核中，不能完成下面的操作");
//     				}
//     			}
//     		}

    		$fields = array(
    				'delivery_name' => $delivery_name,
    				'express_number' => $express_number,
    				'express_time'=>time(),
    				'status' => 2
    		);
    		if ($this->tableObject->save($fields, array('id' => $id))) {
    			//订单跟踪记录
    			$fields = array(
    					'add_time' => time(),
    					'content' => "发货成功[{$remark}]",
    					'order_id' => $item_info['id'],
    					'order_status'=>$item_info['status'],
    					'change_status'=>2
    			);
    			$this->Orders_process_model->save($fields);
//                //发消息
//    			$fields = array(
//    					'message_type' => 'order',
//    					'to_user_id' => $item_info['user_id'],
//    					'from_user_id' => 0,
//    					'content' => "订单{$item_info['order_number']}已成功为您发货，如有疑问请联系客服",
//    					'map_id'=>$item_info['id'],
//    					'admin_id'=>get_cookie('admin_id'),
//    					'add_time' => time()
//    			);
//    			$this->Message_model->save($fields);
//    			//发推送
//    			$user_info = $this->User_model->get(array('user.id'=>$item_info['user_id']));
//    			if ($user_info) {
//    				if($user_info['push_cid']){
//    					$this->_send_push($user_info['push_cid'], "订单{$item_info['order_number']}已成功为您发货，如有疑问请联系客服");
//    				}
//    			}
    			printAjaxSuccess('success', '发货成功');
    		} else {
    			printAjaxError('fail', '发货失败');
    		}
    	}
    }

    //收货
    public function receiving() {
    	checkPermissionAjax("{$this->_table}_edit");
    	if ($_POST) {
    		$id = $this->input->post('id', TRUE);
    		if (!$id) {
    			printAjaxError('fail', "操作异常，刷新重试");
    		}
    		$item_info = $this->tableObject->get('*', array('id' => $id));
    		if (!$item_info) {
    			printAjaxError('fail', "不存在此订单");
    		}
    		if ($item_info['status'] != 2) {
    			printAjaxError('fail', "此订单状态异常，确认收货失败");
    		}
//     		$exchange_info = $this->Exchange_model->get('*', array('orders_id'=>$id, 'user_id'=>$item_info['user_id']));
//     		if ($exchange_info) {
//     			if ($exchange_info['status'] >= 3) {
//     				printAjaxError('fail', "此订单退款申请成功，不能完成下面的操作");
//     			} else {
//     				if ($exchange_info['status'] != 1) {
//     					printAjaxError('fail', "此订单退款申请审核中，不能完成下面的操作");
//     				}
//     			}
//     		}
    		$userInfo = $this->User_model->getInfo('id, username', array('id' => $item_info['user_id']));
    		if (!$userInfo) {
    			printAjaxError('fail', "此订单异常，确认收货失败");
    		}
    		$fields = array(
    				'status' => 3
    		);
    		if ($this->tableObject->save($fields, array('id' => $id))) {
    			//操作记录
    			$fields = array(
    					'add_time' => time(),
    					'content' => "确认收货，交易成功",
    					'order_id' => $item_info['id'],
    					'order_status'=>$item_info['status'],
    					'change_status'=>3
    			);
    			$this->Orders_process_model->save($fields);

    			//减库存与加销售量
    			$orders_detail_list = $this->Orders_detail_model->gets('product_id, buy_number', array('order_id' => $id));
    			if ($orders_detail_list) {
    				foreach ($orders_detail_list as $key=>$value) {
    					//商品库存与销售量
    					$product_info = $this->Product_model->get('sales', array('id'=>$value['product_id']));
    					if ($product_info) {
    						$fields = array(
    								'sales'=>$product_info['sales'] + $value['buy_number']
    						);
    						$this->Product_model->save($fields, array('id'=>$value['product_id']));
    					}
    				}
    			}
    			printAjaxSuccess('success', '确认收货成功');
    		} else {
    			printAjaxError('fail', "确认收货失败");
    		}
    	}
    }

    /**
	 * 自动确认收货
	 */
    public function auto_receiving() {
    	$this->System_model->save(array('auto_receiving_time'=>time()), array('id'=>1));
    	$systemInfo = $this->System_model->get('receiving_order_time', array('id' => 1));
		$space_time = $systemInfo['receiving_order_time']*3600*24;
    	$item_list = $this->tableObject->gets("status = 2 and unix_timestamp(now()) - express_time > {$space_time}", 100, 0);
		if ($item_list) {
			foreach($item_list as $key=>$value) {
				$userInfo = $this->User_model->getInfo('id, username, score_gold, score_silver', array('id' => $value['user_id']));
	    		if ($userInfo) {
	    			$fields = array(
		    				'status' => 3
		    		);
		    		if ($this->tableObject->save($fields, array('id' => $value['id'], 'status'=>2))) {
		    			//操作记录
		    			$fields = array(
		    					'add_time' => time(),
		    					'content' => "确认收货，交易成功[超过系统收货时间，系统自动确认订单]",
		    					'order_id' => $value['id'],
		    					'order_status'=>$value['status'],
		    					'change_status'=>3
		    			);
		    			$this->Orders_process_model->save($fields);
		    			//消费者积分操作
		    			//金象积分
		    			if ($value['gold_score'] > 0) {
		    				if (!$this->Score_model->rowCount(array('score_type'=>'gold', 'type' =>'orders_in', 'user_id' =>  $userInfo['id'], 'ret_id' =>   $value['id']))) {
		    					$balance = $userInfo['score_gold'] + $value['gold_score'];
		    					if ($this->User_model->save(array('score_gold' =>$balance), array('id' => $userInfo['id']))) {
		    						$fields = array(
		    								'cause' =>    "订单交易成功-{$value['order_number']}",
		    								'score' =>    $value['gold_score'],
		    								'balance' =>  $balance,
		    								'score_type'=>'gold',
		    								'type' =>     'orders_in',
		    								'add_time' => time(),
		    								'username' => $userInfo['username'],
		    								'user_id' =>  $userInfo['id'],
		    								'ret_id' =>   $value['id']
		    						);
		    						$this->Score_model->save($fields);
		    					}
		    				}
		    			}
		    			//银象积分
		    			if ($value['silver_score'] > 0) {
		    				if (!$this->Score_model->rowCount(array('score_type'=>'silver', 'type'=>'orders_in', 'user_id'=>$userInfo['id'], 'ret_id'=>$value['id']))) {
		    					$balance = $userInfo['score_silver'] + $value['silver_score'];
		    					if ($this->User_model->save(array('score_silver' =>$balance), array('id' => $userInfo['id']))) {
		    						$fields = array(
		    								'cause' =>    "订单交易成功-{$value['order_number']}",
		    								'score' =>    $value['silver_score'],
		    								'balance' =>  $balance,
		    								'score_type'=>'silver',
		    								'type' =>     'orders_in',
		    								'add_time' => time(),
		    								'username' => $userInfo['username'],
		    								'user_id' =>  $userInfo['id'],
		    								'ret_id' =>   $value['id']
		    						);
		    						$this->Score_model->save($fields);
		    					}
		    				}
		    			}
		    			/*********************分销-返积分*******************/
		    			//一级提成
		    			if ($value['divide_user_id_1'] > 0) {
		    				$user_info_1 = $this->User_model->getInfo('id, username, score_gold, score_silver', array('id' => $value['divide_user_id_1']));
		    				if ($user_info_1) {
		    					if ($value['divide_user_score_gold_1'] > 0) {
		    						if (!$this->Score_model->rowCount(array('score_type'=>'gold', 'type' =>'presenter_in', 'user_id' =>$user_info_1['id'], 'ret_id' =>$value['id']))) {
		    							$balance = $user_info_1['score_gold'] + $value['divide_user_score_gold_1'];
		    							if ($this->User_model->save(array('score_gold' =>$balance), array('id' => $user_info_1['id']))) {
		    								$fields = array(
		    										'cause' =>    "订单交易成功-订单{$value['order_number']}返提成",
		    										'score' =>    $value['divide_user_score_gold_1'],
		    										'balance' =>  $balance,
		    										'score_type'=>'gold',
		    										'type' =>     'presenter_in',
		    										'add_time' => time(),
		    										'username' => $user_info_1['username'],
		    										'user_id' =>  $user_info_1['id'],
		    										'ret_id' =>   $value['id'],
		    										'from_user_id'=>$value['user_id']
		    								);
		    								$this->Score_model->save($fields);
		    							}
		    						}
		    					}
		    					if ($value['divide_user_score_silver_1'] > 0) {
		    						if (!$this->Score_model->rowCount(array('score_type'=>'silver', 'type' =>'presenter_in', 'user_id' =>$user_info_1['id'], 'ret_id' =>$value['id']))) {
		    							$balance = $user_info_1['score_silver'] + $value['divide_user_score_silver_1'];
		    							if ($this->User_model->save(array('score_silver' =>$balance), array('id' => $user_info_1['id']))) {
		    								$fields = array(
		    										'cause' =>    "订单交易成功-订单{$value['order_number']}返提成",
		    										'score' =>    $value['divide_user_score_silver_1'],
		    										'balance' =>  $balance,
		    										'score_type'=>'silver',
		    										'type' =>     'presenter_in',
		    										'add_time' => time(),
		    										'username' => $user_info_1['username'],
		    										'user_id' =>  $user_info_1['id'],
		    										'ret_id' =>   $value['id'],
		    										'from_user_id'=>$value['user_id']
		    								);
		    								$this->Score_model->save($fields);
		    							}
		    						}
		    					}
		    				}
		
		    			}
		    			//二级提成
		    			if ($value['divide_user_id_2'] > 0) {
		    				$user_info_2 = $this->User_model->getInfo('id, username, score_gold, score_silver', array('id' => $value['divide_user_id_2']));
		    				if ($user_info_2) {
		    					if ($value['divide_user_score_gold_2'] > 0) {
		    						if (!$this->Score_model->rowCount(array('score_type'=>'gold', 'type' =>'presenter_in', 'user_id' =>$user_info_2['id'], 'ret_id' =>$value['id']))) {
		    							$balance = $user_info_2['score_gold'] + $value['divide_user_score_gold_2'];
		    							if ($this->User_model->save(array('score_gold' =>$balance), array('id' => $user_info_2['id']))) {
		    								$fields = array(
		    										'cause' =>    "订单交易成功-订单{$value['order_number']}返提成",
		    										'score' =>    $value['divide_user_score_gold_2'],
		    										'balance' =>  $balance,
		    										'score_type'=>'gold',
		    										'type' =>     'presenter_in',
		    										'add_time' => time(),
		    										'username' => $user_info_2['username'],
		    										'user_id' =>  $user_info_2['id'],
		    										'ret_id' =>   $value['id'],
		    										'from_user_id'=>$value['user_id']
		    								);
		    								$this->Score_model->save($fields);
		    							}
		    						}
		    					}
		    					if ($value['divide_user_score_silver_2'] > 0) {
		    						if (!$this->Score_model->rowCount(array('score_type'=>'silver', 'type' =>'presenter_in', 'user_id' =>$user_info_2['id'], 'ret_id' =>$value['id']))) {
		    							$balance = $user_info_2['score_silver'] + $value['divide_user_score_silver_2'];
		    							if ($this->User_model->save(array('score_silver' =>$balance), array('id' => $user_info_2['id']))) {
		    								$fields = array(
		    										'cause' =>    "订单交易成功-订单{$value['order_number']}返提成",
		    										'score' =>    $value['divide_user_score_silver_2'],
		    										'balance' =>  $balance,
		    										'score_type'=>'silver',
		    										'type' =>     'presenter_in',
		    										'add_time' => time(),
		    										'username' => $user_info_2['username'],
		    										'user_id' =>  $user_info_2['id'],
		    										'ret_id' =>   $value['id'],
		    										'from_user_id'=>$value['user_id']
		    								);
		    								$this->Score_model->save($fields);
		    							}
		    						}
		    					}
		    				}
		    			}
		    			//减库存与加销售量
		    			$orders_detail_list = $this->Orders_detail_model->gets('product_id, buy_number', array('order_id' => $value['id']));
		    			if ($orders_detail_list) {
		    				foreach ($orders_detail_list as $od_key=>$od_value) {
		    					//商品库存与销售量
		    					$product_info = $this->Product_model->get('sales', array('id'=>$od_value['product_id']));
		    					if ($product_info) {
		    						$fields = array(
		    								'sales'=>$product_info['sales'] + $od_value['buy_number']
		    						);
		    						$this->Product_model->save($fields, array('id'=>$od_value['product_id']));
		    					}
		    				}
		    			}
		    		}
	    		}
			}
		}
    }

    public function query_logistics($num = '') {
        checkPermissionAjax("{$this->_table}_logistics");
        $key = 'mZRQwDVc3377';
        $result = file_get_contents("http://www.kuaidi100.com/autonumber/auto?num=$num&key=$key");
        $arr = json_decode($result, true);
        if (empty($arr)) {
            return array();
        }
        $com = $arr[0]['comCode'];
        $post_data = array();
        $post_data["customer"] = '1FCC0BAA1D04BBDFEB9EE3F96F963265';
        $post_data["param"] = '{"com":"' . $com . '","num":"' . $num . '"}';
        $url = 'http://www.kuaidi100.com/poll/query.do';
        $post_data["sign"] = md5($post_data["param"] . $key . $post_data["customer"]);
        $post_data["sign"] = strtoupper($post_data["sign"]);
        $o = "";
        foreach ($post_data as $k => $v) {
            $o.= "$k=" . urlencode($v) . "&";  //默认UTF-8编码格式
        }
        $post_data = substr($o, 0, -1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = str_replace("\&quot;", '"', $result);
        echo $data;
    }

    //消息推送，只针对手机登录的用户有效
    private function _send_push($cid = '', $message = '') {
           $getui = new Getuiapiclass();
           $getui->send_push($cid, $message);
    }

    public function export() {
        $prfUrl = $this->session->userdata("{$this->_table}RefUrl") ? $this->session->userdata("{$this->_table}RefUrl") : base_url() . "admincp.php/{$this->_table}/index/";

        $title_arr = array(
            'order_num'=>'订单号',
            'buyer_name'=>'客户姓名',
            'mobile'=>'手机',
            'order_abstract'=>'订单描述',
            'buyer_city'=>'城市',
            'address'=>'收件地址',
            'time'=>'时间',
            'status'=>'订单状态',
            'total'=>'总金额',
            'join_people'=>'人数',
            'parent_info'=>'推荐人',
            'remark'=>'备注'
            );
        if($_POST) {
            ignore_user_abort(true); // run script in background
            set_time_limit(0); // run script forever,运行时间为无限后结束

            $status = trim($this->input->post('status', TRUE));
            $inputdate_start = trim($this->input->post('inputdate_start', TRUE));
            $inputdate_end =  trim($this->input->post('inputdate_end', TRUE));

            $strWhere = "{$this->_table}.id > 0 ";
            if ($status != '') {
                $strWhere .= " and {$this->_table}.status = {$status} ";
            }
            if ($inputdate_start && $inputdate_end) {
                $strWhere .= " and ({$this->_table}.add_time > ".strtotime($inputdate_start.' 00:00:00')." and {$this->_table}.add_time < ".strtotime($inputdate_end.' 23:59:59').") ";
            }
            $item_list = $this->tableObject->gets($strWhere);
            foreach ($item_list as $key => $order) {
//                $orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
//                $item_list[$key]['orderdetailList'] = $orderdetailList;
                //推荐人
                $user_info = $this->User_model->getInfo('parent_id',array('id'=>$order['user_id']));
                $parent_info = '无';
                if ($user_info && $user_info['parent_id']){
                    $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                    $parent_info = $parent_user_info ? $parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']' : '无';
                }
                $item_list[$key]['parent_info'] = $parent_info;
                //团购人数
                $groupon_info = $this->Groupon_model->get('*',array('id' => $order['groupon_id']));
                $item_list[$key]['join_people'] = $groupon_info['join_people'];
                //城市地址
                $city_info = $this->Area_model->get('name', array('id' => $order['city_id']));
                $area_info = $this->Area_model->get('name', array('id' => $order['area_id']));
                $item_list[$key]['address'] = $area_info['name'].$order['address'];
                $item_list[$key]['city'] = $city_info['name'];
            }

            //标题字段
            $tmp_title_arr = array();
            foreach ($title_arr as $key=>$value) {
                $tmp_title_arr[$key] = iconv('utf-8', 'gbk', $value);
            }
            if ($item_list) {
                $tmp_path = "./uploads/".getUniqueFileName('./uploads').".csv";
                $file = fopen($tmp_path, "w");
                //标题
                fputcsv($file, $tmp_title_arr);
                foreach ($item_list as $key=>$value) {
                    $add_time = date("Y-m-d H:i", $value['add_time']);
                    $pay_time = $value['pay_time'] ? date("Y-m-d H:i", $value['pay_time']) : '';
                    $status = $this->_status_arr2[$value['status']];
                    if ($value['is_free']){
                        $status = $status."\r\n(免单)";
                    }
                    $order_detail_list = $this->Orders_detail_model->gets('*',array('order_id'=>$value['id']));
                    if ($order_detail_list){
                        foreach ($order_detail_list as $k=>$v){
                            $product_title = $v['product_title'];
                            $buy_price = $v['buy_price'];
                            $product_num = $v['product_num'];

                            $item = array();
                            foreach ($title_arr as $t_key=>$t_value) {
                                //订单号
                                if ($t_key == 'order_num') {
                                    $item[0] = iconv('utf-8', 'gbk', "\t".$value['order_number']);
                                }
                                //姓名
                                else if ($t_key == 'buyer_name') {
                                    $item[1] = iconv('utf-8', 'gbk', "\t".$value['buyer_name']);
                                }
                                //手机
                                else if ($t_key == 'mobile') {
                                    $item[2] = iconv('utf-8', 'gbk', "\t".$value['mobile']);
                                }
                                //订单描述
                                else if ($t_key == 'order_abstract') {
                                    $item[3] = iconv('utf-8', 'gbk', $product_title."\r\n价格：{$buy_price}\r\n编号：{$product_num}");
                                }
                                //收件人市
                                else if ($t_key == 'buyer_city') {
                                    $item[4] = iconv('utf-8', 'gbk', $value['city']);
                                }
                                //收件地址
                                else if ($t_key == 'address') {
                                    $item[5] = iconv('utf-8', 'gbk', $value['address']);
                                }
                                //时间
                                else if ($t_key == 'time') {
                                    $item[6] = iconv('utf-8', 'gbk', "下单：".$add_time."\r\n付款：{$pay_time}");
                                }
                                //状态
                                else if ($t_key == 'status') {
                                    $item[7] = iconv('utf-8', 'gbk', "\t".$status);
                                }
                                //总金额
                                else if ($t_key == 'total') {
                                    $item[8] = iconv('utf-8', 'gbk', $value['total']."\r\n（定金：{$value['deposit']}）");
                                }
                                //人数
                                else if ($t_key == 'join_people') {
                                    $item[9] = iconv('utf-8', 'gbk', "\t".$value['join_people']);
                                }
                                //推荐人
                                else if ($t_key == 'parent_info') {
                                    $item[10] = iconv('utf-8', 'gbk//IGNORE', "\t".$value['parent_info']);
                                }
                                //备注
                                else if ($t_key == 'remark') {
                                    $item[11] = iconv('utf-8', 'gbk', $value['remark']);
                                }
                            }
                            fputcsv($file, $item);
                        }
                    }
                }
                fclose($file);
                //设置header控制下载
                $fileName = 'orders.csv';
                header("Cache-Control:public");
                header("Pragma:public");
                header("Content-Type: application/octet-stream; utf-8");
                header("Accept-Ranges: bytes");
                header( "Content-Disposition: attachment; filename=\"$fileName\"" );
                @readfile($tmp_path);
                @unlink($tmp_path);
                exit;
            } else {
                $data = array(
                    'msg'=>'没有导出数据!',
                    'url'=>'goback'
                );
                $this->session->set_userdata($data);
                redirect('/message/index');
            }
        }

        $data = array(
            'tool' => $this->_tool,
            'status' => $this->_status_arr,
            'prfUrl' => $prfUrl
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("orders/export", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }
}

/* End of file news.php */
/* Location: ./application/admin/controllers/news.php */