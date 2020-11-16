<?php
class Product_category_ids_model extends CI_Model {

	private $_tableName = 'product_category_ids';
	private $_productCategoryTName = 'product_category';

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

		return $ret > 0 ? TRUE : FALSE;
	}

	public function delete($where = '') {
		return $this->db->delete($this->_tableName, $where) > 0 ? TRUE : FALSE;
	}

	public function gets($select = '*', $strWhere = NULL) {
		$ret = array();
		$this->db->select($select);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0){
			$ret = $query->result_array();
		}

		return $ret;
	}
	
	public function gets2($strWhere = NULL) {
		$ret = array();
		$this->db->select("{$this->_tableName}.parent_id, {$this->_productCategoryTName}.product_category_name");
		$this->db->order_by("{$this->_tableName}.id", 'DESC');
		$this->db->join($this->_productCategoryTName, "{$this->_tableName}.product_category_id = {$this->_productCategoryTName}.id");
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0){
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
        
        public function get_category_num($select = '*',$strWhere = null ,$group_by){
             $ret = array();
		$this->db->select($select);
		$this->db->order_by('id', 'DESC');
		$this->db->group_by($group_by);
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0){
			$ret = $query->result_array();
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
}
/* End of file admingroup_model.php */
/* Location: ./application/admin/models/admingroup_model.php */