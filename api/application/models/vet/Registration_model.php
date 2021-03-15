<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function is_registered($vet_id, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        $query = $this->db->select('*')
                    ->from(TBL_VET_REGISTRATION)
                    ->where('vet_id', $vet_id)
                    ->where('UNIX_TIMESTAMP(expiration_time)>', time())
                    ->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }

    public function is_expired($vet_id, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        $query = $this->db->select('*')
                    ->from(TBL_VET_REGISTRATION)
                    ->where('vet_id', $vet_id)
                    ->where('UNIX_TIMESTAMP(expiration_time)<=', time())
                    ->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }
    
    public function create($data)
    {
        $data['status'] = 1;
        $data['registration_time'] = date('Y-m-d H:i:s');
        $data['expiration_time'] = date('Y-m-d H:i:s', strtotime('+1 year'));
        $data['status'] = 1;
        $this->db->insert(TBL_VET_REGISTRATION, $data);
        return $this->db->insert_id();
    }
    
    public function update($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(TBL_VET_REGISTRATION, $data);
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_VET_REGISTRATION)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function delete($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_VET_REGISTRATION);
    }

    // public function list($where = [])
    // {
    //     return $this->db->select('*')
    //                 ->from(TBL_VET_REGISTRATION)
    //                 ->where_arr($where)
    //                 ->limit(TABLE_ROWS, $page)
    //                 ->get()
    //                 ->result();
    // }

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