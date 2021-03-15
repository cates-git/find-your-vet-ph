<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        is_logged_in();
		$this->load->model('Users_model');
    }

    public function update_photo()
    {
        $file_name = $_FILES ? upload_file() : '';
        $data['avatar'] = $file_name;

        $this->Users_model->update($data, get_user_data()['id']);
        
		json_response(TRUE, 'Uploaded successfully');
    }
    
    public function all()
    {
        $where['type'] = $this->input->post('type');

        if ((int) $where['type'] === PET_OWNER && get_user_type() === PET_OWNER) {
            $where['id!='] = get_user_data()['id'];
        }
        
        if ((int) $where['type'] === VETERINARIAN) 
        {
            $data = $this->Users_model->all($where);
            
            $this->load->model('vet/Registration_model');
            foreach ($data as $key => $value) 
            {
                $reg = $this->Registration_model->is_registered($value->id);
                if ($reg)
                {
                    $data[$key]->expired = FALSE;
                    $data[$key]->registration_time = $reg->registration_time;
                    $data[$key]->expiration_time = $reg->expiration_time;

                }
                else
                {
                    $is_expired = $this->Registration_model->is_expired($value->id);
                    if ($is_expired) 
                    {
                        $data[$key]->expired = TRUE;
                        $data[$key]->registration_time = $is_expired->registration_time;
                        $data[$key]->expiration_time = $is_expired->expiration_time;
                    }
                }
            }
        }
        else 
        {
            $data = $this->Users_model->all($where);
        }
        
        json_response(TRUE, '', $data);
    }
    
    // public function list()
    // {
    //     $page = set_page($this->input->post('page'));

    //     $where['type'] = $this->input->post('type');
        
    //     $data = [
    //         'list' => $this->Users_model->list($page, $where),
    //         'total' => $this->Users_model->total($where)
    //     ];
        
    //     json_response(TRUE, '', $data);
    // }

    public function get($id)
    {
        if ( ! $id) {
            json_response(FALSE, 'Invalid id');
        }
        
        $data = $this->Users_model->get_by_id($id);
        $data->avatar = $data->avatar ? base_url('uploads/' . $data->avatar) : NULL;
        
        $this->load->model('vet/Rate_model');
        $where['vet_id'] = get_user_type() == VETERINARIAN 
                ? get_user_data()['id'] 
                : $id;

        $ratings = $this->Rate_model->all($where);

        $data->rating = $ratings ? array_sum(array_column($ratings, 'rating')) / count($ratings) : 0;
        
        json_response(TRUE, '', $data);
    }

    public function registration_fee()
    {
        $data = $this->Users_model->registration_fee();
        if ( ! $data) 
        {
            $data = [
                'id' => 0,
                'account' => '',
                'amount' => '',
                'email' => ''
            ];
        }
        json_response(TRUE, '', $data);
    }

    public function update_registration_fee()
    {
		$fields = ['account', 'amount', 'email'];

		$data = parse_data($_POST, $fields);

        $this->Users_model->update_registration_fee($data, $this->input->post('id'));
        
		json_response(TRUE, 'Updated successfully');
	}

    public function update_name()
    {
		$fields = ['first_name', 'last_name'];

        $data = parse_data($_POST, $fields);
        
        $id = get_user_data()['id'];

        $this->Users_model->update($data, $id);
        
		json_response(TRUE, 'Updated successfully');
    }

	public function create_admin()
	{
        $data = $this->validate_create_admin();
        $data['type'] = 0;

		try 
		{
			$this->db->trans_start();
			
			$user_id = $this->Users_model->create($data);

			$this->db->trans_complete();
		}
		catch(Exeption $e)
		{
			json_response(FALSE, $e->getMessage());
        }
        
        json_response(TRUE, 'Account created successfully');
	}
    
	private function validate_create_admin() 
	{
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
		}

		$fields = ['first_name', 'last_name', 'email', 'password'];

		$data = parse_data($_POST, $fields);

		if ( ! isset($data['password']))
		{
			json_response(FALSE, 'Please provide a password');
		}

		if($this->Users_model->is_email_exists($data['email']))
		{
			json_response(FALSE, 'Email is already used. Please use another.');
		}

		return $data;
    }
    
    public function delete($user_id)
    {
        if (get_user_type() !== ADMIN || get_user_data()['id'] == $user_id) 
        {
			json_response(FALSE, 'You are not authorized to delete the user.');
        }

        if ( ! $user_id) 
        {
			json_response(FALSE, 'User id not found');
        }

        $msg = $this->Users_model->delete($user_id);
        if ($msg !== TRUE)
        {
            json_response(FALSE, $msg);
        }

        json_response(TRUE, 'User deleted successfully');
    }
}
?>