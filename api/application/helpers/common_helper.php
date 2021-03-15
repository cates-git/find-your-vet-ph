<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * returns 401 header if no session found
 */
function is_logged_in()
{
    $CI =& get_instance();
    if (empty($CI->session->userdata('logged_in')))
    {
        header("HTTP/1.1 401 Unauthorized");
        die();
    }
}

function get_user_data()
{
    $CI =& get_instance();
    return $CI->session->userdata('logged_in');
}

function get_user_type()
{
    $CI =& get_instance();
    return (int) $CI->session->userdata('logged_in')['type'];
}

function set_page($page)
{
    return $page ? ($page - 1) * TABLE_ROWS : 0;
}

/**
 * CHECK empty post datas
 * @param array - index to avoid checking if empty
 *
 * @return boolean
 */
function has_empty_post($exclude = [])
{
    foreach ($_POST as $key => $value)
    {
        if (in_array($key, $exclude)) continue;
        if (trim($value) == '')
        {
            return true;
        }
    }
    return false;
}

function parse_data($input, $fields)
{
    $data = [];
    foreach ($input as $key => $value) 
    {
        if (in_array($key, $fields))
        {
            $data[$key] = trim($value);
        }
    }
    return $data;
}

function set_key_obj(array $array, $key)
{
    if (!$array || !is_array($array)) {
        die("Array required on set_key function.");
    }
    $new_array = [];

    foreach ($array as $obj)
    {
        if (!is_object($obj))
            return [];
        if (!isset($obj->{$key}))
            return [];
        $new_array[$obj->{$key}] = $obj;
    }
    return $new_array;
}

function json_response($status, $message = '', $data = [])
{
    $return = [
        'status'  => $status,
        'message' => $message,
        'data'    => $data
    ];
    echo json_encode($return);
    die();
}

function upload_file($allowed_types = 'gif|jpg|jpeg|png')
{
    $CI =& get_instance();
    $config['upload_path'] = './uploads/';
    $config['allowed_types'] = $allowed_types;
    
    $CI->load->library('upload', $config);
    
    $field_name = "file";
    
    if ( ! $CI->upload->do_upload($field_name, $config))
    {
        json_response(false, $CI->upload->display_errors());
    }

    return $CI->upload->data()['file_name'];
}


function set_key(array $array, $key){
    if (!$array || !is_array($array)) {
        die("Array required on set_key function.");
    }
    foreach ($array as $arr) {
        if (!is_array($arr)) {
            die("All contents must be array in order to use set_key function.");
        }
        if (!isset($arr[$key])) {
            // die("Key not found in one or more array contents.");
            die();
        }
    }
    $new_array = [];
    foreach ($array as $arr_val) {
        $new_array[$arr_val[$key]] = $arr_val;
    }
    return $new_array;
}

function dd($data = 'test', $die = TRUE)
{
    echo '<pre>';
    var_dump($data);
    if ($die)
    {
        die();
    }
}
?>