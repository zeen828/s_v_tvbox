<?php
if (! function_exists('format_helper_vidol_data')) {

    function format_helper_vidol_data ()
    {
        // 變數
        $result = array(
                'html' => array(
                        'title' => 'Vidol 影音 線上看'
                ),
                'user' => array(
                        'user_pk' => '',
                        'user_id' => '',
                        'user_nick_name' => '',
                        'user_propic' => ''
                ),
                'header' => array(
                        'title' => 'header',
                        'data' => array(),
                        'view_path' => null,
                        'view_data' => null
                ),
                'content' => array(
                        'title' => 'content',
                        'data' => array(),
                        'view_path' => null,
                        'view_data' => null
                ),
                'footer' => array(
                        'title' => 'footer',
                        'data' => array(),
                        'view_path' => null,
                        'view_data' => null
                ),
                'system' => array(
                        'lang' => 'zh-Hant-TW',
                        'themes' => 'vidol',
                        'time' => 0
                )
        );
        return $result;
    }
}

if (! function_exists('format_helper_board')) {

    function format_helper_board ($data)
    {
        // $CI = & get_instance ();
        // $CI->load->model ( 'user_model' );
        $result = array(
                'no' => $data->b_no,
                'video_type' => $data->b_type,
                'video_id' => $data->b_type_no,
        		'member_id' => $data->b_member_id,
                //'nick_name' => ($data->b_nick_name != 'Guest') ? $data->b_nick_name : $data->b_member_id,
        		'nick_name' => $data->b_nick_name,
                'propic' => $data->b_propic,
                'messages' => $data->b_message,
                'video_time' => $data->b_video_time,
                'color' => $data->b_color,
                'size' => $data->b_size,
                'time_tw' => date('Y-m-d H:i:s', $data->b_creat_unix),
                'time_utc' => $data->b_creat_utc,
                'time_unix' => $data->b_creat_unix,
                'reply' => array(
                        'info' => array(
                                'page' => 0,
                                'page_size' => 0,
                                'page_max' => 0,
                                'count' => 0
                        ),
                        'data' => array()
                )
        );
        return $result;
    }
}

if (! function_exists('format_helper_board_v2')) {

    function format_helper_board_v2 ($data)
    {
        // $CI = & get_instance ();
        // $CI->load->model ( 'user_model' );
        $result = array(
                'no' => $data->b_no,
                'video_type' => $data->b_type,
                'video_id' => $data->b_type_no,
        		'member_id' => $data->b_member_id,
                //'nick_name' => ($data->b_nick_name != 'Guest') ? $data->b_nick_name : $data->b_member_id,
        		'nick_name' => $data->b_nick_name,
                'profile_image_url' => $data->b_propic,
                'msg' => $data->b_message,
                'video_time' => $data->b_video_time,
                'color' => $data->b_color,
                'size' => $data->b_size,
                'time_tw' => date('Y-m-d H:i:s', $data->b_creat_unix),
                'time_utc' => $data->b_creat_utc,
                'create_time' => $data->b_creat_unix,
                'reply' => array(
                        'info' => array(
                                'page' => 0,
                                'page_size' => 0,
                                'page_max' => 0,
                                'count' => 0
                        ),
                        'data' => array()
                )
        );
        return $result;
    }
}

if (! function_exists('format_helper_board_reply')) {

    function format_helper_board_reply ($data)
    {
        // $CI = & get_instance ();
        // $CI->load->model ( 'user_model' );
        $result = array(
                'no' => $data->b_no,
                'video_type' => $data->b_type,
                'video_id' => $data->b_type_no,
                'nick_name' => ($data->b_nick_name != 'Guest') ? $data->b_nick_name : $data->b_member_id,
                'propic' => $data->b_propic,
                'messages' => $data->b_message,
                'video_time' => $data->b_video_time,
                'color' => $data->b_color,
                'size' => $data->b_size,
                'time_tw' => date('Y-m-d H:i:s', $data->b_creat_unix),
                'time_utc' => $data->b_creat_utc,
                'time_unix' => $data->b_creat_unix
        );
        return $result;
    }
}

if (! function_exists('format_helper_return_data')) {

    function format_helper_return_data ()
    {
        // 變數
        $result = array(
        		'api' => '',
                'status' => false,
                'info' => array(),
        		'input' => array(),
                'data' => array(),
                'time' => 0
        );
        return $result;
    }
}

if (! function_exists('format_helper_return_data_v2')) {

    function format_helper_return_data_v2 ()
    {
        // 變數
        $result = array(
        		'api' => '',
                'status' => false,
                'info' => array(),
        		'input' => array(),
                'messages' => array(),
                'time' => 0
        );
        return $result;
    }
}