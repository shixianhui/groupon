<?php

class Enroll_progress_model extends CI_Model {

    private $_tableName = 'enroll_progress';
    private $_userTName = 'user';

    public function __construct() {
        parent::__construct();
    }

    public function gets($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*, {$this->_userTName}.username, {$this->_userTName}.nickname, {$this->_userTName}.path");
        $this->db->join($this->_userTName, "{$this->_userTName}.id = {$this->_tableName}.user_id");
        $this->db->order_by("{$this->_tableName}.add_time", 'DESC');
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }

    public function gets2($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("count(id) as count,img_id");
        $this->db->group_by("img_id");
        $this->db->order_by("img_id", 'ASC');
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }

    public function gets3($count,$strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("count(distinct img_id) as count,enroll_id");
        $this->db->group_by("enroll_id");
        $this->db->having("count = {$count}");
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }

    public function get($select = '*', $strWhere = NULL) {
        $ret = array();
        $this->db->select($select);
        $this->db->order_by("id", 'DESC');
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0];
        }

        return $ret;
    }

    public function get2($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*, {$this->_userTName}.username, {$this->_userTName}.nickname, {$this->_userTName}.path");
        $this->db->join($this->_userTName, "{$this->_userTName}.id = {$this->_tableName}.user_id");
        $this->db->order_by("{$this->_tableName}.id", 'DESC');
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0];
        }

        return $ret;
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
        if (!empty($where)) {
            $ret = $this->db->update($this->_tableName, $data, $where);
        } else {
            $this->db->insert($this->_tableName, $data);
            $ret = $this->db->insert_id();
        }

        return $ret > 0 ? $ret : FALSE;
    }

    public function save2($column, $data, $where = NULL) {
        $ret = 0;

        if (!empty($where)) {
            $this->db->set($column, $data, FALSE);
            $this->db->where($where);
            $ret = $this->db->update($this->_tableName);
        }

        return $ret > 0 ? $ret : FALSE;
    }

    public function get_max_id($strWhere = NULL) {
        $this->db->select("max({$this->_tableName}.id) as 'max_id'");
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0]['max_id'] ? $ret[0]['max_id'] : 0;
        }
        return 0;
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
     * delete data
     *
     * @param $where is array or string
     * @return boolean
     */
    public function delete($where = '') {
        return $this->db->delete($this->_tableName, $where) > 0 ? TRUE : FALSE;
    }

    public function get_sum($column = 'number',$strWhere = NULL) {
    	$this->db->select("sum({$this->_tableName}.{$column}) as 'sum_number'");
    	$query = $this->db->get_where($this->_tableName, $strWhere);
    	if ($query->num_rows() > 0) {
    		$ret = $query->result_array();
    		return $ret[0]['sum_number'] ? $ret[0]['sum_number'] : 0;
    	}
    	return 0;
    }
}

/* End of file advertising_model.php */
/* Location: ./application/admin/models/advertising_model.php */