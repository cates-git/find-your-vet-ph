<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_us_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function create($data)
    {
        $this->db->insert(TBL_CLIENT_INQUIRY, $data);
        return $this->db->insert_id();
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_CLIENT_INQUIRY)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function list($page, $where = [])
    {
        return $this->db->select('*')
                    ->from(TBL_CLIENT_INQUIRY)
                    ->where_arr($where)
                    ->limit(TABLE_ROWS, $page)
                    ->order_by('id', 'DESC')
                    ->get()
                    ->result();
    }

    public function total($where = [])
    {
        $query = $this->db->select('count(id) as total')
                    ->from(TBL_CLIENT_INQUIRY)
                    ->where_arr($where)
                    ->get();
        return (int) $query->row()->total;
    }
}
?>