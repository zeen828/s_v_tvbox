<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	require APPPATH . '/libraries/REST_Controller.php';
	class MY_REST_Controller extends REST_Controller {
		public $_my_logs_start = false;
		public $_my_logs_type = 'system';
		protected $_my_logs_file_path = '';
		protected $_my_logs_file_name = '';
		protected $_my_logs_file_type = '.csv';
		protected $_my_logs_array = array (
				'A1' => '',
				'B1' => '',
				'C1' => '',
				'D1' => '',
				'E1' => '',
				'F1' => '',
				'G1' => '',
				'H1' => ''
		);
		public function __construct() {
			parent::__construct ();
			$this->_my_logs_file_path = APPPATH . 'logs/';
			$this->_my_logs_file_name = date ( 'Y-m-d' );
		}
		protected function _log_request($authorized = FALSE) {
			// 資料
			$this->_my_logs_array ['A1'] = $this->uri->uri_string ();
			$this->_my_logs_array ['B1'] = $this->request->method;
			$this->_my_logs_array ['C1'] = $this->_args ? ($this->config->item ( 'rest_logs_json_params' ) === TRUE ? json_encode ( $this->_args ) : serialize ( $this->_args )) : NULL;
			$this->_my_logs_array ['D1'] = isset ( $this->rest->key ) ? $this->rest->key : '';
			$this->_my_logs_array ['E1'] = $this->input->ip_address ();
			$this->_my_logs_array ['F1'] = time ();
			$this->_my_logs_array ['G1'] = $authorized;
			return true;
		}
		protected function _log_response_code($http_code) {
			// 資料
			$this->_my_logs_array ['H1'] = $http_code;
			$this->files_logs_array ();
			return true;
		}
		protected function files_logs_array() {
			// http_build_query($a,'',', ');
			// implode(" ",$arr);
			if ($this->_my_logs_start === true) {
				$str = implode ( ',', $this->_my_logs_array );
				$file_path = sprintf ( '%s%s_%s_%s%s', $this->_my_logs_file_path, ENVIRONMENT, $this->_my_logs_type, $this->_my_logs_file_name, $this->_my_logs_file_type );
				$fd = fopen ( $file_path, 'a' );
				fwrite ( $fd, $str . "\n" );
				fclose ( $fd );
			}
			return true;
		}
		public function files_logs_string($logs_type = 'system', $string = null) {
			if (! empty ( $string )) {
				// http_build_query($a,'',', ');
				// implode(" ",$arr);
				$copy_my_logs_array = $this->_my_logs_array;
				$copy_my_logs_array ['C1'] = $string;
				$str = implode ( ',', $copy_my_logs_array );
				$file_path = sprintf ( '%s%s_%s_%s%s', $this->_my_logs_file_path, ENVIRONMENT, $this->_my_logs_type, $this->_my_logs_file_name, $this->_my_logs_file_type );
				$fd = fopen ( $file_path, 'a' );
				fwrite ( $fd, $str . "\n" );
				fclose ( $fd );
			}
			return true;
		}
	}

	/* End of file MY_REST_Controller.php */
	/* Location: ./application/library/MY_REST_Controller.php */