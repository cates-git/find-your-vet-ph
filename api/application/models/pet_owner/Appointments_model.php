<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function create($data)
    {
        $this->db->insert(TBL_APPOINTMENTS, $data);
        return $this->db->insert_id();
    }
    
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(TBL_APPOINTMENTS, $data);
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_APPOINTMENTS)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function list($where = [])
    {
        return $this->db->select('a.*, CONCAT(v.first_name, " ", v.last_name) as vet, CONCAT(o.first_name, " ", o.last_name) as pet_owner, p.name as pet_name, p.pet_owner_id, r.rating, r.comment')
                    ->from(TBL_APPOINTMENTS . ' AS a')
                    ->join(TBL_USERS . ' AS v', 'a.vet_id = v.id', 'LEFT')
                    ->join(TBL_PETS . ' AS p', 'a.pet_id = p.id', 'LEFT')
                    ->join(TBL_USERS . ' AS o', 'p.pet_owner_id = o.id', 'LEFT')
                    ->join(TBL_VET_RATINGS . ' AS r', 'r.appointment_id = a.id', 'LEFT')
                    ->where_arr($where)
                    ->get()
                    ->result();
    }

    // public function total($where = [])
    // {
    //     $query = $this->db->select('count(a.id) as total')
    //                 ->from(TBL_APPOINTMENTS . ' AS a')
    //                 ->join(TBL_USERS . ' AS v', 'a.vet_id = v.id', 'LEFT')
    //                 ->join(TBL_PETS . ' AS p', 'a.pet_id = p.id', 'LEFT')
    //                 ->join(TBL_USERS . ' AS o', 'p.pet_owner_id = o.id', 'LEFT')
    //                 ->where_arr($where)
    //                 ->get();
    //     return (int) $query->row()->total;
    // }

    public function delete($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_APPOINTMENTS);
    }
}
?>