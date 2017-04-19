<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Ovo extends MY_REST_Controller {
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
	public function ad_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->load->model ( 'vidol_dealer/ovo_ad_model' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			$this->load->driver ( 'cache', array (
					'adapter' => 'memcached',
					'backup' => 'dummy' 
			) );
			// 變數
			$data_input = array ();
			$data_cache = array ();
			$this->data_result = array (
					'status' => '',
					'timestamp' => time (),
					'data' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['debug'] = $this->get ( 'debug' );
			// cache name key
			$cache_name_ovo_ad = sprintf ( '%s_get_ovo_ad', ENVIRONMENT );
			// $this->cache->memcached->delete ( $cache_name_ovo_ad );
			$data_cache [$cache_name_ovo_ad] = $this->cache->memcached->get ( $cache_name_ovo_ad );
			if ($data_cache [$cache_name_ovo_ad] == false) {
				// 防止array組合型態錯誤警告
				$data_cache [$cache_name_ovo_ad] = array ();
				$query = $this->ovo_ad_model->get_OVO_ad_by_status ( 'oa_pk, oa_title, oa_img_url, oa_app_uri, oa_time_update' );
				if ($query->num_rows () > 0) {
					foreach ( $query->result () as $row ) {
						$tmpe_package = array (
								'id' => $row->oa_pk,
								'title' => $row->oa_title,
								'thumbnail' => $row->oa_img_url,
								'appUri' => $row->oa_app_uri,
								'lastUpdateTime' => $row->oa_time_update 
						);
						array_push ( $data_cache [$cache_name_ovo_ad], $tmpe_package );
						unset ( $tmpe_package );
					}
				}
				$this->cache->memcached->save ( $cache_name_ovo_ad, $data_cache [$cache_name_ovo_ad], 3000 );
			}
			$this->data_result ['data'] = $data_cache [$cache_name_ovo_ad];
			$this->data_result ['status'] = 'success';
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
				$this->data_result ['debug'] ['cache_name_ovo_ad'] = $cache_name_ovo_ad;
			}
			unset ( $data_cache [$cache_name_ovo_ad] );
			unset ( $cache_name_ovo_ad );
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
