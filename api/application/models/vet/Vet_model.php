<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vet_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function list($page, $where = [])
    {
        return $this->db->select('*')
                    ->from(TBL_USERS)
                    ->where_arr($where)
                    ->where('type', VETERINARIAN)
                    ->limit(TABLE_ROWS, $page)
                    ->get()
                    ->result();
    }

    public function total($where = [])
    {
        $query = $this->db->select('count(id) as total')
                    ->from(TBL_USERS)
                    ->where_arr($where)
                    ->where('type', VETERINARIAN)
                    ->get();
        return (int) $query->row()->total;
    }
}
?>