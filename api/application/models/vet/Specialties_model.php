<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialties_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    /** Check if email exists
     * 
     * @param email String
     * @return bool true if exists, false if not
     */
    public function is_name_exists($name, $id = null)
    {
        if ($id) {
            $this->db->where('id!=', $id);
        }
        $query = $this->db->select('name')
                    ->from(TBL_VET_SPECIALTIES)
                    ->where('name', $name)
                    ->get();

        return $query->num_rows() > 0;
    }
    
    public function create($data)
    {
        $data['vet_id'] = $this->session->userdata('logged_in')['id'];
        $this->db->insert(TBL_VET_SPECIALTIES, $data);
        return $this->db->insert_id();
    }
    
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(TBL_VET_SPECIALTIES, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(TBL_VET_SPECIALTIES);
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_VET_SPECIALTIES)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function delete_where($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_VET_SPECIALTIES);
    }

    public function list($where = [])
    {
        return $this->db->select('*')
                    ->from(TBL_VET_SPECIALTIES)
                    ->where_arr($where)
                    ->get()
                    ->result();
    }

    // public function total($where = [])
    // {
    //     $query = $this->db->select('count(id) as total')
    //                 ->from(TBL_VET_SPECIALTIES)
    //                 ->where_arr($where)
    //                 ->get();
    //     return (int) $query->row()->total;
    // }
}
?>