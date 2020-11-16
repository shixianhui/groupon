<?php

class User_model extends CI_Model {

    private $_tableName = 'user';
    private $_usergroupTName = 'user_group';

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
        $this->db->select("{$this->_tableName}.*, {$this->_usergroupTName}.group_name");
        $this->db->order_by("{$this->_tableName}.id", 'DESC');
        $this->db->join($this->_usergroupTName, "{$this->_usergroupTName}.id = {$this->_tableName}.user_group_id", 'left');
        $query = $this->db->get_where($this->_tableName, $strWhere, $limit, $offset);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
        }
        return $ret;
    }

    public function get($select = '*', $strWhere = NULL) {
        $this->db->select($select);
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0];
        }
        return array();
    }

    public function getInfo($select = '*', $strWhere = NULL) {
        $this->db->select($select);
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            return $ret[0];
        }
            return array();
    }

    public function validateUnique($username) {
        $adminInfo = $this->get('*', array("lower(username)" => strtolower($username)));
        if ($adminInfo) {
            return true;
        }

        return false;
    }

    public function rowCount2($strWhere = NULL) {
        $count = 0;
        $this->db->select("count(*) as 'count'");
        $this->db->join($this->_usergroupTName, "{$this->_usergroupTName}.id = {$this->_tableName}.user_group_id", 'left');
        $query = $this->db->get_where($this->_tableName, $strWhere);
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            $count = $ret[0]['count'];
        }

        return $count;
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

    public function login($username, $password) {
        $userInfo = $this->get('*', array("lower(username)" => strtolower($username)));
        if ($userInfo) {
            if ($userInfo['password'] == $this->getPasswordSalt($username, $password)) {
                return $userInfo;
            }
        }

        return false;
    }

    public function getPasswordSalt($username, $password) {
        $addTime = 0;
        $this->db->select("{$this->_tableName}.add_time");
        $query = $this->db->get_where($this->_tableName, array('lower(username)' => strtolower($username)));
        if ($query->num_rows() > 0) {
            $ret = $query->result_array();
            $addTime = $ret[0]['add_time'];
        }

        return md5(strtolower($username) . $addTime . $password);
    }

}

/* End of file admin_model.php */
/* Location: ./application/admin/models/admin_model.php */