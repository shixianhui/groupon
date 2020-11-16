<?php
class Product extends CI_Controller {
    private $_title = '产品';
    private $_tool = '';
    private $_table = 'product';
    private $_template = 'product';
    private $_display_arr = array(
    	'1' => '<font color="#ff4200">上架</font>',
    	'0' => '<font color="#333">下架</font>',
    );

    private $_comment_display_arr = array(
        '1' => '显示',
        '0' => '隐藏',
    );

    private $_attribute_arr = array(
        'c' => '推送首页',
        'a' => '精品推荐',
        'h' => '热门关注',
    );

    public function __construct() {
        parent::__construct();
        //获取表名
        $this->_table = $this->uri->segment(1);
        //获取表对象
        $this->load->model(ucfirst($this->_table).'_model', 'tableObject', TRUE);
        $this->_tool = $this->load->view('element/save_list_tool', array('table'=>$this->_table, 'parent_title'=>'商品活动', 'title'=>'商品'), TRUE);
        $this->load->model('Menu_model', '', TRUE);
        $this->load->model('Orders_detail_model', '', TRUE);
        $this->load->model('Groupon_record_model', '', TRUE);
        $this->load->helper(array('url', 'my_fileoperate'));
        $this->load->library(array('Form_validation'));
    }

    public function index($clear = 0, $page = 0) {
        checkPermission("{$this->_template}_index");
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
            $product_num =  trim($this->input->post('product_num'));
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
            if ($product_num) {
                $strWhere .= " and {$this->_table}.product_num = '{$product_num}' ";
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

        $productList = $this->tableObject->gets('*', $strWhere, $paginationConfig['per_page'], $page);
        foreach ($productList as $key => $value) {
            $productList[$key]['title'] = $value['title'] . '&nbsp;&nbsp;' . $this->tableObject->attribute($value['custom_attribute']);
//            //分类
//            $product_category_str = '';
//            $p_c_i_list = $this->Product_category_ids_model->gets('*', array('product_id' => $value['id']));
//            if ($p_c_i_list) {
//                foreach ($p_c_i_list as $p_c_i_key => $p_c_i_value) {
//                    $product_category_str .= $this->Product_category_model->getLocation($p_c_i_value['product_category_id']) . '<br/>';
//                }
//                if ($product_category_str) {
//                    $product_category_str = substr($product_category_str, 0, -1);
//                }
//            }
//            $productList[$key]['product_category_str'] = $product_category_str;
//            //快递模板
//            $postage_way_title = '';
//            $postage_way_info = $this->Postage_way_model->get('title', array('id'=>$value['postage_way_id']));
//            if ($postage_way_info) {
//            	$postage_way_title = $postage_way_info['title'];
//            }
//            $productList[$key]['postage_way_title'] = $postage_way_title;
        }
//        $product_category = $this->Product_category_model->menuTree();
//        //快递模板
//        $postage_way_list = $this->Postage_way_model->gets('id, title');

        $data = array(
            'tool' => $this->_tool,
            'productList' => $productList,
            'pagination' => $pagination,
            'paginationCount' => $paginationCount,
            'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
            'display_arr' => $this->_display_arr,
//            'product_category' => $product_category,
            'attribute_arr' => $this->_attribute_arr,
//        	'postage_way_list'=>$postage_way_list
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
            $product_num = $this->input->post('product_num');
            $display = $this->input->post('display');
            $category_id = $this->input->post('category_id');
            $custom_attribute = $this->input->post('custom_attribute');
            $pay_mode = $this->input->post('pay_mode');
            $startTime = $this->input->post('inputdate_start');
            $endTime = $this->input->post('inputdate_end');

            if (!empty($title)) {
                $strWhere .= " and {$this->_table}.title REGEXP '{$title}'";
            }
            if ($product_num) {
                $strWhere .= " and {$this->_table}.product_num = '{$product_num}' ";
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

        $productList = $this->tableObject->gets('*', $strWhere, $paginationConfig['per_page'], $page);
        foreach ($productList as $key => $value) {
            $productList[$key]['title'] = $value['title'] . '&nbsp;&nbsp;' . $this->tableObject->attribute($value['custom_attribute']);
            $productList[$key]['product_title'] = $value['title'];
            //分类
//            $product_category_str = '';
//            $p_c_i_list = $this->Product_category_ids_model->gets('*', array('product_id' => $value['id']));
//            if ($p_c_i_list) {
//                foreach ($p_c_i_list as $p_c_i_key => $p_c_i_value) {
//                    $product_category_str .= $this->Product_category_model->getLocation($p_c_i_value['product_category_id']) . '<br/>';
//                }
//                if ($product_category_str) {
//                    $product_category_str = substr($product_category_str, 0, -1);
//                }
//            }
//            $productList[$key]['product_category_str'] = $product_category_str;
        }
        $data = array(
            'tool' => $this->_tool,
            'productList' => $productList,
            'pagination' => $pagination,
            'paginationCount' => $paginationCount,
            'pageCount' => ceil($paginationCount / $paginationConfig['per_page']),
            'display_arr' => $this->_display_arr
        );

        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view('product/selector', $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function save($id = NULL) {
        if ($id) {
            checkPermission("{$this->_template}_edit");
        } else {
            checkPermission("{$this->_template}_add");
        }
        $prfUrl = $this->session->userdata('productRefUrl') ? $this->session->userdata('productRefUrl') : base_url() . 'admincp.php/product/index/';
        if ($_POST) {
//        	$postage_way_id = $this->input->post('postage_way_id', TRUE);
//            $brand_name = $this->input->post('brand_name', TRUE);
            $sell_price = $this->input->post('sell_price', TRUE);
            $market_price = $this->input->post('market_price', TRUE);
            $stock = $this->input->post('stock', TRUE);
//            $weight = $this->input->post('weight', TRUE);
//            $give_score = $this->input->post('give_score', TRUE);
//            $consume_score = $this->input->post('consume_score', TRUE);
            $product_num = $this->input->post('product_num', TRUE);
//            $categoryId = $this->input->post('category_id', TRUE);
            $title = $this->input->post('title', TRUE);
            $content = $this->input->post('content');
//            $app_content = $this->input->post('app_content');
//            $custom_attribute = $this->input->post('custom_attribute', TRUE);
//            $product_type = $this->input->post('product_type', TRUE);
            /**************************规格开始**************************/
//            $product_color_name = $this->input->post('product_color_name', TRUE);
//            $product_size_name = $this->input->post('product_size_name', TRUE);
//            $color_size_open = $this->input->post('color_size_open', TRUE);
//            //颜色
//            $attribute_color_ids = $this->input->post('attribute_color_ids', TRUE);
//            $attribute_color_name = $this->input->post('attribute_color_name', TRUE);
//            $attribute_color_hint = $this->input->post('attribute_color_hint', TRUE);
//            $attribute_path = $this->input->post('attribute_path', TRUE);
//            //尺码
//            $attribute_size_ids = $this->input->post('attribute_size_ids', TRUE);
//            $attribute_size_name = $this->input->post('attribute_size_name', TRUE);
//            $attribute_size_hint = $this->input->post('attribute_size_hint', TRUE);
//            //属性价格
//            $attribute_price = $this->input->post('attribute_price', TRUE);
//            //属性数量
//            $attribute_stock = $this->input->post('attribute_stock', TRUE);
//            //属性编号
//            $attribute_product_num = $this->input->post('attribute_product_num', TRUE);
            /**************************规格结束**************************/

//            if (!empty($custom_attribute)) {
//            	$custom_attribute = implode($custom_attribute, ',');
//            } else {
//            	$custom_attribute = '';
//            }
//            if (!$postage_way_id) {
//            	printAjaxError('postage_way_id', '请选择快递模板');
//            }
//            //开启了规格
//            if ($color_size_open) {
//            	if (!$product_color_name || !$product_size_name) {
//            		printAjaxError('fail', '请填写规格名称');
//            	}
//            	if (!$attribute_color_ids) {
//            		printAjaxError('fail', "请选择{$product_color_name}");
//            	}
//            	if (!$attribute_size_ids) {
//            		printAjaxError('fail', "请选择{$product_size_name}");
//            	}
//            	//颜色名称
//            	foreach ($attribute_color_ids as $key=>$value) {
//            		if (!$attribute_color_name[$key]) {
//            			printAjaxError('fail', "{$product_color_name}主属性存在没有填写项");
//            		}
//            	}
//            	$attribute_color_name_filter = array_unique($attribute_color_name);
//            	if (count($attribute_color_name) != count($attribute_color_name_filter)) {
//            		printAjaxError('fail', "{$product_color_name}主属性存在重复项");
//            	}
//            	//尺码名称
//            	foreach ($attribute_size_ids as $key=>$value) {
//            		if (!$attribute_size_name[$key]) {
//            			printAjaxError('fail', "{$product_size_name}主属性存在没有填写项");
//            		}
//            	}
//            	$attribute_size_name_filter = array_unique($attribute_size_name);
//            	if (count($attribute_size_name) != count($attribute_size_name_filter)) {
//            		printAjaxError('fail', "{$product_size_name}主属性存在重复项");
//            	}
//            	//价格
//            	foreach ($attribute_price as $value) {
//            		if (!$value) {
//            			printAjaxError('fail', '属性价格存在没有填写的项!');
//            		}
//            		if (!$this->form_validation->numeric($value)) {
//            			printAjaxError('fail', '请填写正确的属性价格!');
//            		}
//            	}
//            	//库存
//            	foreach ($attribute_stock as $value) {
//            		if (!$this->form_validation->required($value)) {
//            			printAjaxError('fail', '属性库存存在没有填写的项!');
//            		}
//            		if (!$this->form_validation->integer($value)) {
//            			printAjaxError('fail', '请填写正确的属性库存!');
//            		}
//            	}
//            }

            if (!$this->form_validation->required($sell_price)) {
                printAjaxError('sell_price', '出售价不能为空!');
            }
            if (!$this->form_validation->numeric($sell_price)) {
                printAjaxError('sell_price', '请输入正确的出售价!');
            }
            if (!$this->form_validation->required($market_price)) {
                printAjaxError('market_price', '市场价不能为空!');
            }
            if (!$this->form_validation->numeric($market_price)) {
                printAjaxError('market_price', '请输入正确的市场价!');
            }
            if (!$this->form_validation->required($stock)) {
                printAjaxError('stock', '库存数量不能为空!');
            }
            if (!$this->form_validation->integer($stock)) {
                printAjaxError('stock', '请输入正确的库存数量!');
            }
//            if ($weight) {
//                if (!$this->form_validation->integer($weight)) {
//                    printAjaxError('weight', '请输入正确的重量!');
//                }
//            }

            if (!$this->form_validation->required($title)) {
                printAjaxError('title', '产品名称不能为空!');
            }
            if (!$this->form_validation->required($content)) {
                printAjaxError('content', '产品描述不能为空!');
            }
//            if (!$this->form_validation->required($app_content)) {
//                printAjaxError('content', 'App产品描述不能为空!');
//            }
            if ($id) {
                if ($this->tableObject->rowCount("product_num = '{$product_num}' and id <> {$id}")) {
                    printAjaxError('product_num', '产品编号已存在，请换一个');
                }
            } else {
                if ($this->tableObject->rowCount("product_num = '{$product_num}'")) {
                    printAjaxError('product_num', '产品编号已存在，请换一个');
                }
            }

            $fields = array(
//            	'postage_way_id'=>$postage_way_id,
//            	'product_color_name'=>$product_color_name,
//            	'product_size_name'=> $product_size_name,
//            	'color_size_open'=>   $color_size_open,
//                'brand_name' => $brand_name,
                'sell_price' => $sell_price,
                'market_price' => $market_price,
                'stock' => $stock,
//                'weight' => $weight,
//                'give_score' => $give_score,
//                'consume_score' => $consume_score,
                'product_num' => $product_num,
                'title' => $title,
//                'keyword' => $this->input->post('keyword', TRUE),
//                'abstract' => $this->input->post('abstract', TRUE),
                'content' => unhtml($content),
//                'app_content' => unhtml($app_content),
//                'hits' => $this->input->post('hits', TRUE),
//                'sales' => $this->input->post('sales', TRUE),
//                'custom_attribute' => $custom_attribute,
                'path' => $this->input->post('path', TRUE),
                'batch_path_ids' => $this->input->post('batch_path_ids', TRUE),
                'display' => $this->input->post('display', TRUE),
//                'remark' => $this->input->post('remark', TRUE),
                'name' => $this->input->post('name', TRUE),
                'model' => $this->input->post('model', TRUE),
                'power' => $this->input->post('power', TRUE),
                'size' => $this->input->post('size', TRUE),
                'suit_area' => $this->input->post('suit_area', TRUE),
                'content_short' => $this->input->post('content_short', TRUE),
                'add_time' => strtotime($this->input->post('add_time')),
                'display_time' => time(),
            );
            $retId = $this->tableObject->save($fields, $id ? array('id' => $id) : $id);
            if ($retId) {
                $retId = $id ? $id : $retId;
                /*****************添加分类ID******************** */
//                $product_category_id_arr = $this->input->post('product_category_id', TRUE);
//                $this->Product_category_ids_model->delete(array('product_id' => $retId));
//                if ($product_category_id_arr) {
//                    foreach ($product_category_id_arr as $key => $value) {
//                        $id_arr = explode(",", $value);
//                        if ($id_arr && count($id_arr) > 1) {
//                            $pc_fields = array(
//                                'parent_id' => $id_arr[0],
//                                'product_category_id' => $id_arr[1],
//                                'product_id' => $retId
//                            );
//                            $this->Product_category_ids_model->save($pc_fields);
//                        }
//                    }
//                }
                /** **********************尺寸颜色属性****************************** */
//                $this->product_size_color_model->delete(array('product_id'=> $retId));
//                if ($color_size_open) {
//                	if ($attribute_color_ids && $attribute_size_ids) {
//                		foreach ($attribute_color_ids as $key => $value) {
//                			foreach ($attribute_size_ids as $s_key=>$s_value) {
//                				$color_size_fields = array(
//                						'color_name' =>    $attribute_color_name[$key],
//                						'color_name_hint'=>$attribute_color_hint[$key],
//                						'color_id'=>       $key+1,
//                						'path' =>          $attribute_path[$key],
//                						'size_name' =>     $attribute_size_name[$s_key],
//                						'size_name_hint'=> $attribute_size_hint[$s_key],
//                						'size_id'=>        $s_key+1,
//                						'price' =>         $attribute_price[$key*count($attribute_size_ids) + $s_key],
//                						'stock' =>         $attribute_stock[$key*count($attribute_size_ids) + $s_key],
//                						'product_num' =>   $attribute_product_num[$key*count($attribute_size_ids) + $s_key],
//                						'product_id' =>    $retId
//                				);
//                				$this->product_size_color_model->save($color_size_fields);
//                			}
//                		}
//                	}
//                }
                printAjaxSuccess($prfUrl);
            } else {
                printAjaxError('fail',"操作失败！");
            }
        }
        //产品分类
//        $product_category_list = $this->Product_category_model->menuTree();
//        $pci_info = $this->Product_category_ids_model->gets('product_category_id', array('product_id' => $id));
//        $color_size_list = NULL;
//        $color_list = NULL;
//        $size_list = NULL;
        //产品详细
        $item_info = $this->tableObject->get('*', array('id' => $id));
        if ($item_info) {
            //颜色与尺寸
//        	$color_list = $this->product_size_color_model->gets('color_name, color_id, color_name_hint, path', array('product_id' => $item_info['id']), 'color_id');
//        	$size_list = $this->product_size_color_model->gets('size_name, size_id, size_name_hint', array('product_id' => $item_info['id']), 'size_id');
//        	$color_size_list = $color_list;
//            foreach ($color_size_list as $key => $value) {
//                $color_size_list[$key]['size_list'] = $this->product_size_color_model->gets('*', array('product_id' => $item_info['id'], 'color_id' => $value['color_id']), 'size_id');
//            }
        }
        $tmp_product_num = '';
        $tmp_product_info = $this->tableObject->get("max(id) as 'max_id'");
        if ($tmp_product_info) {
            $tmp_product_num = sprintf("C%06d", $tmp_product_info['max_id'] + 1);
        }
        //快递模板
//        $postage_way_list = $this->Postage_way_model->gets('id, title');

        $data = array(
            'tool' => $this->_tool,
            'item_info' => $item_info,
            'tmp_product_num' => $tmp_product_num,
//            'color_size_list' => $color_size_list,
//        	'color_list'=>$color_list,
//        	'size_list'=>$size_list,
//            'product_category_list' => $product_category_list,
//            'pci_info' => $pci_info,
            'display_arr' => $this->_display_arr,
            'attribute_arr' => $this->_attribute_arr,
//        	'postage_way_list'=>$postage_way_list,
        	'prfUrl' => $prfUrl
        );
        $layout = array(
            'title' => $this->_title,
            'content' => $this->load->view('product/save', $data, TRUE)
        );
        $this->load->view('layout/default', $layout);
    }

    public function sort() {
        checkPermissionAjax("{$this->_template}_edit");
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
        checkPermissionAjax("{$this->_template}_delete");
        if ($_POST) {
        	$ids = $this->input->post('ids', TRUE);

        	if (!$ids) {
        		printAjaxError('fail', '请选定操作项');
        	}
//        	//购物车
//        	if ($this->Cart_model->rowCount("product_id in ({$ids})")) {
//        		printAjaxError('fail','购物车存在关联数据，删除失败！');
//        	}
        	//订单详细
        	if ($this->Orders_detail_model->rowCount("product_id in ({$ids})")) {
        		printAjaxError('fail','订单详细存在关联数据，删除失败！');
        	}
        	//收藏夹
        	if ($this->Groupon_record_model->rowCount("groupon_record.product_id in ({$ids})")) {
        		printAjaxError('fail','拼单记录存在关联数据，删除失败！');
        	}
        	if (!empty($ids)) {
        		if ($this->tableObject->delete('id in (' . $ids . ')')) {
        			//删除浏览记录
//        			$this->Product_browse_model->delete("product_id in ({$ids})");
        			//删除属性
//        			$this->Product_size_color_model->delete("product_id in ({$ids})");
        			printAjaxData(array('ids' => explode(',', $ids)));
        		}
        	}

        	printAjaxError('fail', '删除失败！');
        }
    }

    public function getKeycode() {
        $this->load->library('Splitwordclass');
        $title = $this->input->post('title', TRUE);
        if ($title) {
            $splitword = new Splitwordclass();
            $keycode = $splitword->SplitRMM(iconv("UTF-8", "gbk", $title));
            $splitword->Clear();
            $keycode = iconv("gbk", "UTF-8", $keycode);
            printAjaxData(array('keycode' => $keycode));
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

    //使用时改成import(),关掉上面的，将上面的改成下面
    private function _clearStr($str) {
        return iconv("gb2312", "UTF-8", trim($str));
    }

    private function _clearStr2($str) {
        return iconv("gb2312", "UTF-8", preg_replace(array('/\'/'), array(''), trim($str)));
    }

    private function _upload($field, $filePath = './uploads', $ext = 'csv', $maxSize = '1024000') {
        $config['upload_path'] = createDateTimeDir($filePath);
        $config['file_name'] = getUniqueFileName($filePath);
        $config['allowed_types'] = $ext;
        $config['max_size'] = $maxSize;
        $this->load->library('upload', $config);
        $this->_thumbPath = substr($config['upload_path'], 2);

        if ($this->upload->do_upload($field)) {
            return $this->upload->data();
        } else {
            return false;
        }

        return fasle;
    }

    public function get_product_info() {
        if ($_POST){
            $product_id = $this->input->post('product_id', TRUE);
            $product_info = $this->tableObject->get('title, path',array('id'=>$product_id));
            $product_info['comment_display_arr'] = $this->_comment_display_arr;

            printAjaxData($product_info);
        }
    }

    public function get_brand_list() {
    	if ($_POST) {
    		$item_name = trim($this->input->post('item_name', TRUE));

    		$strWhere = "display = 1 ";
    		if ($item_name) {
    			$strWhere .= " and (first_letter REGEXP '^{$item_name}' or brand_name REGEXP '^{$item_name}') ";
    		}
    		$item_list = $this->Brand_model->gets($strWhere);
    		if (!$item_list) {
    			printAjaxError('fail', '没有数据');
    		}
    		printAjaxData($item_list);
    	}
    }

    public function add_comment() {
        if ($_POST){
            $product_id = $this->input->post('product_id', TRUE);
            if(!$product_id){
                printAjaxError('fail', "操作异常！");
            }

            $data = array(
                'product_id' => $product_id,
                'product_title' => $this->input->post('product_title', TRUE),
                'username' => $this->input->post('username', TRUE),
                'grade'=> $this->input->post('grade', TRUE),
                'add_time' => strtotime($this->input->post('add_time', TRUE)),
                'content' => $this->input->post('content', TRUE),
                'display' => $this->input->post('display', TRUE),
                'path' => $this->input->post('path', TRUE)
            );

            if($this->comment_model->save($data)){
                printAjaxSuccess('success', '操作成功');
            } else {
                printAjaxError('fail', "操作失败！");
            }
        }
    }
}

/* End of file news.php */
/* Location: ./application/admin/controllers/news.php */