<?php
class Product_size_color_model extends CI_Model {

	private $_tableName = 'product_size_color';

	public function __construct() {
		parent::__construct();
	}

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

	public function delete($where = '') {
		return $this->db->delete($this->_tableName, $where) > 0 ? TRUE : FALSE;
	}

	public function get($select = '*', $strWhere = NULL) {
		$ret = array();
		$this->db->select($select);
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0) {
			$ret = $query->result_array();
		}

		return $ret;
	}

	public function gets($select = '*', $strWhere = NULL, $by = NULL) {
		$ret = array();
		$this->db->select($select);
		if (!empty($by)) {
		    $this->db->group_by($by);
		}
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0) {
			$ret = $query->result_array();
		}

		return $ret;
	}
}
/* End of file link_model.php */
/* Location: ./application/admin/models/link_model.php */