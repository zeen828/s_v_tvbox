<?php
if (! function_exists('token_postgre_check_back')) {

    function token_postgre_check_back ($token = null)
    {
        $CI = & get_instance();
        $data = false;
        $headers_token = null;
        //$token = (empty($token)) ? $CI->input->server('HTTP_TOKEN') : $token;
        $headers = apache_request_headers();
        if(!empty($headers['authorization'])){
            $headers_token = $headers['authorization'];
        }
        if(!empty($headers['Authorization'])){
            $headers_token = $headers['Authorization'];
        }
        $token = (empty($token)) ? $headers_token : $token;
        if (! empty($token)) {
            $token = str_replace('bearer ', '', $token);
            $token = str_replace('Bearer ', '', $token);
            $CI->p_db = $CI->load->database('postgre_read', TRUE);
            $CI->p_db->select('*, now() as now_date');
            $CI->p_db->where('token', $token);
            $query = $CI->p_db->get('oauth_access_tokens');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $time_created = strtotime($row->created_at . '+00');
                $time_now = strtotime($row->now_date);
                if(($time_now - $time_created) <= $row->expires_in){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}

if (! function_exists('token_postgre_check')) {

	function token_postgre_check ($token = null)
	{
		$CI = & get_instance();
		$data = false;
		$headers_token = null;
		//$token = (empty($token)) ? $CI->input->server('HTTP_TOKEN') : $token;
		$headers = apache_request_headers();
		if(!empty($headers['authorization'])){
			$headers_token = $headers['authorization'];
		}
		if(!empty($headers['Authorization'])){
			$headers_token = $headers['Authorization'];
		}
		$token = (empty($token)) ? $headers_token : $token;
		if (! empty($token)) {
			$token = str_replace('bearer ', '', $token);
			$token = str_replace('Bearer ', '', $token);
			$CI->load->model ( 'postgre/vidol_model' );
			$query = $CI->vidol_model->get_oauth_access_tokens_by_token($token);
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$time_created = strtotime($row->created_at . '+00');
				$time_now = strtotime($row->now_date);
				if(($time_now - $time_created) <= $row->expires_in){
					return TRUE;
				}
			}
		}
		return FALSE;
	}
}
