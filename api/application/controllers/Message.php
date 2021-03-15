<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
        $this->load->model('Message_model');
	}
	
	public function create ()
	{
        $data = $this->validate();

        $this->Message_model->create($data);
        
		json_response(TRUE, 'Message sent');
    }
    
	private function validate($update = FALSE) 
	{        
		if (has_empty_post())
		{
			json_response(FALSE, 'Fill in the empty fields');
        }

        $data = parse_data($_POST, ['message', 'receiver']);

		return $data;
    }
    
    public function all()
    {
		$data = $this->Message_model->user_messages();
		$user_id = (int) get_user_data()['id'];
		foreach ($data as $key => $value) 
		{
			if ($user_id === (int) $value->sender)
			{
				$data[$key]->avatar = $value->receiver_avatar ? base_url('uploads/' . $value->receiver_avatar) : NULL;
			}
			else
			{
				$data[$key]->avatar = $value->sender_avatar ? base_url('uploads/' . $value->sender_avatar) : NULL;
			}
		}
        
        json_response(TRUE, '', $data);
    }

}
?>