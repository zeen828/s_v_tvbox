<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
ini_set ( "display_errors", "On" ); // On, Off
require_once APPPATH . '/libraries/MY_REST_Controller.php';
class Users extends MY_REST_Controller {
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
	public function binding_post() {
		try {
			// 開始時間標記
			$this->benchmark->mark ( 'code_start' );
			// 引入
			$this->config->load ( 'restful_status_code' );
			$this->load->helper ( 'token' );
			$this->load->model ( 'mongo_model' );
			$this->load->model ( 'vidol_dealer/dealers_model' );
			$this->load->model ( 'vidol_dealer/user_binding_model' );
			$this->load->model ( 'vidol_dealer/user_binding_log_model' );
			$this->load->model ( 'vidol_billing/call_function_model' );
			$this->load->model ( 'vidol_billing/coupon_model' );
			$this->lang->load ( 'restful_status_lang', 'traditional-chinese' );
			// 變數
			$data_input = array ();
			$this->data_result = array (
					'result' => array (),
					'code' => $this->config->item ( 'system_default' ),
					'message' => '',
					'time' => 0 
			);
			// 接收變數
			$data_input ['token'] = $this->post ( 'token' );
			$data_input ['user_no'] = $this->post ( 'user_no' );
			$data_input ['mongo_id'] = $this->post ( 'mongo_id' );
			$data_input ['member_id'] = $this->post ( 'member_id' );
			$data_input ['device_key'] = $this->post ( 'device_key' );
			$data_input ['ip'] = $this->post ( 'ip' );
			$data_input ['debug'] = $this->post ( 'debug' );
			// 必填檢查
			if (empty ( $data_input ['member_id'] ) || strlen ( $data_input ['member_id'] ) != 6 || empty ( $data_input ['device_key'] )) {
				// 必填錯誤
				$this->data_result ['message'] = $this->lang->line ( 'input_required_error' );
				$this->data_result ['code'] = $this->config->item ( 'input_required_error' );
				$this->response ( $this->data_result, 416 );
				return;
			}
			if (! token_postgre_check ( $data_input ['token'] )) {
				// 非授權
				$this->data_result ['message'] = $this->lang->line ( 'permissions_middle_layer_token_error' );
				$this->data_result ['code'] = $this->config->item ( 'permissions_middle_layer_token_error' );
				$this->response ( $this->data_result, 403 );
				return;
			}
			// tvbox沒有mongo_id補充取得
			if (empty ( $data_input ['mongo_id'] )) {
				$data_mongo_user = $this->mongo_model->get_user_by_memberid ( $data_input ['member_id'] );
				$this->data_result ['mongo_user'] = $data_mongo_user;
				$data_input ['mongo_id'] = $data_mongo_user ['_id'];
				if (empty ( $data_input ['mongo_id'] )) {
					// 非授權
					$this->data_result ['message'] = $this->lang->line ( 'permissions_not_mongo_user' );
					$this->data_result ['code'] = $this->config->item ( 'permissions_not_mongo_user' );
					$this->response ( $this->data_result, 400 );
					return;
				}
			}
			$binding = $this->user_binding_model->get_row_User_binding_by_devicekey_status ( '*', $data_input ['device_key'] );
			if ($binding == false) {
				// 設備MAC未匯入OR設備MAC已綁定
				$this->data_result ['message'] = $this->lang->line ( 'dealer_not_mac_binding' );
				$this->data_result ['code'] = $this->config->item ( 'dealer_not_mac_binding' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			// get經銷商資料
			$dealer = $this->dealers_model->get_row_Dealers_by_title_status ( '*', $binding->ub_dealer );
			if ($dealer == false || empty ( $dealer->d_coupon )) {
				// 經銷商未設定贈送序號
				$this->data_result ['message'] = $this->lang->line ( 'dealer_not_set_coupon' );
				$this->data_result ['code'] = $this->config->item ( 'dealer_not_set_coupon' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			// 綁定商品
			$coupon_title = $this->coupon_model->get_row_Coupon_by_sn ( 'c_set_title as title', $dealer->d_coupon );
			$this->data_result ['result'] = array();
			// 序號兌換
			$coupon = $this->call_function_model->exchange_SN ( 0, 0, $data_input ['mongo_id'], $data_input ['member_id'], $dealer->d_coupon, $data_input ['ip'] );
			$update_array = array (
					'ub_user_no' => (empty ( $data_input ['user_no'] )) ? '' : $data_input ['user_no'],
					'ub_mongo_id' => (empty ( $data_input ['mongo_id'] )) ? '' : $data_input ['mongo_id'],
					'ub_member_id' => $data_input ['member_id'],
					'ub_order_sn' => $coupon ['order_sn'],
					'ub_status_code' => sprintf ( 'coupon_%s', $coupon ['status_code'] ),
					'ub_status' => '0' 
			);
			if ($coupon ['status_code'] != '200') {
				// 序號兌換錯誤
				$this->user_binding_model->update_User_binding_for_data ( $binding->ub_pk, $update_array );
				$this->data_result ['message'] = $this->lang->line ( 'billing_exchange_SN_error' );
				$this->data_result ['code'] = $this->config->item ( 'billing_exchange_SN_error' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			// 交易完成
			$cash = $this->call_function_model->add_to_cash ( $coupon ['order_sn'], '', 0, 0, null, null );
			$update_array ['ub_status_code'] = sprintf ( 'cash_%s', $cash ['status_code'] );
			if ($cash ['status_code'] != '200') {
				// 交易完成失敗
				$this->user_binding_model->update_User_binding_for_data ( $binding->ub_pk, $update_array );
				$this->data_result ['message'] = $this->lang->line ( 'billing_add_to_cash_error' );
				$this->data_result ['code'] = $this->config->item ( 'billing_add_to_cash_error' );
				$this->response ( $this->data_result, 400 );
				return;
			}
			$update_array ['ub_status'] = '1';
			$this->user_binding_model->update_User_binding_for_data ( $binding->ub_pk, $update_array );
			//$this->data_result ['message'] = sprintf('%s : %s', $this->lang->line ( 'system_success' ), $coupon_title->title);
			$this->data_result ['message'] = $coupon_title->title;
			$this->data_result ['code'] = $this->config->item ( 'system_success' );
			//建立綁定紀錄資料
			$this->user_binding_log_model->insert_copy_User_binding($data_input ['device_key'], $this->data_result ['code']);
			// DEBUG印出
			if ($data_input ['debug'] == 'debug') {
				$this->data_result ['debug'] ['data_input'] = $data_input;
				$this->data_result ['debug'] ['binding'] = $binding;
				$this->data_result ['debug'] ['dealer'] = $dealer;
				$this->data_result ['debug'] ['coupon_title'] = $coupon_title;
				$this->data_result ['debug'] ['coupon'] = $coupon;
				$this->data_result ['debug'] ['cash'] = $cash;
				$this->data_result ['debug'] ['update_array'] = $update_array;
			}
			unset ( $update_array );
			unset ( $cash );
			unset ( $coupon );
			unset ( $coupon_title );
			unset ( $dealer );
			unset ( $binding );
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
