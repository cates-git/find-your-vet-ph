<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function create($data)
    {
        $data['sender'] = get_user_data()['id'];
        $this->db->insert(TBL_MESSAGES, $data);
        return $this->db->insert_id();
    }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_MESSAGES)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function user_messages()
    {
        $user = get_user_data();
        $this->db->group_start();
        $this->db->or_where('sender', $user['id']);
        $this->db->or_where('receiver', $user['id']);
        if ((int) $user['type'] === ADMIN) 
        {
            $this->db->or_where('receiver', null);
        }
        $this->db->group_end();
        return $this->db->select('m.*, s.avatar as sender_avatar, r.avatar as receiver_avatar, CONCAT(s.first_name, " ", s.last_name) as sender_name, CONCAT(r.first_name, " ", r.last_name) as receiver_name, s.email as sender_email, r.email as receiver_email')
            ->from(TBL_MESSAGES . ' AS m')
            ->join(TBL_USERS . ' AS s', 's.id = m.sender', 'LEFT')
            ->join(TBL_USERS . ' AS r', 'r.id = m.receiver', 'LEFT')
            ->order_by('m.create_time', 'DESC')
            ->get()
            ->result();
    }

    // public function total($where = [])
    // {
    //     $query = $this->db->select('count(id) as total')
    //                 ->from(TBL_CLIENT_INQUIRY)
    //                 ->where_arr($where)
    //                 ->get();
    //     return (int) $query->row()->total;
    // }

    public function delete($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_MESSAGES);
    }
}
?>