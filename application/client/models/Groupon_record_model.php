<?php

class Groupon_record_model extends CI_Model {

    private $_tableName = 'groupon_record';
    private $_otherTable = 'groupon';
    private $_productTable = 'product';

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
        return $ret > 0 ? $ret : FALSE;
    }

    public function delete($where = '') {
        return $this->db->delete($this->_tableName, $where) > 0 ? TRUE : FALSE;
    }

    public function gets($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*,{$this->_otherTable}.end_time,{$this->_otherTable}.join_people,{$this->_otherTable}.type,{$this->_otherTable}.sale_price,{$this->_otherTable}.min_number");
        $this->db->order_by("{$this->_tableName}.add_time", 'ASC');
        $this->db->order_by("{$this->_tableName}.id", 'DESC');
        $this->db->join($this->_otherTable, "{$this->_tableName}.groupon_id = {$this->_otherTable}.id");
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }
        return $ret;
    }
    public function gets2($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*,{$this->_otherTable}.end_time,{$this->_otherTable}.join_people,{$this->_otherTable}.type,{$this->_otherTable}.sale_price,{$this->_otherTable}.min_number,{$this->_otherTable}.deposit");
        $this->db->order_by("{$this->_tableName}.add_time", 'DESC');
        $this->db->order_by("{$this->_tableName}.id", 'DESC');
        $this->db->join($this->_otherTable, "{$this->_tableName}.groupon_id = {$this->_otherTable}.id");
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }
        return $ret;
    }

    public function get($strWhere = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*,{$this->_otherTable}.end_time,{$this->_otherTable}.join_people,{$this->_otherTable}.deposit,{$this->_otherTable}.type,{$this->_otherTable}.sale_price,{$this->_otherTable}.min_number");
        $this->db->order_by("{$this->_tableName}.id", 'DESC');
        $this->db->join($this->_otherTable, "{$this->_tableName}.groupon_id = {$this->_otherTable}.id");
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0];
        }
        return $ret;
    }

    public function get_rand($strWhere = NULL) {
        $ret = array();
        $this->db->select("{$this->_tableName}.*,{$this->_otherTable}.end_time,{$this->_otherTable}.join_people,{$this->_otherTable}.deposit,{$this->_otherTable}.type,{$this->_otherTable}.sale_price,{$this->_otherTable}.min_number");
        $this->db->order_by("{$this->_tableName}.id", 'RANDOM');
        $this->db->join($this->_otherTable, "{$this->_tableName}.groupon_id = {$this->_otherTable}.id");
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

    public function my_record_list($strWhere = NULL) {
        
    }

}

/* End of file advertising_model.php */
/* Location: ./application/admin/models/advertising_model.php */