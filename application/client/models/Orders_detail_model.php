<?php
class Orders_detail_model extends CI_Model {

	private $_tableName = 'orders_detail';
	private $_productTName = 'product';

	public function __construct() {
		 parent::__construct();
	}

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

	public function gets2($strWhere = NULL, $limit = NULL, $offset = NULL) {
		$ret = array();
		$this->db->select("{$this->_tableName}.*, {$this->_productTName}.divide_total, {$this->_productTName}.divide_store_price");
		$this->db->order_by("{$this->_tableName}.id", 'DESC');
		$this->db->join($this->_productTName, "{$this->_tableName}.product_id = {$this->_productTName}.id");
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
		if ($query->num_rows() > 0){
			$ret = $query->result_array();
			return $ret[0];
		}

		return $ret;
	}

    public function get2($select = '*', $strWhere = NULL) {
        $ret = array();
        $this->db->select($select);
        $this->db->join($this->_productTName, "{$this->_tableName}.product_id = {$this->_productTName}.id");
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0){
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

	public function get_order_product_sum($strWhere = NULL) {
		$count = 0;
		$this->db->select("sum(buy_number) as 'sum'");
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0) {
			$ret = $query->result_array();
			$count = $ret[0]['sum'];
		}

		return $count?$count:0;
	}
}
/* End of file advertising_model.php */
/* Location: ./application/admin/models/advertising_model.php */