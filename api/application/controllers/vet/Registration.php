<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
        is_logged_in();
		$this->load->model('vet/Registration_model');
	}
	
	public function create()
	{
        $data = $this->validate();

        $this->Registration_model->create($data);
        
		json_response(TRUE, 'Activated successfully');
	}

	private function validate($update = FALSE) 
	{
		$fields = ['vet_id'];

		$data = parse_data($_POST, $fields);

		$id = $update ? $this->input->post('id') : NULL;

		if($this->Registration_model->is_registered($data['vet_id'], $id))
		{
			json_response(FALSE, 'Vet\'s account already activated.');
		}

		return $data;
    }
    
    public function get()
    {
        $where['vet_id'] = get_user_type() === VETERINARIAN 
                            ? get_user_data()['id'] 
							: $this->post->input('vet_id');
		
        
        $reg = $this->Registration_model->get($where);

        if ( ! $reg) 
        {
            json_response(FALSE, 'Not registered');
        }
		$registered = $this->Registration_model->is_registered($reg->vet_id);
		if ($registered)
		{
			$reg->expired = FALSE;
			$reg->registration_time = $registered->registration_time;
			$reg->expiration_time = $registered->expiration_time;

		}
		else
		{
			$is_expired = $this->Registration_model->is_expired($reg->vet_id);
			if ($is_expired) 
			{
				$reg->expired = TRUE;
				$reg->registration_time = $is_expired->registration_time;
				$reg->expiration_time = $is_expired->expiration_time;
			}
		}
        
        json_response(TRUE, '', $reg);
    }

}
?>