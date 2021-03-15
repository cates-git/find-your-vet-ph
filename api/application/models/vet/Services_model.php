<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function create($data)
    {
        $data['vet_id'] = $this->session->userdata('logged_in')['id'];
        $this->db->insert(TBL_VET_SERVICES, $data);
        return $this->db->insert_id();
    }
    
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(TBL_VET_SERVICES, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(TBL_VET_SERVICES);
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_VET_SERVICES)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function all($where = [])
    {
        return $this->db->select('*')
                    ->from(TBL_VET_SERVICES)
                    ->where_arr($where)
                    ->get()
                    ->result();
    }

    public function delete_where($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_VET_SERVICES);
    }

    // public function list($page, $where = [])
    // {
    //     return $this->db->select('*')
    //                 ->from(TBL_VET_SERVICES)
    //                 ->where_arr($where)
    //                 ->limit(TABLE_ROWS, $page)
    //                 ->get()
    //                 ->result();
    // }

    // public function total($where = [])
    // {
    //     $query = $this->db->select('count(id) as total')
    //                 ->from(TBL_VET_SERVICES)
    //                 ->where_arr($where)
    //                 ->get();
    //     return (int) $query->row()->total;
    // }
}
?>