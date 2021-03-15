<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Users_model');
	}

	public function get()
	{
		$user_data = $this->Users_model->get(['id' => get_user_data()['id']]);

		if ( ! $user_data)
		{
			$this->session->sess_destroy();
			header("HTTP/1.1 401 Unauthorized");
			die();
		}

		$data = array(
			'id'          => (int) $user_data->id,
			'first_name'  => $user_data->first_name,
			'last_name'   => $user_data->last_name,
			'address'     => $user_data->address,
			'email'       => $user_data->email,
			'type'        => (int) $user_data->type,
			'create_time' => $user_data->create_time,
			'avatar'      => $user_data->avatar ? base_url('uploads/' . $user_data->avatar) : NULL
		);

		json_response(TRUE, '', $data);
	}
	
	public function check_session()
	{
		is_logged_in();
		$this->get();
	}

	/** Creates user from create an account page */
	public function create()
	{
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
		}

		$data = $this->validate_create();

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

		$session_data = [
			'id'         => (int) $user_id,
			'first_name' => $data['first_name'],
			'last_name'  => $data['last_name'],
			'address'    => $data['address'],
			'email'      => $data['email'],
			'type'       => (int) $data['type']
		];

		$this->session->set_userdata('logged_in', $session_data);
		$_SESSION['user'] = $session_data;
        json_response(TRUE, 'Created account successfully', $session_data);
	}
    
    public function sign_in()
    {
		if (has_empty_post())
		{
			json_response(FALSE, ['email' => 'Fill in the empty fields']);
		}

		$user_data = $this->validate_login();

		$session_data = array(
			'id'         => (int) $user_data->id,
			'first_name' => $user_data->first_name,
			'last_name'  => $user_data->last_name,
			'address'  	 => $user_data->address,
			'email'      => $user_data->email,
			'type'       => (int) $user_data->type
		);

		$this->session->set_userdata('logged_in', $session_data);
		
		json_response(TRUE, 'Signed in successfully', $session_data);

	}

	public function sign_out()
	{
        $this->session->sess_destroy();
		json_response(TRUE);
	}

	private function validate_create() 
	{
		$fields = ['first_name', 'last_name', 'address', 'email', 'password', 'type'];

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

	private function validate_login()
	{
		$fields = ['email', 'password'];

		$data = parse_data($_POST, $fields);

		if( ! $this->Users_model->is_email_exists($data['email']))
		{
			json_response(FALSE, ['email' => 'Couldn\'t find your account']);
		}

		$user_data = $this->Users_model->get(['email' => $data['email']]);

		if( ! password_verify($data['password'], $user_data->password))
		{			
			json_response(FALSE, ['password' => 'Wrong password. Try again']);
		}

		return $user_data;
	}

	public function change_password() 
	{
		$fields = ['current_password', 'new_password'];

		$data = parse_data($_POST, $fields);

		$user_id = get_user_data()['id'];
		$user_data = $this->Users_model->get(['id' => $user_id]);

		if( ! password_verify($data['current_password'], $user_data->password))
		{			
			json_response(FALSE, 'Wrong current password. Try again');
		}

		$this->Users_model->update_password($data['new_password'], $user_id);

		json_response(TRUE, 'Updated successfully');
	}
}
