<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_model extends CI_Model {

    public function __construct()
    {
		parent::__construct();
    }

    public function create($data)
    {
        $data['user_id'] = $this->user['id'];
        $this->db->insert(TBL_POSTS, $data);
        return $this->db->insert_id();
    }
    
    // public function update($data, $id)
    // {
    //     $this->db->where('id', $id);
    //     return $this->db->update(TBL_POSTS, $data);
    // }

    public function get($where, $fields = '*')
    {
        return $this->db->select($fields)
                    ->from(TBL_POSTS)
                    ->where_arr($where)
                    ->get()
                    ->row();
    }

    public function all($where = [])
    {
        return $this->db->select('p.*, CONCAT(u.first_name, " ", u.last_name) AS user_name, u.avatar, u.email')
                    ->from(TBL_POSTS . ' AS p')
                    ->join(TBL_USERS . ' AS u', 'p.user_id = u.id')
                    ->where_arr($where)
                    ->order_by('id', 'DESC')
                    ->get()
                    ->result();
    }

    // public function total_comments($post_id)
    // {
    //     $query = $this->db->select('count(id) as total')
    //                 ->from(TBL_POST_COMMENTS)
    //                 ->where('post_id', $post_id)
    //                 ->get();
    //     return (int) $query->row()->total;
    // }

    public function create_comment($data)
    {
        $data['user_id'] = $this->user['id'];
        $this->db->insert(TBL_POST_COMMENTS, $data);
        return $this->db->insert_id();
    }

    public function all_comments($post_id)
    {
        return $this->db->select('c.*, CONCAT(u.first_name, " ", u.last_name) AS user_name')
                    ->from(TBL_POST_COMMENTS . ' AS c')
                    ->join(TBL_USERS . ' AS u', 'c.user_id = u.id', 'LEFT')
                    ->where('post_id', $post_id)
                    ->get()
                    ->result();
    }

    public function delete($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_POSTS);
    }

    public function delete_comments($where)
    {
        if ( ! $where) return;
        $this->db->where_arr($where)->delete(TBL_POST_COMMENTS);
    }

    // public function list($page, $where = [])
    // {
    //     return $this->db->select('p.*, CONCAT(u.first_name, " ", u.last_name) AS user_name, u.avatar')
    //                 ->from(TBL_POSTS . ' AS p')
    //                 ->join(TBL_USERS . ' AS u', 'p.user_id = u.id')
    //                 ->where_arr($where)
    //                 ->limit(TABLE_ROWS, $page)
    //                 ->order_by('id', 'DESC')
    //                 ->get()
    //                 ->result();
    // }

    // public function total($where = [])
    // {
    //     $query = $this->db->select('count(id) as total')
    //                 ->from(TBL_POSTS)
    //                 ->where_arr($where)
    //                 ->get();
    //     return (int) $query->row()->total;
    // }
}
?>