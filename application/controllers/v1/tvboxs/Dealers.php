<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Dealers extends MY_REST_Controller {
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
	public function dealer_get() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->load->model ( 'vidol_dealer/dealers_model' );
			$this->load->model ( 'vidol_dealer/user_binding_model' );
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
			$data_input ['device_key'] = $this->get ( 'device_key' );
			$data_input ['debug'] = $this->get ( 'debug' );
			// 必填檢查
			if (empty ( $data_input ['device_key'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			// 查詢是否有綁定資料
			$binding = $this->user_binding_model->get_row_User_binding_by_devicekey_status ( '*', $data_input ['device_key'] );
			if ($binding == false) {
				// 設備MAC未匯入OR設備MAC已綁定
				$this->data_result ['message'] = $this->lang->line ( 'dealer_not_mac_binding' );
				$this->data_result ['code'] = $this->config->item ( 'dealer_not_mac_binding' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			// cache name key
			$cache_name_dealer = sprintf ( '%s_get_dealer_by_title_%s', ENVIRONMENT, $binding->ub_dealer );
			// $this->cache->memcached->delete ( $cache_name_dealer );
			$data_cache [$cache_name_dealer] = $this->cache->memcached->get ( $cache_name_dealer );
			if ($data_cache [$cache_name_dealer] == false) {
				// 防止array組合型態錯誤警告
				$data_cache [$cache_name_dealer] = array ();
				$dealer = $this->dealers_model->get_row_Dealers_by_title_status ( '*', $binding->ub_dealer );
				if ($dealer != false) {
					$tmpe_package = array (
							'no' => $dealer->d_pk,
							'title' => $dealer->d_title,
							'description' => $dealer->d_des,
							'logo_url' => $dealer->d_logo_url,
							'version' => $dealer->d_version,
							'update' => $dealer->d_update,
							'update_url' => $dealer->d_update_url,
							'status' => $dealer->d_status 
					);
					array_push ( $data_cache [$cache_name_dealer], $tmpe_package );
					unset ( $tmpe_package );
				}
				$this->cache->memcached->save ( $cache_name_dealer, $data_cache [$cache_name_dealer], 3000 );
				unset ( $dealer );
			}
			$this->data_result ['result'] = $data_cache [$cache_name_dealer];
			$this->data_result ['message'] = $this->lang->line ( 'system_success' );
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['data_cache'] = $data_cache;
				$this->data_result ['debug'] ['binding'] = $binding;
				$this->data_result ['debug'] ['cache_name_dealer'] = $cache_name_dealer;
			}
			unset ( $data_cache [$cache_name_dealer] );
			unset ( $cache_name_dealer );
			unset ( $binding );
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
