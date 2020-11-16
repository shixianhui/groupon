<?php

class Napi extends CI_Controller {

    private $_status = array(
        '0' => '<font color="#ff4200">未付款</font>',
        '1' => '<font color="#cc3333">已付款</font>',
        '2' => '<font color="#ff811f">待收货</font>',
        '3' => '<font color="#066601">交易成功</font>',
        '4' => '<font color="#a0a0a0">交易关闭</font>',
    );

    private $_hideValue = array(
        'a' => 0,
        'b' => 1,
        'c' => 2,
        'd' => 3,
        'e' => 4,
    );
    private $_status_arr = array(
        '0'=>'待审核',
        '1'=>'审核未通过',
        '2'=>'正在退款',
        '3'=>'退款成功'
    );

    private $_char = array("零","一","二","三","四","五","六","七","八","九");

    private $_img_arr = array(
        array('id'=>'1','name'=>'主卧','img_path'=>'images/default/zhuwo2.png','light_img_path'=>'images/default/zhuwo1.png','rate'=>10),
        array('id'=>'2','name'=>'次卧','img_path'=>'images/default/ciwo2.png','light_img_path'=>'images/default/ciwo1.png','rate'=>10),
        array('id'=>'3','name'=>'书房','img_path'=>'images/default/shufang2.png','light_img_path'=>'images/default/shufang1.png','rate'=>20),
        array('id'=>'4','name'=>'卫生间','img_path'=>'images/default/weishengjian2.png','light_img_path'=>'images/default/weishengjian1.png','rate'=>30),
        array('id'=>'5','name'=>'餐厅','img_path'=>'images/default/canting2.png','light_img_path'=>'images/default/canting1.png','rate'=>10),
        array('id'=>'6','name'=>'客厅','img_path'=>'images/default/keting2.png','light_img_path'=>'images/default/keting1.png','rate'=>20),
    );


    private $_quote_price_arr = array(
        '0'=>array('name'=>'两室一厅一卫','price'=>'26800元'),
        '1'=>array('name'=>'两室两厅一卫','price'=>'30500元'),
        '2'=>array('name'=>'三室两厅两卫','price'=>'31800元'),
        '3'=>array('name'=>'四室两厅两卫','price'=>'34800元')
    );

    public function __construct() {
        parent::__construct();
        $this->load->model('Menu_model', '', TRUE);
        $this->load->model('System_model', '', TRUE);
        $this->load->model('User_model', '', TRUE);
        $this->load->model('User_address_model', '', TRUE);
        $this->load->model('Area_model', '', TRUE);
        $this->load->model('Attachment_model', '', TRUE);
        $this->load->model('Orders_model', '', TRUE);
        $this->load->model('Orders_detail_model', '', TRUE);
        $this->load->model('Orders_process_model', '', TRUE);
//        $this->load->model('Sms_model', '', TRUE);

        $this->load->model('Exchange_model', '', TRUE);
        $this->load->model('Pay_log_model', '', TRUE);
        $this->load->model('Financial_model', '', TRUE);
        $this->load->model('Product_model', '', TRUE);
        $this->load->model('Product_category_model', '', TRUE);
        $this->load->model('Product_category_ids_model', '', TRUE);
        $this->load->model('Product_size_color_model', '', TRUE);
        $this->load->model('Groupon_model', '', TRUE);
        $this->load->model('Groupon_rule_model', '', TRUE);
        $this->load->model('Groupon_record_model', '', TRUE);
        $this->load->model('Groups_model', '', TRUE);
//        $this->load->model('Message_model', '', TRUE);
        $this->load->model('Page_model', '', TRUE);
        $this->load->model('News_model', '', TRUE);
        $this->load->model('Enroll_model', '', TRUE);
        $this->load->model('Enroll_progress_model', '', TRUE);
        $this->load->model('Light_img_model', '', TRUE);
        $this->load->model('Quote_price_record_model', '', TRUE);
        $this->load->helper('my_ajaxerror');
        $this->load->helper('my_fileoperate');
        $this->load->library('Form_validation');
        $this->_beforeFilter();
    }

    /**
     * 登录接口
     * @param username 用户名
     * @param password 密码
     *
     * @return json
     */
    public function login() {
        if ($_POST) {
            $username = trim($this->input->post('username', TRUE));
            $password = $this->input->post('password', TRUE);
            if (!$username) {
                printAjaxError('username', '用户名不能为空');
            }
            if (!$password) {
                printAjaxError('password', '登录密码不能为空');
            }
            $count = $this->User_model->rowCount(array('lower(username)' => strtolower($username)));
            if (!$count) {
                printAjaxError('username', '用户名不存在，登录失败');
            }
            $user_info = $this->User_model->login($username, $password);
            if (!$user_info) {
                printAjaxError('fail', '用户名或密码错误，登录失败');
            }
            if ($user_info['display'] == 0) {
                printAjaxError('fail', '你的账户还未激活，请先激活账户或联系管理员激活');
            } else if ($user_info['display'] == 2) {
                printAjaxError('fail', '你的账户被冻结，请联系管理员或者网站客服');
            }
            $session_id = session_id();
//            session_write_close();
            $this->_set_session($user_info['id']);
            printAjaxData($this->_tmp_user_info($user_info['id'], $session_id));
        }
    }

//    public function get_wx_openid(){
//        $code = $this->input->get("code", true);
//        if (!$code) {
//            printAjaxError('fail', 'DO NOT ACCESS!');
//        }
//        $appid = 'wx9d98913319351fbc';
//        $appSecret = '8ff0e8077cbd788807d2e11e8ba37919';
//        $json = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appSecret}&js_code={$code}&grant_type=authorization_code");
//        $obj = json_decode($json);
//        if (isset($obj->errmsg)) {
//            printAjaxError('fail', 'invalid code!');
//        }
//        $session_key = $obj->session_key;
//        $openid = $obj->openid;
//
//        printAjaxData(array('openid'=>$openid,'session_key'=>$session_key));
//    }

//微信-QQ登录-注册新用户
    public function third_login_to_user() {
        if ($_POST) {
            $sex = $this->input->post('sex', TRUE);
            $unionid = $this->input->post('unionid', TRUE);
            $path_url = $this->input->post('path_url', TRUE);
            $type = $this->input->post('type', TRUE);
            $push_cid = $this->input->post('push_cid', TRUE);
            $nickname = $this->input->post('nickname', TRUE);

            if (!$unionid || !$type) {
                printAjaxError('fail', '操作异常');
            }
            $user_info = NULL;
            if ($type == 'weixin') {
                $user_info = $this->User_model->get('*', array('wx_unionid'=>$unionid));
            } else if ($type == 'qq') {
                $user_info = $this->User_model->get('*', array('qq_unionid'=>$unionid));
            } else {
                printAjaxError('fail', '无效的登录认证通道');
            }
            //已绑定用户直接登录
            if ($user_info) {
                if ($user_info['display'] == 0) {
                    printAjaxError('fail', '你的账户还未激活，请先激活账户或联系管理员激活');
                } else if ($user_info['display'] == 2) {
                    printAjaxError('fail', '你的账户被冻结，请联系管理员或者网站客服');
                }
                //登录成功
                if ($push_cid) {
                    if ($user_info['push_cid'] != $push_cid) {
                        $this->User_model->save(array('push_cid'=>$push_cid), array('id'=>$user_info['id']));
                    }
                }
                $session_id = $this->session->userdata['session_id'];
                $this->_set_session($user_info['id']);
                printAjaxData($this->_tmp_user_info($user_info['id'], $session_id));
            } else {
                $addTime = time();
                $fields = array(
                    'user_group_id' => 1,
                    'username' => '',
                    'password' => '',
                    'mobile' => '',
                    'login_time' => $addTime,
                    'add_time' => $addTime,
                    'nickname' => $nickname,
                    'path' => $path_url,
                    'sex' => $sex,
                    'push_cid'=>$push_cid
                );
                if ($type == 'weixin') {
                    $fields['wx_unionid'] = $unionid;
                } else if ($type == 'qq') {
                    $fields['qq_unionid'] = $unionid;
                }
                $ret_id = $this->User_model->save($fields);
                if (!$ret_id) {
                    printAjaxError('fail', '登录失败');
                }
                $session_id = $this->session->userdata['session_id'];
                $this->_set_session($ret_id);
                printAjaxData($this->_tmp_user_info($ret_id, $session_id));
            }
        }
    }


//小程序登录
    public function wx_login(){
        if ($_POST){
            $code = $this->input->post("code", true);
            $iv = $this->input->post('iv', TRUE);
            $encryptedData = $this->input->post('encryptedData', TRUE);
            $parent_id = $this->input->post('parent_id', TRUE);

            if (!$code) {
                printAjaxError('fail', 'DO NOT ACCESS!');
            }
            $appid = 'wx5b6972e9b65eefc0';
            $appSecret = 'c7ef31e34df248d7403bfa3186051396';
            $json = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appSecret}&js_code={$code}&grant_type=authorization_code");
            $obj = json_decode($json);
            if (isset($obj->errmsg)) {
                printAjaxError('fail', 'invalid code!');
            }
            $session_key = $obj->session_key;
            $openid = $obj->openid;

            $param = array('appid'=>$appid,'sessionKey'=>$session_key);
            $this->load->library('WXBizDataCrypt/WXBizDataCrypt',$param);
            $pc = new WXBizDataCrypt($param);
            $errCode = $pc->decryptData($encryptedData, $iv, $data );

            if ($errCode != 0) {
                printAjaxError('fail',$errCode);
            }

            $get_user_info = json_decode($data);

            if (empty($get_user_info->unionId)){
                printAjaxError('fail','小程序登录异常！');
            }

        $user_info = $this->User_model->get('*', array('wx_unionid'=>$get_user_info->unionId));

        //已绑定用户直接登录
        if ($user_info) {
            if ($user_info['display'] == 0) {
                printAjaxError('fail', '你的账户还未激活，请先激活账户或联系管理员激活');
            } else if ($user_info['display'] == 2) {
                printAjaxError('fail', '你的账户被冻结，请联系管理员或者网站客服');
            }

            $fields = array(
                'login_time' => time(),
            );
            $this->User_model->save($fields, array('id' => $user_info['id']));
            $session_id = session_id();
            $this->_set_session($user_info['id']);
            printAjaxData($this->_tmp_user_info($user_info['id'], $session_id));
        } else {
            $addTime = time();
            $fields = array(
//                'user_group_id' => 1,
                'username' => '',
                'password' => '',
                'mobile' => '',
                'login_time' => $addTime,
                'add_time' => $addTime,
                'nickname' => $get_user_info->nickName,
                'path' => $get_user_info->avatarUrl,
                'sex' => $get_user_info->gender,
                'wx_unionid' => $get_user_info->unionId,
            );
            if ($parent_id){
                $fields['parent_id'] = $parent_id;
            }

            $ret_id = $this->User_model->save($fields);
            if (!$ret_id) {
                printAjaxError('fail', '登录失败');
            }

            $session_id = session_id();
            $this->_set_session($ret_id);
            printAjaxData($this->_tmp_user_info($ret_id, $session_id));
        }

        }

    }

    //活动列表
    public function get_groupon_list(){
        $time = time();
        $item_list_0 = $this->Groupon_model->gets("groupon.type = 0 and groupon.is_open=1 and groupon.end_time > {$time}");
        if($item_list_0){
            foreach ($item_list_0 as $key=>$value){
                if ($value['path']){
                    $tmp_image_arr = $this->_fliter_image_path($value['path']);
                    $item_list_0[$key]['path'] = $tmp_image_arr['path'];
                    $item_list_0[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
                }
                //当前价格
                $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $value['id']));
                $cur_price = $value['sell_price'];
                $max = 0;
                foreach ($groupon_rule as $ls) {
                    if ($value['join_people'] >= $ls['low'] && $value['join_people'] <= $ls['high']) {
                        $cur_price = number_format($ls['money'], 2,'.','');
                    }
                    $max = max($max,$ls['high']);
                }
                $item_list_0[$key]['min_number'] = $max;
                $item_list_0[$key]['cur_price'] = $cur_price;
                $item_list_0[$key]['end_time_format'] = date('Y-m-d H:i:s',$value['end_time']);
            }
        }
        $item_list_1 = $this->Groupon_model->gets("groupon.type = 1 and groupon.is_open=1 and groupon.end_time > {$time}");
        if($item_list_1){
            foreach ($item_list_1 as $key=>$value){
                if ($value['path']){
                    $tmp_image_arr = $this->_fliter_image_path($value['path']);
                    $item_list_1[$key]['path'] = $tmp_image_arr['path'];
                    $item_list_1[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
                }
                $item_list_1[$key]['cur_price'] = $value['sale_price'] ? $value['sale_price'] : $value['sell_price'];
                $item_list_1[$key]['end_time_format'] = date('Y-m-d H:i:s',$value['end_time']);
            }
        }
        $item_list_2 = $this->Groupon_model->gets("groupon.is_open=1 and groupon.end_time < {$time}");
        if($item_list_2){
            foreach ($item_list_2 as $key=>$value){
                if ($value['path']){
                    $tmp_image_arr = $this->_fliter_image_path($value['path']);
                    $item_list_2[$key]['path'] = $tmp_image_arr['path'];
                    $item_list_2[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
                }
                //当前价格
                $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $value['id']));
                $cur_price = $value['sell_price'];
                if ($value['type']){
                    $cur_price = $value['sale_price'];
                }else{
                    $max = 0;
                    foreach ($groupon_rule as $ls) {
                        if ($value['join_people'] >= $ls['low'] && $value['join_people'] <= $ls['high']) {
                            $cur_price = number_format($ls['money'], 2,'.','');
                        }
                        $max = max($max,$ls['high']);
                    }
                    $item_list_2[$key]['min_number'] = $max;
                }
                $item_list_2[$key]['cur_price'] = $cur_price;
            }
        }
        printAjaxData(array('item_list_0' => $item_list_0,'item_list_1' => $item_list_1,'item_list_2' => $item_list_2));
    }

    /**
     * 产品详细
     * @return json
     */
    public function get_groupon_detail($id = 0){
        $groupon_info = $this->Groupon_model->get('*',array('id' => $id, 'is_open'=>1));
        if (!$groupon_info) {
            printAjaxError('fail', '参数异常');
        }
        $item_info = $this->Product_model->get('*', array('id' => $groupon_info['product_id']));
        if (!$item_info) {
            printAjaxError('fail', '此产品不存在');
        }
        //轮播图
        $attachment_list = array();
        if ($item_info && $item_info['batch_path_ids']) {
            $tmp_atm_ids = preg_replace(array('/^_/', '/_$/', '/_/'), array('', '', ','), $item_info['batch_path_ids']);
            $attachment_list = $this->Attachment_model->gets3($tmp_atm_ids);
            foreach ($attachment_list as $key => $ls) {
                $tmp_image_arr = $this->_fliter_image_path($ls['path']);
                $attachment_list[$key]['path'] = $tmp_image_arr['path'];
                $attachment_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
            }
        }
        $item_info['attachment_list'] = $attachment_list;

        $item_info['app_content'] = filter_content(html($item_info['content']), base_url());
        preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $item_info['content'], $arr);
        if (isset($arr[2]) && $arr[2]) {
            foreach ($arr[2] as $value) {
                if (!preg_match("/^http/", $value)) {
                    $item_info['content'] = str_replace($value, base_url() . $value, $item_info['content']);
                }
            }
        }

        //当前价格
        $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $groupon_info['id']));
        $cur_price = $item_info['sell_price'];
        $min = $cur_price;
        $max_low = 0;
        if ($groupon_info['type']){
            $cur_price = $groupon_info['sale_price'];
        }else{
            foreach ($groupon_rule as $ls) {
                if ($groupon_info['join_people'] >= $ls['low'] && $groupon_info['join_people'] <= $ls['high']) {
                    $cur_price = number_format($ls['money'], 2,'.','');
                }
                $min = min($min,$ls['money']);
                $max_low = max($max_low,$ls['low']);
            }
        }
        $item_info['cur_price'] = $cur_price;
        $item_info['min'] = $min;
        $item_info['max_low'] = $max_low;

        $user_path_list = array();
        if (!$groupon_info['type']){
            $user_record_list = $this->Groupon_record_model->gets2(array('groupon_record.groupon_id' => $groupon_info['id'],'groupon_record.is_bond'=>1));
            if ($user_record_list) {
                foreach ($user_record_list as $key => $value) {
                    $user_info = $this->User_model->get('path,nickname', array('id' => $value['user_id']));
                    if ($user_info) {
                        $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
                        $user_info['path'] = $tmp_image_arr['path'];
                        $user_info['path_thumb'] = $tmp_image_arr['path_thumb'];
                        $order_count = $this->Orders_model->rowCount(array('user_id' => $value['user_id'], 'record_id' => $value['id'], 'is_free' => 1));
                        $user_path_list[$key]['path'] = $user_info['path_thumb'];
                        $user_path_list[$key]['nickname'] = $user_info['nickname'];
                        $user_path_list[$key]['is_free'] = $order_count ? 1 : 0;
                    }
                }
            }

        }

        $groupon_info['is_end'] = 0;
        if ($groupon_info['end_time'] < time()){
            $groupon_info['is_end'] = 1;
        }

        $groups_list = array();
        if(!$groupon_info['is_end']){
            //拼单列表
            if ($groupon_info['type']){
                $groups_list = $this->Groups_model->gets("groupon_id = {$groupon_info['id']} and is_success = 0");
                if ($groups_list){
                    foreach ($groups_list as $key=>$value){
                        $user_record_list = $this->Groupon_record_model->gets(array('groupon_record.groups_id' => $value['id'],'groupon_record.is_bond'=>1));
                        $user_info = $this->User_model->get('path,nickname', array('id' => $value['head_user_id']));
                        if ($user_info) {
                            $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
                            $user_info['path'] = $tmp_image_arr['path'];
                            $user_info['path_thumb'] = $tmp_image_arr['path_thumb'];
                        }
                        $groups_list[$key]['path_thumb'] = !empty($user_info['path_thumb']) ? $user_info['path_thumb'] : '';
                        $groups_list[$key]['nickname'] = !empty($user_info['nickname']) ? $user_info['nickname'] : '';
                        $surplus_people = $groupon_info['min_number'] - count($user_record_list);
                        $groups_list[$key]['surplus_people'] = $surplus_people;
                    }
                }
            }
        }




        printAjaxData(array('item_info'=>$item_info,'groupon_info'=>$groupon_info,'groups_list'=>$groups_list,'user_path_list'=>$user_path_list));
    }

    //定金发起拼团
     public function add_group(){
        $user_id = $this->_check_login();
        if ($_POST){
            $address_id = $this->input->post('address_id', TRUE);
            if(!$address_id){
                printAjaxError('fail', '请选择收货地址');
            }
            $groupon_id = $this->input->post('groupon_id', TRUE);
            $timestamp = time();
            $groupon_info = $this->Groupon_model->get('*',"id = {$groupon_id} and is_open = 1 and end_time > {$timestamp}");
            if (!$groupon_info) {
                printAjaxError('fail', '参数异常');
            }
            $product_info = $this->Product_model->get('*', array('id' => $groupon_info['product_id']));
            if (!$product_info) {
                printAjaxError('fail', '此产品不存在');
            }

            //当前价格
//            $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $groupon_info['id']));
//            $cur_price = $product_info['sell_price'];
//            if ($groupon_info['type']){
//                $cur_price = $groupon_info['sale_price'];
//            }else{
//                foreach ($groupon_rule as $ls) {
//                    if ($groupon_info['join_people'] >= $ls['low'] && $groupon_info['join_people'] <= $ls['high']) {
//                        $cur_price = number_format($ls['money'], 2,'.','');
//                    }
//                }
//            }


            if ($groupon_info['type']){
                $record_info = $this->Groupon_record_model->get(array('groupon_record.groupon_id' => $groupon_info['id'], 'groupon_record.user_id' => $user_id, 'groupon_record.groups_id'=>0,'groupon_record.is_bond'=>0));
                if ($record_info){
//                    $this->Groupon_record_model->save(array('add_time' => $timestamp,'address_id'=>$address_id), array('id' => $record_info['id']));
                    $this->Groupon_record_model->save(array('address_id'=>$address_id), array('id' => $record_info['id']));
                    $record_id = $record_info['id'];
                }else{
                    $datas = array(
                        'user_id' => $user_id,
                        'groupon_id' => $groupon_id,
                        'product_id' => $product_info['id'],
                        'buy_number' => 1,
                        'add_time' => $timestamp,
                        'is_deposit' => 1,
                        'address_id' => $address_id
                    );
                    $bond_number = $this->_getUniqueOrderNumber();
                    $datas['bond_number'] = $bond_number;
                    $record_id = $this->Groupon_record_model->save($datas);
                }
            }else{
                $record_info = $this->Groupon_record_model->get(array('groupon_record.groupon_id' => $groupon_info['id'], 'groupon_record.user_id' => $user_id, 'groupon_record.groups_id'=>0));
                if ($record_info){
                    if ($record_info['is_bond']){
                        printAjaxError('fail','已参加过此拼团！');
                    }else{
                        $this->Groupon_record_model->save(array('add_time'=>$timestamp,'address_id'=>$address_id),array('id'=>$record_info['id']));
                    }
                    $record_id = $record_info['id'];

                }else{
                    $datas = array(
                        'user_id' => $user_id,
                        'groupon_id' => $groupon_id,
                        'product_id' => $product_info['id'],
                        'buy_number' => 1,
                        'add_time' => $timestamp,
                        'is_deposit' => 1,
                        'address_id' => $address_id
                    );
                    $bond_number = $this->_getUniqueOrderNumber();
                    $datas['bond_number'] = $bond_number;
                    $record_id = $this->Groupon_record_model->save($datas);
                }

            }

            
            printAjaxData($record_id);

        }
    }

    //拼团详情
    public function group_detail($id = null){
        $user_id = $this->_check_login();
        $group_info = $this->Groups_model->get('*',array('id'=>$id));
        if (!$group_info) {
            printAjaxError('fail', '参数异常');
        }
        $time = time();
        $groupon_info = $this->Groupon_model->get('*',"id = {$group_info['groupon_id']} and is_open = 1");

        $product_info = $this->Product_model->get('*', array('id' => $groupon_info['product_id']));
        if (!$product_info) {
            printAjaxError('fail', '此产品不存在');
        }
        $record_list = $this->Groupon_record_model->gets(array('groupon_record.groups_id'=>$id,'groupon_record.is_bond'=>1));
        if (!$record_list){
            printAjaxError('fail', '拼团详情不存在');
        }

        if ($groupon_info['type']){
            $group_info['surplus_people'] = $groupon_info['min_number'] - count($record_list);
            $group_info['name_format'] = $this->_char[$groupon_info['min_number']].'人团';
        }

        //拼团状态
        if(!$group_info['is_success'] && $groupon_info['end_time'] < time()){
            $group_info['is_success'] = 2;
        }

        $group_info['deposit'] = $groupon_info['deposit'];
        $group_info['is_me'] = 0;
        $user_path_list = array();
        foreach ($record_list as $key=>$value){
            $user_info = $this->User_model->get('path',array('id'=>$value['user_id']));
            if ($user_info){
                $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
                $user_info['path'] = $tmp_image_arr['path'];
                $user_info['path_thumb'] = $tmp_image_arr['path_thumb'];
                $order_count = $this->Orders_model->rowCount(array('user_id'=>$value['user_id'],'record_id'=>$value['id'],'is_free'=>1));
                $user_path_list[$key]['path'] = $user_info['path_thumb'];
                $user_path_list[$key]['is_free'] = $order_count ? 1 : 0;
                if ($value['user_id'] == $user_id){
                    $group_info['is_me'] = 1;
                }
                $user_path_list[$key]['is_header'] = $value['user_id'] == $group_info['head_user_id'] ? 1 : 0;
            }
        }
        $user_path_list = array_merge($user_path_list,array());

        //当前价格
        $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $groupon_info['id']));
        $cur_price = $product_info['sell_price'];
        if ($groupon_info['type']){
            $cur_price = $groupon_info['sale_price'];
        }else{
            foreach ($groupon_rule as $ls) {
                if ($groupon_info['join_people'] >= $ls['low'] && $groupon_info['join_people'] <= $ls['high']) {
                    $cur_price = number_format($ls['money'], 2,'.','');
                }
            }
        }
        $product_info['cur_price'] = $cur_price;
        $tmp_image_arr = $this->_fliter_image_path($product_info['path']);
        $product_info['path'] = $tmp_image_arr['path'];
        $product_info['path_thumb'] = $tmp_image_arr['path_thumb'];




        printAjaxData(array('user_path_list'=>$user_path_list,'product_info'=>$product_info,'group_info'=>$group_info));

    }


    //参团
    public function join_group($id = null){
        $user_id = $this->_check_login();
        $address_id = $this->input->post('address_id', TRUE);
        if(!$address_id){
            printAjaxError('fail', '请选择收货地址');
        }
        $group_info = $this->Groups_model->get('*',array('id'=>$id));
        if (!$group_info) {
            printAjaxError('fail', '参数异常');
        }

        $time = time();
        $groupon_info = $this->Groupon_model->get('*',"id = {$group_info['groupon_id']} and is_open = 1 and end_time > {$time}");
        if (!$groupon_info) {
            printAjaxError('fail', '活动已结束');
        }
        $product_info = $this->Product_model->get('*', array('id' => $groupon_info['product_id']));
        if (!$product_info) {
            printAjaxError('fail', '此产品不存在');
        }
        //团是否满员 重新单独发起拼团
        $is_success = 0;
        if ($group_info['is_success']){
            $is_success = 1;
            $this->Groupon_record_model->delete(array('groupon_record.groupon_id' => $groupon_info['id'], 'groupon_record.user_id' => $user_id, 'groupon_record.groups_id'=>$id, 'groupon_record.is_bond'=>0));
            printAjaxData(array('is_success'=>$is_success));
        }
        $timestamp = time();
        $record_info = $this->Groupon_record_model->get(array('groupon_record.groupon_id' => $groupon_info['id'], 'groupon_record.user_id' => $user_id, 'groupon_record.groups_id'=>$id));

        if ($record_info){
            if ($record_info['is_bond']){
                printAjaxError('fail','已参加过此拼团！');
            }else{
//                $this->Groupon_record_model->save(array('add_time'=>$timestamp,'address_id'=>$address_id),array('id'=>$record_info['id']));
                $this->Groupon_record_model->save(array('address_id'=>$address_id),array('id'=>$record_info['id']));
            }

            $record_id = $record_info['id'];

        }else{
            $datas = array(
                'user_id' => $user_id,
                'groupon_id' => $groupon_info['id'],
                'product_id' => $product_info['id'],
                'buy_number' => 1,
                'add_time' => $timestamp,
                'is_deposit' => 1,
                'groups_id' => $id,
                'address_id' => $address_id
            );
            $bond_number = $this->_getUniqueOrderNumber();
            $datas['bond_number'] = $bond_number;
            $record_id = $this->Groupon_record_model->save($datas);

        }


        $count = $this->Groupon_record_model->rowCount(array('groups_id'=>$group_info['id'],'is_bond'=>1));
        $is_full = 0;
        if ($groupon_info['min_number'] == $count+1){
            $is_full = 1;
        }

        printAjaxData(array('is_success'=>$is_success,'record_id'=>$record_id,'is_full'=>$is_full));
    }

    //大型团详情
    public function groupon_detail($id = null){
        $user_id = $this->_check_login();
        $time = time();
        $groupon_info = $this->Groupon_model->get('*',"id = {$id} and is_open = 1");

        $product_info = $this->Product_model->get('*', array('id' => $groupon_info['product_id']));
        if (!$product_info) {
            printAjaxError('fail', '此产品不存在');
        }
        $record_list = $this->Groupon_record_model->gets(array('groupon_record.groupon_id'=>$id,'groupon_record.is_bond'=>1));

        //拼团状态
        if($groupon_info['end_time'] > time()){
            $groupon_info['is_success'] = 0;
        }else{
            $groupon_info['is_success'] = 1;
        }

        $groupon_info['is_me'] = 0;
        $user_path_list = array();
        $cur_user_path = array();
        if ($record_list){
            foreach ($record_list as $key=>$value){
                $user_info = $this->User_model->get('path',array('id'=>$value['user_id']));
                if ($user_info){
                    $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
                    $user_info['path'] = $tmp_image_arr['path'];
                    $user_info['path_thumb'] = $tmp_image_arr['path_thumb'];
                    $order_count = $this->Orders_model->rowCount(array('user_id'=>$value['user_id'],'record_id'=>$value['id'],'is_free'=>1));
                    if ($value['user_id'] != $user_id){
                        $user_path_list[$key]['path'] = $user_info['path_thumb'];
                        $user_path_list[$key]['is_free'] = $order_count ? 1 : 0;
                    }else{
                        $cur_user_path[$key]['path'] = $user_info['path_thumb'];
                        $cur_user_path[$key]['is_free'] = $order_count ? 1 : 0;
                        $groupon_info['is_me'] = 1;
                    }

                }
            }
//            if ($cur_user_path){
//                array_push($user_path_list,$cur_user_path);
//            }
//            $user_path_list = array_reverse($user_path_list);
            $user_path_list = array_merge($cur_user_path,$user_path_list);
        }


        //当前价格
        $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $groupon_info['id']));
        $cur_price = $product_info['sell_price'];

        foreach ($groupon_rule as $ls) {
            if ($groupon_info['join_people'] >= $ls['low'] && $groupon_info['join_people'] <= $ls['high']) {
                $cur_price = number_format($ls['money'], 2,'.','');
            }
        }

        $product_info['cur_price'] = $cur_price;
        $tmp_image_arr = $this->_fliter_image_path($product_info['path']);
        $product_info['path'] = $tmp_image_arr['path'];
        $product_info['path_thumb'] = $tmp_image_arr['path_thumb'];

        printAjaxData(array('user_path_list'=>$user_path_list,'product_info'=>$product_info,'groupon_info'=>$groupon_info));
    }


    //付款页面
    public function confirm($id = NULL,$is_deposit = null,$record_id = null,$is_buy = null){
        $user_id = $this->_check_login();
        if ($record_id){
            $time = time();
            $record_info = $this->Groupon_record_model->get("groupon_record.id = '{$record_id}' and groupon_record.user_id = {$user_id} and groupon_record.is_bond = 1 and groupon_record.order_id = 0 ");
            if (!$record_info){
                printAjaxError('fail','参数异常！');
            }
            if ($record_info['type']){
                if ($record_info['end_time'] < $time){
                    printAjaxError('fail','活动已结束！');
                }
            }else{
                if ($record_info['end_time'] > $time){
                    printAjaxError('fail','活动未成团，请等待！');
                }
            }
            $id = $record_info['groupon_id'];
        }
        $groupon_info = $this->Groupon_model->get('*',array('id' => $id, 'is_open'=>1));
        if (!$groupon_info){
            printAjaxError('fail','参数异常！');
        }
        $product_info = $this->Product_model->get('*', array('id' => $groupon_info['product_id']));
        $tmp_image_arr = $this->_fliter_image_path($product_info['path']);
        $product_info['path'] = $tmp_image_arr['path'];
        $product_info['path_thumb'] = $tmp_image_arr['path_thumb'];

        //当前价格
        $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $groupon_info['id']));
        $cur_price = $product_info['sell_price'];
        if ($groupon_info['type']){
            $cur_price = $groupon_info['sale_price'];
        }else{
            foreach ($groupon_rule as $ls) {
                if ($groupon_info['join_people'] >= $ls['low'] && $groupon_info['join_people'] <= $ls['high']) {
                    $cur_price = number_format($ls['money'], 2,'.','');
                }
            }
        }
        $product_info['cur_price'] = $is_buy ? $product_info['sell_price'] : $cur_price;
        if ($record_id){
            $total = $cur_price - $groupon_info['deposit'];
        }else{
            $total = $is_buy ? $product_info['sell_price'] : ($is_deposit ? $groupon_info['deposit'] : $cur_price);
        }

        //用户收货地址
        if ($record_id){
            $default_user_address = $this->User_address_model->gets('*', array('id' => $record_info['address_id']));
        }else{
            $default_user_address = $this->User_address_model->gets('*', array('user_id' => $user_id, 'is_default' => 1));
        }
        printAjaxData(array('groupon_info'=>$groupon_info,'product_info'=>$product_info,'default_user_address'=>$default_user_address,'total'=>$total));

    }

    public function deposit_xcx_wx_pay()
    {
        $record_id = $this->input->post('record_id', TRUE);

        $code = $this->input->post("code", true);

        if (!$code) {
            printAjaxError('fail', 'DO NOT ACCESS!');
        }
        $appid = 'wx5b6972e9b65eefc0';
        $appSecret = 'c7ef31e34df248d7403bfa3186051396';
        $json = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appSecret}&js_code={$code}&grant_type=authorization_code");
        $obj = json_decode($json);
        if (isset($obj->errmsg)) {
            printAjaxError('fail', 'invalid code!');
        }
        $openid = $obj->openid;
        $user_id = $this->_check_login();

        if (!$record_id) {
            printAjaxError('fail','操作异常');
        }

        //判断下单用户是否存在
        $user_info = $this->User_model->get('*', array('user.id' => $user_id));
        if (!$user_info) {
            printAjaxError('fail','用户信息不存在，结算失败');
        }
        $item_info = $this->Groupon_record_model->get("groupon_record.id = '{$record_id}' and groupon_record.user_id = {$user_id} and groupon_record.is_bond = 0");
        if (!$item_info) {
            printAjaxError('fail','此订单信息不存在');
        }

        /********************微信支付**********************/
        require_once "sdk/weixin_pay/lib/WxPay.Api.php";
        require_once "sdk/weixin_pay/WxPay.JsApiPay.php";

        $product_id = $item_info['bond_number'];
        $out_trade_no = $item_info['bond_number'];
        $tools = new JsApiPay();
        $input = new WxPayUnifiedOrder();
        $input->SetAppid($appid);
        $input->SetOpenid($openid);
        $input->SetBody("日立小程序付款");
        $input->SetAttach("{$item_info['bond_number']}");
        $input->SetTotal_fee($item_info['deposit'] * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url(base_url() . "index.php/napi/deposit_xcx_wx_pay_notify");
        $input->SetTrade_type("JSAPI");
        $input->SetProduct_id($product_id);
        $input->SetOut_trade_no($out_trade_no);
        $result = WxPayApi::unifiedOrder($input,6,1);
        $jsApiParameters = array();
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $jsApiParameters = $tools->GetJsApiParameters($result);
            //生成支付记录
            if (!$this->Pay_log_model->rowCount(array('out_trade_no' => $out_trade_no, 'payment_type' => 'weixin', 'order_type' => 'groupon'))) {
                $fields = array(
                    'user_id' => $user_id,
                    'total_fee' => $item_info['deposit'],
                    'total_fee_give' => 0,
                    'out_trade_no' => $out_trade_no,
                    'order_num' => $item_info['bond_number'],
                    'trade_status' => 'WAIT_BUYER_PAY',
                    'add_time' => time(),
                    'payment_type' => 'weixin',
                    'order_type' => 'groupon',
                );
                $this->Pay_log_model->save($fields);
            }


        } else {
            if (array_key_exists('result_code', $result) && $result['result_code'] == "FAIL") {
                //商户号重复时，要重新生成
                if ($result['err_code'] == 'OUT_TRADE_NO_USED' || $result['err_code'] == 'INVALID_REQUEST') {
                    $out_trade_no = $this->_get_unique_out_trade_no();

                    $input = new WxPayUnifiedOrder();
                    $input->SetAppid($appid);
                    $input->SetOpenid($openid);
                    $input->SetBody("日立小程序付款");
                    $input->SetAttach("{$item_info['bond_number']}");
                    $input->SetTotal_fee($item_info['deposit'] * 100);
                    $input->SetTime_start(date("YmdHis"));
                    $input->SetTime_expire(date("YmdHis", time() + 600));
                    $input->SetNotify_url(base_url() . "index.php/napi/deposit_xcx_wx_pay_notify");
                    $input->SetTrade_type("JSAPI");
                    $input->SetProduct_id($product_id);
                    $input->SetOut_trade_no($out_trade_no);
                    $result = WxPayApi::unifiedOrder($input,6,1);
                    if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                        $jsApiParameters = $tools->GetJsApiParameters($result);
                        //生成支付记录
                        if (!$this->Pay_log_model->rowCount(array('out_trade_no' => $out_trade_no, 'payment_type' => 'weixin', 'order_type' => 'groupon'))) {
                            $fields = array(
                                'user_id' => $user_id,
                                'total_fee' => $item_info['deposit'],
                                'total_fee_give' => 0,
                                'out_trade_no' => $out_trade_no,
                                'order_num' => $item_info['bond_number'],
                                'trade_status' => 'WAIT_BUYER_PAY',
                                'add_time' => time(),
                                'payment_type' => 'weixin',
                                'order_type' => 'groupon',
                            );
                            $this->Pay_log_model->save($fields);
                        }
                    } else {
                        printAjaxError('fail', $result['err_code']);
                    }
                } else {
                    printAjaxError('fail', $result['err_code']);
                }
            } else {
                printAjaxError('fail', '交易失败，请重试');
            }
        }
        $jsApiParameters = json_decode($jsApiParameters, true);
        printAjaxData($jsApiParameters);

    }

    //微信支付异步通知
    public function deposit_xcx_wx_pay_notify(){
        /********************微信支付**********************/
        require_once "sdk/weixin_pay/lib/WxPay.Api.php";
        $msg = '支付通知失败';
        $appid = 'wx5b6972e9b65eefc0';
        $appSecret = 'c7ef31e34df248d7403bfa3186051396';
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        try {
            $data = WxPayResults::Init($xml);
            if (array_key_exists("transaction_id", $data)) {
                $input = new WxPayOrderQuery();
                $input->SetTransaction_id($data["transaction_id"]);
                $input->SetAppid($appid);
                $result = WxPayApi::orderQuery($input,6,1);
                if (array_key_exists("return_code", $result)
                    && array_key_exists("result_code", $result)
                    && $result["return_code"] == "SUCCESS"
                    && $result["result_code"] == "SUCCESS"
                ) {
                    //订单号
                    $order_num = $result['attach'];
                    //商户订单号
                    $out_trade_no = $result['out_trade_no'];
                    //微信交易号
                    $trade_no = $result['transaction_id'];
                    //通知时间
                    $notify_time = $result['time_end'];
                    $total_fee = $result['total_fee'];

                    $pay_log_info = $this->Pay_log_model->get('*', array('out_trade_no' => $out_trade_no, 'order_type' => 'groupon', 'payment_type' => 'weixin'));
                    if ($pay_log_info && $total_fee == $pay_log_info['total_fee'] * 100) {
                        if ($pay_log_info['trade_status'] != 'TRADE_FINISHED' && $pay_log_info['trade_status'] != 'TRADE_SUCCESS' && $pay_log_info['trade_status'] != 'TRADE_CLOSED') {
                            //支付记录
                            $fields = array(
                                'payment_type' => 'weixin',
                                'order_type' => 'groupon',
                                'trade_no' => $trade_no,
                                'trade_status' => 'TRADE_SUCCESS',
                                'buyer_email' => '',
                                'notify_time' => strtotime($notify_time)
                            );
                            if ($this->Pay_log_model->save($fields, array('id' => $pay_log_info['id']))) {
                                $item_info = $this->Groupon_record_model->get("groupon_record.bond_number = '{$order_num}' and groupon_record.is_bond = 0");
                                $user_info = $this->User_model->get('id, total, username', array('id' => $item_info['user_id']));
                                if ($item_info && $user_info) {
                                    //修改订单状态
                                    $fields = array(
                                        'is_bond'=> 1,
                                        'trade_no'=>$trade_no,
                                        'change_time'=>time(),
                                        'payment_id' => 3);
                                    if ($this->Groupon_record_model->save($fields, array('id' => $item_info['id']))) {
                                        $this->Groupon_model->save(array('join_people'=>$item_info['join_people']+1),array('id'=>$item_info['groupon_id']));

                                        //添加小团
                                        if ($item_info['type']){
                                            if (!$item_info['groups_id']){
                                                $files = array(
                                                    'head_user_id' => $user_info['id'],
                                                    'groupon_id' => $item_info['groupon_id'],
                                                    'add_time' => time(),
                                                );
                                                $groups_id = $this->Groups_model->save($files);
                                                $this->Groupon_record_model->save(array('groups_id'=>$groups_id),array('id'=>$item_info['id']));
                                            }else{
                                                $count = $this->Groupon_record_model->rowCount(array('groups_id'=>$item_info['groups_id'],'is_bond'=>1));

                                                if ($item_info['min_number'] == $count){
                                                    $this->Groups_model->save(array('is_success'=>1,'success_time'=>time()),array('id'=>$item_info['groups_id']));
                                                }

                                            }

                                        }

                                        //财务记录
                                        if (!$this->Financial_model->rowCount(array('type' => 'order_out', 'ret_id' => $item_info['id']))) {
                                            $fields = array(
                                                'cause' => "定金支付成功-[订单号：{$item_info['bond_number']}]",
                                                'price' => -$item_info['deposit'],
                                                'balance' => $user_info['total'],
                                                'add_time' => time(),
                                                'user_id' => $user_info['id'],
                                                'username' => $user_info['username'],
                                                'type' => 'order_out',
                                                'pay_way' => '3',
                                                'ret_id' => $item_info['id'],
                                                'from_user_id' => $user_info['id'],
                                            );
                                            $this->Financial_model->save($fields);
                                        }
                                        echo $this->returnInfo("SUCCESS", "OK");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (WxPayException $e) {
            $msg = $e->errorMessage();

        }
        echo $this->returnInfo("FAIL", $msg);
    }

    public function order_xcx_wx_pay($order_id = NULL)
    {
        $code = $this->input->post("code", true);

        if (!$code) {
            printAjaxError('fail', 'DO NOT ACCESS!');
        }
        $appid = 'wx5b6972e9b65eefc0';
        $appSecret = 'c7ef31e34df248d7403bfa3186051396';
        $json = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appSecret}&js_code={$code}&grant_type=authorization_code");
        $obj = json_decode($json);
        if (isset($obj->errmsg)) {
            printAjaxError('fail', 'invalid code!');
        }
        $openid = $obj->openid;
        $user_id = $this->_check_login();

        if (!$order_id) {
            printAjaxError('fail','操作异常');
        }

        //判断下单用户是否存在
        $user_info = $this->User_model->get('*', array('user.id' => $user_id));
        if (!$user_info) {
            printAjaxError('fail','用户信息不存在，结算失败');
        }
        $item_info = $this->Orders_model->get('*', "id = {$order_id} and user_id = {$user_id} and status = 0");
        if (!$item_info) {
            printAjaxError('fail','此订单信息不存在');
        }

        /********************微信支付**********************/
        require_once "sdk/weixin_pay/lib/WxPay.Api.php";
        require_once "sdk/weixin_pay/WxPay.JsApiPay.php";

        $product_id = $item_info['order_number'];
        $out_trade_no = $item_info['out_trade_no'] ? $item_info['out_trade_no'] : $item_info['order_number'];
        $this->Orders_model->save(array('out_trade_no' => $out_trade_no), array('id' => $item_info['id']));

        $tools = new JsApiPay();
        $input = new WxPayUnifiedOrder();
        $input->SetAppid($appid);
        $input->SetOpenid($openid);
        $input->SetBody("日立小程序付款");
        $input->SetAttach("{$item_info['order_number']}");
        $input->SetTotal_fee($item_info['total'] * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url(base_url() . "index.php/napi/order_xcx_wx_pay_notify");
        $input->SetTrade_type("JSAPI");
        $input->SetProduct_id($product_id);
        $input->SetOut_trade_no($out_trade_no);
        $result = WxPayApi::unifiedOrder($input,6,1);
        $jsApiParameters = array();
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $jsApiParameters = $tools->GetJsApiParameters($result);
            //生成支付记录
            if (!$this->Pay_log_model->rowCount(array('out_trade_no' => $out_trade_no, 'payment_type' => 'weixin', 'order_type' => 'orders'))) {
                $fields = array(
                    'user_id' => $user_id,
                    'total_fee' => $item_info['total'],
                    'total_fee_give' => 0,
                    'out_trade_no' => $out_trade_no,
                    'order_num' => $item_info['order_number'],
                    'trade_status' => 'WAIT_BUYER_PAY',
                    'add_time' => time(),
                    'payment_type' => 'weixin',
                    'order_type' => 'orders',
                );
                $this->Pay_log_model->save($fields);
            }


        } else {
            if (array_key_exists('result_code', $result) && $result['result_code'] == "FAIL") {
                //商户号重复时，要重新生成
                if ($result['err_code'] == 'OUT_TRADE_NO_USED' || $result['err_code'] == 'INVALID_REQUEST') {
                    $out_trade_no = $this->_get_unique_out_trade_no();
                    $this->Orders_model->save(array('out_trade_no' => $out_trade_no), array('id' => $item_info['id']));

                    $input = new WxPayUnifiedOrder();
                    $input->SetAppid($appid);
                    $input->SetOpenid($openid);
                    $input->SetBody("日立小程序付款");
                    $input->SetAttach("{$item_info['order_number']}");
                    $input->SetTotal_fee($item_info['total'] * 100);
                    $input->SetTime_start(date("YmdHis"));
                    $input->SetTime_expire(date("YmdHis", time() + 600));
                    $input->SetNotify_url(base_url() . "index.php/napi/order_xcx_wx_pay_notify");
                    $input->SetTrade_type("JSAPI");
                    $input->SetProduct_id($product_id);
                    $input->SetOut_trade_no($out_trade_no);
                    $result = WxPayApi::unifiedOrder($input,6,1);
                    if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
                        $jsApiParameters = $tools->GetJsApiParameters($result);
                        //生成支付记录
                        if (!$this->Pay_log_model->rowCount(array('out_trade_no' => $out_trade_no, 'payment_type' => 'weixin', 'order_type' => 'orders'))) {
                            $fields = array(
                                'user_id' => $user_id,
                                'total_fee' => $item_info['total'],
                                'total_fee_give' => 0,
                                'out_trade_no' => $out_trade_no,
                                'order_num' => $item_info['order_number'],
                                'trade_status' => 'WAIT_BUYER_PAY',
                                'add_time' => time(),
                                'payment_type' => 'weixin',
                                'order_type' => 'orders',
                            );
                            $this->Pay_log_model->save($fields);
                        }
                    } else {
                        printAjaxError('fail', $result['err_code']);
                    }
                } else {
                    printAjaxError('fail', $result['err_code']);
                }
            } else {
                printAjaxError('fail', '交易失败，请重试');
            }
        }
        $jsApiParameters = json_decode($jsApiParameters, true);
        printAjaxData($jsApiParameters);

    }

    //微信支付异步通知
    public function order_xcx_wx_pay_notify(){
        /********************微信支付**********************/
        require_once "sdk/weixin_pay/lib/WxPay.Api.php";
        $msg = '支付通知失败';
        $appid = 'wx5b6972e9b65eefc0';
        $appSecret = 'c7ef31e34df248d7403bfa3186051396';
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        try {
            $data = WxPayResults::Init($xml);
            if (array_key_exists("transaction_id", $data)) {
                $input = new WxPayOrderQuery();
                $input->SetTransaction_id($data["transaction_id"]);
                $input->SetAppid($appid);
                $result = WxPayApi::orderQuery($input,6,1);
                if (array_key_exists("return_code", $result)
                    && array_key_exists("result_code", $result)
                    && $result["return_code"] == "SUCCESS"
                    && $result["result_code"] == "SUCCESS"
                ) {
                    //订单号
                    $order_num = $result['attach'];
                    //商户订单号
                    $out_trade_no = $result['out_trade_no'];
                    //微信交易号
                    $trade_no = $result['transaction_id'];
                    //通知时间
                    $notify_time = $result['time_end'];
                    $total_fee = $result['total_fee'];

                    $pay_log_info = $this->Pay_log_model->get('*', array('out_trade_no' => $out_trade_no, 'order_type' => 'orders', 'payment_type' => 'weixin'));
                    if ($pay_log_info && $total_fee == $pay_log_info['total_fee'] * 100) {
                        if ($pay_log_info['trade_status'] != 'TRADE_FINISHED' && $pay_log_info['trade_status'] != 'TRADE_SUCCESS' && $pay_log_info['trade_status'] != 'TRADE_CLOSED') {
                            //支付记录
                            $fields = array(
                                'payment_type' => 'weixin',
                                'order_type' => 'orders',
                                'trade_no' => $trade_no,
                                'trade_status' => 'TRADE_SUCCESS',
                                'buyer_email' => '',
                                'notify_time' => strtotime($notify_time)
                            );
                            if ($this->Pay_log_model->save($fields, array('id' => $pay_log_info['id']))) {
                                $item_info = $this->Orders_model->get('*', array('order_number' => $order_num, 'status' => 0));
                                $user_info = $this->User_model->get('id, total, username', array('id' => $item_info['user_id']));
                                if ($item_info && $user_info) {
                                    //修改订单状态
                                    $fields = array(
                                        'status' => 1,
                                        'payment_title' => '微信支付',
                                        'pay_time' => strtotime($notify_time),
                                        'payment_id' => 3);
                                    if ($this->Orders_model->save($fields, array('id' => $item_info['id']))) {
                                        if ($item_info['order_type']){
                                            //参团记录
                                            $this->Groupon_record_model->save(array('order_id'=>$item_info['id']), array('id' => $item_info['record_id']));
                                        }else{
                                            $groupon_info = $this->Groupon_model->get('*',array('id' => $item_info['groupon_id']));
                                            if ($groupon_info){
                                                $this->Groupon_model->save(array('join_people'=>$groupon_info['join_people']+1),array('id'=>$item_info['groupon_id']));
                                            }
                                        }

                                        //加销量
                                        $order_detail_info = $this->Orders_detail_model->get2('product.id,product.sales',array('order_id'=>$item_info['id']));
                                        if ($order_detail_info){
                                            $this->Product_model->save(array('sales'=>$order_detail_info['sales'] + 1),array('id'=>$order_detail_info['id']));
                                        }
                                        //订单追踪记录
                                        $fields = array(
                                            'add_time' => time(),
                                            'content' => "订单付款成功[微信小程序支付]",
                                            'order_id' => $item_info['id'],
                                            'order_status' => $item_info['status'],
                                            'change_status' => 1
                                        );
                                        $this->Orders_process_model->save($fields);
                                        //财务记录
                                        if (!$this->Financial_model->rowCount(array('type' => 'order_out', 'ret_id' => $item_info['id']))) {
                                            $fields = array(
                                                'cause' => "小程序支付成功-[订单号：{$item_info['order_number']}]",
                                                'price' => -$item_info['total'],
                                                'balance' => $user_info['total'],
                                                'add_time' => time(),
                                                'user_id' => $user_info['id'],
                                                'username' => $user_info['username'],
                                                'type' => 'order_out',
                                                'pay_way' => '3',
                                                'ret_id' => $item_info['id'],
                                                'from_user_id' => $user_info['id'],
                                            );
                                            $this->Financial_model->save($fields);
                                        }
                                        echo $this->returnInfo("SUCCESS", "OK");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (WxPayException $e) {
            $msg = $e->errorMessage();

        }
        echo $this->returnInfo("FAIL", $msg);
    }

    public function add_now_order()
    {
        if($_POST) {

            //判断是否登录
            $user_id = $this->_check_login();
            $user_info = $this->User_model->get('total,username', array('id' => $user_id));

            $record_id = $this->input->post('record_id', TRUE);
            $address_id = $this->input->post('address_id', TRUE);
            $remark = trim($this->input->post('remark', TRUE));
            //用已经存在的收货地址
            $user_address_info = $this->User_address_model->get('*', array('id' => $address_id));
            if (!$user_address_info) {
                printAjaxError('fail', '此收货地址信息不存在，下单失败');
            }

            $timestamp = time();

            $record_info = $this->Groupon_record_model->get("groupon_record.id = '{$record_id}' and groupon_record.user_id = {$user_id} and groupon_record.is_bond = 1 and groupon_record.order_id = 0 ");
            if (!$record_info) {
                printAjaxError('fail', '参数异常！');
            }
            if ($record_info['type']) {
                if ($record_info['end_time'] < $timestamp) {
                    printAjaxError('fail', '活动已结束！');
                }
                $group_info = $this->Groups_model->get('is_success,success_time', array('id' => $record_info['groups_id']));
                if (!$group_info['is_success']) {
                    printAjaxError('fail', '拼团未成功！');
                }
                $success_time = $group_info['success_time'];
            } else {
                if ($record_info['end_time'] > $timestamp) {
                    printAjaxError('fail', '活动未成团，请等待！');
                }
                $success_time = $record_info['end_time'];
            }

//        $groupon_info = $this->Groupon_model->get('*',array('id' => $record_info['groupon_id'], 'is_open'=>1));
//        if (empty($groupon_info)) {
//            printAjaxError('fail','该拼团活动不存在');
//        }


            //是否逾期
            if (strtotime('+48hours', $success_time) < time()) {
                printAjaxError('fail', '逾期未支付，订单已失效');
            }

            $product_info = $this->Product_model->get('*', array('id' => $record_info['product_id'], 'display' => 1));
            if (!$product_info) {
                printAjaxError('fail', '此产品不存在或被删除');
            }


            //当前价格
            $groupon_rule = $this->Groupon_rule_model->gets(array('groupon_id' => $record_info['groupon_id']));
            $cur_price = $product_info['sell_price'];
            if ($record_info['type']) {
                $cur_price = $record_info['sale_price'];
            } else {
                foreach ($groupon_rule as $ls) {
                    if ($record_info['join_people'] >= $ls['low'] && $record_info['join_people'] <= $ls['high']) {
                        $cur_price = number_format($ls['money'], 2, '.', '');
                    }
                }
            }

            $order_info = $this->Orders_model->get('id', "record_id = {$record_info['id']} and user_id = {$user_id} and status = 0");
            if ($order_info){
                printAjaxData($order_info['id']);
            }else{

                $total = $cur_price - $record_info['deposit'];
                $order_number = $this->_getUniqueOrderNumber();
                /****************提交订单*****************/
                $fields = array(
                    'user_id' => $user_id,
                    'user_name' => $user_info['username'],
                    'order_number' => $order_number,
                    'postage_id' => 0,
                    'postage_title' => '',
                    'postage_price' => 0,
                    'product_total' => 0,
                    'discount_total' => 0,
                    'total' => $total,
                    'deposit' => $record_info['deposit'],
                    'status' => 0,
                    'add_time' => time(),
                    'buyer_name' => $user_address_info['buyer_name'],
                    'country_id' => $user_address_info['country_id'],
                    'province_id' => $user_address_info['province_id'],
                    'city_id' => $user_address_info['city_id'],
                    'area_id' => $user_address_info['area_id'],
                    'txt_address' => $user_address_info['txt_address'],
                    'address' => $user_address_info['address'],
                    'phone' => $user_address_info['phone'],
                    'mobile' => $user_address_info['mobile'],
                    'remark' => $remark,
                    'order_type' => 1,
                    'groupon_id' => $record_info['groupon_id'],
                    'record_id' => $record_info['id']
                );
                if ($record_info['type']) {
                    $fields['groups_id'] = $record_info['groups_id'];
                }
                //添加订单
                $ret_id = $this->Orders_model->save($fields);
                if ($ret_id) {
                    /***************************添加订单详细信息*********************** */

                    //订单详情
                    $orders_detail_fields = array(
                        'order_id' => $ret_id,
                        'product_id' => $product_info['id'],
                        'product_num' => $product_info['product_num'],
                        'product_title' => $product_info['title'],
                        'content_short' => $product_info['content_short'],
                        'market_price' => $product_info['market_price'],
                        'buy_number' => 1,
                        'buy_price' => $cur_price,
                        'path' => $product_info['path']
                    );
                    if (!$this->Orders_detail_model->save($orders_detail_fields)) {
                        //删除订单详细信息
                        $this->Orders_detail_model->delete("order_id = {$ret_id}");
                        //删除记录
                        $this->Orders_process_model->delete("order_id = {$ret_id}");
                        //删除已经添加进去的数据，保持数据统一性
                        $this->Orders_model->delete("id order_id = {$ret_id}");
                        printAjaxError('fail', '订单创建失败');
                    } else {
                        //订单跟踪记录
                        $orders_process_fields = array(
                            'add_time' => time(),
                            'content' => "订单创建成功",
                            'order_id' => $ret_id,
                            'order_status' => 0,
                            'change_status' => 0
                        );
                        $this->Orders_process_model->save($orders_process_fields);
                    }
                    printAjaxData($ret_id);

                }
            }

            printAjaxError('fail', '订单创建失败');
        }
    }


    //单独购买
    public function add_order()
    {
        if($_POST) {

            //判断是否登录
            $user_id = $this->_check_login();
            $user_info = $this->User_model->get('total,username', array('id' => $user_id));

            $id = $this->input->post('groupon_id', TRUE);
            $remark = trim($this->input->post('remark', TRUE));
            $address_id = $this->input->post('address_id', TRUE);
            if(!$address_id){
                printAjaxError('fail', '请选择收货地址');
            }
            //用已经存在的收货地址
            $user_address_info = $this->User_address_model->get('*', array('id' => $address_id));
            if (!$user_address_info) {
                printAjaxError('fail', '此收货地址信息不存在，下单失败');
            }


            $groupon_info = $this->Groupon_model->get('*',array('id' => $id, 'is_open'=>1));
            if (!$groupon_info){
                printAjaxError('fail','参数异常！');
            }


            $product_info = $this->Product_model->get('*', array('id' => $groupon_info['product_id'], 'display' => 1));
            if (!$product_info) {
                printAjaxError('fail', '此产品不存在或被删除');
            }


            $order_info = $this->Orders_model->get('id', " user_id = {$user_id} and status = 0 and groupon_id = {$id} and order_type = 0");
            if ($order_info){
                printAjaxData($order_info['id']);
            }else{

                $total = $product_info['sell_price'];
                $order_number = $this->_getUniqueOrderNumber();
                /****************提交订单*****************/
                $fields = array(
                    'user_id' => $user_id,
                    'user_name' => $user_info['username'],
                    'order_number' => $order_number,
                    'postage_id' => 0,
                    'postage_title' => '',
                    'postage_price' => 0,
                    'product_total' => 0,
                    'discount_total' => 0,
                    'total' => $total,
                    'status' => 0,
                    'add_time' => time(),
                    'buyer_name' => $user_address_info['buyer_name'],
                    'country_id' => $user_address_info['country_id'],
                    'province_id' => $user_address_info['province_id'],
                    'city_id' => $user_address_info['city_id'],
                    'area_id' => $user_address_info['area_id'],
                    'txt_address' => $user_address_info['txt_address'],
                    'address' => $user_address_info['address'],
                    'phone' => $user_address_info['phone'],
                    'mobile' => $user_address_info['mobile'],
                    'remark' => $remark,
                    'order_type' => 0,
                    'groupon_id' => $groupon_info['id'],
                );

                //添加订单
                $ret_id = $this->Orders_model->save($fields);
                if ($ret_id) {
                    /***************************添加订单详细信息*********************** */

                    //订单详情
                    $orders_detail_fields = array(
                        'order_id' => $ret_id,
                        'product_id' => $product_info['id'],
                        'product_num' => $product_info['product_num'],
                        'product_title' => $product_info['title'],
                        'content_short' => $product_info['content_short'],
                        'market_price' => $product_info['market_price'],
                        'buy_number' => 1,
                        'buy_price' => $total,
                        'path' => $product_info['path']
                    );
                    if (!$this->Orders_detail_model->save($orders_detail_fields)) {
                        //删除订单详细信息
                        $this->Orders_detail_model->delete("order_id = {$ret_id}");
                        //删除记录
                        $this->Orders_process_model->delete("order_id = {$ret_id}");
                        //删除已经添加进去的数据，保持数据统一性
                        $this->Orders_model->delete("id order_id = {$ret_id}");
                        printAjaxError('fail', '订单创建失败');
                    } else {
                        //订单跟踪记录
                        $orders_process_fields = array(
                            'add_time' => time(),
                            'content' => "订单创建成功",
                            'order_id' => $ret_id,
                            'order_status' => 0,
                            'change_status' => 0
                        );
                        $this->Orders_process_model->save($orders_process_fields);
                    }
                    printAjaxData($ret_id);

                }
            }

            printAjaxError('fail', '订单创建失败');
        }
    }

    //列表-拼团中
    public function get_record_list($per_page = 10, $page = 1){
        $user_id = $this->_check_login();
        $time = time();
        $strWhere = "groupon_record.user_id = {$user_id} and groupon_record.is_bond = 1 and groupon.end_time > $time and groupon_record.order_id = 0 ";
        $record_list = $this->Groupon_record_model->gets2($strWhere);
        if ($record_list){
            foreach ($record_list as $key=>$value){
                if ($value['type']){
                    $group_info = $this->Groups_model->get('is_success',array('id'=>$value['groups_id']));
                    if (!empty($group_info) && $group_info['is_success']){
                        unset($record_list[$key]);
                    }
                }
            }
        }
        $record_list = array_values($record_list);

        $item_list = array_slice($record_list, $per_page * ($page - 1), $per_page);
        if ($item_list){
            foreach ($item_list as $key=>$value){
                $user_path_list = array();
                if ($value['type']) {
                    $user_record_list = $this->Groupon_record_model->gets(array('groupon_record.groups_id' => $value['groups_id'],'groupon_record.is_bond'=>1));
                    $user_path_list = array();
                    foreach ($user_record_list as $k => $v) {
                        $user_info = $this->User_model->get('path', array('id' => $v['user_id']));
                        if ($user_info) {
                            $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
                            $user_info['path'] = $tmp_image_arr['path'];
                            $user_info['path_thumb'] = $tmp_image_arr['path_thumb'];
                            $user_path_list[] = $user_info['path_thumb'];
                        }
                    }
                    $surplus_people = $value['min_number'] - count($user_record_list);
                    $item_list[$key]['surplus_people'] = $surplus_people;

                }
                $item_list[$key]['user_path_list'] = $user_path_list;
                $product_info = $this->Product_model->get('*', array('id' => $value['product_id']));
                $tmp_image_arr = $this->_fliter_image_path($product_info['path']);
                $product_info['path'] = $tmp_image_arr['path'];
                $product_info['path_thumb'] = $tmp_image_arr['path_thumb'];
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

                $item_list[$key]['add_time_format'] = date('Y-m-d H:i:s',$value['add_time']);
            }
        }

        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = count($record_list);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }

        printAjaxData(array('item_list'=>$item_list,'is_next_page'=>$is_next_page));
    }

    //列表-待付款
    public function get_record_list_0($per_page = 10, $page = 1){
        $user_id = $this->_check_login();
        $time = time();
        $strWhere = "groupon_record.user_id = {$user_id} and groupon_record.order_id = 0 ";
        $record_list = $this->Groupon_record_model->gets2($strWhere);
        if ($record_list){
            foreach ($record_list as $key=>$value){
                $record_list[$key]['is_order'] = 0;
                if ($value['type']){
                    if ($value['is_bond']){
                        $group_info = $this->Groups_model->get('is_success',array('id'=>$value['groups_id']));
                        if (!$group_info['is_success'] || $value['end_time'] < time()){
                            unset($record_list[$key]);
                        }
                    }else{
                        if ($value['end_time'] < time()){
                            unset($record_list[$key]);
                        }
                    }

                }else{
                    if ($value['is_bond']){
                        if ($value['end_time'] > time()){
                            unset($record_list[$key]);
                        }
                    }else{
                        if ($value['end_time'] < time()){
                            unset($record_list[$key]);
                        }
                    }

                }

            }
        }

        $record_list = array_values($record_list);

        $order_list = $this->Orders_model->gets('*',"order_type = 0 and status = 0 and user_id = {$user_id}");
        if ($order_list){
            foreach ($order_list as $key=>$value){
                $order_list[$key]['is_order'] = 1;
            }
        }

        $orders_list = array_merge($record_list,$order_list);
        if ($orders_list){
            foreach ($orders_list as $key=>$value){
                $add_time[$key] = $value['add_time'];
            }
            array_multisort($add_time,SORT_DESC,$orders_list);
        }

        $item_list = array_slice($orders_list, $per_page * ($page - 1), $per_page);
        if ($item_list){
            foreach ($item_list as $key=>$value){
                $user_path_list = array();
                if ($value['is_order']){
                    $item_list[$key]['user_path_list'] = $user_path_list;
                    $order_detail_info = $this->Orders_detail_model->get('product_id',array('order_id'=>$value['id']));
                    $product_info = $this->Product_model->get('*', array('id' => $order_detail_info['product_id']));
                    $tmp_image_arr = $this->_fliter_image_path($product_info['path']);
                    $product_info['path'] = $tmp_image_arr['path'];
                    $product_info['path_thumb'] = $tmp_image_arr['path_thumb'];
                    $product_info['cur_price'] = $product_info['sell_price'];
                    $item_list[$key]['product_info'] = $product_info;
                    $item_list[$key]['total'] = $value['total'];
                    $item_list[$key]['record_id'] = 0;
                }else{
                    if ($value['type'] && $value['is_bond']) {
                        $user_record_list = $this->Groupon_record_model->gets(array('groupon_record.groups_id' => $value['groups_id'],'groupon_record.is_bond'=>1));
                        $user_path_list = array();
                        foreach ($user_record_list as $k => $v) {
                            $user_info = $this->User_model->get('path', array('id' => $v['user_id']));
                            if ($user_info) {
                                $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
                                $user_info['path'] = $tmp_image_arr['path'];
                                $user_info['path_thumb'] = $tmp_image_arr['path_thumb'];
                                $user_path_list[] = $user_info['path_thumb'];
                            }
                        }
                        $surplus_people = $value['min_number'] - count($user_record_list);
                        $item_list[$key]['surplus_people'] = $surplus_people;

                    }
                    $item_list[$key]['user_path_list'] = $user_path_list;
                    $product_info = $this->Product_model->get('*', array('id' => $value['product_id']));
                    $tmp_image_arr = $this->_fliter_image_path($product_info['path']);
                    $product_info['path'] = $tmp_image_arr['path'];
                    $product_info['path_thumb'] = $tmp_image_arr['path_thumb'];
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
                    $item_list[$key]['total'] = $value['is_bond'] ? ($cur_price - $value['deposit']) : $value['deposit'];
                    $item_list[$key]['record_id'] = $value['is_bond'] ? $value['id'] : 0;
                }


                $item_list[$key]['add_time_format'] = date('Y-m-d H:i:s',$value['add_time']);
            }
        }

        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = count($orders_list);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }

        printAjaxData(array('item_list'=>$item_list,'is_next_page'=>$is_next_page));
    }

    public function get_order_list($per_page = 10, $page = 1) {
        $user_id = $this->_check_login();
        $strWhere = "user_id = {$user_id} and status > 0";
//        if ($since_id) {
//            $strWhere .= " and id > {$since_id} ";
//        }
//        if ($max_id) {
//            $strWhere .= " and id <= {$max_id} ";
//        }
        $order_list = $this->Orders_model->gets('*', $strWhere, $per_page, $per_page * ($page - 1));
        if ($order_list) {
            foreach ($order_list as $key => $order) {
                $orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $order['id']));
                foreach ($orderdetailList as $k => $v) {
                    $tmp_image_arr = $this->_fliter_image_path($v['path']);
                    $orderdetailList[$k]['path'] = $tmp_image_arr['path'];
                    $orderdetailList[$k]['path_thumb'] = $tmp_image_arr['path_thumb'];
                }
                $order_list[$key]['product_info'] = $orderdetailList[0];
//                $order_list[$key]['status_format'] = $this->_status[$order['status']];
                $order_list[$key]['add_time_format'] = date('Y-m-d H:i:s', $order['add_time']);
                $groupon_info = $this->Groupon_model->get('type',array('id' => $order['groupon_id']));
                $order_list[$key]['type'] = $groupon_info ? $groupon_info['type'] : 0;

                $exchange_info = $this->Exchange_model->rowCount(array('orders_id'=>$order['id']));
                $order_list[$key]['is_exchange'] = $exchange_info ? 1 : 0;
            }
        }

        // 最大ID
        // 第一次加载
//        if (!$max_id && !$since_id) {
//            $max_id = $this->Orders_model->get_max_id(NULL);
//        } else {
//            //下拉刷新
//            if (!$max_id && $since_id) {
//                $max_id = $this->Orders_model->get_max_id(NULL);
//            }
//        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($order_list);
        $total_count = $this->Orders_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $order_list, 'is_next_page' => $is_next_page, 'total_count' => $total_count));
    }

    /*
     * 用户申请退换货
     */
    public function apply_exchange($order_id = NULL){
        //判断是否登录
        $user_id = $this->_check_login();
        $order_id = intval($order_id);
        $item_info = $this->Orders_model->get('id,order_number,total,deposit', "id = {$order_id} and user_id = {$user_id} and status >= 1");
        if (!$item_info) {
            printAjaxError('fail', '此订单信息不存在');
        }
        $exchange_info = $this->Exchange_model->get('*', array('orders_id'=>$order_id, 'user_id' => $user_id));

        $user_info = $this->User_model->get('id, nickname', array('id' => $user_id));
        if (!$user_info) {
            printAjaxError('fail', '退款异常');
        }

        if ($exchange_info) {
            if ($exchange_info['status'] >= 3) {
                printAjaxError('fail', '已成功退款，不能重复操作');
            } else {
                if ($exchange_info['status'] != 1) {
                    printAjaxError('fail', '退款申请审核中，请耐心等待');
                }
            }
        }

        $fields = array(
            'user_id' => $user_info['id'],
            'username' => $user_info['nickname'],
            'orders_id' => $item_info['id'],
            'order_num' => $item_info['order_number'],
            'add_time' => time(),
            'status' => 0,
            'exchange_type' => 1,
            'price' => $item_info['total']+$item_info['deposit']
        );
        if (!$this->Exchange_model->save($fields, $exchange_info ? array('id' => $exchange_info['id']) : NULL)) {
            printAjaxError('fail', '提交申请失败');
        }
        printAjaxSuccess('success', '提交申请成功');

    }

    //获取商家退换货列表
    public function get_exchange_list($per_page = 10, $page = 1) {
        //判断是否登录
        $user_id = $this->_check_login();
        $strWhere = "user_id = {$user_id}";
        $item_list = $this->Exchange_model->gets('*', $strWhere, $per_page, $per_page * ($page - 1));
        if ($item_list){
            foreach ($item_list as $key=>$value){
                $order_info = $this->Orders_model->get('*', "id = {$value['orders_id']}");
                $item_list[$key]['order_info'] = $order_info;
                $orderdetailList = $this->Orders_detail_model->gets('*', array('order_id' => $value['orders_id']));
                foreach ($orderdetailList as $k => $v) {
                    $tmp_image_arr = $this->_fliter_image_path($v['path']);
                    $orderdetailList[$k]['path'] = $tmp_image_arr['path'];
                    $orderdetailList[$k]['path_thumb'] = $tmp_image_arr['path_thumb'];
                }
                $item_list[$key]['product_info'] = $orderdetailList[0];
                $item_list[$key]['status_format'] = $this->_status_arr[$value['status']];
                $item_list[$key]['add_time_format'] = date('Y-m-d H:i:s', $value['add_time']);
//                $groupon_info = $this->Groupon_model->get('type',array('id' => $value['groupon_id']));
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Exchange_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'is_next_page' => $is_next_page, 'total_count' => $total_count));
    }


    //实时订单
//    public function new_record()
//    {
//        $time = time();
//        $item_info = array();
//        $record_info = $this->Groupon_record_model->get_rand("groupon_record.change_time = ({$time} - 1) and groupon_record.is_bond = 1");
//        if ($record_info){
//            $user_address_info = $this->User_address_model->get('*', array('id' => $record_info['address_id']));
//            $item_info['mobile'] = substr($user_address_info['mobile'],-4);
//            $item_info['type'] = $record_info['type'];
//            $item_info['groups_id'] = $record_info['groups_id'];
//            $item_info['groupon_id'] = $record_info['groupon_id'];
//        }
//
//        printAjaxData($item_info);
//    }

    public function new_record()
    {
        $time = time();
        $item_info = array();
        $groupon_info = $this->Groupon_model->get_rand('id',"groupon.is_open=1 and groupon.end_time > {$time}");
        $item_info['groupon_id'] = $groupon_info ? $groupon_info['id'] : '';
        $str = '0123456789';
        $maxLen = strlen($str)-1;
        $randStr = '';
        for ($i = 0; $i < 4; $i++) {
            $randStr .= substr($str, rand(0, $maxLen), 1);
        }
        $item_info['mobile'] = $randStr;

        printAjaxData($item_info);
    }


    public function get_news_list(){
        $item_list = $this->News_model->gets(null,2);
        if ($item_list){
            foreach ($item_list as $key => $value){
//                $item_list[$key]['content'] = filter_content(html($value['content']),base_url());
                $item_list[$key]['abstract'] = my_substr($value['abstract'],42);
                $tmp_image_arr = $this->_fliter_image_path($value['path']);
                $item_list[$key]['path'] = $tmp_image_arr['path'];
                $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
                if ($value['hits'] >= 1000){
                    $item_list[$key]['hits'] = ($value['hits']/1000).'k';
                }
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }

    public function get_news_detail($id = NULL){
        $item_info = $this->News_model->get('*', array('id'=>$id));
        if ($item_info){
            $item_info['content'] = filter_content(html($item_info['content']),base_url());
            $this->News_model->save(array('hits'=>$item_info['hits']+1),array('id'=>$id));
        }
        printAjaxData(array('item_info' => $item_info));
    }


    //报名
    public function enroll()
    {
        //判断是否登录
        $user_id = $this->_check_login();
        if ($this->Enroll_model->rowCount(array('user_id'=>$user_id))){
            printAjaxError('fail','您已经报名！');
        }

        if ($_POST){
            $real_name = $this->input->post('real_name',TRUE);
            $mobile = $this->input->post('mobile',TRUE);

            if (!$this->form_validation->required($mobile)) {
                printAjaxError('username', '请输入手机号');
            }
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $mobile)) {
                printAjaxError('username', '请输入正确的手机号');
            }
            $data = array(
                'real_name' => $real_name,
                'mobile' => $mobile,
                'user_id' => $user_id,
                'add_time'=>time()
            );
            if ($this->Enroll_model->save($data)){
                printAjaxSuccess('success','报名成功！');
            }
        }
        printAjaxError('fail','报名失败！');
    }

    //帮他点赞
    public function thumbs_up()
    {
        //判断是否登录
        $user_id = $this->_check_login();
        $parent_id = $this->input->post('parent_id',TRUE);
        if(!$parent_id){
            printAjaxError('fail','参数异常！');
        }
        $item_info = $this->Enroll_model->get('id',array('user_id'=>$parent_id));
        if(!$item_info){
            printAjaxError('fail','参数异常！');
        }

        if($this->Enroll_progress_model->rowCount(array('user_id'=>$user_id,'enroll_id'=>$item_info['id']))){
            printAjaxError('fail','您已经帮他点过赞！');
        }

        $prize_arr = $this->Light_img_model->gets();
        $actor = 100;

        $arr = array();
        foreach ($prize_arr as $v) {
            $arr[$v['id']] = $v['rate'];
        }
        foreach ($arr as &$v) {
            $v = $v*$actor;
        }
        asort($arr);
        $sum = array_sum($arr);   //总概率

//        $rand = mt_rand(1,$sum);
//
        $result = '';    //中奖产品id
//
//        foreach ($arr as $k => $x)
//        {
//            if($rand <= $x)
//            {
//                $result = $k;
//                break;
//            }
//            else
//            {
//                $rand -= $x;
//            }
//        }
        //概率数组循环
        foreach ($arr as $key => $proCur) {
            $randNum = mt_rand(1, $sum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $sum -= $proCur;
            }
        }

        $img_id = $prize_arr[$result-1]['id'];
        $data = array(
            'user_id'=>$user_id,
            'enroll_id'=>$item_info['id'],
            'img_id'=>$img_id,
            'add_time'=>time()
        );
        if ($this->Enroll_progress_model->save($data)){
            $this->Light_img_model->save2('total','total+1', array('id' => $img_id));
            printAjaxSuccess('success','点赞成功！');
        }
        printAjaxError('fail','点赞失败！');
    }

    //点赞详情
    public function get_enroll_detail()
    {
        $parent_id = $this->input->post('parent_id',TRUE);
        $user_id = $this->session->userdata("user_id");
        $item_info = $this->Enroll_model->get('id',array('user_id'=>$parent_id ? $parent_id : $user_id));
        $img_arr = $this->Light_img_model->gets();
        if ($img_arr){
            foreach ($img_arr as $key=>$value){
                $tmp_image_arr = $this->_fliter_image_path($value['img_path']);
                $img_arr[$key]['img_path'] = $tmp_image_arr['path'];
                $tmp_image_arr = $this->_fliter_image_path($value['light_img_path']);
                $img_arr[$key]['light_img_path'] = $tmp_image_arr['path'];
            }
        }
        $enroll_progress = array();
        $progress_list = array();
        if ($item_info){
            $enroll_progress = $this->Enroll_progress_model->gets2(array('enroll_id'=>$item_info['id']));
            $progress_list = $this->Enroll_progress_model->gets(array('enroll_progress.enroll_id'=>$item_info['id']));
            if ($progress_list){
                foreach ($progress_list as $key=>$value){
                    $name = '';
                    foreach ($img_arr as $v){
                        if ($v['id'] == $value['img_id']){
                            $name = $v['name'];
                            break;
                        }
                    }
                    $progress_list[$key]['img_name'] = $name;
                }
            }
        }
        $is_enroll = 0;
        if ($user_id){
            if ($this->Enroll_model->get('id',array('user_id'=>$user_id))){
                $is_enroll = 1;
            }
        }

        printAjaxData(array('img_arr'=>$img_arr,'enroll_progress'=>$enroll_progress,'progress_list'=>$progress_list,'is_enroll'=>$is_enroll));

    }

    //获取报价列表
    public function get_quote_price_arr()
    {
        printAjaxData($this->_quote_price_arr);
    }

    //发送报价
    public function send_price()
    {
        $id = $this->input->post('id',TRUE);
        $area = $this->input->post('area',TRUE);
        $mobile = $this->input->post('mobile',TRUE);
        if ($area < 50 || $area > 500){
            printAjaxError('fail','您输入的面积我们系统不支持，请电话咨询');
        }
        if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $mobile)) {
            printAjaxError('mobile', '请输入正确的手机号');
        }
        if ($id == ""){
            printAjaxError('fail','参数异常！');
        }
        $quote_price_arr = $this->_quote_price_arr;
        if (empty($quote_price_arr[$id])){
            printAjaxError('fail','参数异常！');
        }
        $res_code = $this->sendSMS($mobile,$quote_price_arr[$id]['name'],$quote_price_arr[$id]['price'],$area);
        if ($res_code == 'SUCCESS'){
            $data = array(
                'area'=>$area,
                'type_id'=>$id,
                'mobile'=>$mobile,
                'type_name'=>$quote_price_arr[$id]['name'],
                'price'=>$quote_price_arr[$id]['price'],
                'add_time'=>time()
            );
            $user_id = $this->session->userdata("user_id");
            if ($user_id){
                $data['user_id'] = $user_id;
            }
            $this->Quote_price_record_model->save($data);
            printAjaxSuccess('success','系统已将结果经短信发送至您手机');
        }
    }


    //发送短信
    public function http_request($url, $data)
    {
        $data = http_build_query($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public function signmd5($appId,$secretKey,$timestamp){
        return md5($appId.$secretKey.$timestamp);
    }

    public function sendSMS($mobile,$name,$price,$area)
    {
        $YM_SMS_ADDR = "http://shmtn.b2m.cn";
        $YM_SMS_APPID = "6SDK-EMY-6688-JDWUO";
        $YM_SMS_AESPWD = "99CBC0E048E3A37B";
        $YM_SMS_SEND_URI = "/simpleinter/sendSMS";
        $content = "【Hi未莱】您咨询的 {$area} ㎡ {$name}参考报价为 {$price} 左右，详情咨询：0571-86691616";
        $timestamp = date("YmdHis");
        $sign = $this->signmd5($YM_SMS_APPID,$YM_SMS_AESPWD,$timestamp);
        // 如果您的系统环境不是UTF-8，需要转码到UTF-8。如下：从gb2312转到了UTF-8
        // $content = mb_convert_encoding( $content,"UTF-8","gb2312");
        // 另外，如果包含特殊字符，需要对内容进行urlencode
        $data = array(
            "appId" => $YM_SMS_APPID,
            "timestamp" => $timestamp,
            "sign" => $sign,
            "mobiles" => "{$mobile}",
            "content" =>  $content,
            "customSmsId" => "10001",
//            "timerTime" => "",
//            "extendedCode" => "1234"
        );
        $url = $YM_SMS_ADDR.$YM_SMS_SEND_URI;
        $resobj = $this->http_request($url, $data);
        $resobj = json_decode($resobj);
        return $resobj->code;
    }

    //获取报价记录
    public function get_quote_price_record()
    {
        $item_list = $this->Quote_price_record_model->gets(array('add_time >'=>time()-3600*24));
        if ($item_list){
            foreach ($item_list as $key=>$value){
                $mobile = substr($value['mobile'],0,3).'****'.substr($value['mobile'],-4);
                $time = $this->tranTime($value['add_time']);
                $item_list[$key]['content'] = floatval($value['area'])."㎡ ".$value['type_name'].' '.$mobile.' '.$time.'获取了报价';
            }
        }

        printAjaxData(array('item_list'=>$item_list,'count'=>count($item_list)));
    }

    public function tranTime($time) {
        $rtime = date("m-d H:i",$time);
        $htime = date("H:i",$time);
        $time = time() - $time;
        if ($time < 60) {
            $str = '刚刚';
        }
        elseif ($time < 60 * 60) {
            $min = floor($time/60);
            $str = $min.'分钟前';
        }
        elseif ($time < 60 * 60 * 24) {
            $h = floor($time/(60*60));
            $str = $h.'小时前 ';
        }
        elseif ($time < 60 * 60 * 24 * 3) {
            $d = floor($time/(60*60*24));
            if($d==1)
                $str = '昨天 ';
            else
                $str = '前天 ';
        }
        else {
            $str = $rtime;
        }
        return $str;
    }

    //锦鲤
    public function get_koi_list()
    {
        $light_count = $this->Light_img_model->rowCount();
        $item_list = $this->Enroll_progress_model->gets3($light_count);
        if($item_list){
            foreach ($item_list as $key=>$value){
                $enroll_info = $this->Enroll_model->get2(array('enroll.id'=>$value['enroll_id']));
                $item_list[$key]['nickname'] = $enroll_info ? my_substr($enroll_info['nickname'],21) : '';
            }
        }
        printAjaxData(array('item_list'=>$item_list));
    }


    //获取小程序码
    public function get_wx_code()
    {
        $parent_id = $this->input->post('parent_id',TRUE);
        $user_id = $this->session->userdata("user_id");
//        $user_info = $this->User_model->get('*', array('user.id' => $user_id));
        $encode_user_id = $parent_id ? $parent_id : $user_id;

        $appid = 'wx5b6972e9b65eefc0';
        $appSecret = 'c7ef31e34df248d7403bfa3186051396';
        $json = $this->http_curl("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appSecret}");
        $obj = json_decode($json);
        if (isset($obj->errmsg)) {
            printAjaxError('fail', 'invalid appid!');
        }
        $access_token = $obj->access_token;
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$access_token}";
        $encode_user_id = urlencode($encode_user_id);
        $data = array(
            'page'=>"pages/product/product",
            'scene'=>"{$encode_user_id}",
            'width'=>430,
            'auto_color'=>false,
        );
        $data=json_encode($data);
        $result = $this->http_curl($url,$data);
        if (json_decode($result)){
            printAjaxError('fail', 'invalid result!');
        }
        $save_dir='uploads/wxcode/';
        if (!file_exists($save_dir)) {
            mkdir($save_dir, 0777, true);
        }
        $time = date('md',time());
        $file_name = $save_dir.$time.'_'.$user_id.".png";
        file_put_contents($file_name, $result);

        $tmp_image_arr = $this->_fliter_image_path($file_name);
        $path = $tmp_image_arr['path'];
        $path_thumb = $tmp_image_arr['path_thumb'];
        printAjaxData($path);

    }

    private function http_curl($url, $data = '')
    {
        //2初始化
        $ch = curl_init();
        //3.设置参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    //不验证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);    //不验证证书
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            //         'Content-Type: application/json',
            //         'Content-Length: ' . strlen($data))
            // );
        }
        //4.调用接口
        $res = curl_exec($ch);
        //5.关闭curl
        curl_close( $ch );
//        if(curl_errno($ch)){
//            return curl_error($ch);
//        }else{
//            //5.关闭curl
//        curl_close( $ch );
//        $arr = json_decode($res, true);
        return $res;
//        }
    }

    /* ==================================================================================== */

  //手机号登录
    public function bind_mobile() {

        if ($_POST) {
            $mobile = trim($this->input->post('mobile', TRUE));
            $smscode = $this->input->post('smscode', TRUE);
            $user_id = $this->input->post('user_id', TRUE);
            if (!$this->form_validation->required($mobile)) {
                printAjaxError('username', '请输入手机号');
            }
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $mobile)) {
                printAjaxError('username', '请输入正确的手机号');
            }
            if (!$this->form_validation->required($smscode)) {
                printAjaxError('smscode', '请输入短信验证码');
            }
            $timestamp = time();
            if (!$this->Sms_model->get('id', "smscode = '{$smscode}' and mobile = '{$mobile}' and add_time > {$timestamp} - 15*60")) {
                printAjaxError('smscode', '短信验证码错误或者已过期');
            }
            $user_info = $this->User_model->get('*', array('id'=>$user_id));
            if (!$user_info){
                printAjaxError('fail','登录异常，稍后重试');
            }
            if ($user_info['username'] && $user_info['mobile']){
                $session_id = $this->session->userdata['session_id'];
                $this->_set_session($user_info['id']);
                printAjaxData($this->_tmp_user_info($user_info['id'], $session_id));
            }else{
                $mobile_user_info = $this->User_model->get('*', array('username'=>$mobile));
                if ($mobile_user_info){
                    if ($this->User_model->save(array('wx_unionid' => $user_info['wx_unionid']),array('id'=>$mobile_user_info['id']))){
                        $this->User_model->delete(array('id'=>$user_id));
                        $session_id = $this->session->userdata['session_id'];
                        $this->_set_session($mobile_user_info['id']);
                        printAjaxData($this->_tmp_user_info($mobile_user_info['id'], $session_id));
                    }else{
                        printAjaxError('fail','登录失败');
                    }
                }else{
                    $password = substr($mobile,-6);
                    $addTime = time();
                    $data = array(
                        'username'=>$mobile,
                        'mobile'=>$mobile,
                        'add_time' => $addTime,
                        'login_time' => $addTime,
                        'password' => $this->_createPasswordSALT($mobile, $addTime, $password),
                    );
                    if (!$this->User_model->save($data,array('id'=>$user_id))){
                        printAjaxError('fail','登录失败');
                    }
                    $session_id = $this->session->userdata['session_id'];
                    $this->_set_session($user_info['id']);
                    printAjaxData($this->_tmp_user_info($user_info['id'], $session_id));
                }

            }

        }


    }

    /**
     * 手机校验接口
     * @param username 用户名
     * @param code 图片验证码
     * @param remember 服务协议 0=不同意 1=同意
     *
     * @return json
     */
    public function check_mobile() {
        if ($_POST) {
            $username = $this->input->post('username', TRUE);
//            $code = $this->input->post('code', TRUE);
            $remember = $this->input->post('remember', TRUE);
            if (!$this->form_validation->required($username)) {
                printAjaxError('username', '请输入手机号');
            }
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $username)) {
                printAjaxError('username', '请输入正确的手机号');
            }
//            if (!$this->form_validation->required($code)) {
//                printAjaxError('code', '请输入图片验证码');
//            }
            if (!$remember) {
                printAjaxError('remember', '请同意服务协议');
            }
//            $securitysecoder = new Securitysecoderclass();
//            if (!$securitysecoder->check(strtolower($code))) {
//                printAjaxError('code_fail', '图片验证码');
//            }
            if ($this->User_model->validateUnique($username)) {
                printAjaxError('username', '手机号已经存在，请换一个');
            }
            printAjaxSuccess('success', '手机号码验证成功');
        }
    }

    /**
     * 短信验证码校验
     * @param username 手机号
     * @param smscode 短信验证码
     * @return json
     */
    public function check_sms_code() {
        if ($_POST) {
            $username = trim($this->input->post('username', TRUE));
            $smscode = $this->input->post('smscode', TRUE);
            if (!$this->form_validation->required($username)) {
                printAjaxError('username', '请输入手机号');
            }
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $username)) {
                printAjaxError('username', '请输入正确的手机号');
            }
            if (!$this->form_validation->required($smscode)) {
                printAjaxError('smscode', '请输入短信验证码');
            }
            $timestamp = time();
            if (!$this->Sms_model->get('id', "smscode = '{$smscode}' and mobile = '{$username}' and add_time > {$timestamp} - 15*60")) {
                printAjaxError('smscode', '短信验证码错误或者已过期');
            }
            printAjaxSuccess('success', '短信验证码校验成功');
        }
    }

    /**
     * 注册接口
     * @param username 用户名
     * @param password 密码
     * @param ref_password 确认密码
     * @param smscode 短信验证码
     *
     * @return json
     */
    public function reg() {
        if ($_POST) {
            $username = trim($this->input->post('username', TRUE));
            $password = $this->input->post('password', TRUE);
            $refPassword = $this->input->post('ref_password', TRUE);
            $smscode = $this->input->post('smscode', TRUE);
            $push_cid = $this->input->post('push_cid', TRUE);
            if (!$this->form_validation->required($username)) {
                printAjaxError('username', '请输入手机号');
            }
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $username)) {
                printAjaxError('username', '请输入正确的手机号');
            }
            if ($this->User_model->validateUnique($username)) {
                printAjaxError('username', '手机号已经存在，请换一个');
            }
            if (!$this->form_validation->required($password)) {
                printAjaxError('password', '请输入登录密码');
            }
            if (!$this->form_validation->required($refPassword)) {
                printAjaxError('ref_password', '请输入确认密码');
            }
            if ($password != $refPassword) {
                printAjaxError('ref_password', '前后密码不一致');
            }
            if (!$this->form_validation->required($smscode)) {
                printAjaxError('smscode', '请输入短信验证码');
            }
            $timestamp = time();
            if (!$this->Sms_model->get('id', "smscode = '{$smscode}' and mobile = '{$username}' and add_time > {$timestamp} - 15*60")) {
                printAjaxError('smscode', '短信验证码错误或者已过期');
            }
            $addTime = time();
            $ip_arr = getUserIPAddress();
            $fields = array(
                'user_group_id' => 1,
                'username' => $username,
                'login_time' => time(),
                'ip' => $ip_arr[0],
                'ip_address' => $ip_arr[1],
                'password' => $this->_createPasswordSALT($username, $addTime, $password),
                'pay_password' => $this->_createPasswordSALT($username, $addTime, $password),
                'mobile' => $username,
                'add_time' => $addTime,
            );
            $user_id = $this->User_model->save($fields);
            if ($user_id) {
                $session_id = $this->session->userdata['session_id'];
                $this->_set_session($user_id);
                printAjaxData($this->_tmp_user_info($user_id, $session_id, $push_cid));
            } else {
                printAjaxError('fail', '注册失败！');
            }
        }
    }

    /**
     * 找回密码
     *
     * @param username 手机号
     * @param passwd 密码
     * @param ref_password 确认密码
     * @param smscode 短信验证码
     *
     * @return json
     */
    public function get_pass() {
        if ($_POST) {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);
            $refPassword = $this->input->post('ref_password', TRUE);
            $smscode = $this->input->post('smscode', TRUE);
            if (!$username) {
                printAjaxError('username', "手机号不能为空");
            }
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $username)) {
                printAjaxError('username', '请输入正确的手机号');
            }
            if (!$this->form_validation->required($password)) {
                printAjaxError('password', '请输入新密码');
            }
            if (!$this->form_validation->required($refPassword)) {
                printAjaxError('ref_password', '请输入确认密码');
            }
            if ($password != $refPassword) {
                printAjaxError('ref_password', '前后密码不一致');
            }
            if (!$smscode) {
                printAjaxError('smscode', "短信验证码不能为空");
            }
            $userInfo = $this->User_model->get('id,username', array('lower(username)' => strtolower($username)));
            if (!$userInfo) {
                printAjaxError('fail', "手机号不存在");
            }
            $timestamp = time();
            if (!$this->Sms_model->get('id', "smscode = '{$smscode}' and mobile = '{$username}' and add_time > {$timestamp} - 15*60")) {
                printAjaxError('smscode', '短信验证码错误或者已过期');
            }
            $fields = array(
                'password' => $this->User_model->getPasswordSalt($userInfo['username'], $refPassword)
            );
            if ($this->User_model->save($fields, array('id' => $userInfo['id']))) {
                printAjaxSuccess('success', '密码修改成功');
            } else {
                printAjaxError('fail', '密码修改失败');
            }
        }
    }

    /**
     * 获取短信验证码
     * @param username 手机号
     * @param code 验证码
     * @return json
     */
    public function get_reg_sms_code() {
        if ($_POST) {
            $type = $this->input->post('type', TRUE);
            $username = $this->input->post('username', TRUE);
//            $code = $this->input->post('code', TRUE);
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $username)) {
                printAjaxError('username', '请输入正确的手机号');
            }
//            if (!$this->form_validation->required($code)) {
//                printAjaxError('code', '请输入验证码');
//            }
//            $securitysecoder = new Securitysecoderclass();
//            if (!$securitysecoder->check(strtolower($code))) {
//                printAjaxError('code_fail', '验证码错误');
//            }
            if ($type == 'reg') {
                $count = $this->User_model->rowCount(array("username" => $username));
                if ($count) {
                    printAjaxError('username', '此手机号已被使用，请换一个');
                }
            } else if ($type == 'get_pass') {
                $count = $this->User_model->rowCount(array("username" => $username));
                if ($count == 0) {
                    printAjaxError('username', '您注册的手机号不存在!');
                }
            } else if ($type == 'change_mobile') {
                $count = $this->User_model->rowCount(array("mobile" => $username));
                if ($count) {
                    printAjaxError('username', '此手机已被使用');
                }
            } else if ($type == 'change_pay_pass') {
                $user_id = $this->_check_login();
                $user_info = $this->User_model->get('mobile', array('id' => $user_id));
                if (!$user_info['mobile']) {
                    printAjaxError('mobile', '您暂未绑定手机号,请先绑定手机号码!');
                }
                if ($user_info['mobile'] != $username) {
                    printAjaxError('username', '手机号错误');
                }
            } else if($type == 'bind_mobile'){

            } else {
                printAjaxError('type', 'type值异常!');
            }

            $add_time = time();
            $sms_info = $this->Sms_model->get('*', "mobile = '{$username}' and {$add_time} - add_time < 60  ");
            if ($sms_info) {
                printAjaxError('fail', '操作太频繁，请至少间隔一分钟再发');
            }
            $verify_code = getRandCode(4);
            $sms_content = "【日立】您的验证码是：{$verify_code} 请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
            /*             * *************************半小时限制**************************** */
            $cur_time = $add_time - 1800;
            //30分钟内最多5次
            $count = $this->Sms_model->rowCount("mobile = '{$username}' and add_time > {$cur_time} ");
            if ($count >= 4) {
                printAjaxError('fail', '半小时内只能发4次，等一下再来');
            }
            /*             * ************************一天限制*************************** */
            $start_time = strtotime(date('Y-m-d 00:00:00', $add_time));
            $end_time = strtotime(date('Y-m-d 23:59:59', $add_time));
            $count = $this->Sms_model->rowCount("mobile = '{$username}' and add_time > {$start_time} and add_time <= {$end_time} ");
            //同一手机一天最多20次
            if ($count >= 15) {
                printAjaxError('fail', '你的手机号发送验证码次数超限，请更换手机号或明天再来');
            }
            $fields = array(
                'mobile' => $username,
                'smscode' => $verify_code,
                'sms_content' => $sms_content,
                'add_time' => $add_time
            );
            if (!$this->Sms_model->save($fields)) {
                printAjaxError('fail', '发送验证码失败');
            }
            $reponse = $this->send_sms($username, $sms_content);
            if ($reponse > 0) {
                printAjaxSuccess('success', '验证码已经发送，注意查看手机短信');
            } else {
                printAjaxError('fail', '验证码发送失败，请重试');
            }
        }
    }

    //获取用户信息
    public function get_user_info() {
        $user_id = $this->_check_login();
        $user_info = $this->User_model->get('*', array('id' => $user_id));
        if ($user_info) {
            //头像
            $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
            $user_info['path'] = $tmp_image_arr['path'];
            $user_info['path_thumb'] = $tmp_image_arr['path_thumb'];
        }
        $strWhere = array('user_id'=>$user_id,'is_bond'=>1);
        $user_info['record_count'] = $this->Groupon_record_model->rowCount($strWhere);
        printAjaxData($user_info);
    }

    /**
     * 退出登录
     */
    public function logout() {
        $this->_delete_session();
        printAjaxSuccess('success', '退出成功');
    }

    /**
     * 获取产品分类
     * @return json
     */
    public function get_product_category_list($store_id = 0) {
        $item_list = $this->Product_category_model->menuTree($store_id);
         if($item_list){
            foreach($item_list as $key=>$item){
                $tmp_image_arr = $this->_fliter_image_path($item['path']);
                $item_list[$key]['path'] = $tmp_image_arr['path'];
                $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
                if($item['subMenuList']){
                     foreach($item['subMenuList'] as $subkey=>$subitem){
                         $tmp_image_arr = $this->_fliter_image_path($subitem['path']);
                         $item_list[$key]['subMenuList'][$subkey]['path'] = $tmp_image_arr['path'];
                         $item_list[$key]['subMenuList'][$subkey]['path_thumb'] = $tmp_image_arr['path_thumb'];
                    }
                }
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }

    /**
     * 获取筛选
     * @return json
     */
    public function get_product_select_list($parent_id = 0,$store_id = 0) {
        //品牌
        $brand_list = $this->Brand_model->gets('id, brand_name,path', array('display' => 1,'store_id' => $store_id));
        if ($brand_list) {
            foreach ($brand_list as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $brand_list[$key]['path'] = $tmp_image['path'];
                $brand_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        //风格
        $style_list = $this->Style_model->gets(array('display' => 1,'store_id' => $store_id));
        //材质
        $material_list = $this->Material_model->gets(array('display' => 1,'store_id' => $store_id));
        //分类
        $category_list = $this->Product_category_model->gets(array('display' => 1, 'parent_id' => $parent_id,'store_id' => $store_id));
        $current_category = NULL;
        if($parent_id){
            $current_category = $this->Product_category_model->get('*', array('display' => 1, 'id' => $parent_id,'store_id' => $store_id));
        }
        printAjaxData(array('style_list' => $style_list, 'brand_list' => $brand_list, 'material_list' => $material_list, 'category_list' => $category_list, 'current_category'=>$current_category));
    }

    /**
     * 获取店铺筛选
     * @return json
     */
    public function get_store_product_select_list($parent_id = 0,$store_id = 0) {
        //品牌
        $brand_list = $this->Brand_model->gets('id, brand_name,path', array('store_id' => $store_id));
        if ($brand_list) {
            foreach ($brand_list as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $brand_list[$key]['path'] = $tmp_image['path'];
                $brand_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        //风格
        $style_list = $this->Style_model->gets(array('store_id' => $store_id));
        //材质
        $material_list = $this->Material_model->gets(array('store_id' => $store_id));
        $category_list = $this->Product_category_model->gets(array( 'parent_id' => $parent_id,'store_id' => $store_id));

        printAjaxData(array('style_list' => $style_list, 'brand_list' => $brand_list, 'material_list' => $material_list, 'category_list' => $category_list));
    }



    /**
     * 产品列表
     *
     * @param number $max_id     若指定此参数，则返回ID小于或等于max_id的信息,相当于more
     * @param number $since_id   获取最新信息,相当于下拉刷新
     * @param number $per_page   每页数
     * @param number $page       页数
     */
    public function get_product_list($by = 'id', $order = 'desc', $max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $strWhere = "display = 1";
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        if ($_POST) {
            $store_id = $this->input->post('store_id', TRUE);
            $category_id_1 = $this->input->post('category_id_1', TRUE);
            $category_id_2 = $this->input->post('category_id_2', TRUE);
            $brand_name = $this->input->post('brand_name', TRUE);
            $style_name = $this->input->post('style_name', TRUE);
            $material_name = $this->input->post('material_name', TRUE);
            $low_price = floatval($this->input->post('low_price', TRUE));
            $high_price = floatval($this->input->post('high_price', TRUE));
            $keyword = $this->input->post('keyword', TRUE);
            $custom_attribute = $this->input->post('custom_attribute', TRUE);
            if ($store_id) {
                $strWhere .= " and store_id = '{$store_id}' ";
            }
            if ($store_id){
                if ($category_id_1) {
                    if ($category_id_2){
                        $strWhere .= " and id in (select product_id from product_category_ids where product_category_id = '{$category_id_2}' and parent_id = '{$category_id_1}')";
                    }else{
                        $strWhere .= " and id in (select product_id from product_category_ids where product_category_id = '{$category_id_1}' or parent_id = '{$category_id_1}')";
                    }
                }
            }else{
                if ($category_id_1) {
                    $strWhere .= " and category_id_1 = '{$category_id_1}' ";
                }
                if ($category_id_2) {
                    $strWhere .= " and category_id_2 = '{$category_id_2}' ";
                }
            }

            if ($brand_name) {
                $strWhere .= " and brand_name = '{$brand_name}' ";
            }
            if ($style_name) {
                $strWhere .= " and style_name = '{$style_name}' ";
            }
            if ($material_name) {
                $strWhere .= " and material_name = '{$material_name}' ";
            }
            if ($low_price && !$high_price) {
                $strWhere .= " and sell_price >= {$low_price} ";
            }
            if (!$low_price && $high_price) {
                $strWhere .= " and sell_price <= {$high_price} ";
            }
            if ($low_price && $high_price) {
                $strWhere .= " and (sell_price >= {$low_price} and sell_price <= {$high_price}) ";
            }
            if ($keyword) {
                $keyword_info = $this->Keyword_model->get('*', array('name' => $keyword));
                if ($keyword_info) {
                    $this->Keyword_model->save(array('hits' => $keyword_info['hits'] + 1), array('name' => $keyword_info['name']));
                } else {
                    $this->Keyword_model->save(array('name' => $keyword, 'hits' => 1));
                }
                $strWhere .= " and (title regexp '{$keyword}' or keyword regexp '{$keyword}')";
            }
            if($custom_attribute){
                $strWhere .= " and FIND_IN_SET('{$custom_attribute}', custom_attribute) ";
            }
        }

        //判断权限
        if (!$this->check_store_type()){
            $strWhere .= " and store_id not in (select id from store where producer_auth = 1 and id is not null)";
        }

        $item_count = $this->Product_model->rowCount($strWhere);
        $item_list = $this->Product_model->gets('id,title,path,sell_price,sales,store_id', $strWhere, $per_page, $per_page * ($page - 1), $by, $order);
        if ($item_list) {
            foreach ($item_list as $key => $item) {
                $tmp_image_arr = $this->_fliter_image_path($item['path']);
                $item_list[$key]['path'] = $tmp_image_arr['path'];
                $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
                $comment_num = $this->Comment_model->rowCount(array('product_id' => $item['id']));
                $item_list[$key]['coumment_num'] = $comment_num;
                $store = $this->Store_model->get('store_name',array('id'=>$item['store_id']));
                $item_list[$key]['store_name'] = $store ? $store['store_name'] : '';
            }
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Product_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Product_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Product_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'item_count' => $item_count,'max_id' => $max_id, 'is_next_page' => $is_next_page));
    }



    /**
     * 评论列表
     * @param number $product_id  产品id
     * @param number $max_id     若指定此参数，则返回ID小于或等于max_id的信息,相当于more
     * @param number $since_id   获取最新信息,相当于下拉刷新
     * @param number $per_page   每页数
     * @param number $page       页数
     * @return json
     */
    public function get_comment_list($product_id = 0, $store_id = 0, $max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $strWhere = "display = 1";
        if($product_id){
            $strWhere .= " and product_id = {$product_id}";
        }
        if($store_id){
            $strWhere .= " and store_id = {$store_id}";
        }
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        //评论列表
        $item_list = $this->Comment_model->gets('username,content,user_id,add_time', $strWhere, $per_page, $per_page * ($page - 1));
        if ($item_list) {
            foreach ($item_list as $key => $ls) {
                $user_info = $this->User_model->get('path', array('id' => $ls['user_id']));
                $tmp_image_arr = $this->_fliter_image_path($user_info['path']);
                $item_list[$key]['username'] = hideStar($ls['username']);
                $item_list[$key]['add_time'] = date('Y-m-d', $ls['add_time']);
                $item_list[$key]['path'] = $tmp_image_arr['path'];
                $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
            }
        }

        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Comment_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Comment_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Comment_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page));
    }

//加入购物车
    public function add_cart() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $product_id = $this->input->post('product_id', TRUE);
            $color_id = $this->input->post('color_id', TRUE);
            $size_id = $this->input->post('size_id', TRUE);
            $buy_number = $this->input->post('buy_number', TRUE);
            $color_name = '';
            $size_name = '';
            $sell_price = '';
            if (!$this->form_validation->required($product_id)) {
                printAjaxError('fail', '产品id不存在');
            }
            $item_info = $this->Product_model->get('color_size_open, stock, sell_price, store_id, color_size_open, product_color_name, product_size_name', array('id' => $product_id, 'display' => 1));
            if (!$item_info) {
                printAjaxError('fail', '此商品不存在或被删除');
            }
            if ($item_info['color_size_open']) {
                if (!$this->form_validation->required($color_id)) {
                    printAjaxError('fail', '请选择' . $item_info['product_color_name']);
                }
                $color_list = $this->Product_model->getDetailColor($product_id);
                if ($color_list) {
                    foreach ($color_list as $key => $value) {
                        if ($value['color_id'] == $color_id) {
                            $color_name = $value['color_name'];
                            break;
                        }
                    }
                }
                if (!$color_name) {
                    printAjaxError('fail', '此' . $item_info['product_color_name'] . '不存在');
                }
                if (!$this->form_validation->required($size_id)) {
                    printAjaxError('fail', '请选择' . $item_info['product_size_name']);
                }
                $sizeList = $this->Product_model->getDetailSize($product_id);
                if ($sizeList) {
                    foreach ($sizeList as $key => $value) {
                        if ($value['size_id'] == $size_id) {
                            $size_name = $value['size_name'];
                        }
                    }
                }
                if (!$size_name) {
                    printAjaxError('fail', '此' . $item_info['product_size_name'] . '不存在');
                }
            }
            if (!$this->form_validation->integer($buy_number)) {
                printAjaxError('fail', '请填写正确的购买数量');
            }
            if ($buy_number < 1) {
                printAjaxError('fail', '购买数量必须大于零');
            }

            //有规格的产品
            if ($item_info['color_size_open']) {
                $product_stock_info = $this->Product_model->getProductStock($product_id, $color_id, $size_id);
                if (!$product_stock_info) {
                    printAjaxError('fail', '没有此规格的商品');
                }
                $sell_price = $product_stock_info['price'];
                if ($buy_number > $product_stock_info['stock']) {
                    printAjaxError('fail', '购买数量不能大于库存');
                }
            } else {
                //没有规格的产品
                $color_id = 0;
                $size_id = 0;
                $sell_price = $item_info['sell_price'];
                //没有规格的产品
                if ($buy_number > $item_info['stock']) {
                    printAjaxError('fail', '购买数量不能大于库存');
                }
            }
            $strWhere = array(
                'user_id' => $user_id,
                'product_id' => $product_id,
                'size_id' => $size_id,
                'color_id' => $color_id
            );
            $cartInfo = $this->Cart_model->get('buy_number,id', $strWhere);
            //已购买的
            if ($cartInfo) {
                $edit_fields = array(
                    'buy_number' => $buy_number + $cartInfo['buy_number']
                );
                if ($this->Cart_model->save($edit_fields, $strWhere)) {
                    $cart_count = $this->Cart_model->rowSum(array('user_id' => $user_id));
                    printAjaxData(array('cart_count'=>$cart_count, 'cart_ids'=>$cartInfo['id']));
                } else {
                    printAjaxError('fail', '加入购物车失败');
                }
            } else {//第一次购买的
                $fields = array(
                    'user_id' => $user_id,
                    'store_id' => $item_info['store_id'],
                    'product_id' => $product_id,
                    'size_name' => $size_name,
                    'size_id' => $size_id,
                    'color_name' => $color_name,
                    'color_id' => $color_id,
                    'buy_number' => $buy_number,
                    'sell_price' => $sell_price
                );
                $ret_id = $this->Cart_model->save($fields);
                if ($ret_id) {
                    $total = $this->Cart_model->get_total('user_id = ' . $user_id);
                    $cart_count = $this->Cart_model->rowSum(array('user_id' => $user_id));
                    printAjaxData(array('total' => $total, 'cart_count' => $cart_count,'cart_ids'=>$ret_id));
                } else {
                    printAjaxError('fail', '加入购物车失败');
                }
            }
        }
    }

    /*
     * 购物车列表
     */

    public function get_cart_list() {
        $user_id = $this->_check_login();
        //按店铺分组显示
        $item_list = $this->Cart_model->gets_store_list(array("cart.user_id" => $user_id));
        if ($item_list) {
            foreach ($item_list as $key => $value) {
                $cart_list = $this->Cart_model->gets(array("cart.user_id" => $value['user_id'], "cart.store_id" => $value['store_id']));
                foreach ($cart_list as $k => $item) {
                    unset($cart_list[$k]['weight']);
                    unset($cart_list[$k]['product_num']);
                    unset($cart_list[$k]['give_score']);
                    unset($cart_list[$k]['consume_score']);
                    unset($cart_list[$k]['user_id']);
                    unset($cart_list[$k]['store_id']);
                    $tmp_image_arr = $this->_fliter_image_path($item['path']);
                    $cart_list[$k]['path'] = $tmp_image_arr['path'];
                    $cart_list[$k]['path_thumb'] = $tmp_image_arr['path_thumb'];
                }
                $item_list[$key]['cart_list'] = $cart_list;
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }

    /**
     * 修改购物车购买数量
     * @param buy_number 购买数量
     * @param cart_id 购物车id
     * @return json
     */
    public function change_cart_buy_number() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $buy_number = intval($this->input->post('buy_number', TRUE));
            $cart_id = intval($this->input->post('cart_id', TRUE));
            if (!$buy_number || !$cart_id) {
                printAjaxError('fail', '操作异常，刷新重试');
            }
            if ($buy_number <= 0) {
                printAjaxError('buy_number', '购买数量不能小于或等于0');
            }
            $item_info = $this->Cart_model->get2(array("cart.id" => $cart_id));
            if (!$item_info) {
                printAjaxError('fail', '修改信息不存在，刷新重试');
            }
            if ($item_info['color_size_open']) {
                //有规格的商品
                $product_stock_info = $this->Product_model->getProductStock($item_info['product_id'], $item_info['color_id'], $item_info['size_id']);
                if (!$product_stock_info) {
                    printAjaxError('fail', '没有此规格的商品，请删除');
                }
                if ($buy_number > $product_stock_info['stock']) {
                    printAjaxError('fail', "此款商品库存不足，库存为：{$product_stock_info['stock']}");
                }
            } else {
                if ($buy_number > $item_info['stock']) {
                    printAjaxError('fail', "此款商品库存不足，库存为：{$item_info['stock']}");
                }
            }
            if (!$this->Cart_model->save(array('buy_number' => $buy_number), array('id' => $cart_id, 'user_id' => $user_id))) {
                printAjaxError('fail', '数量修改失败');
            }
            $total = $this->Cart_model->get_total('user_id = ' . $user_id);
            $cart_num = $this->Cart_model->rowSum(array('user_id' => $user_id));
            printAjaxData(array('total' => $total, 'cart_num' => $cart_num));
        }
    }

    /**
     * 删除购物车
     * @param ids 购物车id
     * @return json
     */
    public function delete_cart_product() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $ids = $this->input->post('ids', TRUE);
            if (!$ids) {
                printAjaxError('ids', '请选择删除项');
            }
            if ($this->Cart_model->delete("id in ($ids)")) {
                $total = $this->Cart_model->get_total('user_id = ' . $user_id);
                $cart_num = $this->Cart_model->rowSum(array('user_id' => $user_id));
                printAjaxData(array('total' => $total, 'cart_num' => $cart_num));
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //收货地址列表
    public function get_user_address_list() {
        $user_id = $this->_check_login();
        $user_address_list = $this->User_address_model->gets('*', array('user_id' => $user_id));
        printAjaxData(array('item_list' => $user_address_list));
    }

    /**
     * 新增或修改收货地址
     *
     * @param number buyer_name  收货人
     * @param string mobile
     * @param number province_id
     * @param number area_id
     * @param number city_id
     * @param number default
     * @param number
     */
    public function add_user_address($id = 0) {
        $user_id = $this->_check_login();
        if ($_POST) {
            $buyer_name = $this->input->post('buyer_name', TRUE);
            $mobile = $this->input->post('mobile', TRUE);
            $zip = $this->input->post('zip', TRUE);
            $province_id = $this->input->post('province_id', TRUE);
            $city_id = $this->input->post('city_id', TRUE);
            $area_id = $this->input->post('area_id', TRUE);
            $address = $this->input->post('address', TRUE);
            $is_default = $this->input->post('is_default', TRUE);

            if (!$buyer_name) {
                printAjaxError('buyer_name', '姓名不能为空');
            }
            if (!$mobile) {
                printAjaxError('mobile', '手机号不能为空');
            }
            if (!$province_id) {
                printAjaxError('province_id', '选择省');
            }
            if (!$city_id) {
                printAjaxError('city_id', '选择市');
            }
            if (!$address) {
                printAjaxError('address', '请填写详细地址');
            }
            $txt_address_str = '';
            $area_info = $this->Area_model->get('name', array('id' => $province_id));
            if ($area_info) {
                $txt_address_str .= $area_info['name'];
            }
            $area_info = $this->Area_model->get('name', array('id' => $city_id));
            if ($area_info) {
                $txt_address_str .= ' ' . $area_info['name'];
            }
            $area_info = $this->Area_model->get('name', array('id' => $area_id));
            if ($area_info) {
                $txt_address_str .= ' ' . $area_info['name'];
            }
            $fields = array(
                'buyer_name' => $buyer_name,
                'mobile' => $mobile,
                'phone' => '',
                'zip' => '',
                'province_id' => $province_id,
                'city_id' => $city_id,
                'area_id' => $area_id,
                'txt_address' => $txt_address_str,
                'address' => $address,
                'is_default' => $is_default,
                'user_id' => $user_id,
            );
            //当收货地址为一个时，设为默认
            if ($this->User_address_model->rowCount(array('user_id' => $user_id)) == 0) {
                $fields['is_default'] = 1;
            }
            if ($this->User_address_model->rowCount(array('user_id' => $user_id)) > 10) {
                printAjaxError('fail', '最多只能设置十个收货地址');
            }
            if ($is_default == 1) {
                $this->User_address_model->save(array('is_default' => 0), array('user_id' => $user_id, 'is_default' => 1));
            }
            if ($this->User_address_model->save($fields, $id ? array('id' => $id) : NULL)) {
                printAjaxSuccess('success', '收货地址操作成功');
            } else {
                printAjaxError('fail', '收货地址操作失败');
            }
        }
    }

    /*
     * 删除收货地址
     * @param number $address_id  收货地址id
     */

    public function delete_user_address() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $address_id = $this->input->post('address_id', true);
            if (!$address_id) {
                printAjaxError('address_id', '收货地址id不能为空');
            }
            $result = $this->User_address_model->delete(array('id' => $address_id, 'user_id' => $user_id));
            if ($result) {
                printAjaxSuccess('success', '删除成功!');
            } else {
                printAjaxError('fail', '删除失败！');
            }
        }
    }

    //设置默认地址
    public function set_default_user_address() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $id = $this->input->post('id', TRUE);
            if (!$id) {
                printAjaxError('fail', '操作异常');
            }
            if (!$this->User_address_model->save(array('is_default' => 0), array('user_id' => $user_id, 'is_default' => 1))) {
                printAjaxError('fail', '操作异常');
            }
            $this->User_address_model->save(array('is_default' => 1), array('user_id' => $user_id, 'id' => $id));
            printAjaxSuccess('success', '操作成功');
        }
    }

    public function get_postage_way_info(){
        //判断是否登录
        $user_id = $this->_check_login();
        if ($_POST) {
            $postage_id = $this->input->post('postage_id', TRUE);
            $user_address_id = $this->input->post('user_address_id', TRUE);
            $store_cart_ids = $this->input->post('store_cart_ids', TRUE);
            $area_name = '';
            $weight_num_total = 0;

            $user_address_info = $this->User_address_model->get('id, province_id', array('id' => $user_address_id));
            if (!$user_address_info) {
                printAjaxError('fail', '收货地址不存在，请重新选择');
            }
            $area_info = $this->Area_model->get('name', array('id' => $user_address_info['province_id']));
            if ($area_info) {
                $area_name = $area_info['name'];
            }

            $postage_way_info = $this->Postage_way_model->get('id,charging_mode', array('id' => $postage_id,'display' => 1));
            if (!$postage_way_info) {
                printAjaxError('fail', '配送方式不存在，请重新选择');
            }

            $strWhere = "cart.user_id = {$user_id} and cart.id in ({$store_cart_ids}) ";
            $item_list = $this->Cart_model->gets($strWhere);
            if (!$item_list) {
                printAjaxError('fail', '购物车信息不存在');
            }

            $store_product_total = 0;
            foreach ($item_list as $value){
                if ($postage_way_info['charging_mode'] == 2){
                    $weight_num_total += $value['buy_number'] * $value['weight'];
                }else{
                    $weight_num_total += $value['buy_number'];
                }

                $store_product_total += $value['buy_number'] * $value['sell_price'];
            }

            $ret = $this->_get_postage_price($postage_id, $weight_num_total, $area_name);


            $store_total = number_format($store_product_total + $ret, '2', '.', '');
            printAjaxData(array('postage_price'=>$ret,'store_total'=>$store_total));
        }
    }

    //获取配送费用
    private function _get_postage_price($postage_way_id,$weight_num_total,$area_name) {
        $postage_way_info = $this->Postage_way_model->get('payer,charging_mode', array('id'=>$postage_way_id));
        //卖家承担运费
        if ($postage_way_info['payer'] == 2) {
            return 0;
        }
        $postage_price_info = NULL;
        $postage_price_info = $this->Postage_price_model->get('start_val,start_price,add_val,add_price', "postage_way_id = {$postage_way_id} and FIND_IN_SET('{$area_name}', area)");
        if (!$postage_price_info) {
            $postage_price_info = $this->Postage_price_model->get('start_val,start_price,add_val,add_price', "postage_way_id = {$postage_way_id} and area = '其它地区'");
        }

        //首重
        $add_price = 0;
        if ($weight_num_total <= $postage_price_info['start_val']) {
            $add_val = 0;
            $start_price = $postage_price_info['start_price'];
        } else {
            $add_val = $weight_num_total - $postage_price_info['start_val'];
            $start_price = $postage_price_info['start_price'];
        }
        //续重
        if ($postage_price_info['add_val'] > 0) {
            $add_price = ceil($add_val/$postage_price_info['add_val'])*$postage_price_info['add_price'];
        }

        $postage_price = number_format($start_price+$add_price, 2, '.', '');

        return $postage_price;
    }

    //购物车去结算
    public function cart_confirm() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $cart_ids = $this->input->post('cart_ids', TRUE);
            if (!$cart_ids) {
                printAjaxError('fail', '请选购商品');
            }
            //按店铺分组显示
            //优惠
            $discount_total = 0;
            //税费
            $taxation_total = 0;
            //商品
            $product_total = 0;
            //邮费
            $postage_price = 0;
            //总金额
            $total = 0;
            $item_list = $this->Cart_model->gets_store_list("cart.id in ({$cart_ids}) and cart.user_id = {$user_id}");
            if ($item_list) {
                foreach ($item_list as $key => $value) {
                    //优惠
                    $store_discount_total = 0;
                    //税费
                    $store_taxation_total = 0;
                    //商品
                    $store_product_total = 0;
                    //邮费
                    $store_postage_price = 0;
                    //店铺订单商品数
                    $store_cart_ids = '';
                    //总金额
                    $store_total = 0;
                    $cart_list = $this->Cart_model->gets("cart.id in ({$cart_ids}) and cart.user_id = {$user_id} and cart.store_id = {$value['store_id']}");
                    if ($cart_list) {
                        foreach ($cart_list as $k => $cart) {
                            unset($cart_list[$k]['user_id']);
                            unset($cart_list[$k]['store_id']);
                            unset($cart_list[$k]['product_num']);
                            unset($cart_list[$k]['give_score']);
                            unset($cart_list[$k]['consume_score']);
                            unset($cart_list[$k]['weight']);
                            $tmp_image_arr = $this->_fliter_image_path($cart['path']);
                            $cart_list[$k]['path'] = $tmp_image_arr['path'];
                            $cart_list[$k]['path_thumb'] = $tmp_image_arr['path_thumb'];
                            $store_product_total += $cart['buy_number'] * $cart['sell_price'];
                            $store_cart_ids .= $cart['id'].',';
                        }
                        if ($store_cart_ids) {
                            $store_cart_ids = substr($store_cart_ids, 0, -1);
                        }
                    }
                    $product_total += $store_product_total;
                    $discount_total += $store_discount_total;
                    $taxation_total += $store_taxation_total;
                    $postage_price += $store_postage_price;
                    $item_list[$key]['store_product_total'] = number_format($store_product_total, '2', '.', '');
                    $item_list[$key]['store_discount_total'] = number_format($store_discount_total, '2', '.', '');
                    $item_list[$key]['store_taxation_total'] = number_format($store_taxation_total, '2', '.', '');
                    $item_list[$key]['store_postage_price'] = number_format($store_postage_price, '2', '.', '');
                    $item_list[$key]['store_total'] = number_format($store_product_total + $store_taxation_total + $store_postage_price - $store_discount_total, '2', '.', '');
                    $item_list[$key]['cart_list'] = $cart_list;
                    //配送方式
                    $postage_way_list = $this->Postage_way_model->gets('*', array('display' => 1,'store_id'=>$value['store_id']));
                    $item_list[$key]['postage_way_list'] = $postage_way_list;
                    $item_list[$key]['store_cart_ids'] = $store_cart_ids;
                }
            }
            $total = number_format($product_total + $taxation_total + $postage_price - $discount_total, '2', '.', '');
            if (!$item_list) {
                printAjaxError('fail', '购物车无商品信息');
            }
            //用户收货地址
            $default_user_address = $this->User_address_model->gets('*', array('user_id' => $user_id, 'is_default' => 1));
            //配送方式
            $postage_way_list = $this->Postage_way_model->gets('*', array('display' => 1));
            printAjaxData(array('item_list' => $item_list, 'total' => $total, 'default_user_address' => $default_user_address, 'postage_way_list' => $postage_way_list));
        }
    }

    /**
     * 提交订单
     * @param number $select_user_address_id  收货地址id
     * @param string $store_remark  备注
     * @param string $store_postage_ids  配送方式
     * @param string $cart_ids  选购的商品
     */
    public function add_order_bac() {
        //判断是否登录
        $user_id = $this->_check_login();
        if ($_POST) {
            //备注
            $store_remark = $this->input->post('store_remark', TRUE);
            //配送方式
            $store_postage_ids = $this->input->post('store_postage_ids', TRUE);
            //收货地址ID
            $select_user_address_id = $this->input->post('select_user_address_id', TRUE);
            //选购商品
            $cart_ids = $this->input->post('cart_ids', true);
            if (!$select_user_address_id) {
                printAjaxError('fail', "请选择收货地址");
            }
            //用已经存在的收货地址
            $user_address_info = $this->User_address_model->get('*', array('id' => $select_user_address_id));
            if (!$user_address_info) {
                printAjaxError('fail', '此收货地址信息不存在，下单失败');
            }
            $store_postage_ids_arr = explode(',', $store_postage_ids);
//            if (!$store_postage_ids_arr) {
//                printAjaxError('fail', "请选择配送方式");
//            }
            if (!$cart_ids) {
                printAjaxError('fail', "您未选购商品，订单提交失败");
            }
            $user_info = $this->User_model->getInfo('*', array('id' => $user_id));
            if (!$user_info) {
                printAjaxError('fail', "您的账号不存在或被管理员删除");
            }
            //提交订单-每个店生成一个订单
            //按店铺分组显示
            //优惠
            $discount_total = 0;
            //税费
            $taxation_total = 0;
            //商品
            $product_total = 0;
            //邮费
            $postage_price = 0;
            //总金额
            $total = 0;
            //成功数量
            $store_succes_count = 0;
            $order_ids = '';
            $strWhere = "cart.id in ({$cart_ids}) and cart.user_id = {$user_id}";
            $item_list = $this->Cart_model->gets_store_list($strWhere);
            if ($item_list) {
                foreach ($item_list as $key => $value) {
//                    $postage_way_list = $this->Postage_way_model->gets('*', array('display' => 1,'store_id'=>$value['store_id']));
//                    if ($postage_way_list && empty($store_postage_ids_arr[$key])) {
//                        printAjaxError('fail', "请选择配送方式");
//                    }
                    $order_number = $this->_getUniqueOrderNumber();
                    //优惠
                    $store_discount_total = 0;
                    //税费
                    $store_taxation_total = 0;
                    //商品
                    $store_product_total = 0;
                    //邮费
                    $store_postage_price = 0;
                    $area_name = '';
                    $weight_num_total = 0;
                    //店铺订单商品数
                    $store_cart_ids = '';
                    //总金额
                    $store_total = 0;

                    //运费
                    $area_info = $this->Area_model->get('name', array('id' => $user_address_info['province_id']));
                    if ($area_info) {
                        $area_name = $area_info['name'];
                    }

                    $postage_way_list = $this->Postage_way_model->gets('*', array('display' => 1,'store_id'=>$value['store_id']));
                    $postage_way_info = array();
                    if ($postage_way_list) {
                        if (empty($store_postage_ids_arr[$key])){
                            printAjaxError('fail', "请选择配送方式");
                        }
                        $postage_way_info = $this->Postage_way_model->get('id,charging_mode', array('id' => $store_postage_ids_arr[$key],'display' => 1));
                        if (!$postage_way_info) {
                            printAjaxError('fail', '配送方式不存在，请重新选择');
                        }
                    }


                    $cart_list = $this->Cart_model->gets("cart.id in ({$cart_ids}) and cart.user_id = {$user_id} and cart.store_id = {$value['store_id']}");
                    if ($cart_list) {
                        foreach ($cart_list as $cart) {
                            $store_product_total += $cart['buy_number'] * $cart['sell_price'];
                            if ($postage_way_info){
                                if ($postage_way_info['charging_mode'] == 2){
                                    $weight_num_total += $cart['buy_number'] * $cart['weight'];
                                }else{
                                    $weight_num_total += $cart['buy_number'];
                                }
                            }

                        }

                    }

                    //运费
                    if ($postage_way_info){
                        $store_postage_price = $this->_get_postage_price($store_postage_ids_arr[$key], $weight_num_total, $area_name);
                    }


                    $product_total += $store_product_total;
                    $discount_total += $store_discount_total;
                    $taxation_total += $store_taxation_total;
                    $postage_price += $store_postage_price;
                    $store_total = number_format($store_product_total + $store_taxation_total + $store_postage_price - $store_discount_total, '2', '.', '');
                    $store_info = $this->Store_model->get('user_id',array('id'=>$value['store_id']));
                    /*                     * **************提交订单**************** */
                    $fields = array(
                        'user_id' => $user_info['id'],
                        'user_name' => $user_info['username'],
                        'seller_id' => $store_info['user_id'],
                        'store_id' => $value['store_id'],
                        'store_name' => $value['store_name'],
                        'order_number' => $order_number,
                        'postage_id' => 0,
                        'postage_title' => '',
                        'postage_price' => $store_postage_price,
                        'product_total' => $store_product_total,
                        'taxation_total' => $store_taxation_total,
                        'discount_total' => $store_discount_total,
                        'total' => $store_total,
                        'status' => 0,
                        'add_time' => time(),
                        'buyer_name' => $user_address_info['buyer_name'],
                        'country_id' => $user_address_info['country_id'],
                        'province_id' => $user_address_info['province_id'],
                        'city_id' => $user_address_info['city_id'],
                        'area_id' => $user_address_info['area_id'],
                        'txt_address' => $user_address_info['txt_address'],
                        'address' => $user_address_info['address'],
                        'phone' => $user_address_info['phone'],
                        'mobile' => $user_address_info['mobile'],
                        'remark' => empty($store_remark[$key]) ? '' : $store_remark[$key],
                    );
                    //添加订单
                    $ret_id = $this->Orders_model->save($fields);
                    if ($ret_id) {
                        /*                         * *************************添加订单详细信息*********************** */
                        $succes_count = 0;
                        if ($cart_list) {
                            foreach ($cart_list as $cart) {
                                //订单详情
                                $orders_detail_fields = array(
                                    'order_id' => $ret_id,
                                    'product_id' => $cart['product_id'],
                                    'product_num' => $cart['product_num'],
                                    'product_title' => $cart['title'],
                                    'buy_number' => $cart['buy_number'],
                                    'buy_price' => number_format($cart['sell_price'], 2, '.', ''),
                                    'size_name' => $cart['size_name'],
                                    'size_id' => $cart['size_id'],
                                    'color_name' => $cart['color_name'],
                                    'color_id' => $cart['color_id'],
                                    'path' => $cart['path'],
                                    'color_size_open' => $cart['color_size_open'],
                                    'product_color_name' => $cart['product_color_name'],
                                    'product_size_name' => $cart['product_size_name']
                                );
                                if ($this->Orders_detail_model->save($orders_detail_fields)) {
                                    $succes_count++;
                                }
                            }
                        }
                        if (($succes_count != count($cart_list)) || count($cart_list) == 0) {
                            //删除订单详细信息
                            $this->Orders_detail_model->delete("order_id in (" . $order_ids . $ret_id . ")");
                            //删除记录
                            $this->Orders_process_model->delete("order_id in (" . $order_ids . $ret_id . ")");
                            //删除已经添加进去的数据，保持数据统一性
                            $this->Orders_model->delete("id in (" . $order_ids . $ret_id . ")");
                            //下单失败，直接退出
                            break;
                        } else {
                            $store_succes_count++;
                            $order_ids .= $ret_id . ',';
                            //订单跟踪记录
                            $orders_process_fields = array(
                                'add_time' => time(),
                                'content' => "订单创建成功",
                                'order_id' => $ret_id,
                                'order_status' => 0,
                                'change_status' => 0
                            );
                            $this->Orders_process_model->save($orders_process_fields);
                        }
                    } else {
                        //失败，直接退出
                        if ($order_ids) {
                            //删除订单详细信息
                            $this->Orders_detail_model->delete("order_id in (" . substr($order_ids, 0, -1) . ")");
                            //删除记录
                            $this->Orders_process_model->delete("order_id in (" . substr($order_ids, 0, -1) . ")");
                            //删除已经添加进去的数据，保持数据统一性
                            $this->Orders_model->delete("id in (" . substr($order_ids, 0, -1) . ")");
                        }
                        break;
                    }
                }
            } else {
                printAjaxError('fail', '您选购的商品在购物车中不存在');
            }
            if ($store_succes_count != count($item_list)) {
                if ($order_ids) {
                    //删除订单详细信息
                    $this->Orders_detail_model->delete("order_id in (" . substr($order_ids, 0, -1) . ")");
                    //删除记录
                    $this->Orders_process_model->delete("order_id in (" . substr($order_ids, 0, -1) . ")");
                    //删除已经添加进去的数据，保持数据统一性
                    $this->Orders_model->delete("id in (" . substr($order_ids, 0, -1) . ")");
                }
                printAjaxError('fail', '下单失败，请重试');
            } else {
                //删除购物车数据
                $this->Cart_model->delete($strWhere);
            }
            if ($order_ids) {
                $order_ids = substr($order_ids, 0, -1);
            }
            if (count($item_list) > 1) {
                $order_list = $this->Orders_model->gets('id, order_number, total, store_name',"id in ({$order_ids})");
                printAjaxSuccess('go_to_pay_list', $order_list);
            } else {
                $order_info = $this->Orders_model->get('id, order_number, total', array('id'=>$order_ids));
                printAjaxSuccess('go_to_pay', $order_info);
            }
        }
    }

    /*
     * 订单列表
     *
     * @param type $s 订单状态
     * @param type $max_id 最大id
     * @param type $since_id
     * @param type $per_page
     * @param type $page
     */

//    public function get_order_list($status = 'all', $max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
//        $user_id = $this->_check_login();
//        $strWhere = "user_id = {$user_id} ";
//        if ($status != 'all') {
//            $strWhere .= " and status = {$this->_hideValue[$status]}";
//        }
//        if ($since_id) {
//            $strWhere .= " and id > {$since_id} ";
//        }
//        if ($max_id) {
//            $strWhere .= " and id <= {$max_id} ";
//        }
//        $order_list = $this->Orders_model->gets('id,seller_id,store_id,store_name,order_number,total, postage_price,status, add_time, is_comment_to_seller', $strWhere, $per_page, $per_page * ($page - 1));
//        if ($order_list) {
//            foreach ($order_list as $key => $order) {
//                $orderdetailList = $this->Orders_detail_model->gets('id,product_id,product_title,buy_number,buy_price,size_name,size_id,color_name,color_id,path,color_size_open,product_color_name,product_size_name', array('order_id' => $order['id']));
//                foreach ($orderdetailList as $k => $v) {
//                    $tmp_image_arr = $this->_fliter_image_path($v['path']);
//                    $orderdetailList[$k]['path'] = $tmp_image_arr['path'];
//                    $orderdetailList[$k]['path_thumb'] = $tmp_image_arr['path_thumb'];
//                }
//                $order_list[$key]['orderdetailList'] = $orderdetailList;
//                $order_list[$key]['status_format'] = $this->_status[$order['status']];
//                $order_list[$key]['add_time'] = date('Y-m-d H:i:s', $order['add_time']);
//            }
//        }
//
//        // 最大ID
//        // 第一次加载
//        if (!$max_id && !$since_id) {
//            $max_id = $this->Orders_model->get_max_id(NULL);
//        } else {
//            //下拉刷新
//            if (!$max_id && $since_id) {
//                $max_id = $this->Orders_model->get_max_id(NULL);
//            }
//        }
//        //是否有下一页
//        $cur_count = $per_page * ($page - 1) + count($order_list);
//        $total_count = $this->Orders_model->rowCount($strWhere);
//        $is_next_page = 0;
//        if ($total_count > $cur_count) {
//            $is_next_page = 1;
//        }
//        printAjaxData(array('item_list' => $order_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page, 'total_count' => $total_count));
//    }

    /*
     * 获取各状态订单数量
     */
    public function get_order_count($status = 'all'){
        $user_id = $this->_check_login();
        $strWhere = "user_id = {$user_id} ";
        if ($status != 'all') {
            $strWhere .= " and status = {$this->_hideValue[$status]}";
        }
        $order_count = $this->Orders_model->rowCount($strWhere);
        printAjaxData($order_count);
    }

    /*
     * 修改用户昵称
     */

    public function change_user_nickname() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $nickname = $this->input->post('nickname', TRUE);
            if (!$nickname) {
                printAjaxError('fail', '昵称不能为空');
            }
            $result = $this->User_model->save(array('nickname' => $nickname), array('id' => $user_id));
            if ($result) {
                printAjaxSuccess('success', '修改成功');
            } else {
                printAjaxSuccess('fail', '修改失败');
            }
        }
    }

    /*
     * 修改用户性别
     */

    public function change_user_gender() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $sex = intval($this->input->post('sex', TRUE));
            $result = $this->User_model->save(array('sex' => $sex), array('id' => $user_id));
            if ($result) {
                printAjaxSuccess('success', '修改成功');
            } else {
                printAjaxSuccess('fail', '修改失败');
            }
        }
    }

    /*
     * 修改密码
     */

    public function change_pass() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $old_password = $this->input->post('old_password', TRUE);
            $new_password = $this->input->post('new_password', TRUE);
            $con_password = $this->input->post('con_password', TRUE);
            //检测
            if (!$this->form_validation->required($old_password)) {
                printAjaxError('old_password', '旧密码不能为空');
            }
            if (!$this->form_validation->required($new_password)) {
                printAjaxError('new_password', '新密码不能为空');
            }
            if (!$this->form_validation->required($con_password)) {
                printAjaxError('con_password', '确认新密码不能为空');
            }

            if ($new_password != $con_password) {
                printAjaxError('con_password', '新密码前后不一致');
            }
            if (strlen($con_password) < 6) {
                printAjaxError('con_password', '密码长度不能小于6位');
            }

            //验证密码是否正确
            $user_info = $this->User_model->get('password, username', array('user.id' => $user_id));
            if (!$user_info) {
                printAjaxError('fail', '此用户不存在');
            }
            if ($user_info['password'] != $this->User_model->getPasswordSalt($user_info['username'], $old_password)) {
                printAjaxError('old_password', '旧密码错误');
            }
            if ($this->User_model->getPasswordSalt($user_info['username'], $con_password) == $user_info['password']) {
                printAjaxError('old_password', '新密码不能与旧密码一样');
            }
            $fields = array(
                'password' => $this->User_model->getPasswordSalt($user_info['username'], $new_password)
            );
            if ($this->User_model->save($fields, array('id' => $user_id))) {
                printAjaxSuccess('success', '密码修改成功');
            } else {
                printAjaxError('fail', '密码修改失败');
            }
        }
    }

    /*
     * 修改支付密码
     */

    public function change_pay_pass() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $smscode = $this->input->post('smscode', TRUE);
            $new_password = $this->input->post('new_password', TRUE);
            $con_password = $this->input->post('con_password', TRUE);
            //检测
            if (!$this->form_validation->required($smscode)) {
                printAjaxError('smscode', '短信验证码不能为空');
            }
            if (!$this->form_validation->required($new_password)) {
                printAjaxError('new_password', '新密码不能为空');
            }
            if (!$this->form_validation->required($con_password)) {
                printAjaxError('con_password', '确认新密码不能为空');
            }

            if ($new_password != $con_password) {
                printAjaxError('con_password', '新密码前后不一致');
            }
            if (strlen($con_password) < 6) {
                printAjaxError('con_password', '密码长度不能小于6位');
            }
            //验证短信验证码是否正确
            $user_info = $this->User_model->get('password, username,mobile', array('user.id' => $user_id));
            if (!$user_info) {
                printAjaxError('fail', '此用户不存在');
            }
            if (!$user_info['mobile']) {
                printAjaxError('fail', '暂未绑定手机号码，请先绑定');
            }
            $timestamp = time();
            if (!$this->Sms_model->get('id', "smscode = '$smscode' and mobile = '{$user_info['mobile']}' and add_time > $timestamp - 15*60")) {
                printAjaxError('smscode', '短信验证码错误或者已过期');
            }
            $fields = array(
                'pay_password' => $this->User_model->getPasswordSalt($user_info['username'], $new_password)
            );
            if ($this->User_model->save($fields, array('id' => $user_id))) {
                printAjaxSuccess('success', '支付密码修改成功');
            } else {
                printAjaxError('fail', '支付密码修改失败');
            }
        }
    }

    /*
     * 获取商品推荐
     */

    public function get_member_product_list() {
        $item_list = $this->Product_model->gets('title,sell_price,id,path', null, 6, 0);
        if ($item_list) {
            foreach ($item_list as $key => $item) {
                $tmp_image_arr = $this->_fliter_image_path($item['path']);
                $item_list[$key]['path'] = $tmp_image_arr['path'];
                $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }

    //获取消息列表
    public function get_message_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        //判断是否登录
        $user_id = $this->_check_login();
        $strWhere = "to_user_id = {$user_id}";
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        $item_list = $this->Message_model->gets('*', $strWhere, $per_page, $per_page * ($page - 1));
        foreach ($item_list as $key => $item) {
            if ($item['message_type'] == 'order') {
                $item_list[$key]['message_type'] = '订单消息';
            }
            if ($item['message_type'] == 'system') {
                $item_list[$key]['message_type'] = '系统消息';
            }
            $item_list[$key]['add_time'] = date('Y/m/d H:i');
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Message_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Message_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Message_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page));
    }

    //删除消息
    public function delete_message($id = 0) {
        $user_id = $this->_check_login();
        $id = intval($id);
        if (empty($id)) {
            printAjaxError('id', 'id不能为空');
        }
        $result = $this->Message_model->delete(array('id' => $id, 'to_user_id' => $user_id));
        if ($result) {
            printAjaxSuccess('success', '删除成功');
        } else {
            printAjaxError('fail', '删除失败');
        }
    }

    //删除收藏
    public function delete_favorite($type = 'product', $id = 0, $item_id = 0) {
        $user_id = $this->_check_login();
        if (!$id && !$item_id) {
            printAjaxError('fail', '参数不能为空');
        }
        if($item_id){
            $item_info =$this->Favorite_model->get2($type, array('favorite.type'=>$type, 'favorite.item_id' => $item_id, 'favorite.user_id' => $user_id));
        }else{
            $item_info =$this->Favorite_model->get2($type, array('favorite.type'=>$type, 'favorite.id' => $id, 'favorite.user_id' => $user_id));
        }
        if (!$item_info) {
            printAjaxError('fail', '删除项不存在，删除失败');
        }
        if($item_id){
            $ret = $this->Favorite_model->delete(array('item_id'=>$item_info['item_id']));
        }else{
            $ret = $this->Favorite_model->delete(array('id'=>$item_info['id']));
        }
        if ($ret) {
            printAjaxSuccess('success', '删除成功');
        } else {
            printAjaxError('fail', '删除失败');
        }
    }

    //获取商场列表
    public function get_market_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $strWhere = "display = 1";
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        //商场
        $item_list = $this->Market_model->gets($strWhere, $per_page, $per_page * ($page - 1));
        if ($item_list) {
            foreach ($item_list as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $item_list[$key]['path'] = $tmp_image['path'];
                $item_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Market_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Market_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Market_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page));
    }

    //获取商场详情
    public function get_market_detail($id = 0) {
        $id = intval($id);
        if (empty($id)) {
            printAjaxError('id', '商场id不能为空');
        }
        $item_info = $this->Market_model->get('*', array('id' => $id));
        if ($item_info) {
            $tmp_image = $this->_fliter_image_path($item_info['path']);
            $item_info['path'] = $tmp_image['path'];
            $item_info['path_thumb'] = $tmp_image['path_thumb'];
            //商场顶部广告图
            $top_attachment_list = NULL;
            if ($item_info['batch_path_ids_top']) {
                $tmp_atm_ids = preg_replace(array('/^_/', '/_$/', '/_/'), array('', '', ','), $item_info['batch_path_ids_top']);
                $top_attachment_list = $this->Attachment_model->gets2($tmp_atm_ids);
                if($top_attachment_list){
                    foreach($top_attachment_list as $key=>$item){
                        $tmp_image = $this->_fliter_image_path($item['path']);
                        $top_attachment_list[$key]['path'] = $tmp_image['path'];
                        $top_attachment_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
                    }
                }
                $item_info['top_attachment_list'] = $top_attachment_list;
            }
            //商场首页轮播图
            $attachment_list = NULL;
            if ($item_info['batch_path_ids']) {
                $tmp_atm_ids = preg_replace(array('/^_/', '/_$/', '/_/'), array('', '', ','), $item_info['batch_path_ids']);
                $attachment_list = $this->Attachment_model->gets2($tmp_atm_ids);
                if($attachment_list){
                    foreach($attachment_list as $key=>$item){
                        $tmp_image = $this->_fliter_image_path($item['path']);
                        $attachment_list[$key]['path'] = $tmp_image['path'];
                        $attachment_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
                    }
                }
                $item_info['attachment_list'] = $attachment_list;
            }
            //商场底部广告图
            $bottom_attachment_list = NULL;
            if ($item_info['batch_path_ids_bottom']) {
                $tmp_atm_ids = preg_replace(array('/^_/', '/_$/', '/_/'), array('', '', ','), $item_info['batch_path_ids_bottom']);
                $bottom_attachment_list = $this->Attachment_model->gets2($tmp_atm_ids);
                if($bottom_attachment_list){
                    foreach($bottom_attachment_list as $key=>$item){
                        $tmp_image = $this->_fliter_image_path($item['path']);
                        $bottom_attachment_list[$key]['path'] = $tmp_image['path'];
                        $bottom_attachment_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
                    }
                }
                $item_info['bottom_attachment_list'] = $bottom_attachment_list;
            }
        }
        printAjaxData($item_info);
    }

    //获取所在地
    public function get_site_list() {
        $site_list = $this->advdbclass->get_site_list(null);
        printAjaxData(array('item_list' => $site_list));
    }

    //获取店铺列表
    public function get_store_list($auth_type = 0, $max_id = 0, $since_id = 0, $per_page = 1, $page = 1) {
        if ($auth_type == 2){
            $this->_check_login(true);
            if (!$this->check_store_type()){
                printAjaxError('fail','经认证的入驻商家方可进入，商家入驻请登录网站申请');
            }

        }
        $strWhere = "display = 1";
        $style_id = 0;
        $style_name = '';
        $store_category_id = 0;
        $store_category_name = '';
        $market_id = 0;
        $market_name = '';
        $market_info = NULL;
        $city = '';
        if ($_POST) {

            $style_id = $this->input->post('style_id', TRUE);
            if($style_id){
                $strWhere .= " and id in (select store_id from product where style_name = '{$style_name}')";
            }
            $store_category_id = $this->input->post('store_category_id', TRUE);
            if ($store_category_id) {
                $strWhere .= " and store_category_id = {$store_category_id}";
            }
            $search_word = trim($this->input->post('search_word', TRUE));
            if ($search_word) {
                $strWhere .= " and (business_scope regexp '{$search_word}' or store_name regexp '{$search_word}')";
            }
            $market_id = $this->input->post('market_id', TRUE);
            if ($market_id) {
                $strWhere .= " and market_id = {$market_id}";
                $market_info = $this->Market_model->get('*',array('id'=>$market_id));
                if ($market_info) {
                    $tmp_image = $this->_fliter_image_path($market_info['path']);
                    $market_info['path'] = $tmp_image['path'];
                    $market_info['path_thumb'] = $tmp_image['path_thumb'];
                }
            }
            $lat = $this->input->post('lat', TRUE);
            $lng = $this->input->post('lng', TRUE);
            $city = $this->input->post('city', TRUE);
            if (($lat && $lng) || $city){
                if (!$city){
                    $city = $this->get_address($lat,$lng);
                    $city = mb_substr($city, 0, -1, 'utf-8');
                }
                $strWhere .= " and txt_address regexp '{$city}'";
            }

        }
        if ($auth_type && !empty($this->_auth_type_arr[$auth_type])){
            $strWhere .= " and {$this->_auth_type_arr[$auth_type]} = 1";
        }
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
//        $item_list = $this->Store_model->gets($strWhere, $per_page, $per_page * ($page - 1));
        $strWhere1 = $strWhere." and find_in_set('h',custom_attribute)";
        $cus_list = $this->Store_model->gets($strWhere1);
        if (count($cus_list) < $per_page){
            $strWhere2 = $strWhere." and !find_in_set('h',custom_attribute)";
            if ($per_page*($page-1) == 0){
                $item_list1 = $this->Store_model->gets($strWhere2, $per_page-count($cus_list), $per_page*($page-1));
                $item_list = array_merge($cus_list,$item_list1);
            }else{
                $item_list = $this->Store_model->gets($strWhere2, $per_page, $per_page*($page-1)-count($cus_list));
            }
        }else{
            $remainder = count($cus_list) - $per_page*($page-1);
            $strWhere2 = $strWhere." and !find_in_set('h',custom_attribute)";
            if ($remainder >=  $per_page){
                $cus_list1 = array_slice($cus_list,$per_page*($page-1),$per_page);
                $item_list = $cus_list1;
            }else{
                if ($remainder > 0){
                    $cus_list2 = array_slice($cus_list,$per_page*($page-1),$remainder);
                    $item_list1 = $this->Store_model->gets($strWhere2, $per_page-$remainder);
                    $item_list = array_merge($cus_list2,$item_list1);
                }else{
                    $item_list = $this->Store_model->gets($strWhere2, $per_page, $per_page*($page-1)-count($cus_list));
                }
            }
        }
        if ($item_list) {
            foreach ($item_list as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $item_list[$key]['path'] = $tmp_image['path'];
                $item_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
                $tmp_image = $this->_fliter_image_path($item['store_banner']);
                $item_list[$key]['store_banner'] = $tmp_image['path_thumb'];
            }
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Store_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Store_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Store_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        //风格
        $style_list = $this->Style_model->gets(array('display' => 1));
        //分类
        $store_category_list = $this->Store_category_model->gets(array('display' => 1));
        //商场
        $market_list = $this->Market_model->gets(array('display' => 1));
        if ($market_list) {
            foreach ($market_list as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $market_list[$key]['path'] = $tmp_image['path'];
                $market_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        printAjaxData(array('item_list' => $item_list, 'market_info' => $market_info, 'style_list' => $style_list, 'store_category_list' => $store_category_list, 'market_list' => $market_list, 'city'=> $city, 'max_id' => $max_id, 'is_next_page' => $is_next_page));
    }

    /*
     * 获取店铺首页
     */

    public function get_store_home($store_id = 0) {
        $store_id = intval($store_id);
        if (empty($store_id)) {
            printAjaxError('store_id', '店铺id不能为空');
        }
        $item_info = $this->Store_model->get3(array('store.id' => $store_id, 'store.display' => 1));
        if (!$item_info) {
            printAjaxError('error', '此店铺不存在');
        }
        $tmp_image = $this->_fliter_image_path($item_info['store_banner']);
        $item_info['store_banner'] = $tmp_image['path'];
        $item_info['store_banner_thumb'] = $tmp_image['path_thumb'];
        $tmp_image = $this->_fliter_image_path($item_info['app_banner']);
        $item_info['app_banner'] = $tmp_image['path'];
        $item_info['app_banner_thumb'] = $tmp_image['path_thumb'];
        $tmp_image = $this->_fliter_image_path($item_info['path']);
        $item_info['path'] = $tmp_image['path'];
        $item_info['path_thumb'] = $tmp_image['path_thumb'];
        $item_info['des_grade'] = floatval($item_info['des_grade']);
        $item_info['serve_grade'] = floatval($item_info['serve_grade']);
        $item_info['express_grade'] = floatval($item_info['express_grade']);

        $ad_banner_list = $this->advdbclass->get_ad_store_list(1, $store_id, 10);
        foreach ($ad_banner_list as $key => $item) {
            $tmp_image = $this->_fliter_image_path($item['path']);
            $ad_banner_list[$key]['path'] = $tmp_image['path'];
            $ad_banner_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
        }
        $item_info['ad_banner_list'] = $ad_banner_list;

        $ad_store_list = $this->advdbclass->get_ad_store_list(2, $store_id, 10);
        foreach ($ad_store_list as $key => $item) {
            $tmp_image = $this->_fliter_image_path($item['path']);
            $ad_store_list[$key]['path'] = $tmp_image['path'];
            $ad_store_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
        }
        $item_info['ad_list'] = $ad_store_list;
        $cus_product_list = $this->advdbclass->get_cus_product_list('', 12, $store_id);
        foreach ($cus_product_list as $key => $item) {
            $tmp_image = $this->_fliter_image_path($item['path']);
            $cus_product_list[$key]['path'] = $tmp_image['path'];
            $cus_product_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
        }
        $item_info['cus_product_list'] = $cus_product_list;

        $item_info['brand_list'] = $this->Brand_model->gets('id,brand_name', array('store_id'=>$store_id));
        unset($item_info['owner_card']);
        unset($item_info['real_name_auth']);
        unset($item_info['producer_auth']);
        unset($item_info['retailer_auth']);
        unset($item_info['username']);
        $item_info['product_count'] = $this->Product_model->rowCount(array('store_id'=>$store_id));
        $item_info['favorite_count'] = $this->Favorite_model->rowCount('store',array('item_id'=>$store_id,'type'=>'store'));
        $user_id = $this->session->userdata("user_id");
        $item_info['is_favorite'] = $this->Favorite_model->rowCount('store',array('item_id'=>$store_id,'type'=>'store','favorite.user_id'=>$user_id));
        printAjaxData($item_info);
    }

    //我的店铺
    public function get_my_store(){
        $user_id = $this->_check_login(true);
        $item_info = $this->Store_model->get('id,store_name,path,store_banner,description', array('user_id'=>$user_id));
        if(!$item_info){
            printAjaxError('fail', '您还不是商家');
        }
        $path_arr = $this->_fliter_image_path($item_info['path']);
        $store_banner_arr = $this->_fliter_image_path($item_info['store_banner']);
        $item_info['path'] = $path_arr['path'];
        $item_info['path_thumb'] = $path_arr['path_thumb'];
        $item_info['store_banner'] = $store_banner_arr['path'];
        $item_info['store_banner_thumb'] = $store_banner_arr['path_thumb'];
        printAjaxData(array('id'=>$item_info['id'],'store_name'=>$item_info['store_name'],'path'=>$item_info['path'],'path_thumb'=>$item_info['path_thumb'],'store_banner'=>$item_info['store_banner'],'store_banner_thumb'=>$item_info['store_banner_thumb'],'description'=>$item_info['description']));
    }

    //商家修改店铺信息
    public function change_store_info() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_name = $this->input->post('stroe_name', TRUE);
            $description = $this->input->post('description', TRUE);
            if (empty($store_name) && empty($description)) {
                printAjaxError('fail', '店铺名称和店铺介绍不能都为空');
            }
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            if ($store_name) {
                $result = $this->Store_model->save(array('store_name' => $store_name), array('id' => $store_info['id']));
            }
            if ($description) {
                $result = $this->Store_model->save(array('description' => $description), array('id' => $store_info['id']));
            }
            if ($result) {
                printAjaxSuccess('success', '修改成功');
            } else {
                printAjaxError('fail', '修改失败');
            }
        }
    }

    /**
     * 商品收藏列表
     *
     * @param number $max_id     若指定此参数，则返回ID小于或等于max_id的信息,相当于more
     * @param number $since_id   获取最新信息,相当于下拉刷新
     * @param number $per_page   每页数
     * @param number $page       页数
     */
    public function get_product_favorite_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $user_id = $this->_check_login();
        $strWhere = "favorite.user_id = {$user_id} and favorite.type = 'product'";
        if ($since_id) {
            $strWhere .= " and favorite.id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and favorite.id <= {$max_id} ";
        }
        //分页
        $item_list = $this->Favorite_model->gets('product', $strWhere, $per_page, $per_page * ($page - 1));
        foreach ($item_list as $key => $value) {
            $tmp_image_arr = $this->_fliter_image_path($value['path']);
            $item_list[$key]['path'] = $tmp_image_arr['path'];
            $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
        }
        // 最大ID
//        $max_id = $this->Favorite_model->get_max_id('product', $strWhere);
//        if (!$max_id) {
//            if ($since_id) {
//                $max_id = $since_id;
//            }
//        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Favorite_model->get_max_id('product', NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Favorite_model->get_max_id('product', NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Favorite_model->rowCount('product', $strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'is_next_page' => $is_next_page, 'max_id' => $max_id, 'total_count' => $total_count));
    }

    /**
     * 店铺收藏列表
     *
     * @param number $max_id     若指定此参数，则返回ID小于或等于max_id的信息,相当于more
     * @param number $since_id   获取最新信息,相当于下拉刷新
     * @param number $per_page   每页数
     * @param number $page       页数
     */
    public function get_store_favorite_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $user_id = $this->_check_login();
        $strWhere = "favorite.user_id = {$user_id}  and favorite.type = 'store'";
        if ($since_id) {
            $strWhere .= " and favorite.id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and favorite.id <= {$max_id} ";
        }
        //分页
        $item_list = $this->Favorite_model->gets('store', $strWhere, $per_page, $per_page * ($page - 1));
        foreach ($item_list as $key => $value) {
            $tmp_image_arr = $this->_fliter_image_path($value['path']);
            $item_list[$key]['path'] = $tmp_image_arr['path'];
            $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
        }

        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Favorite_model->get_max_id('store', NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Favorite_model->get_max_id('store', NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Favorite_model->rowCount('store', $strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'is_next_page' => $is_next_page, 'max_id' => $max_id, 'total_count' => $total_count));
    }

    //商品浏览记录列表
    public function get_browse_product_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $user_id = $this->_check_login();
        $strWhere = "browse.user_id = {$user_id} ";
        if ($since_id) {
            $strWhere .= " and browse.id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and browse.id <= {$max_id} ";
        }
        //分页
        $item_list = $this->Browse_model->gets('product', $strWhere, $per_page, $per_page * ($page - 1));
        foreach ($item_list as $key => $value) {
            $tmp_image_arr = $this->_fliter_image_path($value['path']);
            $item_list[$key]['path'] = $tmp_image_arr['path'];
            $item_list[$key]['path_thumb'] = $tmp_image_arr['path_thumb'];
            $item_list[$key]['add_time'] = date('Y-m-d H:i:s', $value['add_time']);
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Browse_model->get_max_id('product', NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Browse_model->get_max_id('product', NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Browse_model->rowCount('product', $strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'is_next_page' => $is_next_page, 'max_id' => $max_id, 'total_count' => $total_count));
    }

    //收藏商品/店铺
    public function save_favorite() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $type = $this->input->post('type');
            $item_id = $this->input->post('item_id');
            if (!$item_id || !$type) {
                printAjaxError('fail', '操作异常，刷新重试');
            }
            $item_info = NULL;
            if ($type == 'store') {
                $item_info = $this->Store_model->get('favorite_num', array('id' => $item_id));
                if (!$item_info) {
                    printAjaxError('fail', '此店铺不存在，收藏失败');
                }
            } else {
                $item_info = $this->Product_model->get('favorite_num', array('id' => $item_id));
                if (!$item_info) {
                    printAjaxError('fail', '此商品不存在，收藏失败');
                }
            }

            $favorite_info = $this->Favorite_model->get('id', array('item_id' => $item_id, 'user_id' => $user_id, 'type' => $type));
            if ($favorite_info) {
                if ($this->Favorite_model->delete(array('id' => $favorite_info['id']))) {
                    if ($type == 'store') {
                        $this->Store_model->save(array('favorite_num' => $item_info['favorite_num'] - 1 > 0 ? $item_info['favorite_num'] - 1 : 0), array('id' => $item_id));
                    } else {
                        $this->Product_model->save(array('favorite_num' => $item_info['favorite_num'] - 1 > 0 ? $item_info['favorite_num'] - 1 : 0), array('id' => $item_id));
                    }
                    printAjaxData(array('action' => 'delete', 'id' => $favorite_info['id']));
                } else {
                    printAjaxError('fail', '收藏失败');
                }
            } else {
                $fields = array(
                    'item_id' => $item_id,
                    'type' => $type,
                    'user_id' => $user_id,
                    'add_time' => time()
                );
                $ret_id = $this->Favorite_model->save($fields);
                if ($ret_id) {
                    if ($type == 'store') {
                        $this->Store_model->save(array('favorite_num' => $item_info['favorite_num'] + 1), array('id' => $item_id));
                    } else {
                        $this->Product_model->save(array('favorite_num' => $item_info['favorite_num'] + 1), array('id' => $item_id));
                    }
                    printAjaxData(array('action' => 'add', 'id' => $ret_id));
                } else {
                    printAjaxError('fail', '收藏失败');
                }
            }
        }
    }

    //我的积分
    public function get_score_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $user_id = $this->_check_login();
        if (!$user_id) {
            printAjaxError('login', '请登录');
        }
        $strWhere = "user_id = " . $user_id;
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        $item_list = $this->Score_model->gets($strWhere, $per_page, $per_page * ($page - 1));
        if ($item_list) {
            foreach ($item_list as $key => $value) {
                $item_list[$key]['add_time'] = date('Y-m-d H:i:s', $value['add_time']);
            }
        }

        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Score_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Score_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Score_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        //积分余额
        $userInfo = $this->User_model->get('score', "id = {$user_id}");
        printAjaxData(array('item_list' => $item_list, 'score_balance' => $userInfo['score'], 'max_id' => $max_id, 'is_nex_page' => $is_next_page));
    }

    /**
     * 我的资金(分页)
     * @param type $max_id
     * @param type $since_id
     * @param type $per_page
     * @param type $page
     */
    public function get_financial_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $user_id = $this->_check_login();
        if (!$user_id) {
            printAjaxError('login', '请登录');
        }
        $strWhere = 'user_id = ' . $user_id;
        if ($since_id) {
            $strWhere .= " and financial.id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and financial.id <= {$max_id} ";
        }
        $item_list = $this->Financial_model->gets($strWhere, $per_page, $per_page * ($page - 1));
        foreach ($item_list as $key => $ls) {
            $item_list[$key]['add_time'] = date('Y-m-d H:i:s', $ls['add_time']);
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Financial_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Financial_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Financial_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        //账号余额
        $userInfo = $this->User_model->get('total', "id = {$user_id}");
        printAjaxData(array('item_list' => $item_list, 'total' => $userInfo['total'], 'max_id' => $max_id, 'is_next_page' => $is_next_page));
    }

    //获取订单详情
    public function get_order_detail($order_id = 0) {
        $user_id = $this->_check_login();
        $order_info = $this->Orders_model->get('id,store_id,store_name,order_number,payment_title,product_total,taxation_total,discount_total,total,postage_id,postage_title,postage_price,status,delivery_name,express_number,add_time,buyer_name,mobile,txt_address,address,is_comment_to_seller', array('id' => $order_id, 'user_id' => $user_id));
        if (empty($order_info)) {
            printAjaxError('fail', '不存在此订单信息');
        }
        $orders_detail = $this->Orders_detail_model->gets('id,buy_number,buy_price,size_name,size_id,color_name,color_id,path,product_title', array('order_id' => $order_id));
        foreach ($orders_detail as $key => $item) {
            $tmp_image = $this->_fliter_image_path($item['path']);
            $orders_detail[$key]['path'] = $tmp_image['path'];
            $orders_detail[$key]['path_thumb'] = $tmp_image['path_thumb'];
        }
        $order_info['orders_detail'] = $orders_detail;
        $orders_process = $this->Orders_process_model->gets('*', array('order_id' => $order_id));
        foreach ($orders_process as $k => $ls) {
            $orders_process[$k]['add_time'] = date('Y-m-d H:i:s', $ls['add_time']);
        }
        $order_info['order_process'] = $orders_process;
        $order_info['add_time'] = date('Y-m-d H:i:s', $order_info['add_time']);
        $order_info['status_format'] = $this->_status[$order_info['status']];
        printAjaxData($order_info);
    }

    //确认收货
    public function order_receipt() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $order_id = $this->input->post('order_id', TRUE);
            if (!$order_id) {
                printAjaxError('fail', "操作异常，刷新重试");
            }
            $ordersInfo = $this->Orders_model->get('id,user_id,status,score,order_number,divide_total,divide_store_price', array('id' => $order_id, 'user_id' => $user_id));
            if (!$ordersInfo) {
                printAjaxError('fail', "不存在此订单");
            }
            if ($ordersInfo['status'] != 2) {
                printAjaxError('fail', "此订单状态异常，确认收货失败");
            }
            $fields = array(
                'status' => 3
            );
            if ($this->Orders_model->save($fields, array('id' => $order_id))) {
                //订单记录跟踪(只修改状态，扣钱，是下线交易的)
                $fields = array(
                    'add_time' => time(),
                    'content' => '确认收货，交易成功',
                    'order_id' => $order_id
                );
                $this->Orders_process_model->save($fields);
                //积分记录操作
                if ($ordersInfo && $ordersInfo['score']) {
                    $userInfo = $this->User_model->getInfo('id, username, score', array('id' => $ordersInfo['user_id']));
                    if ($userInfo) {
                        if ($this->User_model->save(array('score' => $ordersInfo['score'] + $userInfo['score']), array('id' => $ordersInfo['user_id']))) {
                            $sFields = array(
                                'cause' => "订单交易成功--{$ordersInfo['order_number']}",
                                'score' => $ordersInfo['score'],
                                'balance' => $ordersInfo['score'] + $userInfo['score'],
                                'type' => 'product_in',
                                'add_time' => time(),
                                'username' => $userInfo['username'],
                                'user_id' => $userInfo['id'],
                                'ret_id' => $ordersInfo['id']
                            );
                            $this->Score_model->save($sFields);
                        }
                    }
                }
                //减库存与加销售量
                $orderdetailInfo = $this->Orders_detail_model->get('product_id, buy_number', array('order_id' => $order_id));
                if ($orderdetailInfo) {
                    $productInfo = $this->Product_model->get('stock, sales', array('id' => $orderdetailInfo['product_id']));
                    $stock = 0;
                    if ($productInfo['stock'] - $orderdetailInfo['buy_number'] > 0) {
                        $stock = $productInfo['stock'] - $orderdetailInfo['buy_number'];
                    }
                    $pFields = array(
                        'stock' => $stock,
                        'sales' => $productInfo['sales'] + $orderdetailInfo['buy_number']
                    );
                    $this->Product_model->save($pFields, array('id' => $orderdetailInfo['product_id']));
                }
                printAjaxSuccess('success', '操作成功！');
            } else {
                printAjaxError('fail', "操作失败！");
            }
        }
    }

    //订单评价
    public function order_comment_save($order_id = 0) {
        //判断是否登录
        $user_id = $this->_check_login();
        $order_id = intval($order_id);
        $order_info = $this->Orders_model->get('*', array('id' => $order_id, 'user_id' => $user_id, 'status' => 3, 'is_comment_to_seller' => 0));
        if (empty($order_info)) {
            printAjaxError('order_id', '不存在要评价的订单');
        }
        $user_info = $this->User_model->get('username,nickname', array('id' => $user_id));
        if ($_POST) {
            $orders_detail_id = $this->input->post('orders_detail_id', TRUE);
            $des_grade = intval($this->input->post('des_grade', TRUE));
            $serve_grade = intval($this->input->post('serve_grade', TRUE));
            $express_grade = intval($this->input->post('express_grade', TRUE));

            $orders_detail_id = explode('_',substr($orders_detail_id,0,-1));
            if (empty($des_grade)) {
                printAjaxError('des_grade', '请给宝贝与描述相符打分');
            }
            if (empty($serve_grade)) {
                printAjaxError('serve_grade', '请给卖家的服务态度打分');
            }
            if (empty($express_grade)) {
                printAjaxError('express_grade', '请给卖家发货的速度打分');
            }
            foreach ($orders_detail_id as $ls) {
                $evaluate = $this->input->post('evaluate_' . $ls, TRUE);
                $content = $this->input->post('content_' . $ls, TRUE);
                $batch_path_ids = $this->input->post('batch_path_ids_' . $ls, TRUE);
                $is_anonymous = $this->input->post('is_anonymous_' . $ls, TRUE);
                $order_detail = $this->Orders_detail_model->get('*', array('id' => $ls));
//                if($batch_path_ids){
//                    $batch_path_ids = implode('_', $batch_path_ids);
//                    $batch_path_ids .= '_';
//                }
                if (!$this->form_validation->required($evaluate)) {
                    printAjaxError('evaluate', '请选择好评、中评、差评的一项');
                }
                if ($evaluate == 2 || $evaluate == 3) {
                    if (!$this->form_validation->required($content)) {
                        printAjaxError('content', '选择中评、差评的一项评价不能为空');
                    }
                }
                $fields = array(
                    'order_number' => $order_info['order_number'],
                    'product_id' => $order_detail['product_id'],
                    'user_id' => $user_id,
                    'username' => $user_info['username'] ? $user_info['username'] : $user_info['nickname'],
                    'store_id' => $order_info['store_id'],
                    'content' => $content,
                    'product_title' => $order_detail['product_title'],
                    'path' => $order_detail['path'],
                    'batch_path_ids' => $batch_path_ids,
                    'evaluate' => $evaluate,
                    'is_anonymous' => $is_anonymous ? 1 : 0,
                    'add_time' => time(),
                );
                $result = $this->Comment_model->save($fields);
                if ($result) {
                    $store_info = $this->Store_model->get('evaluate_a,evaluate_b,evaluate_c,des_grade,serve_grade,express_grade', array('id' => $order_info['store_id']));
                    if ($evaluate == 1) {
                        $this->Store_model->save(array('evaluate_a' => $store_info['evaluate_a'] + 1), array('id' => $order_info['store_id']));
                    }
                    if ($evaluate == 2) {
                        $this->Store_model->save(array('evaluate_b' => $store_info['evaluate_b'] + 1), array('id' => $order_info['store_id']));
                    }
                    if ($evaluate == 3) {
                        $this->Store_model->save(array('evaluate_c' => $store_info['evaluate_c'] + 1), array('id' => $order_info['store_id']));
                    }
                }
            }

            $store_info = $this->Store_model->get('evaluate_a,evaluate_b,evaluate_c,des_grade,serve_grade,express_grade', array('id' => $order_info['store_id']));
            $data = array(
                'order_id'=>$order_id,
                'user_id'=>$user_id,
                'store_id'=>$order_info['store_id'],
                'add_time'=>time(),
                'des_grade'=>$des_grade,
                'serve_grade'=>$serve_grade,
                'express_grade'=>$express_grade,
                );
            $this->Comment_store_model->save($data);
            $this->Store_model->save(array('des_grade' => $store_info['des_grade'] + $des_grade, 'serve_grade' => $store_info['serve_grade'] + $serve_grade, 'express_grade' => $store_info['express_grade'] + $express_grade), array('id' => $order_info['store_id']));
            $this->Orders_model->save(array('is_comment_to_seller' => 1), array('id' => $order_id));
            printAjaxSuccess('success', '评价成功');
        }
    }

    //材质列表
    public function get_material_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Material_model->gets(array('store_id' => $store_info['id']));
        printAjaxData(array('item_list' => $item_list));
    }

    //编辑材质
    public function save_material($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_info = $this->Material_model->get('*', array('id' => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $material_name = $this->input->post('material_name', TRUE);
            $tag = $this->input->post('tag', TRUE);
            if (!$this->form_validation->required($material_name)) {
                printAjaxError('brand_name', '材质名称不能为空');
            }
            if (!$this->form_validation->max_length($material_name, 100)) {
                printAjaxError('title', '材质名称字数不能超过100字');
            }
            if ($id) {
                $fields = array(
                    'material_name' => $material_name,
                    'tag' => $tag,
                    'store_id' => $store_info['id'],
                );
                if ($this->Material_model->save($fields, array('id' => $id))) {
                    printAjaxSuccess('success', '修改成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            } else {
                $i = 0;
                $material_name = preg_replace(array('/^\|+/', '/\|+$/', '/｜/'), array('', '', '|'), $material_name);
                $titleArr = explode("|", $material_name);
                foreach ($titleArr as $key => $title) {
                    $fields = array(
                        'material_name' => $title,
                        'tag' => $tag,
                        'display' => 0,
                        'store_id' => $store_info['id'],
                    );
                    if ($this->Material_model->save($fields)) {
                        $i++;
                    }
                }
                if (count($titleArr) == $i) {
                    printAjaxSuccess('success', '添加成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            }
        }
        printAjaxData($item_info);
    }

    //删除材质
    public function delete_material() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!$this->form_validation->required($id)) {
                printAjaxError('fail', '请选择删除项');
            }
            $result = $this->Material_model->delete(array('id' => intval($id), 'store_id' => $store_info['id']));
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //风格列表
    public function get_style_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Style_model->gets(array('store_id' => $store_info['id']));
        printAjaxData(array('item_list' => $item_list));
    }

    //编辑风格
    public function save_style($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_info = $this->Style_model->get('*', array('id' => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $style_name = $this->input->post('style_name', TRUE);
            $tag = $this->input->post('tag', TRUE);
            if (!$this->form_validation->required($style_name)) {
                printAjaxError('style_name', '风格名称不能为空');
            }
            if (!$this->form_validation->max_length($style_name, 100)) {
                printAjaxError('style_name', '风格名称字数不能超过100字');
            }
            if ($id) {
                $fields = array(
                    'style_name' => $style_name,
                    'tag' => $tag,
                    'store_id' => $store_info['id'],
                );
                if ($this->Style_model->save($fields, array('id' => $id))) {
                    printAjaxSuccess('success', '修改成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            } else {
                $i = 0;
                $style_name = preg_replace(array('/^\|+/', '/\|+$/', '/｜/'), array('', '', '|'), $style_name);
                $titleArr = explode("|", $style_name);
                foreach ($titleArr as $key => $title) {
                    $fields = array(
                        'style_name' => $title,
                        'tag' => $tag,
                        'display' => 0,
                        'store_id' => $store_info['id'],
                    );
                    if ($this->Style_model->save($fields)) {
                        $i++;
                    }
                }
                if (count($titleArr) == $i) {
                    printAjaxSuccess('success', '添加成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            }
        }
    }

    //删除风格
    public function delete_style() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!$this->form_validation->required($id)) {
                printAjaxError('title', 'id不能为空');
            }
            $result = $this->Style_model->delete(array('id' => intval($id), 'store_id' => $store_info['id']));
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //面料列表
    public function get_fabric_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Fabric_model->gets(array('store_id' => $store_info['id']));
        printAjaxData(array('item_list' => $item_list));
    }

    //编辑面料
    public function save_fabric($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_info = $this->Fabric_model->get('*', array('id' => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $fabric_name = $this->input->post('fabric_name', TRUE);
            $tag = $this->input->post('tag', TRUE);
            if (!$this->form_validation->required($fabric_name)) {
                printAjaxError('fabric_name', '面料名称不能为空');
            }
            if (!$this->form_validation->max_length($fabric_name, 100)) {
                printAjaxError('fabric_name', '面料名称字数不能超过100字');
            }
            if ($id) {
                $fields = array(
                    'fabric_name' => $fabric_name,
                    'tag' => $tag,
                    'store_id' => $store_info['id'],
                );
                if ($this->Fabric_model->save($fields, array('id' => $id))) {
                    printAjaxSuccess('success', '修改成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            } else {
                $i = 0;
                $fabric_name = preg_replace(array('/^\|+/', '/\|+$/', '/｜/'), array('', '', '|'), $fabric_name);
                $titleArr = explode("|", $fabric_name);
                foreach ($titleArr as $key => $title) {
                    $fields = array(
                        'fabric_name' => $title,
                        'tag' => $tag,
                        'display' => 0,
                        'store_id' => $store_info['id'],
                    );
                    if ($this->Fabric_model->save($fields)) {
                        $i++;
                    }
                }
                if (count($titleArr) == $i) {
                    printAjaxSuccess('success', '添加成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            }
        }
    }

    //删除面料
    public function delete_fabric() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!$this->form_validation->required($id)) {
                printAjaxError('title', 'id不能为空');
            }
            $result = $this->Fabric_model->delete(array('id' => intval($id), 'store_id' => $store_info['id']));
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //皮革列表
    public function get_leather_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Leather_model->gets(array('store_id' => $store_info['id']));
        printAjaxData(array('item_list' => $item_list));
    }

    //编辑皮革
    public function save_leather($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_info = $this->Leather_model->get('*', array('id' => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $leather_name= $this->input->post('leather_name', TRUE);
            $tag = $this->input->post('tag', TRUE);
            if (!$this->form_validation->required($leather_name)) {
                printAjaxError('leather_name', '皮革名称不能为空');
            }
            if (!$this->form_validation->max_length($leather_name, 100)) {
                printAjaxError('leather_name', '皮革名称字数不能超过100字');
            }
            if ($id) {
                $fields = array(
                    'leather_name' => $leather_name,
                    'tag' => $tag,
                    'store_id' => $store_info['id'],
                );
                if ($this->Leather_model->save($fields, array('id' => $id))) {
                    printAjaxSuccess('success', '修改成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            } else {
                $i = 0;
                $leather_name = preg_replace(array('/^\|+/', '/\|+$/', '/｜/'), array('', '', '|'), $leather_name);
                $titleArr = explode("|", $leather_name);
                foreach ($titleArr as $key => $title) {
                    $fields = array(
                        'leather_name' => $title,
                        'tag' => $tag,
                        'display' => 0,
                        'store_id' => $store_info['id'],
                    );
                    if ($this->Leather_model->save($fields)) {
                        $i++;
                    }
                }
                if (count($titleArr) == $i) {
                    printAjaxSuccess('success', '添加成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            }
        }
    }

    //删除皮革
    public function delete_leather() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!$this->form_validation->required($id)) {
                printAjaxError('title', 'id不能为空');
            }
            $result = $this->Leather_model->delete(array('id' => intval($id), 'store_id' => $store_info['id']));
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //填充物列表
    public function get_filler_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Filler_model->gets(array('store_id' => $store_info['id']));
        printAjaxData(array('item_list' => $item_list));
    }

    //编辑填充物
    public function save_filler($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_info = $this->Filler_model->get('*', array('id' => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $filler_name = $this->input->post('filler_name', TRUE);
            $tag = $this->input->post('tag', TRUE);
            if (!$this->form_validation->required($filler_name)) {
                printAjaxError('filler_name', '风格名称不能为空');
            }
            if (!$this->form_validation->max_length($filler_name, 100)) {
                printAjaxError('filler_name', '风格名称字数不能超过100字');
            }
            if ($id) {
                $fields = array(
                    'filler_name' => $filler_name,
                    'tag' => $tag,
                    'store_id' => $store_info['id'],
                );
                if ($this->Filler_model->save($fields, array('id' => $id))) {
                    printAjaxSuccess('success', '修改成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            } else {
                $i = 0;
                $filler_name = preg_replace(array('/^\|+/', '/\|+$/', '/｜/'), array('', '', '|'), $filler_name);
                $titleArr = explode("|", $filler_name);
                foreach ($titleArr as $key => $title) {
                    $fields = array(
                        'filler_name' => $title,
                        'tag' => $tag,
                        'display' => 0,
                        'store_id' => $store_info['id'],
                    );
                    if ($this->Filler_model->save($fields)) {
                        $i++;
                    }
                }
                if (count($titleArr) == $i) {
                    printAjaxSuccess('success', '添加成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            }
        }
    }

    //删除填充物
    public function delete_filler() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!$this->form_validation->required($id)) {
                printAjaxError('title', 'id不能为空');
            }
            $result = $this->Filler_model->delete(array('id' => intval($id), 'store_id' => $store_info['id']));
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //导航列表
    public function get_navigation_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Navigation_model->gets(array('store_id' => $store_info['id']));
        printAjaxData(array('item_list' => $item_list));
    }

    //添加、修改、查询导航
    public function save_navigation($id = null) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_info = $this->Navigation_model->get('*', array('id' => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $title = $this->input->post('title', TRUE);
            $url = $this->input->post('url', TRUE);
            $sort = $this->input->post('sort', TRUE);
            $display = $this->input->post('display', TRUE);
            $content = $this->input->post('content');
            if (!$this->form_validation->required($title)) {
                printAjaxError('title', '导航名称不能为空');
            }
            if (!$this->form_validation->max_length($title, 20)) {
                printAjaxError('title', '导航名称字数不能超过20字');
            }
            if ($url && !preg_match('/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/', $url)) {
                printAjaxError('url', '导航链接格式错误');
            }
            if (!$this->form_validation->required($content)) {
                printAjaxError('content', '描述不能为空');
            }
            $fields = array(
                'title' => $title,
                'url' => $url,
                'sort' => intval($sort),
                'display' => $display ? 1 : 0,
                'content' => unhtml($content),
                'store_id' => $store_info['id']
            );
            $result = $this->Navigation_model->save($fields, $id ? array('id' => $id) : NULL);
            if ($result) {
                printAjaxSuccess('success', '提交成功');
            } else {
                printAjaxError('fail', '提交失败');
            }
        }
        printAjaxData($item_info);
    }

    //删除导航
    public function delete_navigation() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!$this->form_validation->required($id)) {
                printAjaxError('fail', '请选定删除项');
            }
            $result = $this->Navigation_model->delete(array('id' => intval($id), 'store_id' => $store_info['id']));
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //商品分类列表
    public function get_seller_category_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Product_category_model->menuTree($store_info['id']);
        if ($item_list) {
            foreach ($item_list as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $item_list[$key]['path'] = $tmp_image['path'];
                $item_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
                if ($item['subMenuList']) {
                    foreach ($item['subMenuList'] as $k => $subItem) {
                        $tmp_image = $this->_fliter_image_path($subItem['path']);
                        $item_list[$key]['subMenuList'][$k]['path'] = $tmp_image['path'];
                        $item_list[$key]['path_thumb'][$k]['path_thumb'] = $tmp_image['path_thumb'];
                    }
                }
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }

    //添加商品分类
    public function save_seller_category($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $parent_category_list = $this->Product_category_model->gets(array('store_id' => $store_info['id'], 'parent_id' => 0));
        $item_info = $this->Product_category_model->get('*', array('store_id' => $store_info['id'], 'id' => $id));
        if ($_POST) {
            $parent_id = $this->input->post('parent_id', TRUE);
            $product_category_name = $this->input->post('product_category_name', TRUE);
            $sort = $this->input->post('sort', TRUE);
            $path = $this->input->post('path', TRUE);
            if (!$this->form_validation->required($product_category_name)) {
                printAjaxError('product_category_name', '分类名称不能为空');
            }
            if ($id) {
                if ($parent_id == $id) {
                    printAjaxError('parent_id', '自己不能是自己的上级分类');
                }
            }
            if ($id) {
                $fields = array(
                    'parent_id' => $parent_id,
                    'product_category_name' => $this->input->post('product_category_name', TRUE),
                    'sort' => intval($sort),
                    'path' => $path,
                    'store_id' => $store_info['id'],
                );
                if ($this->Product_category_model->save($fields, array('id' => $id))) {
                    printAjaxSuccess('success', '提交成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            } else {
                $i = 0;
                $title = preg_replace(array('/^\|+/', '/\|+$/', '/｜/'), array('', '', '|'), $product_category_name);
                $titleArr = explode("|", $title);
                foreach ($titleArr as $key => $title) {
                    $fields = array(
                        'parent_id' => $parent_id,
                        'sort' => $sort + $key,
                        'product_category_name' => trim($title),
                        'path' => $path,
                        'store_id' => $store_info['id'],
                    );
                    if ($this->Product_category_model->save($fields)) {
                        $i++;
                    }
                }
                if (count($titleArr) == $i) {
                    printAjaxSuccess('success', '提交成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            }
        }
        if ($item_info) {
            $tmp_image = $this->_fliter_image_path($item_info['path']);
            $item_info['path'] = $tmp_image['path'];
            $item_info['path_thumb'] = $tmp_image['path_thumb'];
        }
        if ($parent_category_list) {
            foreach ($parent_category_list as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item_info['path']);
                $parent_category_list[$key]['path'] = $tmp_image['path'];
                $parent_category_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        printAjaxData(array('item_info' => $item_info, 'parent_category_list' => $parent_category_list));
    }

    //删除商家商品分类
    public function delete_seller_category() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!empty($id)) {
                $ids = $this->Product_category_model->getChildIds($id);
                if ($ids != $id) {
                    printAjaxError('fail', '删除失败，请先删除下级分类！');
                }
                $parent_id = $this->Product_category_model->get('parent_id', array('id'=>$id,'store_id'=>$store_info['id']));
                if($parent_id){
                    $product_info = $this->Product_category_ids_model->get('product_id',array('parent_id'=>$parent_id['parent_id'],'product_category_id'=>$id));
                    if($product_info){
                        printAjaxError('fail', '删除失败，此分类下还有商品未删除！');
                    }
                }
                if ($this->Product_category_model->delete("product_category.id in ({$id}) and store_id = {$store_info['id']}")) {
                    printAjaxData(array('id' => $id));
                }
            }
            printAjaxError('fail', '删除失败！');
        }
    }

    //商家中心->品牌列表
    public function get_seller_brand_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_list = $this->Brand_model->gets('*', array('store_id' => $store_info['id']));
        foreach ($item_list as $key => $item) {
            $tmp_image = $this->_fliter_image_path($item['path']);
            $item_list[$key]['path'] = $tmp_image['path'];
            $item_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
        }
        printAjaxData(array('item_list' => $item_list));
    }

    //商家中心->添加、修改、查询品牌
    public function save_seller_brand($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $item_info = $this->Brand_model->get('*', array('id' => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $brand_name = $this->input->post('brand_name', TRUE);
            $tag = $this->input->post('tag', TRUE);
            $path = $this->input->post('path', TRUE);
            if (!$this->form_validation->required($brand_name)) {
                printAjaxError('brand_name', '品牌名称不能为空');
            }
            if (!$this->form_validation->max_length($brand_name, 100)) {
                printAjaxError('title', '品牌名称字数不能超过100字');
            }
            if ($id) {
                $fields = array(
                    'brand_name' => $brand_name,
                    'tag' => $tag,
                    'path' => $path,
                    'store_id' => $store_info['id'],
                );
                if ($this->Brand_model->save($fields, array('id' => $id))) {
                    printAjaxSuccess('success', '修改成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            } else {
                $i = 0;
                $brand_name = preg_replace(array('/^\|+/', '/\|+$/', '/｜/'), array('', '', '|'), $brand_name);
                $titleArr = explode("|", $brand_name);
                foreach ($titleArr as $key => $title) {
                    $fields = array(
                        'brand_name' => $title,
                        'tag' => $tag,
                        'path' => $path,
                        'display' => 0,
                        'store_id' => $store_info['id'],
                    );
                    if ($this->Brand_model->save($fields)) {
                        $i++;
                    }
                }
                if (count($titleArr) == $i) {
                    printAjaxSuccess('success', '添加成功');
                } else {
                    printAjaxError('fail', "操作失败！");
                }
            }
        }
        if ($item_info) {
            $tmp_image = $this->_fliter_image_path($item_info['path']);
            $item_info['path'] = $tmp_image['path'];
            $item_info['path_thumb'] = $tmp_image['path_thumb'];
        }
        printAjaxData($item_info);
    }

    //商家中心->删除品牌
    public function delete_seller_brand() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $id = $this->input->post('id', TRUE);
            if (!$this->form_validation->required($id)) {
                printAjaxError('id', 'id不能为空');
            }
            $result = $this->Brand_model->delete(array('id' => intval($id), 'store_id' => $store_info['id']));
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //商家中心->广告列表
    public function get_seller_ad_list() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $ad_store_list_1 = $this->Ad_store_model->gets('*', array('position' => 1, 'store_id' => $store_info['id']));
        $ad_store_list_2 = $this->Ad_store_model->gets('*', array('position' => 2, 'store_id' => $store_info['id']));
        if ($ad_store_list_1) {
            foreach ($ad_store_list_1 as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $ad_store_list_1[$key]['path'] = $tmp_image['path'];
                $ad_store_list_1[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        if ($ad_store_list_2) {
            foreach ($ad_store_list_2 as $key => $item) {
                $tmp_image = $this->_fliter_image_path($item['path']);
                $ad_store_list_2[$key]['path'] = $tmp_image['path'];
                $ad_store_list_2[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        printAjaxData(array('ad_store_list_1' => $ad_store_list_1, 'ad_store_list_2' => $ad_store_list_2));
    }

    //商家中心->修改广告
    public function save_seller_ad() {
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $json = file_get_contents('php://input');
        if (empty($json)) {
            printAjaxError('fail', '提交的数据为空');
        }
        $item_list = json_decode($json, true);
        if (empty($item_list)) {
            printAjaxError('fail', '提交的数据为空');
        }
        foreach ($item_list as $ls) {
            if ($ls['url'] && strpos($ls['url'], preg_replace('/\/$/', '', base_url()), 0) === false) {
                printAjaxError('fail', '其中有一项不是本站地址');
            }
        }
        foreach ($item_list as $item) {
            $this->Ad_store_model->save(array('sort' => intval($item['sort']), 'ad_text' => $item['ad_text'], 'url' => $item['url']), array('id' => $item['id'], 'store_id' => $store_info['id']));
        }
        printAjaxSuccess('success', '保存成功');
    }

    //商家中心->删除店铺广告
    public function delete_seller_ad() {
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
            $ids = $this->input->post('ids');
            if (!$this->form_validation->required($ids)) {
                printAjaxError('fail', '请选定删除项');
            }
            $item_info = $this->Ad_store_model->gets('*', "id in ({$ids}) and store_id = {$store_info['id']}");
            if (empty($item_info)) {
                printAjaxError('fail', '不存在此项');
            }
            foreach ($item_info as $key => $value){
                unlink($value['path']);
                unlink(str_replace('.', '_thumb.', $value['path']));
            }
            $result = $this->Ad_store_model->delete("id in ({$ids}) and store_id = {$store_info['id']}");
            if ($result) {
                printAjaxSuccess('success', '删除成功');
            } else {
                printAjaxError('fail', '删除失败');
            }
        }
    }

    //获取商家订单列表
    public function get_seller_order_list($status = 'all', $max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $strWhere = "store_id = {$store_info['id']} ";
        if ($status != 'all') {
            $strWhere .= " and status = {$this->_hideValue[$status]}";
        }
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        $order_list = $this->Orders_model->gets('id, order_number,total, postage_price,status, add_time', $strWhere, $per_page, $per_page * ($page - 1));
        if ($order_list) {
            foreach ($order_list as $key => $order) {
                $orderdetailList = $this->Orders_detail_model->gets('product_id,product_title,buy_number,buy_price,size_name,size_id,color_name,color_id,path,color_size_open,product_color_name,product_size_name', array('order_id' => $order['id']));
                foreach ($orderdetailList as $k => $v) {
                    $tmp_image_arr = $this->_fliter_image_path($v['path']);
                    $orderdetailList[$k]['path'] = $tmp_image_arr['path'];
                    $orderdetailList[$k]['path_thumb'] = $tmp_image_arr['path_thumb'];
                }
                $order_list[$key]['orderdetailList'] = $orderdetailList;
                $order_list[$key]['status_format'] = $this->_status[$order['status']];
                $order_list[$key]['add_time'] = date('Y-m-d H:i:s', $order['add_time']);
            }
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Orders_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Orders_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($order_list);
        $total_count = $this->Orders_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $order_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page, 'total_count' => $total_count));
    }

    //修改价格
    public function seller_change_price(){
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        if ($_POST) {
            $id = $this->input->post('id', TRUE);
            $total = $this->input->post('total', TRUE);
            if (! $this->form_validation->required($total)) {
                printAjaxError('fail','修改价格不能为空！');
            }
            if (! $this->form_validation->numeric($total)) {
                printAjaxError('fail','修改价格必须为正确的金额！');
            }
            $count = $this->Orders_model->rowCount(array('id'=>$id,'store_id'=>$store_info['id']));
            if (!$count) {
                printAjaxError('fail','此订单不存在，修改价格失败！');
            }
            $fields = array(
                'total'=>$total
            );
            if ($this->Orders_model->save($fields, array('id'=>$id,'store_id'=>$store_info['id']))) {
                $fields = array(
                    'add_time'=>time(),
                    'content'=>'修改价格成功',
                    'order_id'=>$id
                );
                $this->Orders_process_model->save($fields);
                printAjaxSuccess('success', '修改价格成功！');
            } else {
                printAjaxError('fail',"修改价格失败！");
            }
        }
    }

    //交易关闭
    public function seller_close_order(){
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        if ($_POST) {
            $id = $this->input->post('id', TRUE);
            $cancelCause = $this->input->post('cancel_cause', TRUE);
            if (! $this->form_validation->required($cancelCause)) {
                printAjaxError('fail','请填写交易关闭的原因！');
            }
            $count = $this->Orders_model->rowCount(array('id'=>$id,'store_id'=>$store_info['id']));
            if (!$count) {
                printAjaxError('fail','此订单不存在，交易关闭失败！');
            }
            $fields = array(
                'cancel_cause'=>$cancelCause,
                'status'=>4
            );
            if ($this->Orders_model->save($fields, array('id'=>$id,'store_id'=>$store_info['id']))) {
                $fields = array(
                    'add_time'=>time(),
                    'content'=>'交易关闭',
                    'order_id'=>$id
                );
                $this->Orders_process_model->save($fields);
                printAjaxSuccess('success', '交易关闭成功！');
            } else {
                printAjaxError('fail',"交易关闭失败！");
            }
        }
    }

    //修改已付款
    public function seller_change_status_2(){
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        if ($_POST) {
            $id = $this->input->post('id', TRUE);

            $ordersInfo = $this->Orders_model->get('user_id, total, order_number', array('id' => $id, 'store_id' => $store_info['id']));
            if (!$ordersInfo) {
                printAjaxError('fail', "操作异常！");
            }
            $fields = array(
                'status' => 1
            );
            if ($this->Orders_model->save($fields, array('id' => $id))) {
                //财务记录

                $userInfo = $this->User_model->getInfo('username', array('id' => $ordersInfo['user_id']));
                if (!$userInfo) {
                    printAjaxError('fail', "操作异常！");
                }
                $this->load->model('Financial_model', '', TRUE);
                $fFields = array(
                    'cause' => "付款成功--{$ordersInfo['order_number']}",
                    'price' => -$ordersInfo['total'],
                    'add_time' => time(),
                    'username' => $userInfo['username']
                );
                $this->Financial_model->save($fFields);
                //订单跟踪记录
                $fields = array(
                    'add_time' => time(),
                    'content' => '付款成功',
                    'order_id' => $id
                );
                $this->Orders_process_model->save($fields);
                printAjaxSuccess('success', '操作成功！');
            } else {
                printAjaxError('fail', "操作失败！");
            }
        }
    }

    //发货
    public function seller_delivery() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        if ($_POST) {
            $id = $this->input->post('id', TRUE);
            $deliveryName = $this->input->post('delivery_name', TRUE);
            $expressNumber = $this->input->post('express_number', TRUE);
            if (! $this->form_validation->required($deliveryName)) {
                printAjaxError('fail','快递名称不能为空！');
            }
            if (! $this->form_validation->numeric($expressNumber)) {
                printAjaxError('fail','快递单号不能为空！');
            }
            $item_info = $this->Orders_model->get('user_id',array('id'=>$id, 'store_id' => $store_info['id']));
            if (!$item_info) {
                printAjaxError('fail','此订单不存在，发货失败！');
            }
            $exchange_info = $this->Exchange_model->get('*', array('orders_id'=>$id, 'user_id'=>$item_info['user_id']));
            if ($exchange_info) {
                if ($exchange_info['status'] >= 3) {
                    printAjaxError('fail', "此订单退款申请成功，不能完成下面的操作");
                } else {
                    if ($exchange_info['status'] != 1) {
                        printAjaxError('fail', "此订单退款申请审核中，不能完成下面的操作");
                    }
                }
            }
            $fields = array(
                'delivery_name'=>$deliveryName,
                'express_number'=>$expressNumber,
                'status'=>2
            );
            if ($this->Orders_model->save($fields, array('id'=>$id))) {
                $fields = array(
                    'add_time'=>time(),
                    'content'=>'开始发货',
                    'order_id'=>$id,
                    'change_status'=> 2
                );
                $this->Orders_process_model->save($fields);
                printAjaxSuccess('success', '发货成功！');
            } else {
                printAjaxError('fail',"发货失败！");
            }
        }

    }

    //确认收货
    public function seller_receiving() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        if ($_POST) {
            $id = $this->input->post('id', TRUE);

            if (!$id) {
                printAjaxError('fail', "操作异常，刷新重试");
            }
            $ordersInfo = $this->Orders_model->get('id, user_id,status,score, order_number, divide_total, divide_store_price', array('id' =>$id, 'store_id' => $store_info['id']));
            if (!$ordersInfo) {
                printAjaxError('fail', "不存在此订单");
            }
            if ($ordersInfo['status'] != 2) {
                printAjaxError('fail', "此订单状态异常，确认收货失败");
            }
            $exchange_info = $this->Exchange_model->get('*', array('orders_id'=>$id, 'user_id'=>$ordersInfo['user_id']));
            if ($exchange_info) {
                if ($exchange_info['status'] >= 3) {
                    printAjaxError('fail', "此订单退款申请成功，不能完成下面的操作");
                } else {
                    if ($exchange_info['status'] != 1) {
                        printAjaxError('fail', "此订单退款申请审核中，不能完成下面的操作");
                    }
                }
            }
            $fields = array(
                'status'=>3
            );
            if ($this->Orders_model->save($fields, array('id'=>$id))) {
                //订单记录跟踪(只修改状态，扣钱，是下线交易的)
                $fields = array(
                    'add_time'=>time(),
                    'content'=>'确认收货，交易成功',
                    'order_id'=>$id,
                    'change_status'=> 3
                );
                $this->Orders_process_model->save($fields);
                //积分记录操作
                if ($ordersInfo && $ordersInfo['score']) {
                    $userInfo = $this->User_model->getInfo('id, username, score', array('id'=>$ordersInfo['user_id']));
                    if ($userInfo) {
                        if ($this->User_model->save(array('score'=>$ordersInfo['score'] + $userInfo['score']), array('id'=>$ordersInfo['user_id']))) {
                            $sFields = array(
                                'cause' =>   "订单交易成功--{$ordersInfo['order_number']}",
                                'score' =>   $ordersInfo['score'],
                                'balance'=>  $ordersInfo['score'] + $userInfo['score'],
                                'type'=>     'product_in',
                                'add_time'=> time(),
                                'username' =>$userInfo['username'],
                                'user_id'=>  $userInfo['id'],
                                'ret_id'=>   $ordersInfo['id']
                            );
                            $this->Score_model->save($sFields);
                        }
                    }
                }
                //减库存与加销售量
                $orderdetailInfo = $this->Orders_detail_model->get('product_id, buy_number', array('order_id'=>$id));
                if ($orderdetailInfo) {
                    $productInfo = $this->Product_model->get('stock, sales', array('id'=>$orderdetailInfo['product_id']));
                    $stock = 0;
                    if ($productInfo['stock'] - $orderdetailInfo['buy_number'] > 0) {
                        $stock = $productInfo['stock'] - $orderdetailInfo['buy_number'];
                    }
                    $pFields = array(
                        'stock'=>$stock,
                        'sales'=>$productInfo['sales']+$orderdetailInfo['buy_number']
                    );
                    $this->Product_model->save($pFields, array('id'=>$orderdetailInfo['product_id']));
                }
                printAjaxSuccess('', '操作成功！');
            } else {
                printAjaxError("操作失败！");
            }
        }
    }

    //商家入驻
    public function jion_store($store_id = NULL) {
        $user_id = $this->_check_login();
        $item_info = NULL;
        if ($store_id) {
            $item_info = $this->Store_model->get('*', array('user_id' => $user_id));
            if ($item_info) {
                if ($item_info['display'] == 2 && $item_info['id'] != $store_id) {
                    printAjaxError('error', '操作异常');
                }
            }
        }
        if ($_POST) {
            $store_type = intval($this->input->post('store_type', TRUE));
            $store_name = trim($this->input->post('store_name', TRUE));
            $province_id = intval($this->input->post('province_id', TRUE));
            $city_id = intval($this->input->post('city_id', TRUE));
            $area_id = intval($this->input->post('area_id', TRUE));
            $address = $this->input->post('address', TRUE);
            $owner_name = $this->input->post('owner_name', TRUE);
            $owner_card = $this->input->post('owner_card', TRUE);
            $mobile = $this->input->post('mobile', TRUE);
            $im_qq = $this->input->post('im_qq', TRUE);
            $im_weixin = $this->input->post('im_weixin', TRUE);
            $im_ww = $this->input->post('im_ww', TRUE);

            $reg_num = trim($this->input->post('reg_num', TRUE));
            $license_store_name = trim($this->input->post('license_store_name', TRUE));
            $license_username = trim($this->input->post('license_username', TRUE));
            $license_form = trim($this->input->post('license_form', TRUE));
            $license_place = trim($this->input->post('license_place', TRUE));
            $license_credit_code = trim($this->input->post('license_credit_code', TRUE));
            $license_store_type = trim($this->input->post('license_store_type', TRUE));
            $license_residence = trim($this->input->post('license_residence', TRUE));
            $license_representative = trim($this->input->post('license_representative', TRUE));
            $license_capital = trim($this->input->post('license_capital', TRUE));
            $license_made_time = trim($this->input->post('license_made_time', TRUE));
            $license_time_limit = trim($this->input->post('license_time_limit', TRUE));
            $license_business_scope = trim($this->input->post('license_business_scope', TRUE));
            //检测用户是否已经入住店铺
            $store_info = $this->Store_model->get('display, close_reason', array('user_id' => $user_id));
            if ($store_info) {
                if ($store_info['display'] == 0) {
                    printAjaxError('fail', '您已提交店铺申请，正在审核中...');
                } else if ($store_info['display'] == 1) {
                    printAjaxError('fail', '您已入驻，不用重复申请');
                } else if ($store_info['display'] == 3) {
                    printAjaxError('fail', '您的店铺已被关闭，请联系网站客服');
                }
            }
            $store_info = $this->Store_model->get('id', "store_name = '{$store_name}' and user_id <> {$user_id} ");
            if ($store_info) {
                printAjaxError('fail', '此店铺名称已被使用，请换个试试');
            }
            if ($store_type < 1 || $store_type > 3) {
                printAjaxError('store_type', '请选择店铺类型');
            }
            if (!$this->form_validation->required($store_name)) {
                printAjaxError('store_name', '店铺名称不能为空');
            }
            if (!$province_id || !$city_id || !$area_id) {
                printAjaxError('area', '省市区不能为空');
            }
            if (!$this->form_validation->required($owner_name)) {
                printAjaxError('owner_name', '店主姓名不能为空');
            }
            if (!$this->form_validation->required($owner_card)) {
                printAjaxError('owner_card', '店主身份证号不能为空');
            }
            if (!preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/', $owner_name)) {
                printAjaxError('owner_name', '店主姓名只包含中文名，2-4位');
            }
            if (!$this->_checkIdentity($owner_card)) {
                printAjaxError('owner_card', '请填写正确的身份证号');
            }
            if (!$this->form_validation->required($mobile)) {
                printAjaxError('mobile', '手机号不能为空');
            }
            if (!preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/', $mobile)) {
                printAjaxError('mobile', '请填写正确手机号');
            }
            if ($im_qq && !preg_match('/^[1-9]\d{4,10}$/', $im_qq)) {
                printAjaxError('im_qq', '请填写正确qq号');
            }
            if ($im_weixin && !preg_match('/^[a-zA-Z\d_]{5,}$/', $im_weixin)) {
                printAjaxError('im_weixin', '请填写正确微信号');
            }
            if ($store_type == 1){
                if (!$this->form_validation->required($reg_num)) {
                    printAjaxError('reg_num', '注册号不能为空');
                }
                if (!$this->form_validation->required($license_store_name)) {
                    printAjaxError('license_store_name', '名称不能为空');
                }
                if (!$this->form_validation->required($license_username)) {
                    printAjaxError('license_username', '经营者姓名不能为空');
                }
            }elseif($store_type == 2){
                if (!$this->form_validation->required($license_credit_code)) {
                    printAjaxError('license_credit_code', '统一社会信用代码不能为空');
                }
                if (!$this->form_validation->required($license_store_name)) {
                    printAjaxError('license_store_name', '名称不能为空');
                }
            }
            $txt_address_str = '';
            $area_info = $this->Area_model->get('name', array('id' => $province_id));
            if ($area_info) {
                $txt_address_str .= $area_info['name'];
            }
            $area_info = $this->Area_model->get('name', array('id' => $city_id));
            if ($area_info) {
                $txt_address_str .= ' ' . $area_info['name'];
            }
            $area_info = $this->Area_model->get('name', array('id' => $area_id));
            if ($area_info) {
                $txt_address_str .= ' ' . $area_info['name'];
            }
            $fields = array(
                'store_type' => $store_type,
                'store_name' => $store_name,
                'province_id' => $province_id,
                'city_id' => $city_id,
                'area_id' => $area_id,
                'address' => unhtml($address),
                'owner_name' => $owner_name,
                'owner_card' => $owner_card,
                'mobile' => $mobile,
                'im_qq' => $im_qq,
                'im_weixin' => $im_weixin,
                'im_ww' => unhtml($im_ww),
                'txt_address' => $txt_address_str,
                'user_id' => $user_id,
                'display' => 0,
                'store_category_id' => 1,
                'store_grade_id' => 1,
                'add_time' => time(),
                'auth_store_type' => $store_type,
                'reg_num' => $reg_num,
                'license_store_name' => $license_store_name,
                'license_username' => $license_username,
                'license_form' => $license_form,
                'license_place' => $license_place,
                'license_credit_code' => $license_credit_code,
                'license_store_type' => $license_store_type,
                'license_residence' => $license_residence,
                'license_representative' => $license_representative,
                'license_capital' => $license_capital,
                'license_made_time' => $license_made_time,
                'license_time_limit' => $license_time_limit,
                'license_business_scope' => $license_business_scope,
            );
            $result = $this->Store_model->save($fields, $store_id ? array('id' => $store_id, 'user_id' => $user_id) : NULL);
            if ($result) {
                printAjaxSuccess('success_store', '您的资料提交成功，我们会尽快处理您的申请');
            } else {
                printAjaxError('fail', '保存失败');
            }
        }
        printAjaxData($item_info);
    }
    //商家商品管理
    public function get_seller_product_list($by = 'id', $order = 'desc', $max_id = 0, $since_id = 0, $per_page = 10, $page = 1){
        //判断是否登录
        $user_id = $this->_check_login(true);
        $strWhere = "display = 1";
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        if($store_info){
            $strWhere .= " and store_id = {$store_info['id']} ";
        }
        $item_list = $this->Product_model->gets('id,title,path,sell_price,sort,custom_attribute,keyword,abstract,style_name', $strWhere, $per_page, $per_page * ($page - 1), $by, $order);
        foreach ($item_list as $key => $item) {
            $tmp_image = $this->_fliter_image_path($item['path']);
            $item_list[$key]['path'] = $tmp_image['path'];
            $item_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Product_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Product_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Product_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page));

    }

    //发布商品
    public function save_seller_product($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        //产品详细
        $item_info = $this->Product_model->get('*', array("product.id" => $id, 'store_id' => $store_info['id']));
        if ($_POST) {
            $category_ids = $this->input->post('category_ids', TRUE);
            $product_category_id_arr = $this->input->post('product_category_id', TRUE);
            $title = $this->input->post('title', TRUE);
            $brand_name = $this->input->post('brand_name', TRUE);
            $style_name = $this->input->post('style_name', TRUE);
            $material_name = $this->input->post('material_name', TRUE);
            $path = $this->input->post('path', TRUE);
            $sell_price = $this->input->post('sell_price', TRUE);
            $stock = $this->input->post('stock', TRUE);
            $content = $this->input->post('content', TRUE);
            if (!$category_ids) {
                printAjaxError('fail', '请选择商品分类');
            }
            if (!$this->form_validation->required($title)) {
                printAjaxError('title', '商品标题不能为空');
            }
            if (!$product_category_id_arr) {
                printAjaxError('fail', '请选择本店分类');
            }
            if (!$this->form_validation->required($path)) {
                printAjaxError('path', '请上传封面图');
            }
            if (!$this->form_validation->required($stock)) {
                printAjaxError('stock', '库存不能为空');
            }
            if (intval($stock) <= 0) {
                printAjaxError('stock', '库存不能小于等于0');
            }
            $category_id_1 = 0;
            $category_id_2 = 0;
            $category_ids_arr = explode(',', $category_ids);
            if ($category_ids_arr) {
                if (count($category_ids_arr) >= 1) {
                    $category_id_1 = $category_ids_arr[0];
                }
                if (count($category_ids_arr) >= 2) {
                    $category_id_2 = $category_ids_arr[1];
                }
            }
            $fields = array(
                'store_id' => $store_info['id'],
                'category_id_1' => $category_id_1,
                'category_id_2' => $category_id_2,
                'brand_name' => $brand_name,
                'style_name' => $style_name,
                'material_name' => $material_name,
                'sell_price' => $sell_price,
                'stock' => intval($stock),
                'title' => $title,
                'content' => unhtml($content),
                'path' => $path,
                'display' => 1,
                'add_time' => time(),
                'display_time' => time(),
            );
            $retId = $this->Product_model->save($fields, $id ? array('id' => $id) : $id);
            if ($retId) {
                $retId = $id ? $id : $retId;
                /*                 * ****************添加本店分类ID******************** */
                $this->Product_category_ids_model->delete(array('product_id' => $retId));
                if ($product_category_id_arr) {
                    foreach ($product_category_id_arr as $key => $value) {
                        $id_arr = explode(",", $value);
                        if ($id_arr && count($id_arr) > 1) {
                            $pc_fields = array(
                                'parent_id' => $id_arr[0],
                                'product_category_id' => $id_arr[1],
                                'product_id' => $retId
                            );
                            $this->Product_category_ids_model->save($pc_fields);
                        }
                    }
                }
                printAjaxSuccess('success', '保存成功');
            } else {
                printAjaxError('fail', '保存失败');
            }
        }
        if ($item_info) {
            $tmp_image = $this->_fliter_image_path($item_info['path']);
            $item_info['path'] = $tmp_image['path'];
            $item_info['path_thumb'] = $tmp_image['path_thumb'];
        }
        printAjaxData($item_info);
    }

    //商家中心->删除商品
    public function delete_seller_product() {
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        if ($_POST) {
            $ids = $this->input->post('ids', TRUE);
            if (!$this->form_validation->required($ids)) {
                printAjaxError('title', '请选择您要删除的项');
            }
            //购物车
            $this->load->model('Cart_model', '', TRUE);
            if ($this->Cart_model->rowCount("product_id in ({$ids})")) {
                printAjaxError('购物车存在关联数据，删除失败！');
            }
            //订单详细
            $this->load->model('Orders_detail_model', '', TRUE);
            if ($this->Orders_detail_model->rowCount("product_id in ({$ids})")) {
                printAjaxError('订单详细存在关联数据，删除失败！');
            }
            if (!empty($ids)) {
                if ($this->Product_model->delete('id in (' . $ids . ') and store_id = ' . $store_info['id'])) {
                    //删除浏览记录
                    $this->Browse_model->delete("item_id in ({$ids}) and type = 'product'");
                    //删除收藏记录
                    $this->Favorite_model->delete("item_id in ({$ids}) and type = 'product'");
                    //删除属性
                    $this->Product_size_color_model->delete("product_id in ({$ids})");
                    printAjaxSuccess('success', '删除成功');
                }
            }
            printAjaxError('fail', '删除失败！');
        }
    }

    //评论详情
    public function get_comment_detail($id = 0) {
        $item_info = $this->Comment_model->get('*', array('id' => $id));
        if ($item_info) {
            $tmp_image = $this->_fliter_image_path($item_info['path']);
            $item_info['path'] = $tmp_image['path'];
            $item_info['path_thumb'] = $tmp_image['path_thumb'];
            $item_info['add_time'] = date('Y-m-d H:i:s', $item_info['add_time']);
        }
        printAjaxData($item_info);
    }

    public function change_mobile() {
        $user_id = $this->_check_login();
        if ($_POST) {
            $mobile = trim($this->input->post('mobile', TRUE));
//            $code = $this->input->post('code', TRUE);
            $smscode = $this->input->post('smscode', TRUE);
            $user_info = $this->User_model->get('*', "id = " . $user_id);
            if (!$this->form_validation->required($mobile)) {
                printAjaxError('mobile', '请输入手机号码');
            }
            if (!preg_match("/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|16|17|18|19)\d{9}$/", $mobile)) {
                printAjaxError('username', '手机号码格式不正确');
            }
//            if (!$this->form_validation->required($code)) {
//                printAjaxError('code', '请输入验证码');
//            }
//            $securitysecoder = new Securitysecoderclass();
//            if (!$securitysecoder->check(strtolower($code))) {
//                printAjaxError('code_fail', '图片验证码错误');
//            }
            $timestamp = time();
            if (!$this->Sms_model->get('id', "smscode = '$smscode' and mobile = $mobile and add_time > $timestamp - 15*60")) {
                printAjaxError('smscode', '短信验证码错误或者已过期');
            }
            $result = $this->User_model->save(array('mobile' => $mobile), array('id' => $user_id));
            if ($result) {
                printAjaxSuccess('success', '修改成功');
            } else {
                printAjaxError('fail', '修改失败');
            }
        }
    }

    public function update() {
        if ($_POST) {
            $platform = $this->input->post('platform', TRUE);
            $version = $this->input->post('version', TRUE);
            $wget_version = $this->input->post('wget_version', TRUE);
            $ret = array('is_update' => 0, 'update_url' => '');
            $item_info = $this->System_model->get('wget_version, wget_url, android_full_update_version, android_full_update_url, ios_full_update_version, ios_full_update_url', array('id' => 1));
            if ($platform == 'ios') {
                if ($item_info['ios_full_update_version'] > $version) {
                    $ret['is_update'] = 1;
                    $ret['update_url'] = $item_info['ios_full_update_url'];
                } else {
                    if ($item_info['wget_version'] > $wget_version) {
                        $ret['is_update'] = 2;
                        $ret['update_url'] = $item_info['wget_url'];
                    }
                }
            } else if ($platform == 'android') {
                if ($item_info['android_full_update_version'] > $version) {
                    $ret['is_update'] = 1;
                    $ret['update_url'] = $item_info['android_full_update_url'];
                } else {
                    if ($item_info['wget_version'] > $wget_version) {
                        $ret['is_update'] = 2;
                        $ret['update_url'] = $item_info['wget_url'];
                    }
                }
            }
            printAjaxData($ret);
        }
    }

    /**
     * 获取地区层级列表
     */
    public function get_area_list() {
        $item_list = $this->Area_model->gets("id as 'value', name as 'text', parent_id", array('parent_id' => 0, 'display' => 1));
        if ($item_list) {
            foreach ($item_list as $key => $value) {
                $sub_item_list = $this->Area_model->gets("id as 'value', name as 'text', parent_id", array('parent_id' => $value['value'], 'display' => 1));
                if ($sub_item_list) {
                    foreach ($sub_item_list as $s_key => $s_value) {
                        $sub_item_list[$s_key]['children'] = $this->Area_model->gets("id as 'value', name as 'text', parent_id", array('parent_id' => $s_value['value'], 'display' => 1));
                    }
                }
                $item_list[$key]['children'] = $sub_item_list;
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }

    /**
     * 获取首页广告
     */
    public function get_index_ad_list($id = 3){
        $item_list = $this->advdbclass->getAd($id, 10);
        if($item_list){
            foreach ($item_list as $key => $value){
                $tmp_image = $this->_fliter_image_path($value['path']);
                $item_list[$key]['path'] = $tmp_image['path'];
                $item_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }
    /**
     * 获取首页展位及商品
     */
    public function get_index_booths_list(){
        $is_check_store = 0;
        if ($this->check_store_type()){
            $is_check_store = 1;
        }
        $menu_tree_list = $this->advdbclass->getMenuList(294);
        if($menu_tree_list){
            foreach ($menu_tree_list as $key => $item){
                $left_ad_list = $this->advdbclass->getAd($this->_left_ad_id_arr[$key], 1);
                if($left_ad_list){
                    foreach ($left_ad_list as $list => $value){
                        $tmp_image = $this->_fliter_image_path($value['path']);
                        $left_ad_list[$list]['path'] = $tmp_image['path'];
                        $left_ad_list[$list]['path_thumb'] = $tmp_image['path_thumb'];
                    }
                }

                $product_list = $this->advdbclass->get_product_list($item['product_category_ids'], $item['id'], 4,'id',1,$is_check_store);
                if ($product_list){
                    foreach ($product_list as $k => $value){
                        $tmp_image = $this->_fliter_image_path($value['path']);
                        $product_list[$k]['path'] = $tmp_image['path'];
                        $product_list[$k]['path_thumb'] = $tmp_image['path_thumb'];
                    }
                }
                $menu_tree_list[$key]['left_ad_list'] = $left_ad_list;
                $menu_tree_list[$key]['product_list'] = $product_list;
            }
        }
        printAjaxData(array('item_list' => $menu_tree_list));
    }

    /**
     * 获取更多展位商品
     */
    public function get_booths_product_list($by = 'id', $order = 'desc', $max_id = 0, $since_id = 0, $per_page = 10, $page = 1){
        $strWhere = "display = 1";
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        if($_POST){
            $booth_id = $this->input->post('booth_id', TRUE);
            if ($booth_id) {
                $strWhere .= " and FIND_IN_SET('{$booth_id}', custom_attribute)  ";
            }
        }
        //判断权限
        if (!$this->check_store_type()){
            $strWhere .= " and store_id not in (select id from store where producer_auth = 1 and id is not null)";
        }
        $item_list = $this->Product_model->gets('id,title,path,sell_price,sales', $strWhere, $per_page, $per_page * ($page - 1), $by, $order);
        if ($item_list){
            foreach ($item_list as $k => $value){
                $tmp_image = $this->_fliter_image_path($value['path']);
                $item_list[$k]['path'] = $tmp_image['path'];
                $item_list[$k]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Product_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Product_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Product_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page));

    }
    /**
     * 猜您喜欢
     */
    public function get_cus_product_list($type = 'c', $num = 10, $store_id = 0){
        $item_list = $this->advdbclass->get_cus_product_list($type, $num, $store_id);
        if ($item_list){
            foreach ($item_list as $k => $value){
                $tmp_image = $this->_fliter_image_path($value['path']);
                $item_list[$k]['path'] = $tmp_image['path'];
                $item_list[$k]['path_thumb'] = $tmp_image['path_thumb'];
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }
    /**
     * *首页新闻轮播
     */
    public function get_cus_page_list(){
        $item_list = $this->advdbclass->get_cus_list('page', 173, '', 0, 5);
        if($item_list){
            foreach ($item_list as $key=>$value){
                $item_list[$key]['content'] = html($value['content']);
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }
    /*
     * 主题管理
     */
    public function get_themes_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1){
        $user_id = $this->_check_login(true);
        $strWhere = "display = 1";
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }

        $store_info = $this->Store_model->get2(array('user_id' => $user_id));
        if($store_info && $store_info['theme_ids']){
            $strWhere .= " and id in ({$store_info['theme_ids']}) ";
        }
        $item_list = $this->Theme_model->gets($strWhere, $per_page, $per_page * ($page - 1));
        if ($item_list) {
            foreach ($item_list as $key => $value) {
                if ($value['alias'] == $store_info['theme']) {
                    $item_list['cur_theme_info'] = $value;
                    break;
                }
            }
        }
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Theme_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Theme_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Theme_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page));

    }

    /*
     * 设置店铺主题模板
     */
    public function set_store_theme() {
        //判断是否登录
        $user_id = $this->_check_login(true);
        if ($_POST) {
            $theme = $this->input->post('theme', TRUE);
            if (!$theme) {
                printAjaxError('fail', '请选择设置的主题');
            }
            $is_yes = false;
            $store_info = $this->Store_model->get2(array('user_id' => $user_id));
            if ($store_info && $store_info['theme_ids']) {
                $item_list = $this->Theme_model->gets("id in ({$store_info['theme_ids']}) and display = 1");
                if ($item_list) {
                    foreach ($item_list as $key => $value) {
                        if ($value['alias'] == $theme) {
                            $is_yes = true;
                            break;
                        }
                    }
                }
            }
            if (!$is_yes) {
                printAjaxError('fali', '主题设置异常');
            }
            if (!$this->Store_model->save(array('theme' => $theme), array('user_id' => $user_id, 'id' => $store_info['id']))) {
                printAjaxError('fali', '主题设置失败，刷新重试');
            }
            printAjaxSuccess('success', '主题设置成功');
        }
    }
    /*
     * 店铺信誉
     */
    public function get_store_credit(){
        if($_POST){
            $store_id = $this->input->post('store_id',true);
            $store_info = $this->Store_model->get('evaluate_a,evaluate_b,evaluate_c,des_grade,serve_grade,express_grade', array('id' => $store_id));
            printAjaxData(array('item_list'=>$store_info));
        }
    }

    /*
     * 用户申请退换货
     */
    public function save_exchange($orders_detail_id = NULL){
        //判断是否登录
        $user_id = $this->_check_login();
        $orders_detail_id = intval($orders_detail_id);
        $orders_detail_info = $this->Orders_detail_model->get('*', array('id' => $orders_detail_id));
        $orders_detail_info['price_total'] = number_format($orders_detail_info['buy_number']*$orders_detail_info['buy_price'], 2, '.', '');
        $exchange_info = $this->Exchange_model->get('*', array('orders_id'=>$orders_detail_info['order_id'], 'orders_detail_id' => $orders_detail_id, 'user_id' => $user_id));
        if ($_POST) {
            $exchange_reason_id = $this->input->post('exchange_reason_id', TRUE);
            $price = $this->input->post('price', TRUE);
            $content = $this->input->post('content', TRUE);
            $batch_path_ids = $this->input->post('batch_path_ids', TRUE);
//            if ($batch_path_ids) {
//                $batch_path_ids = implode('_', $batch_path_ids);
//            }

            if ($exchange_reason_id == '') {
                printAjaxError('exchange_reason_id', '请选择退款原因');
            }
            if (!$price) {
                printAjaxError('price', '请输入退款金额');
            }
            if($price && $price > $orders_detail_info['price_total']){
                printAjaxError('price', "退款金额最多为{$orders_detail_info['price_total']}");
            }
            if (!$content) {
                printAjaxError('content', '请输入退款说明');
            }
            $user_info = $this->User_model->get('id, username', array('id' => $user_id));
            if (!$user_info) {
                printAjaxError('fail', '退款异常');
            }
            $item_info = $this->Orders_model->get('id,order_number,seller_id,store_id,store_name', "id = {$orders_detail_info['order_id']} and user_id = {$user_id} and status in (1,2) ");
            if (!$item_info) {
                printAjaxError('fail', '此订单信息不存在');
            }
            if ($exchange_info) {
                if ($exchange_info['status'] >= 3) {
                    printAjaxError('fail', '已成功退款，不能重复操作');
                } else {
                    if ($exchange_info['status'] != 1) {
                        printAjaxError('fail', '退款申请审核中，请耐心等待');
                    }
                }
            }

            $fields = array(
                'user_id' => $user_info['id'],
                'username' => $user_info['username'],
                'orders_id' => $item_info['id'],
                'orders_detail_id' => $orders_detail_info['id'],
                'order_num' => $item_info['order_number'],
                'seller_id' => $item_info['seller_id'],
                'store_id' => $item_info['store_id'],
                'store_name' => $item_info['store_name'],
                'add_time' => time(),
                'status' => 0,
                'content' => $content,
                'price' => $price,
                'exchange_type' => 1,
                'exchange_reason_id' => $exchange_reason_id,
                'batch_path_ids' => $batch_path_ids ? $batch_path_ids . '_' : ''
            );
            if (!$this->Exchange_model->save($fields, $exchange_info ? array('id' => $exchange_info['id']) : NULL)) {
                printAjaxError('fail', '提交申请失败');
            }
            printAjaxSuccess('success', '提交申请成功');
        }
    }

    //获取商家退换货列表
    public function get_store_exchange_list($max_id = 0, $since_id = 0, $per_page = 10, $page = 1) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $store_info = $this->Store_model->get('id', array('user_id' => $user_id));
        $strWhere = "store_id = {$store_info['id']}";
        if ($since_id) {
            $strWhere .= " and id > {$since_id} ";
        }
        if ($max_id) {
            $strWhere .= " and id <= {$max_id} ";
        }
        $item_list = $this->Exchange_model->gets('*', $strWhere, $per_page, $per_page * ($page - 1));
        // 最大ID
        // 第一次加载
        if (!$max_id && !$since_id) {
            $max_id = $this->Exchange_model->get_max_id(NULL);
        } else {
            //下拉刷新
            if (!$max_id && $since_id) {
                $max_id = $this->Exchange_model->get_max_id(NULL);
            }
        }
        //是否有下一页
        $cur_count = $per_page * ($page - 1) + count($item_list);
        $total_count = $this->Exchange_model->rowCount($strWhere);
        $is_next_page = 0;
        if ($total_count > $cur_count) {
            $is_next_page = 1;
        }
        printAjaxData(array('item_list' => $item_list, 'max_id' => $max_id, 'is_next_page' => $is_next_page));
    }

    public function get_exchange_detail($id = NULL) {
        //判断是否登录
        $user_id = $this->_check_login(true);
        $item_info = $this->Exchange_model->get('*', array('id'=>$id));
        //凭证图片
        $attachment_list = NULL;
        if ($item_info && $item_info['batch_path_ids']) {
            $tmp_atm_ids = preg_replace(array('/^_/', '/_$/', '/_/'), array('', '', ','), $item_info['batch_path_ids']);
            $attachment_list = $this->Attachment_model->gets2($tmp_atm_ids);
            if ($attachment_list){
                foreach ($attachment_list as $key=>$value){
                    $tmp_image = $this->_fliter_image_path($value['path']);
                    $attachment_list[$key]['path'] = $tmp_image['path'];
                    $attachment_list[$key]['path_thumb'] = $tmp_image['path_thumb'];
                }
            }
        }
        $payment_title = '';
        if ($item_info) {
            $orders_info = $this->Orders_model->get('payment_id, payment_title, total, status', array('id'=>$item_info['orders_id']));
            if ($orders_info) {
                $payment_title = $orders_info['payment_title'];
            }
            $item_info['payment_title'] = $payment_title;
        }
        $orders_detail_list = $this->Orders_detail_model->get('*', array('order_id' => $item_info['orders_id']));
        if($orders_detail_list){
            $tmp_image = $this->_fliter_image_path($orders_detail_list['path']);
            $orders_detail_list['path'] = $tmp_image['path'];
            $orders_detail_list['path_thumb'] = $tmp_image['path_thumb'];
        }
        printAjaxData(array('item_info'=>$item_info,'attachment_list'=>$attachment_list,'orders_detail_list'=>$orders_detail_list));
    }

    public function change_check() {
        if($_POST) {
            $id = $this->input->post('id', TRUE);
            $status = $this->input->post('status', TRUE);
            $client_remark = $this->input->post('client_remark', TRUE);
            $admin_remark = $this->input->post('admin_remark', TRUE);

            if (!$id) {
                printAjaxError('fail', '操作异常');
            }
            $item_info = $this->Exchange_model->get('*', array('id'=>$id));
            if (!$item_info) {
                printAjaxError('fail', '此退款信息不存在');
            }
            if ($item_info['status'] != 0) {
                printAjaxError('fail', '此退款状态异常');
            }
            if (!$status) {
                printAjaxError('fail', '请选择审核状态');
            }
            if ($status == 1) {
                if (!$client_remark) {
                    printAjaxError('client_remark', '备注不能为空');
                }
                if (!$admin_remark) {
                    printAjaxError('admin_remark', '备注不能为空');
                }
            }
            $fields = array(
                'status'=>$status,
                'client_remark'=>$client_remark,
                'admin_remark'=>$admin_remark,
                'update_time'=>time()
            );
            if (!$this->Exchange_model->save($fields, array('id'=>$item_info['id']))) {
                printAjaxError('fail', '操作失败');
            }
            printAjaxSuccess('success', '操作成功');
        }
    }
    //退款到余额
    public function refund_to_balance() {
        if ($_POST) {
            $id = $this->input->post('id', TRUE);
            if(!$id) {
                printAjaxError('fail', '操作异常');
            }
            $item_info = $this->Exchange_model->get('*', array('id'=>$id));
            if (!$item_info) {
                printAjaxError('fail', '此退款信息不存在');
            }
            if ($item_info['status'] != 2) {
                printAjaxError('fail', '状态异常');
            }
            $orders_info = $this->Orders_model->get('*', array('id'=>$item_info['orders_id']));
            if (!$orders_info) {
                printAjaxError('fail', '订单信息不存在');
            }
            //预存款支付
            if ($orders_info['payment_id'] == 1) {
                $financial_info = $this->Financial_model->get(array('type'=>'order_out', 'ret_id'=>$orders_info['id']));
                if (!$financial_info) {
                    printAjaxError('fail', '支付记录不存在，退款失败');
                }
                $user_info = $this->User_model->get('*',array('user.id'=>$orders_info['user_id']));
                if (!$user_info) {
                    printAjaxError('fail', '买家信息不存在，退款失败');
                }
                $this->_balance_trade_refund(NULL, $item_info, $orders_info, $user_info, 3);
            }
            //支付宝
            else if ($orders_info['payment_id'] == 2) {
                $pay_log_info = $this->Pay_log_model->get('*',array('pay_log.out_trade_no'=>$orders_info['out_trade_no'], 'pay_log.payment_type'=>'alipay', 'pay_log.order_type'=>'orders'));
                if (!$pay_log_info) {
                    printAjaxError('fail', '支付记录不存在，退款失败');
                }
                if ($pay_log_info['trade_status'] != 'TRADE_SUCCESS' && $pay_log_info['trade_status'] != 'TRADE_FINISHED') {
                    printAjaxError('fail', '订单未付款，退款失败');
                }
                $user_info = $this->User_model->get('*',array('user.id'=>$orders_info['user_id']));
                if (!$user_info) {
                    printAjaxError('fail', '买家信息不存在，退款失败');
                }
                $this->_balance_trade_refund(NULL, $item_info, $orders_info, $user_info, 3);
            }
            //微信
            else if ($orders_info['payment_id'] == 3) {
                $pay_log_info = $this->Pay_log_model->get('*',array('pay_log.out_trade_no'=>$orders_info['out_trade_no'], 'pay_log.payment_type'=>'weixin', 'pay_log.order_type'=>'orders'));
                if (!$pay_log_info) {
                    printAjaxError('fail', '支付记录不存在，退款失败');
                }
                if ($pay_log_info['trade_status'] != 'TRADE_SUCCESS' && $pay_log_info['trade_status'] != 'TRADE_FINISHED') {
                    printAjaxError('fail', '订单未付款，退款失败');
                }
                $user_info = $this->User_model->get('*',array('user.id'=>$orders_info['user_id']));
                if (!$user_info) {
                    printAjaxError('fail', '买家信息不存在，退款失败');
                }
                $this->_balance_trade_refund(NULL, $item_info, $orders_info, $user_info, 3);
            }
            //网银
            else if ($orders_info['payment_id'] == 4) {

            }
        }
    }

    /*
     * 帮助中心分类
     */
    public function get_support_menu(){
        $item_list = $this->Menu_model->getChildMenuTree('id, menu_name', 173);
        printAjaxData(array('item_list' => $item_list));
    }

    /*
     * 文章列表
     */
    public function get_page_list($category_id = null){
        $item_list = $this->Page_model->gets(array('page.category_id'=>$category_id),2);
        if ($item_list){
            foreach ($item_list as $key => $value){
                $item_list[$key]['content'] = filter_content(html($value['content']),base_url());
            }
        }
        printAjaxData(array('item_list' => $item_list));
    }

    /*
     * 文章内容
     */
    public function get_page_detail($id = NULL){
        $item_info = $this->Page_model->get('*', array('id'=>$id));
        if ($item_info){
            $item_info['content'] = filter_content(html($item_info['content']),base_url());
        }
        printAjaxData(array('item_info' => $item_info));
    }

    //实名认证
    public function user_auth()
    {
        $user_id = $this->_check_login();
        $user_info = $this->User_model->getInfo('real_name,id_card,is_id_card_auth', array('id' => $user_id));
        $real_name = $this->input->post('real_name', TRUE);
        $id_card = $this->input->post('id_card', TRUE);
        if ($this->User_model->rowCount(array('user.id' => $user_id, 'user.is_id_card_auth' => 1))) {
            printAjaxError('fail', '您已经实名认证，无需再认证');
        }
        if (!$user_info['real_name']) {
            if (!$real_name) {
                printAjaxError('real_name', '姓名不能为空');
            }
            if (!preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/', $real_name)) {
                printAjaxError('real_name', '姓名只包含中文名，2-4位');
            }
        }
        if (!$id_card) {
            printAjaxError('id_card', '身份证号不能为空');
        }
        if (!preg_match('/(^\d{15}$)|(^\d{17}[0-9Xx]$)/', $id_card)) {
            printAjaxError('id_card', '身份证号格式错误');
        }
        if (!$this->_checkIdentity($id_card)) {
            printAjaxError('id_card', '请输入正确的身份证号码');
        }
        $fields = array(
            'id_card' => $id_card,
            'is_id_card_auth' => 1
        );
        if (!$user_info['real_name']) {
            $fields['real_name'] = $real_name;
        }
        $result = $this->User_model->save($fields, array('id' => $user_id));
        if ($result) {
            printAjaxSuccess('success', '实名认证成功');
        } else {
            printAjaxError('fail', '实名认证失败');
        }
    }


    

    /**
     * app支付-优选订单
     *
     * @param $demand_id 优选订单ID
     * @param $pay_type alipay=支付宝支付；wxpay=微信支付；
     *
     * @return json
     */
    public function order_app_pay($order_id = NULL, $pay_type = NULL) {
        //判断是否登录
        $user_id = $this->_check_login();
        if (!$order_id || !$pay_type) {
            printAjaxError('fail', '操作异常');
        }
        $orders_info = $this->Orders_model->get('*', "id = {$order_id} and user_id = {$user_id} and status = 0");
        if (!$orders_info) {
            printAjaxError('fail', '此订单信息不存在');
        }
        if ($pay_type == 'alipay') {
            $this->_order_alipay_pay($orders_info);
        } else if ($pay_type == 'wxpay') {
            $this->_order_weixin_pay($orders_info);
        } else {
            printAjaxError('fail', '不支持此支付通道');
        }
    }

    //支付宝App支付-优选订单
    private function _order_alipay_pay($orders_info = NULL) {
        $out_trade_no = 'O' . $orders_info['order_number'];
        $total_fee = $orders_info['total'];
        //生成支付记录
        if (!$this->Pay_log_model->rowCount(array('out_trade_no' => $out_trade_no, 'payment_type' => 'alipay', 'order_type' => 'orders'))) {
            $this->Orders_model->save(array('out_trade_no' => $out_trade_no), array('id' => $orders_info['id']));
            $fields = array(
                'user_id' => $orders_info['user_id'],
                'total_fee' => $orders_info['total'],
                'total_fee_give' => 0,
                'out_trade_no' => $out_trade_no,
                'order_num' => $orders_info['order_number'],
                'trade_status' => 'WAIT_BUYER_PAY',
                'add_time' => time(),
                'payment_type' => 'alipay',
                'order_type' => 'orders',
                'seller_id'=>    $orders_info['seller_id'],
                'store_id'=>    $orders_info['store_id'],
            );
            if (!$this->Pay_log_model->save($fields)) {
                printAjaxError('fail', '支付失败，请重试');
            }
        }
        require_once("sdk/alipay/aop/AopClient.php");
        require_once("sdk/alipay/aop/AlipayTradeAppPayRequest.php");
        require_once 'sdk/alipay/aop/SignData.php';

        $aop = new AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->_app_id;
        $aop->rsaPrivateKey = $this->_rsaPrivateKey;
        $aop->alipayrsaPublicKey=$this->_alipayrsaPublicKey;

        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';

        $request = new AlipayTradeAppPayRequest ();
        $request->setNotifyUrl(base_url()."index.php/napi/order_alipay_notify");
        $param = array(
            'body' =>         '日立App支付',
            'subject' =>      '日立付款',
            'out_trade_no' => $out_trade_no,
            'product_code'=> 'QUICK_MSECURITY_PAY',
            'total_amount' =>$total_fee
        );
        $request->setBizContent(json_encode($param));
        $response = $aop->sdkExecute($request);
        echo $response;
        exit;
    }

    //微信App支付-优选订单
    private function _order_weixin_pay($item_info = NULL) {
        $product_id = 'O' . $item_info['order_number'];
        $out_trade_no = $item_info['out_trade_no'];
        if (!$out_trade_no) {
            $out_trade_no = 'O' . $this->advdbclass->get_unique_orders_number('out_trade_no');
            $this->Orders_model->save(array('out_trade_no' => $out_trade_no), array('id' => $item_info['id']));
        }
        require_once "sdk/wxpayv3/WxPay.Api.php";
        require_once "sdk/wxpayv3/WxPay.Data.php";

        $unifiedOrder = new WxPayUnifiedOrder();
        $unifiedOrder->SetAttach($item_info['order_number']);
        $unifiedOrder->SetBody('日立付款'); //商品或支付单简要描述
        $unifiedOrder->SetProduct_id($product_id);
        $unifiedOrder->SetOut_trade_no($out_trade_no);
        $unifiedOrder->SetNotify_url(base_url() . 'index.php/napi/order_wxpay_pay_notify');
        $unifiedOrder->SetTotal_fee($item_info['total']*100);
        $unifiedOrder->SetTrade_type("APP");
        $result = WxPayApi::unifiedOrder($unifiedOrder, 60);
        if (is_array($result)) {
            //生成支付记录
            if (!$this->Pay_log_model->rowCount(array('out_trade_no' => $out_trade_no, 'payment_type' => 'weixin', 'order_type' => 'orders'))) {
                $fields = array(
                    'user_id' => $item_info['user_id'],
                    'total_fee' => $item_info['total'],
                    'total_fee_give' => 0,
                    'out_trade_no' => $out_trade_no,
                    'order_num' => $item_info['order_number'],
                    'trade_status' => 'WAIT_BUYER_PAY',
                    'add_time' => time(),
                    'payment_type' => 'weixin',
                    'order_type' => 'orders',
                    'seller_id'=>    $item_info['seller_id'],
                    'store_id'=>    $item_info['store_id'],
                );
                $this->Pay_log_model->save($fields);
            }
            echo json_encode($result);
            exit;
        } else {
            $out_trade_no = 'O' . $this->advdbclass->get_unique_orders_number('out_trade_no');
            $this->Orders_model->save(array('out_trade_no' => $out_trade_no), array('id' => $item_info['id']));

            $unifiedOrder = new WxPayUnifiedOrder();
            $unifiedOrder->SetAttach($item_info['order_number']);
            $unifiedOrder->SetBody('日立付款'); //商品或支付单简要描述
            $unifiedOrder->SetProduct_id($product_id);
            $unifiedOrder->SetOut_trade_no($out_trade_no);
            $unifiedOrder->SetNotify_url(base_url() . 'index.php/napi/order_wxpay_pay_notify');
            $unifiedOrder->SetTotal_fee($item_info['total']*100);
            $unifiedOrder->SetTrade_type("APP");
            $result = WxPayApi::unifiedOrder($unifiedOrder, 60);
            if (is_array($result)) {
                //生成支付记录
                if (!$this->Pay_log_model->rowCount(array('out_trade_no' => $out_trade_no, 'payment_type' => 'weixin', 'order_type' => 'orders'))) {
                    $fields = array(
                        'user_id' => $item_info['user_id'],
                        'total_fee' => $item_info['total'],
                        'total_fee_give' => 0,
                        'out_trade_no' => $out_trade_no,
                        'order_num' => $item_info['order_number'],
                        'trade_status' => 'WAIT_BUYER_PAY',
                        'add_time' => time(),
                        'payment_type' => 'weixin',
                        'order_type' => 'orders',
                        'seller_id'=>    $item_info['seller_id'],
                        'store_id'=>    $item_info['store_id'],
                    );
                    $this->Pay_log_model->save($fields);
                }
                echo json_encode($result);
                exit;
            } else {
                printAjaxError('fail', '付款失败，请重试');
            }
        }
    }

    //支付宝App支付异步通知-优选
    public function order_alipay_notify() {
        if ($_POST) {
            require_once("sdk/alipay/aop/AopClient.php");
            require_once 'sdk/alipay/aop/SignData.php';

            $aop = new AopClient;
            $aop->alipayrsaPublicKey = $this->_alipayrsaPublicKey;
            $verify_result = $aop->rsaCheckV1($_POST, NULL, "RSA2");
            if($verify_result) {
                //商户订单号
                $out_trade_no = $this->input->post('out_trade_no', TRUE);
                $order_num = str_replace('O', '', $out_trade_no);
                //支付宝交易号
                $trade_no     = $this->input->post('trade_no', TRUE);
                //交易状态
                $trade_status = $this->input->post('trade_status', TRUE);
                //买家支付宝账号
                $buyer_email  = $this->input->post('buyer_logon_id', TRUE);
                //通知时间
                $notify_time  = strtotime($this->input->post('notify_time', TRUE));
                $app_id  = $this->input->post('app_id', TRUE);
                $total_amount  = $this->input->post('total_amount', TRUE);

                if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                    $pay_log_info = $this->Pay_log_model->get('*', array('out_trade_no'=>$out_trade_no, 'order_type'=>'orders', 'payment_type'=>'alipay'));
                    if ($pay_log_info && $app_id == $this->_app_id && $total_amount == $pay_log_info['total_fee']) {
                        if ($pay_log_info['trade_status'] != $trade_status && $pay_log_info['trade_status'] != 'TRADE_FINISHED' && $pay_log_info['trade_status'] != 'TRADE_SUCCESS' && $pay_log_info['trade_status'] != 'TRADE_CLOSED') {
                            //支付记录
                            $fields = array(
                                'payment_type' => 'alipay',
                                'order_type' => 'orders',
                                'trade_no' => $trade_no,
                                'trade_status' => $trade_status,
                                'buyer_email' => $buyer_email,
                                'notify_time' => $notify_time
                            );
                            if ($this->Pay_log_model->save($fields, array('id' => $pay_log_info['id']))) {
                                $item_info = $this->Orders_model->get('*', array('order_number' => $order_num, 'status' => 0));
                                $user_info = $this->User_model->get('id, total, username', array('id' => $item_info['user_id']));
                                if ($item_info && $user_info) {
                                    //修改订单状态
                                    $fields = array(
                                        'status' => 1,
                                        'payment_price' => 0,//费率
                                        'payment_title' => '支付宝支付',
                                        'payment_id' => 2);
                                    if ($this->Orders_model->save($fields, array('id' => $item_info['id']))) {
                                        //订单追踪记录
                                        $fields = array(
                                            'add_time' => time(),
                                            'content' => "订单付款成功[支付宝APP支付]",
                                            'order_id' => $item_info['id'],
                                            'current_status' => $item_info['status'],
                                            'change_status' => 1
                                        );
                                        $this->Orders_process_model->save($fields);
                                        //财务记录
                                        if (!$this->Financial_model->rowCount(array('type' => 'order_out', 'ret_id' => $item_info['id']))) {
                                            $fields = array(
                                                'cause' => "支付成功-[订单号：{$item_info['order_number']}]",
                                                'price' => -$item_info['total'],
                                                'balance' => $user_info['total'],
                                                'add_time' => time(),
                                                'user_id' => $user_info['id'],
                                                'username' => $user_info['username'],
                                                'type' => 'order_out',
                                                'pay_way' => '2',
                                                'ret_id' => $item_info['id'],
                                                'from_user_id' => $user_info['id'],
                                                'seller_id'=>    $item_info['seller_id'],
                                                'store_id'=>    $item_info['store_id'],
                                            );
                                            $this->Financial_model->save($fields);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                echo "success";
            } else {
                echo "fail";
            }
        }
    }

    //微信异步回调认证(付款)-优选
    public function order_wxpay_pay_notify() {
        $xmlInfo = $GLOBALS['HTTP_RAW_POST_DATA'];
        //解析xml
        $result = $this->xmlToArray($xmlInfo);
        /* ======================================== */
        if ($result != null) {
            if(array_key_exists("return_code", $result)
                && array_key_exists("result_code", $result)
                && $result["return_code"] == "SUCCESS"
                && $result["result_code"] == "SUCCESS")
            {
                //订单号
                $order_num    = $result['attach'];
                //商户订单号
                $out_trade_no = $result['out_trade_no'];
                //微信交易号
                $trade_no     = $result['transaction_id'];
                //通知时间
                $notify_time  = $result['time_end'];
                $total_fee    = $result['total_fee'];

                $pay_log_info = $this->Pay_log_model->get('*', array('out_trade_no' => $out_trade_no, 'order_type' => 'orders', 'payment_type' => 'weixin'));
                if ($pay_log_info && $total_fee == $pay_log_info['total_fee'] * 100) {
                    if ($pay_log_info['trade_status'] != 'TRADE_FINISHED' && $pay_log_info['trade_status'] != 'TRADE_SUCCESS' && $pay_log_info['trade_status'] != 'TRADE_CLOSED') {
                        //支付记录
                        $fields = array(
                            'trade_no' => $trade_no,
                            'trade_status' => 'TRADE_SUCCESS',
                            'buyer_email' => '',
                            'notify_time' => strtotime($notify_time)
                        );
                        if ($this->Pay_log_model->save($fields, array('id' => $pay_log_info['id']))) {
                            $item_info = $this->Orders_model->get('*', array('order_number' => $order_num, 'status' => 0));
                            $user_info = $this->User_model->get('id, total, username', array('id' => $item_info['user_id']));
                            if ($item_info && $user_info) {
                                //修改订单状态
                                $fields = array(
                                    'status' => 1,
                                    'payment_price' => 0,//费率
                                    'payment_title' => '微信支付',
                                    'payment_id' => 3);
                                if ($this->Orders_model->save($fields, array('id' => $item_info['id']))) {
                                    //订单追踪记录
                                    $fields = array(
                                        'add_time' => time(),
                                        'content' => "订单付款成功[微信App支付]",
                                        'order_id' => $item_info['id'],
                                        'current_status' => $item_info['status'],
                                        'change_status' => 1
                                    );
                                    $this->Orders_process_model->save($fields);
                                    //财务记录
                                    if (!$this->Financial_model->rowCount(array('type' => 'order_out', 'ret_id' => $item_info['id']))) {
                                        $fields = array(
                                            'cause' => "支付成功-[订单号：{$item_info['order_number']}]",
                                            'price' => -$item_info['total'],
                                            'balance' => $user_info['total'],
                                            'add_time' => time(),
                                            'user_id' => $user_info['id'],
                                            'username' => $user_info['username'],
                                            'type' => 'order_out',
                                            'pay_way' => '3',
                                            'ret_id' => $item_info['id'],
                                            'from_user_id' => $user_info['id'],
                                            'seller_id'=>    $item_info['seller_id'],
                                            'store_id'=>    $item_info['store_id'],
                                        );
                                        $this->Financial_model->save($fields);
                                    }
                                }
                            }
                        }
                    }
                }
                echo $this->returnInfo("SUCCESS", "OK");
            }
        }
    }

    private function returnInfo($type, $msg) {
        $return_xml="<xml>"
            ."<return_code><![CDATA[{$type}]]></return_code>"
            ."<return_msg><![CDATA[{$msg}]]></return_msg>"
            ."</xml>";
        return $return_xml;
    }

    //预存款订单结算
    public function order_yue_pay(){
        //判断是否登录
        $user_id = $this->_check_login();
        if ($_POST) {
            $order_id = $this->input->post('order_id');
            $pay_password = $this->input->post('pay_password');
            if (!$pay_password) {
                printAjaxError('fail', '支付密码不能为空');
            }
            //判断下单用户是否存在
            $user_info = $this->User_model->get('*', array('user.id' => $user_id));
            if (!$user_info) {
                printAjaxError('fail', '此用户不存在，结算失败');
            }
            $item_info = $this->Orders_model->get('*', "id = '{$order_id}' and user_id = {$user_id} and status = 0");
            if (!$item_info) {
                printAjaxError('fail', '此订单信息不存在');
            }
            //预存款支付
            if ($item_info['total'] > $user_info['total']) {
                printAjaxError('fail', '预存款余额不足，请选择其它支付方式');
            }
            if (create_password_salt($user_info['username'], $user_info['add_time'], $pay_password) != $user_info['pay_password']) {
                printAjaxError('fail', '支付密码错误，请重新输入');
            }
            //修改订单状态
            $fields = array(
                'status' => 1,
                'payment_price' => 0,//费率
                'payment_title' => '预存款支付',
                'payment_id' => 1);
            if (!$this->Orders_model->save($fields, array('id' => $item_info['id'], 'user_id' => $user_info['id']))) {
                printAjaxError('fail', '预存款支付失败');
            }
            $fields = array(
                'add_time' => time(),
                'content' => "订单付款成功--[订单号：{$item_info['order_number']}]",
                'order_id' => $item_info['id'],
                'order_status' => $item_info['status'],
                'change_status' => 1
            );
            $orders_process_id = $this->Orders_process_model->save($fields);
            if (!$orders_process_id) {
                $fields = array(
                    'status' => 0,
                    'payment_price' => 0,//费率
                    'payment_title' => '',
                    'payment_id' => 0);
                $this->Orders_model->save($fields, array('id' => $item_info['id'], 'user_id' => $user_info['id']));
                printAjaxError('fail', '预存款支付失败');
            }
//             //付款成功减少相应库存
//             $orders_detail_list = $this->Orders_detail_model->gets('*', array('order_id'=> $item_info['id']));
//             if ($orders_detail_list) {
//             	foreach ($orders_detail_list as $item) {
//             		$product_info = $this->Product_model->get('color_size_open,stock', array('id' => $item['product_id']));
//             		if ($product_info['color_size_open'] == 1) {
//             			$stock_info = $this->Product_model->getProductStock($item['product_id'], $item['color_id'], $item['size_id']);
//             			$this->Product_model->changeStock(array('stock' => $stock_info['stock'] - $item['buy_number']), array('product_id' => $item['product_id'], 'color_id' => $item['color_id'], 'size_id' => $item['size_id']));
//             		} else {
//             			$this->Product_model->save(array('stock' => $product_info['stock'] - $item['buy_number']), array('id' => $item['product_id']));
//             		}
//             	}
//             }
            //进行扣款
            if (!$this->User_model->save(array('total' => $user_info['total'] - $item_info['total']), array('id' => $user_id))) {
                $fields = array(
                    'status' => 0,
                    'payment_price' => 0,//费率
                    'payment_title' => '',
                    'payment_id' => 0);
                $this->Orders_model->save($fields, array('id' => $item_info['id'], 'user_id' => $user_info['id']));
                $this->Orders_process_model->delete(array('id' => $orders_process_id));
                printAjaxError('fail', '预存款支付失败');
            }
            //财务记录还没有添加
            $fields = array(
                'cause' => "订单付款成功--[订单号：{$item_info['order_number']}]",
                'price' => -$item_info['total'],
                'balance' => $user_info['total'] - $item_info['total'],
                'add_time' => time(),
                'user_id' => $user_info['id'],
                'username' => $user_info['username'],
                'type' => 'order_out',
                'pay_way' => 1,
                'ret_id' => $item_info['id'],
                'from_user_id' => $user_info['id'],
                'seller_id'=>    $item_info['seller_id'],
                'store_id'=>    $item_info['store_id'],
            );
            $this->Financial_model->save($fields);
            printAjaxSuccess("success", '恭喜您支付成功!');
        }
    }


    private function _balance_trade_refund($pay_log_info, $item_info, $orders_info, $user_info, $status = '4') {
        $fields = array(
            'total'=>$user_info['total'] + $item_info['price']
        );
        if (!$this->User_model->save($fields, array('id'=>$orders_info['user_id']))) {
            printAjaxError('fail', '退款操作失败');
        }
        $fields = array(
            'cause'=>"退款成功-[订单号：{$orders_info['order_number']}]",
            'price'=>$item_info['price'],
            'balance'=>$user_info['total'] + $item_info['price'],
            'add_time'=>time(),
            'user_id'=>$user_info['id'],
            'username'=>$user_info['username'],
            'type' =>  'order_in',
            'pay_way'=>'1',
            'ret_id'=>$orders_info['id'],
            'from_user_id'=>$user_info['id']
        );
        $this->Financial_model->save($fields);
        //操作订单
        $fields = array(
            'cancel_cause'=> '交易关闭-[买家申请退款成功]',
            'status'=> 4
        );
        if ($this->Orders_model->save($fields, array('id' => $orders_info['id']))) {
            $fields = array(
                'add_time' => time(),
                'content' => "交易关闭成功-[买家申请退款成功]",
                'order_id' => $orders_info['id'],
                'order_status'=>$orders_info['status'],
                'change_status'=>4
            );
            $this->Orders_process_model->save($fields);
        }
        //操作退款申请状态
        $this->Exchange_model->save(array('status'=>$status, 'update_time'=>time()), array('id'=>$item_info['id']));
        printAjaxSuccess('success', '退款成功');

    }

    /**
     * 过滤图片路径
     *
     * @param string $image_path
     * @param string $model
     * @return multitype:string
     */
    private function _fliter_image_path($image_path = NULL) {
        $path = '';
        $path_thumb = '';
        if ($image_path) {
            if (!preg_match('/^http/', $image_path)) {
                $path = base_url() . $image_path;
                $path_thumb = base_url() . preg_replace('/\./', '_thumb.', $image_path);
            } else {
                $path = $image_path;
                $path_thumb = $image_path;
            }
        }
        return array('path' => $path, 'path_thumb' => $path_thumb);
    }

    private function _tmp_user_info($user_id = NULL, $session_id = 0, $push_cid = '') {
        if ($push_cid) {
            $this->User_model->save(array('push_cid' => $push_cid), array('id' => $user_id));
        }
        $user_info = $this->User_model->get('id,username,mobile,path,nickname,total,sex,real_name', array('id' => $user_id));
        $user_info['session_id'] = $session_id;
        $tmp_path = $this->_fliter_image_path($user_info['path']);
        $user_info['path'] = $tmp_path['path'];
        $user_info['path_thumb'] = $tmp_path['path_thumb'];

        $strWhere = array('user_id'=>$user_id,'is_bond'=>1);
        $user_info['record_count'] = $this->Groupon_record_model->rowCount($strWhere);
        return $user_info;
    }

    private function _delete_session() {
        $this->session->unset_userdata("user_id");
        session_write_close();
    }

    private function _set_session($user_id = "") {
        $this->session->set_userdata(array('user_id' => $user_id));
        session_write_close();
    }

    //加盐算法
    private function _createPasswordSALT($user, $salt, $password) {

        return md5($user . $salt . $password);
    }

    private function _beforeFilter() {
        $sid = $this->input->get('sid');
        if ($sid) {
            $sid = preg_replace('/sid-/', '', $sid);
            if ($sid) {
                $ret = NULL;
                $this->db->select('timestamp');
                $query = $this->db->get_where(config_item('sess_save_path'), array('id'=>$sid));
                if ($query->num_rows() > 0) {
                    $ret = $query->result_array();
                    $ret = $ret[0];
                }
                if (!$ret) {
                    $this->session->sess_destroy();
                    return FALSE;
                }
                //大于默认时间，系统 会自动更新
                if (($ret['timestamp'] + config_item('sess_time_to_update')) >= time()) {
                    return FALSE;
                } else {
                    if (config_item('sess_use_database') == TRUE) {
                        $this->db->update(config_item('sess_save_path'), array('timestamp'=>time()), array('id'=>$sid));
                    }
                }
            }
        }
    }

    //获取唯一的订单号
    private function _getUniqueOrderNumber() {
        //一秒钟一万件的量
        $randCode = '';
        while (true) {
            $randCode = getOrderNumber(5);
            $count1= $this->Groupon_record_model->rowCount(array('bond_number' => $randCode));
            $count2 = $this->Orders_model->rowCount(array('order_number' => $randCode));
            $count = $count1 + $count2;
            if ($count > 0) {
                $randCode = '';
                continue;
            } else {
                break;
            }
        }
        return $randCode;
    }

    //获取唯一的商户订单号
    private function _get_unique_out_trade_no() {
        //一秒钟一万件的量
        $randCode = '';
        while (true) {
            $randCode = getOrderNumber(5);
            $count = $this->Pay_log_model->rowCount(array('out_trade_no' => $randCode));
            if ($count > 0) {
                $randCode = '';
                continue;
            } else {
                break;
            }
        }
        return $randCode;
    }

    private function _check_login() {
        if (!$this->session->userdata("user_id")) {
            printAjaxError('login', '请登录');
        }
        $user_id = $this->session->userdata("user_id");
        session_write_close();
        $item_info = $this->User_model->get('display', array('id' => $user_id));
        if (!$item_info) {
            printAjaxError('fail', '此账号不存在或被删除');
        }
        if ($item_info['display'] == 0) {
            printAjaxError('fail', '你的账户还未激活，请先激活账户或联系管理员激活');
        } else if ($item_info['display'] == 2) {
            printAjaxError('fail', '你的账户被冻结，请联系管理员或者网站客服');
        }

        return $user_id;
    }

    private function send_sms($mobile = NULL, $sms_txt = NULL) {
        $sUrl = 'http://api.qirui.com:7891/mt'; // 接入地址
        $apiKey = '901022360004';    // 请替换为你的帐号编号
        $apiSecret = 'BFACCAFFBAFC8FF9017F14B5973A562E';  // 请替换为你的帐号密钥
        $nCgid = 1221;   // 请替换为你的通道组编号
        $sMobile = $mobile;    // 请替换为你的手机号码
        $sContent = $sms_txt;   // 请把数字替换为其他4~10位的数字测试，如需测试其他内容，请先联系客服报备发送模板
        $nCsid = 0;    // 签名编号 ,可以为空时，使用系统默认的编号
        $data = array('un' => $apiKey, 'pw' => $apiSecret, 'da' => $sMobile, 'sm' => $sContent,'dc' => 15,'tf' => 3,'rf' => 1,);  //定义参数
        $data = @http_build_query($data);        //把参数转换成URL数据
        $xml = file_get_contents($sUrl . '?' . $data);  // 发送请求
        $xml_val = $this->xmlToArray($xml);
        return $xml_val;
    }

    private function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }

    private function curlPost($url, $postFields) {
        $postFields = http_build_query($postFields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function _checkIdentity($num,$checkSex=''){
        // 不是15位或不是18位都是无效身份证号
        if(strlen($num) != 15 && strlen($num) != 18){
            return false;
        }
        // 是数值
        if(is_numeric($num)){
            // 如果是15位身份证号
            if(strlen($num) == 15 ){
                // 省市县（6位）
                $areaNum = substr($num,0,6);
                // 出生年月（6位）
                $dateNum = substr($num,6,6);
                // 性别（3位）
                $sexNum = substr($num,12,3);
            }else{
                // 如果是18位身份证号
                // 省市县（6位）
                $areaNum = substr($num,0,6);
                // 出生年月（8位）
                $dateNum = substr($num,6,8);
                // 性别（3位）
                $sexNum = substr($num,14,3);
                // 校验码（1位）
                $endNum = substr($num,17,1);
            }
        }else{
            // 不是数值
            if(strlen($num) == 15){
                return false;
            }else{
                // 验证前17位为数值，且18位为字符x
                $check17 = substr($num,0,17);
                if(!is_numeric($check17)){
                    return false;
                }
                // 省市县（6位）
                $areaNum = substr($num,0,6);
                // 出生年月（8位）
                $dateNum = substr($num,6,8);
                // 性别（3位）
                $sexNum = substr($num,14,3);
                // 校验码（1位）
                $endNum = substr($num,17,1);
                if($endNum != 'x' && $endNum != 'X'){
                    return false;
                }
            }
        }

        if(isset($areaNum)){
            if(!$this ->checkArea($areaNum)){
                return false;
            }
        }

        if(isset($dateNum)){
            if(!$this ->checkDate($dateNum)){
                return false;
            }
        }

        // 性别1为男，2为女
        if($checkSex == 1){
            if(isset($sexNum)){
                if(!$this ->checkSex($sexNum)){
                    return false;
                }
            }
        }else if($checkSex == 2){
            if(isset($sexNum)){
                if($this ->checkSex($sexNum)){
                    return false;
                }
            }
        }

        if(isset($endNum)){
            if(!$this ->checkEnd($endNum,$num)){
                return false;
            }
        }
        return true;
    }

    // 验证城市
    private function checkArea($area){
        $num1 = substr($area,0,2);
        $num2 = substr($area,2,2);
        $num3 = substr($area,4,2);
        // 根据GB/T2260—999，省市代码11到65
        if(10 < $num1 && $num1 < 66){
            return true;
        }else{
            return false;
        }
        //============
        // 对市 区进行验证
        //============
    }

    // 验证出生日期
    private function checkDate($date){
        if(strlen($date) == 6){
            $date1 = substr($date,0,2);
            $date2 = substr($date,2,2);
            $date3 = substr($date,4,2);
            $statusY = $this ->checkY('19'.$date1);
        }else{
            $date1 = substr($date,0,4);
            $date2 = substr($date,4,2);
            $date3 = substr($date,6,2);
            $nowY = date("Y",time());
            if(1900 < $date1 && $date1 <= $nowY){
                $statusY = $this->checkY($date1);
            }else{
                return false;
            }
        }
        if(0<$date2 && $date2 <13){
            if($date2 == 2){
                // 润年
                if($statusY){
                    if(0 < $date3 && $date3 <= 29){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    // 平年
                    if(0 < $date3 && $date3 <= 28){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                $maxDateNum = $this ->getDateNum($date2);
                if(0<$date3 && $date3 <=$maxDateNum){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    // 验证性别
    private function checkSex($sex){
        if($sex % 2 == 0){
            return false;
        }else{
            return true;
        }
    }

    // 验证18位身份证最后一位
    private function checkEnd($end,$num){
        $checkHou = array(1,0,'x',9,8,7,6,5,4,3,2);
        $checkGu = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        $sum = 0;
        for($i = 0;$i < 17; $i++){
            $sum += (int)$checkGu[$i] * (int)$num[$i];
        }
        $checkHouParameter= $sum % 11;
        if($checkHou[$checkHouParameter] != $num[17]){
            return false;
        }else{
            return true;
        }
    }

    // 验证平年润年，参数年份,返回 true为润年  false为平年
    private function checkY($Y){
        if(getType($Y) == 'string'){
            $Y = (int)$Y;
        }
        if($Y % 100 == 0){
            if($Y % 400 == 0){
                return true;
            }else{
                return false;
            }
        }else if($Y % 4 ==  0){
            return true;
        }else{
            return false;
        }
    }

    // 当月天数 参数月份（不包括2月）  返回天数
    private function getDateNum($month){
        if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12){
            return 31;
        }else if($month == 2){
        }else{
            return 30;
        }
    }


    /**
     * 判断实体商家和实力电商
     */
    public function check_store_type()
    {
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->User_model->get('seller_group_id', array('id' => $user_id));
        if (!$user_info){
            return false;
        }
        $seller_group_info = $this->Seller_group_model->get('user_id', array('id' => $user_info['seller_group_id']));
        if (!$seller_group_info) {
            return false;
        } else {
            $store_info = $this->Store_model->get('store_auth,retailer_auth', array('user_id' => $seller_group_info['user_id']));
            if ($store_info) {
                if ($store_info['store_auth'] || $store_info['retailer_auth']) {
                    return true;
                }
            }
            return false;
        }
    }

    //腾讯地图逆地址解析
    public function get_address($lat,$lng){
        $url = "http://apis.map.qq.com/ws/geocoder/v1/?location={$lat},{$lng}&key=3HMBZ-6IE6V-O34PP-UNTVI-4TQ5F-3NFHG";
//        //初始化
//        $curl = curl_init();
//        //设置抓取的url
//        curl_setopt($curl, CURLOPT_URL, $url);
//        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, 1);
//        //设置获取的信息以文件流的形式返回，而不是直接输出。
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        //执行命令
//        $data = curl_exec($curl);
//        //关闭URL请求
//        curl_close($curl);
//        var_dump($data);
        $data = file_get_contents($url);
        $data = json_decode($data,TRUE);
        $city = empty($data['status']) ? $data['result']['address_component']['city'] : '';
        return $city;
    }

}

/* End of file main.php */
/* Location: ./application/client/controllers/main.php */