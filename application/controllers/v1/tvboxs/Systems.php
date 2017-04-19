<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Systems extends MY_REST_Controller {
	private $data_debug;
	private $data_result;
	public function __construct() {
		parent::__construct ();
		$this->_my_logs_start = true;
		$this->_my_logs_type = 'tvbox';
		$this->data_debug = true;
		// 資料庫
		// $this->load->database ( 'vidol_billing_write' );
		// 效能檢查
		// $this->output->enable_profiler(TRUE);
	}
	public function __destruct() {
		parent::__destruct ();
		unset ( $this->data_debug );
		unset ( $this->data_result );
	}
	public function version_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->load->model ( 'vidol_dealer/tvbox_model' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 變數
			$data_input = array ();
			$data_cache = array ();
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['pk'] = '1';
			$data_input ['debug'] = $this->get ( 'debug' );
			// cache name key
			$cache_name_version = sprintf ( '%s_get_version_by_pk_%s', ENVIRONMENT, $data_input ['pk'] );
			//$this->cache->memcached->delete ( $cache_name_version );
			$data_cache [$cache_name_version] = $this->cache->memcached->get ( $cache_name_version );
			if ($data_cache [$cache_name_version] == false) {
				// 防止array組合型態錯誤警告
				$data_cache [$cache_name_version] = array ();
				$dealer = $this->tvbox_model->get_row_Tvbox_by_pk ( 't_des, t_version, t_update, t_update_url', $data_input ['pk'] );
				if ($dealer != false) {
					$tmpe_package = array (
							'description' => $dealer->t_des,
							'version' => $dealer->t_version,
							'update' => $dealer->t_update,
							'update_url' => $dealer->t_update_url 
					);
					array_push ( $data_cache [$cache_name_version], $tmpe_package );
					unset ( $tmpe_package );
				}
				$this->cache->memcached->save ( $cache_name_version, $data_cache [$cache_name_version], 3000 );
				unset ( $dealer );
			}
			$this->data_result ['result'] = $data_cache [$cache_name_version];
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
				$this->data_result ['debug'] ['cache_name_version'] = $cache_name_version;
			}
			unset ( $data_cache [$cache_name_version] );
			unset ( $cache_name_version );
			unset ( $data_cache );
			unset ( $data_input );
			// 結束時間標記
			$this->benchmark->mark ( 'code_end' );
			// 標記時間計算
			$this->data_result ['time'] = $this->benchmark->elapsed_time ( 'code_start', 'code_end' );
			$this->response ( $this->data_result, 200 );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
