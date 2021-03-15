<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rate_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function is_rated($appointment_id, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
        }

        $query = $this->db->select('*')
                    ->from(TBL_VET_RATINGS)
                    ->where('appointment_id', $appointment_id)
                    ->get();

        return $query->num_rows() > 0;
    }
    
    public function create($data)
    {
        $data['pet_owner_id'] = get_user_data()['id'];
        $this->db->insert(TBL_VET_RATINGS, $data);
        return $this->db->insert_id();
    }
    
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(TBL_VET_SERVICES, $data);
    }

    public function get($appointment_id)
    {
        return $this->db->select($fields)
                    ->from(TBL_VET_RATINGS)
                    ->where('appointment_id', $appointment_id)
                    ->get()
                    ->row();
    }

    public function all($where = [])
    {
        return $this->db->select('r.*, CONCAT(o.first_name, " ", o.last_name) as pet_owner')
                    ->from(TBL_VET_RATINGS . ' AS r')
                    ->join(TBL_USERS . ' AS o', 'r.pet_owner_id = o.id')
                    ->where_arr($where)
                    ->get()
                    ->result();
    }

    public function delete($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_VET_RATINGS);
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