<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    /** Check if email exists
     * 
     * @param email String
     * @param id Int - for update, leave null for create
     * @return bool true if exists, false if not
     */
    public function is_email_exists($email, $id = NULL)
    {
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->select('email')
                    ->from(TBL_USERS)
                    ->where('email', $email)
                    ->get();

        return $query->num_rows() > 0;
    }

    /**
     * creates user
     * @return id of new user
     */
    public function create($data)
    {
		// encrypt password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $this->db->insert(TBL_USERS, $data);
        return $this->db->insert_id();
    }

    public function update_password($new_password, $id)
    {
        $data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
        $this->db->where('id', $id);
        return $this->db->update(TBL_USERS, $data);
    }

    public function update($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(TBL_USERS, $data);
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_USERS)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function registration_fee()
    {
        return $this->db->select('*')
                    ->from(TBL_REGISTRATION_FEE)
                    ->limit(1)
                    ->get()
                    ->row();
    }

    public function update_registration_fee($data, $id)
    {
        if ( ! $id) 
        {
            return $this->db->insert(TBL_REGISTRATION_FEE, $data);
        }
        $this->db->where('id', $id);
        return $this->db->update(TBL_REGISTRATION_FEE, $data);
    }

    public function get_by_id($id, $fields = 'email, first_name, last_name, address, avatar')
    {
        return $this->db->select($fields)
                    ->from(TBL_USERS)
                    ->where('id', $id)
                    ->get()
                    ->row();
    }

    public function vets()
    {
        return $this->db->select('v.id, v.first_name, v.last_name, v.address, v.email, r.registration_time, r.expiration_time')
                    ->from(TBL_USERS . ' AS v')
                    ->join(TBL_VET_REGISTRATION . ' AS r', 'r.vet_id = v.id', 'LEFT')
                    ->where('v.type', 1)
                    ->get()
                    ->result();
    }

    public function all($where = [])
    {
        return $this->db->select('id, first_name, last_name, CONCAT(first_name, " ", last_name) as name, address, email')
                    ->from(TBL_USERS)
                    ->where_arr($where)
                    ->get()
                    ->result();
    }

    public function delete($user_id)
    {   
        try 
        {
            $this->db->trans_start();
            $type = (int) $this->get_by_id($user_id, 'type')->type;
            $this->load->model('pet_owner/Appointments_model');
            $this->load->model('vet/Rate_model');
            if ($type === PET_OWNER)
            {
                $this->load->model('pet_owner/Pets_model');
                // delete pets, get pets id use in appointments
                $pets = $this->Pets_model->all(['pet_owner_id' => $user_id], 'id');
                if ($pets)
                {
                    // delete appointments
                    $this->Appointments_model->delete(['pet_id' => array_column($pets, 'id')]);

                    // delete pets
                    $pets = $this->Pets_model->delete(['pet_owner_id' => $user_id]);
                }

                // delete ratings
                $this->Rate_model->delete(['pet_owner_id' => $user_id]);

            }
            elseif ($type === VETERINARIAN)
            {
                // appointment
                $this->Appointments_model->delete(['vet_id' => $user_id]);

                // documents
                $this->load->model('vet/Documents_model');
                $this->Documents_model->delete_where(['vet_id' => $user_id]);
                
                // delete ratings
                $this->Rate_model->delete(['vet_id' => $user_id]);

                // registration
                $this->load->model('vet/Registration_model');
                $this->Registration_model->delete(['vet_id' => $user_id]);

                // services and promos
                $this->load->model('vet/Services_model');
                $this->Services_model->delete_where(['vet_id' => $user_id]);

                // specialties
                $this->load->model('vet/Specialties_model');
                $this->Specialties_model->delete_where(['vet_id' => $user_id]);

            }

            // delete messages
            $this->load->model('Message_model');
            $this->Message_model->delete(['sender' => $user_id]);
            $this->Message_model->delete(['receiver' => $user_id]);

            // delete comments
            $this->load->model('Posts_model');
            $this->Posts_model->delete_comments(['user_id' => $user_id]);

            // delete posts
            $this->Posts_model->delete(['user_id' => $user_id]);
            $this->Posts_model->delete(['user_group_id' => $user_id]);
            
            // delete user
            $this->db->where('id', $user_id)->delete(TBL_USERS);
        }
        catch (Exeption $e)
        {
            $this->db->trans_rollback();
			return $e->getMessage();
        }

        $this->db->trans_complete();
        return TRUE;
    }

    // public function list($page, $where = [], $like = [])
    // {
    //     $this->set_like($like);

    //     return $this->db->select('*')
    //                 ->from(TBL_USERS)
    //                 ->where_arr($where)
    //                 ->limit(TABLE_ROWS, $page)
    //                 ->get()
    //                 ->result();
    // }

    // public function total($where = [], $like = [])
    // {
    //     $this->set_like($like);

    //     $query = $this->db->select('count(id) as total')
    //                 ->from(TBL_USERS)
    //                 ->where_arr($where)
    //                 ->get();
    //     return (int) $query->row()->total;
    // }

    // private function set_like($like = [])
    // {
    //     if ( ! $like) return;
        
    //     $this->db->group_start();
    //     $this->db->or_like($like);
    //     $this->db->group_end();
    // }
}
?>