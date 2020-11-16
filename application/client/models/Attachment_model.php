<?php
class Attachment_model extends CI_Model {

	private $_tableName = 'attachment';

	public function __construct() {
		parent::__construct();
	}

	public function gets($select = '*', $strWhere = NULL) {
		$ret = array();
		$this->db->select($select);
		$query = $this->db->get_where($this->_tableName, $strWhere);
		if ($query->num_rows() > 0) {
			$ret = $query->result_array();
		}

		return $ret;
	}
	
	public function gets2($ids = '') {
	    $ret = array();
	    $query = $this->db->query("select * from {$this->_tableName} where id in ({$ids}) order by field(id, {$ids})");
	    if ($query->num_rows() > 0) {
	        $ret = $query->result_array();
	    }
	
	    return $ret;
	}

    public function gets3($ids = '') {
        $ret = array();
        $query = $this->db->query("select path from {$this->_tableName} where id in ({$ids}) order by field(id, {$ids})");
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }

        return $ret;
    }
}
/* End of file link_model.php */
/* Location: ./application/admin/models/link_model.php */