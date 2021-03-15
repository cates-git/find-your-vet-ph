<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pets_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    /** Check if name exists
     * 
     * @param name String
     * @return bool true if exists, false if not
     */
    public function is_name_exists($name, $id = null)
    {
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->select('name')
                    ->from(TBL_PETS)
                    ->where('name', $name)
                    ->get();

        return $query->num_rows() > 0;
    }

    public function get($id, $fields = '*')
    {
        $query = $this->db->select($fields)
                    ->from(TBL_PETS)
                    ->where('id', $id)
                    ->get();

        return $query->row();
    }
    
    public function create($data)
    {
        $data['pet_owner_id'] = $this->session->userdata('logged_in')['id'];
        $this->db->insert(TBL_PETS, $data);
        return $this->db->insert_id();
    }
    
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(TBL_PETS, $data);
    }

    public function all($where = [], $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_PETS)
                    ->where_arr($where)
                    ->get()
                    ->result();
    }

    public function delete($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_PETS);
    }
}
?>