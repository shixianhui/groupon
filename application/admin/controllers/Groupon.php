<?php
class Groupon extends CI_Controller {
    private $_title = '营销活动管理';
    private $_tool = '';
    private $_table = '';
    private $_template = '';

    public function __construct() {
        parent::__construct();
        //获取表名
        $this->_table = $this->uri->segment(1);
        //模型名
        $this->_template = $this->uri->segment(1);
        $this->_tool = $this->load->view('element/save_list_tool', array('table'=>$this->_table, 'parent_title'=>'商品活动', 'title'=>'活动'), TRUE);
        //获取表对象
        $this->load->model(ucfirst($this->_table).'_model', 'tableObject', TRUE);
        $this->load->model('System_model', '', TRUE);
        $this->load->model('Group_rule_model', '', TRUE);
        $this->load->model('Groupon_record_model', '', TRUE);
        $this->load->model('Product_model', '', TRUE);
    }

    public function index($clear = 0, $page = 0) {
        clearSession(array('search'));
	    if ($clear) {
	    	$clear = 0;
		    $this->session->unset_userdata(array('search'=>''));
		}
		$uri_2 = $this->uri->segment(2)?'/'.$this->uri->segment(2):'/index';
		$uri_sg = base_url().'admincp.php/'.$this->uri->segment(1).$uri_2."/{$clear}/{$page}";
		$this->session->set_userdata(array("{$this->_template}RefUrl"=>$uri_sg));
    	$strWhere = $this->session->userdata('search')?$this->session->userdata('search'):NULL;

    	//分页
    	$this->config->load('pagination_config', TRUE);
    	$paginationConfig = $this->config->item('pagination_config');
    	$paginationCount = $this->tableObject->rowCount($strWhere);
    	$paginationConfig['base_url'] = base_url()."admincp.php/{$this->_template}/index/{$clear}/";
    	$paginationConfig['total_rows'] = $paginationCount;
    	$paginationConfig['uri_segment'] = 4;
    	$this->pagination->initialize($paginationConfig);
    	$pagination = $this->pagination->create_links();

    	$item_list = $this->tableObject->gets($strWhere, $paginationConfig['per_page'], $page);
    	if ($item_list){
    	    foreach ($item_list as $key=>$value){
                    if ($value['type']){
                    $item_list[$key]['type_format'] = $value['min_number'].'人团';
                }else{
                    $item_list[$key]['type_format'] = '大型团';
                }
            }
        }

        $data = array(
            'tool' =>          $this->_tool,
        	'template'=>       $this->_template,
        	'pagination'=>     $pagination,
        	'paginationCount'=>$paginationCount,
        	'pageCount'=>      ceil($paginationCount/$paginationConfig['per_page']),
            'item_list' =>     $item_list
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_template}/index", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function save($id = NULL) {
        $prfUrl = $this->session->userdata($this->_template.'RefUrl')?$this->session->userdata($this->_template.'RefUrl'):base_url()."admincp.php/{$this->_template}/index/1";
        $productInfo = array();
        $rule_arr = array();
        if ($id) {
            $item_info = $this->tableObject->get('*', array('id' => $id));
            $productInfo = $this->Product_model->get('*',array('product.id' => $item_info['product_id']));
            $rule_arr = $this->Group_rule_model->gets(array('groupon_id' => $id));
        } else {
            $item_info = array();
        }
        if ($_POST) {
            $product_id = $this->input->post('product_id', true);
            $type = $this->input->post('type', true);
            $sale_price = $this->input->post('sale_price', true);
            $deposit = $this->input->post('deposit', true);
            $min_number = $this->input->post('min_number', true);
//            $max_number = $this->input->post('max_number', true);
//            $start_time = strtotime($this->input->post('start_time', true));
            $end_time = strtotime($this->input->post('end_time', true));
            $is_open = $this->input->post('is_open', true);
//            if ($end_time <= $start_time) {
//                printAjaxError('error', "拼团活动结束时间必须大于拼团活动开始时间");
//            }
            if (!$id){
                if ($end_time < time()) {
                    printAjaxError('error', "拼团活动结束时间必须大于当前时间");
                }
            }

            if ($type){
                if (empty($sale_price)){
                    printAjaxError('error', "活动价不能为空!");
                }
                if (empty($min_number)){
                    printAjaxError('error', "人数要求不能为空!");
                }
                if ($min_number > 9 || $min_number < 0){
                    printAjaxError('error', "人数要求为不大于9的正整数!");
                }
            }
            if (!empty($item_info) && $item_info['is_open']){
                if ($type != $item_info['type']){
                    printAjaxError('error', "活动已开启，不能修改拼团类型!");
                }
            }

            $fields = array(
                'product_id' => $product_id,
                'type' => $type,
                'sale_price' => $sale_price,
                'deposit' => $deposit,
                'min_number' => $min_number,
//                'max_number' => $max_number,
//                'start_time' => $start_time,
                'end_time' => $end_time,
                'is_open' => $is_open,
                'add_time' => time(),
                'title' => $this->input->post('title', true),
                'keyword' => $this->input->post('keyword', true),
                'abstract' => $this->input->post('abstract', true),
            );

            $retId = $this->tableObject->save($fields, $id ? array('id' => $id) : $id);

            if ($retId) {
                if ($type == 0){
                    $retId = $id ? $id : $retId;
                    $this->Group_rule_model->delete(array('groupon_id' => $retId));
                    $low_arr = $this->input->post('low', TRUE);
                    $high_arr = $this->input->post('high', TRUE);
                    $money_arr = $this->input->post('money', TRUE);
                    if (empty($low_arr)) {
                        printAjaxError('error', "拼团规则不能为空!");
                    }
                    $low_str = implode(',', $low_arr);
                    $high_str = implode(',', $high_arr);
                    $money_str = implode(',', $money_arr);
                    if (!empty($low_str) && !empty($high_str) && !empty($money_str)) {
                        foreach ($low_arr as $key => $ls) {
                            if (!is_numeric($ls) || !is_numeric($high_arr[$key]) || empty($money_arr[$key])) {
                                printAjaxError('error', "拼团规则有一项为空!");
                            }
                            $fields_data = array(
                                'low' => $ls,
                                'high' => $high_arr[$key],
                                'money' => $money_arr[$key],
                                'groupon_id' => $retId,
                            );
                            $this->Group_rule_model->save($fields_data);
                        }
                    } else {
                        printAjaxError('error', "拼团规则有一项为空!");
                    }
                }

                printAjaxSuccess($prfUrl, "保存成功");
            } else {
                printAjaxError('fail', "保存失败");
            }
        }
        $data = array(
            'tool' => $this->_tool,
            'item_info' => $item_info,
            'productInfo' => $productInfo,
            'rule_arr' => $rule_arr,
        	'prfUrl'=>$prfUrl,
            'id' => $id,
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_template}/save", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function view($id = '') {
    	$prfUrl = $this->session->userdata($this->_template.'RefUrl')?$this->session->userdata($this->_template.'RefUrl'):base_url()."admincp.php/{$this->_template}/index/1";
    	$this->session->set_userdata(array("{$this->_template}RefUrl"=>base_url().'admincp.php/'.uri_string()));
        if ($id) {
            $itemInfo = $this->tableObject->get('*', array('id' => $id));
            $productInfo = $this->Product_model->get(array('product.id' => $itemInfo['product_id']));
            $pintuan_arr = $this->Group_rule_model->gets(array('ptkj_id' => $id));
        }

        $data = array(
            'tool' => $this->_tool,
            'itemInfo' => $itemInfo,
            'productInfo' => $productInfo,
            'pintuan_arr' => $pintuan_arr,
        	'template'=>     $this->_template,
        	'prfUrl'=>$prfUrl,
            'id' => $id,
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_template}/view", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function delete() {
    	if ($_POST) {
    		$ids = $this->input->post('ids', TRUE);

    		if (!$ids) {
    			printAjaxError('fail', '请选择删除项');
    		}
    	    $count = $this->Groupon_record_model->rowCount("groupon_id in ($ids)");
    		if ($count > 0) {
    			printAjaxError('fail', '有相关记录,不可删除！');
    		}
    		if ($this->tableObject->delete('id in (' . $ids . ')')) {
    			//删除拼团规则
    			$this->Group_rule_model->delete("groupon_id in ({$ids})");
    			printAjaxData(array('ids' => explode(',', $ids)));
    		}
    		printAjaxError('fail', '删除失败！');
    	}
    }
}

/* End of file news.php */
/* Location: ./application/admin/controllers/news.php */