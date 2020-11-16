<?php

class Product_category_model extends CI_Model {

    private $_tableName = 'product_category';

    public function __construct() {
        parent::__construct();
    }

    public function save($data, $where = NULL) {
        $ret = 0;

        if (!empty($where)) {
            $ret = $this->db->update($this->_tableName, $data, $where);
        } else {
            $this->db->insert($this->_tableName, $data);
            $ret = $this->db->insert_id();
        }

        return $ret > 0 ? TRUE : FALSE;
    }

    public function delete($where = '') {
        return $this->db->delete($this->_tableName, $where) > 0 ? TRUE : FALSE;
    }

    public function gets($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*");
        $this->db->order_by("{$this->_tableName}.sort", 'ASC');
        $this->db->order_by("{$this->_tableName}.id", 'DESC');
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }

    public function gets2($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*");
        $this->db->order_by("{$this->_tableName}.sort", 'ASC');
        $this->db->order_by("{$this->_tableName}.id", 'DESC');
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }

    public function get($select = '*', $strWhere = NULL) {
        $ret = array();
        $this->db->select($select);
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0];
        }

        return $ret;
    }

    public function rowCount($strWhere = NULL) {
        $count = 0;
        $this->db->select("count(*) as 'count'");
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            $count = $ret[0]['count'];
        }

        return $count;
    }

    public function getChildIds($id) {
        $ids = $id . ',';
        $menuList = $this->gets(array('parent_id' => $id));
        if ($menuList) {
            foreach ($menuList as $menu) {
                $ids .= $menu['id'] . ',';
                $subMenuList = $this->gets(array('parent_id' => $menu['id']));
                if ($subMenuList) {
                    foreach ($subMenuList as $subMenu) {
                        $ids .= $subMenu['id'] . ',';
                    }
                }
            }
        }

        return substr($ids, 0, -1);
    }

    public function menuTree($store_id = 0) {
        $whereArray = array('parent_id' => 0, 'display' => 1, 'store_id' => $store_id);
        $menuList = $this->gets($whereArray);
        foreach ($menuList as $key => $value) {
            $subWhereArray = array('parent_id' => $value['id'], 'display' => 1);
            $subMenuList = $this->gets($subWhereArray);
            foreach ($subMenuList as $sKey => $sValue) {
                $sSubWhereArray = array('parent_id' => $sValue['id'], 'display' => 1);
                $sSubMenuList = $this->gets($sSubWhereArray);
                $subMenuList[$sKey]['subMenuList'] = $sSubMenuList;
            }
            $menuList[$key]['subMenuList'] = $subMenuList;
        }

        return $menuList;
    }

    public function getLocation($id = NULL, $html = false, $url = '') {
        $str = '';
        if ($id) {
            $info = $this->get('id, parent_id, product_category_name', array('id' => $id));
            if (!$info) {
                return $str;
            }
            if ($html) {
                $str = "{$info['product_category_name']} -> ";
            } else {
                $str = "{$info['product_category_name']} -> ";
            }
            if ($info['parent_id'] == 0) {
                return $str;
            }
            $info = $this->get('id, parent_id, product_category_name', array('id' => $info['parent_id']));
            if ($html) {
                $str = "{$info['product_category_name']} -> " . $str;
            } else {
                $str = "{$info['product_category_name']} -> " . $str;
            }
//			if ($info['parent'] == 0) {
//				return $str;
//			}
            //三级
            $info = $this->get('id, parent_id, product_category_name', array('id' => $info['parent_id']));
            if ($info) {
                $str = "{$info['product_category_name']} -> " . $str;
            }
        }

        return $str;
    }

}

/* End of file advertising_model.php */
/* Location: ./application/admin/models/advertising_model.php */