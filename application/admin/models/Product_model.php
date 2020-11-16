<?php
class Product_model extends CI_Model {

	private $_tableName = 'product';

    public function __construct() {
		parent::__construct();
	}

	/**
	 * save data
	 *
	 * @param $data is array
	 * @param $where is array or string
	 * @return boolean
	 */
	public function save($data, $where = NULL) {
		$ret = 0;
		if (! empty($where)) {
			$ret = $this->db->update($this->_tableName, $data, $where);
		} else {
			$this->db->insert($this->_tableName, $data);
			$ret = $this->db->insert_id();
		}

		return $ret > 0 ? $ret : FALSE;
	}

	/**
	 * delete data
	 *
	 * @param $where is array or string
	 * @return boolean
	 */
	public function delete($where = '') {
		return $this->db->delete($this->_tableName, $where) > 0 ? TRUE : FALSE;
	}

	/**
	 * select data
	 *
	 * @param $strWhere is string
	 * @param $limit is int
	 * @param $offset is int
	 * @return array
	 */
	public function gets($select = '*', $strWhere = NULL, $limit = NULL, $offset = NULL) {
		$ret = array();
		$this->db->select($select);
		$this->db->order_by("{$this->_tableName}.id", 'DESC');
		$query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
		if ($query->num_rows() > 0) {
			$ret = $query->result_array();
		}

		return $ret;
	}

   /**
	 * select info
	 *
	 * @param $select is string
	 * @param $strWhere is string
	 * @return array
	 */
	public function get($select = '*', $strWhere = NULL) {
		$this->db->select($select);
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0) {
			$ret = $query->result_array();
			return $ret[0];
		}

		return array();
	}

	public function attribute($attributeStr = 'h,c') {
		$strAttribute = '';
		$attribute = array(
		             'h'=>'<font color=#FF0000>App_精品推荐</font>',
		             'c'=>'<font color=#FF0000>推送首页</font>',
		             'a'=>'<font color=#FF0000>精品推荐</font>',
		             'f'=>'<font color=#FF0000>热门关注</font>',
		             's'=>'<font color=#FF0000>App_推送首页</font>',
		             'b'=>'<font color=#FF0000>App_每日上新</font>',
		             'p'=>'<font color=#FF0000>App_特价专区</font>',
		             'j'=>'<font color=#FF0000>App_唐束专区</font>'
		             );
		if (! empty($attributeStr)) {
			$attributeArray = explode(',', $attributeStr);
			$strAttribute = '[';
			foreach ($attributeArray as $key=>$value) {
			    $strAttribute .= '&nbsp;'.$attribute[$value];
			}
			$strAttribute .= ']';
		}
		return $strAttribute;
	}

	/**
	 * select
	 *
	 * @param $strWhere is string
	 * @return int
	 */
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
        
    public function getDetailColor($productId = 0) {
        $ret = array();
        $query = $this->db->query("select distinct color_id, color_name,path from product_size_color where product_id = {$productId} order by color_id ASC");
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }

    public function getDetailSize($productId = 0) {
        $ret = array();
        $query = $this->db->query("select distinct size_id, size_name from product_size_color where product_id = {$productId} order by size_id ASC");
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }
        
    public function getProductStock($product_id = 0, $color_id = 0, $size_id = 0) {
        $ret = array();
        $query = $this->db->query("select stock, price, product_num from product_size_color where product_id = {$product_id} and color_id = {$color_id} and size_id = {$size_id} limit 1");
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret ? $ret[0] : $ret;
    }

    /**
     * @param type $data
     * @param type $where
     * @return type
     */
    public function changeStock($data, $where = null) {
        $ret = 0;
        if (!empty($where)) {
            $ret = $this->db->update('product_size_color', $data, $where);
            if (isset($where['product_id'])) {
                $query = $this->db->query('select sum(stock) as stock from product_size_color where product_id = ' . $where['product_id']);
                if ($query->num_rows() > 0) {
                    $result = $query->result_array();
                    $stock = $result[0]['stock'];
                    $this->save(array('stock'=>$stock),array('id'=>$where['product_id']));
                }
            }
        } else {
            $this->db->insert($this->_tableName, $data);
            $ret = $this->db->insert_id();
        }
        return $ret > 0 ? TRUE : FALSE;
    }
}
/* End of file product_model.php */
/* Location: ./application/admin/models/product_model.php */