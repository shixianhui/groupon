<?php
class Enroll_progress_model extends CI_Model {

	private $_tableName = 'enroll_progress';
	private $_userName = 'user';
	private $_imgName = 'light_img';

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

    public function gets($strWhere = NULL, $limit = NULL, $offset = NULL) {
		$ret = array();
		$this->db->select("{$this->_tableName}.*,{$this->_userName}.nickname,{$this->_imgName}.name");
		$this->db->order_by('id', 'DESC');
		$this->db->join($this->_userName,"{$this->_userName}.id = {$this->_tableName}.user_id");
		$this->db->join($this->_imgName,"{$this->_imgName}.id = {$this->_tableName}.img_id");
		$query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
		if ($query->num_rows() > 0) {
			$ret = $query->result_array();
		}
        return $ret;
	}

    public function gets2($strWhere = NULL, $limit = NULL, $offset = NULL) {
        $ret = array();
        $this->db->select("count({$this->_tableName}.id) as count,{$this->_imgName}.name");
        $this->db->join($this->_imgName,"{$this->_imgName}.id = {$this->_tableName}.img_id");
        $this->db->group_by("{$this->_tableName}.img_id");
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
/* End of file advertising_model.php */
/* Location: ./application/admin/models/advertising_model.php */