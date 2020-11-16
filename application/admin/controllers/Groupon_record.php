<?php

class Groupon_record extends CI_Controller
{
    private $_title = '拼团记录管理';
    private $_tool = '';
    private $_table = '';
    private $_char = array("零","一","二","三","四","五","六","七","八","九");
    private $_status_arr = array('拼团中','<font color="#066601">拼团成功</font>','<font color="#a0a0a0">拼团失败</font>');
    private $_status_arr2 = array('拼团中','拼团成功','拼团失败');

    public function __construct()
    {
        parent::__construct();
        //获取表名
        $this->_table = $this->uri->segment(1);
        //获取表对象
        $this->load->model(ucfirst($this->_table) . '_model', 'tableObject', TRUE);
        $this->load->model('Area_model', '', TRUE);
        $this->load->model('User_model', '', TRUE);
        $this->load->model('User_address_model', '', TRUE);
        $this->load->model('Product_model', '', TRUE);
        $this->load->model('Groups_model', '', TRUE);
        $this->load->model('Groupon_rule_model', '', TRUE);
        $this->load->model('Product_model', '', TRUE);
        $this->load->model('System_model', '', TRUE);
        $this->load->model('Orders_model', '', TRUE);

        $this->load->helper('url');
        $this->load->library('pagination');
        $this->load->library('session');
    }

    public function index($clear = 0, $page = 0)
    {
        checkPermission("{$this->_table}_index");
        if ($clear) {
            $clear = 0;
            $this->session->unset_userdata('search');
        }
        $uri_2 = $this->uri->segment(2) ? '/' . $this->uri->segment(2) : '/index';
        $uri_sg = base_url() . 'admincp.php/' . $this->uri->segment(1) . $uri_2 . "/{$clear}/{$page}";
        $this->session->set_userdata(array("{$this->_table}RefUrl" => $uri_sg));

        $condition = "{$this->_table}.id > 0 and {$this->_table}.is_bond = 1";
        $strWhere = $this->session->userdata('search') ? $this->session->userdata('search') : $condition;
        if ($_POST) {
            $strWhere = $condition;
            $trade_no = trim($this->input->post('trade_no', TRUE));
            $groupon_id = trim($this->input->post('groupon_id', TRUE));
            $mobile = trim($this->input->post('mobile', TRUE));
            $is_refund = trim($this->input->post('is_refund', TRUE));
            $is_success = trim($this->input->post('is_success', TRUE));
            $startTime = $this->input->post('inputdate_start', TRUE);
            $endTime = $this->input->post('inputdate_end', TRUE);

            if (!empty($trade_no)) {
                $strWhere .= " and {$this->_table}.trade_no regexp '{$trade_no}' ";
            }
            if (!empty($groupon_id)) {
                $strWhere .= " and {$this->_table}.groupon_id = '{$groupon_id}' ";
            }
            if (!empty($mobile)) {
                $strWhere .= " and {$this->_table}.address_id in (select id from user_address where mobile regexp '{$mobile}')";
            }
            if ($is_refund != '') {
                $strWhere .= " and {$this->_table}.is_refund = '{$is_refund}' ";
            }
            $time = time();
            if ($is_success === '0'){
                $strWhere .= " and ((groupon.type = 0 and groupon.end_time > {$time}) or (groupon.type = 1 and groupon.end_time > {$time} and groupon_record.groups_id in (select id from groups where is_success = 0))) ";
            }elseif ($is_success == 1){
                $strWhere .= " and ((groupon.type = 0 and groupon.end_time < {$time}) or (groupon.type = 1 and groupon_record.groups_id in (select id from groups where is_success = 1))) ";
            }elseif ($is_success == 2){
                $strWhere .= " and groupon.type = 1 and groupon.end_time < {$time} and groupon_record.groups_id in (select id from groups where is_success = 0) ";
            }
            if (!empty($startTime) && !empty($endTime)) {
                $strWhere .= " and {$this->_table}.add_time > " . strtotime($startTime . ' 00:00:00') . " and {$this->_table}.add_time < " . strtotime($endTime . ' 23:59:59') . " ";
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
        foreach ($item_list as $key => $value) {
            $order_count = $this->Orders_model->rowCount(array('user_id'=>$value['user_id'],'record_id'=>$value['id'],'is_free'=>1));
            $item_list[$key]['is_free'] = $order_count ? 1 : 0;
            if ($value['type']){
                $user_record_list = $this->tableObject->gets(array('groupon_record.groups_id' => $value['groups_id'],'groupon_record.is_bond'=>1));
                $surplus_people = $value['min_number'] - count($user_record_list);
                $item_list[$key]['surplus_people'] = $surplus_people;
                $item_list[$key]['name_format'] = $this->_char[$value['min_number']].'人团';
                $group_info = $this->Groups_model->get('*',array('id'=>$value['groups_id']));
                //拼团状态
                if(empty($group_info['is_success']) && $value['end_time'] < time()){
                    $group_info['is_success'] = 2;
                }
                $item_list[$key]['is_success'] = $group_info['is_success'];
            }else{
                //拼团状态
                if($value['end_time'] > time()){
                    $is_success = 0;
                }else{
                    $is_success = 1;
                }
                $item_list[$key]['is_success'] = $is_success;
            }
            $product_info = $this->Product_model->get('*', array('id' => $value['product_id']));
            //当前价格
            $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $value['groupon_id']));
            $cur_price = $product_info['sell_price'];
            if ($value['type']){
                $cur_price = $value['sale_price'];
            }else{
                foreach ($groupon_rule as $ls) {
                    if ($value['join_people'] >= $ls['low'] && $value['join_people'] <= $ls['high']) {
                        $cur_price = number_format($ls['money'], 2,'.','');
                    }
                }
            }
            $product_info['cur_price'] = $cur_price;
            $item_list[$key]['product_info'] = $product_info;

            $user_address = $this->User_address_model->get('*', array('id' => $value['address_id']));
            $item_list[$key]['user_address'] = $user_address;

            //推荐人
            $user_info = $this->User_model->getInfo('parent_id',array('id'=>$value['user_id']));
            $parent_info = '无';
            if ($user_info && $user_info['parent_id']){
                $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                $parent_info = $parent_user_info ? $parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']' : '无';
            }
            $item_list[$key]['parent_info'] = $parent_info;
        }

        $data = array(
            'tool' => $this->_tool,
            'item_list' => $item_list,
            'pagination' => $pagination,
            'paginationCount' => $paginationCount,
            'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
            'table' => $this->_table,
            'status_arr' => $this->_status_arr,
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("{$this->_table}/index", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }


    public function change_refund(){
        checkPermissionAjax("{$this->_table}_edit");
        if ($_POST){
            $record_id = $this->input->post('record_id');
            $record_info = $this->tableObject->get(array('groupon_record.id'=>$record_id));
            if (!$record_info){
                printAjaxError('fail','参数异常');
            }
            if ($this->tableObject->save(array('is_refund'=>1),array('id'=>$record_id))){
                printAjaxSuccess('success','修改成功');
            }else{
                printAjaxSuccess('success','修改失败');
            }
        }
    }

    public function export() {
        $prfUrl = $this->session->userdata("{$this->_table}RefUrl") ? $this->session->userdata("{$this->_table}RefUrl") : base_url() . "admincp.php/{$this->_table}/index/";

        $title_arr = array(
            'trade_no'=>'微信支付订单号',
            'buyer_name'=>'收货人信息',
            'record_abstract'=>'订单描述',
            'groupon'=>'活动描述',
            'deposit'=>'定金',
            'parent_info'=>'推荐人',
            'time'=>'下单时间',
            'status'=>'拼团状态'
        );
        if($_POST) {
            ignore_user_abort(true); // run script in background
            set_time_limit(0); // run script forever,运行时间为无限后结束

            $is_success = trim($this->input->post('status', TRUE));
            $inputdate_start = trim($this->input->post('inputdate_start', TRUE));
            $inputdate_end =  trim($this->input->post('inputdate_end', TRUE));

            $strWhere = "{$this->_table}.id > 0 and {$this->_table}.is_bond = 1";
            $time = time();
            if ($is_success === '0'){
                $strWhere .= " and ((groupon.type = 0 and groupon.end_time > {$time}) or (groupon.type = 1 and groupon.end_time > {$time} and groupon_record.groups_id in (select id from groups where is_success = 0))) ";
            }elseif ($is_success == 1){
                $strWhere .= " and ((groupon.type = 0 and groupon.end_time < {$time}) or (groupon.type = 1 and groupon_record.groups_id in (select id from groups where is_success = 1))) ";
            }elseif ($is_success == 2){
                $strWhere .= " and groupon.type = 1 and groupon.end_time < {$time} and groupon_record.groups_id in (select id from groups where is_success = 0) ";
            }
            if ($inputdate_start && $inputdate_end) {
                $strWhere .= " and ({$this->_table}.add_time > ".strtotime($inputdate_start.' 00:00:00')." and {$this->_table}.add_time < ".strtotime($inputdate_end.' 23:59:59').") ";
            }
            $item_list = $this->tableObject->gets($strWhere);
            foreach ($item_list as $key => $value) {
                $order_count = $this->Orders_model->rowCount(array('user_id'=>$value['user_id'],'record_id'=>$value['id'],'is_free'=>1));
                $item_list[$key]['is_free'] = $order_count ? 1 : 0;
                if ($value['type']){
                    $user_record_list = $this->tableObject->gets(array('groupon_record.groups_id' => $value['groups_id'],'groupon_record.is_bond'=>1));
                    $surplus_people = $value['min_number'] - count($user_record_list);
                    $item_list[$key]['surplus_people'] = $surplus_people;
                    $item_list[$key]['name_format'] = $this->_char[$value['min_number']].'人团';
                    $group_info = $this->Groups_model->get('*',array('id'=>$value['groups_id']));
                    //拼团状态
                    if(!$group_info['is_success'] && $value['end_time'] < time()){
                        $group_info['is_success'] = 2;
                    }
                    $item_list[$key]['is_success'] = $group_info['is_success'];
                }else{
                    //拼团状态
                    if($value['end_time'] > time()){
                        $is_success = 0;
                    }else{
                        $is_success = 1;
                    }
                    $item_list[$key]['is_success'] = $is_success;
                }
                $product_info = $this->Product_model->get('*', array('id' => $value['product_id']));
                //当前价格
                $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $value['groupon_id']));
                $cur_price = $product_info['sell_price'];
                if ($value['type']){
                    $cur_price = $value['sale_price'];
                }else{
                    foreach ($groupon_rule as $ls) {
                        if ($value['join_people'] >= $ls['low'] && $value['join_people'] <= $ls['high']) {
                            $cur_price = number_format($ls['money'], 2,'.','');
                        }
                    }
                }
                $product_info['cur_price'] = $cur_price;
                $item_list[$key]['product_info'] = $product_info;

                $user_address = $this->User_address_model->get('*', array('id' => $value['address_id']));
                $item_list[$key]['user_address'] = $user_address;

                //推荐人
                $user_info = $this->User_model->getInfo('parent_id',array('id'=>$value['user_id']));
                $parent_info = '无';
                if ($user_info && $user_info['parent_id']){
                    $parent_user_info = $this->User_model->getInfo('id,nickname',array('id'=>$user_info['parent_id']));
                    $parent_info = $parent_user_info ? $parent_user_info['nickname'].'[ID:'.$parent_user_info['id'].']' : '无';
                }
                $item_list[$key]['parent_info'] = $parent_info;
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
                    $status = $this->_status_arr2[$value['is_success']];
                    if ($value['is_free']){
                        $status = $status."\r\n(免单)";
                    }

                    $item = array();
                    foreach ($title_arr as $t_key=>$t_value) {
                        //订单号
                        if ($t_key == 'trade_no') {
                            $item[0] = iconv('utf-8', 'gbk', "\t" . $value['trade_no']);
                        } //姓名
                        else if ($t_key == 'buyer_name') {
                            $item[1] = iconv('utf-8', 'gbk',  $value['user_address']['txt_address'].$value['user_address']['address']."\r\n{$value['user_address']['buyer_name']}\r\n{$value['user_address']['mobile']}");
                        } //订单描述
                        else if ($t_key == 'record_abstract') {
                            $item[2] = iconv('utf-8', 'gbk', $value['product_info']['title'] . "\r\n价格：{$value['product_info']['cur_price']}\r\n编号：{$value['product_info']['product_num']}");
                        } //活动描述
                        else if ($t_key == 'groupon') {
                            if ($value['type']){
                                $pur = $value['surplus_people'] ? "还差{$value['surplus_people']}人成团!" : "团满了!";
                                $item[3] = iconv('utf-8', 'gbk', $value['name_format']."[活动ID:".$value['groupon_id']."]\r\n{$pur}");
                            }else{
                                $item[3] = iconv('utf-8', 'gbk', '大型团[活动ID:'.$value['groupon_id'].']');
                            }
                        } //收件地址
                        else if ($t_key == 'deposit') {
                            $item[4] = iconv('utf-8', 'gbk', $value['deposit']);
                        }
                        else if ($t_key == 'parent_info') {
                            $item[5] = iconv('utf-8', 'gbk//IGNORE', $value['parent_info']);
                        } //时间
                        else if ($t_key == 'time') {
                            $item[6] = iconv('utf-8', 'gbk', "\t" .$add_time );
                        } //状态
                        else if ($t_key == 'status') {
                            $item[7] = iconv('utf-8', 'gbk', "\t" . $status);
                        }
                    }
                    fputcsv($file, $item);

                }
                fclose($file);
                //设置header控制下载
                $fileName = 'records.csv';
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
            'table' => $this->_table,
            'status' => $this->_status_arr,
            'prfUrl' => $prfUrl
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view("groupon_record/export", $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

}


/* End of file news.php */
/* Location: ./application/admin/controllers/news.php */