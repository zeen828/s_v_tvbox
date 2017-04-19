<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Doc extends CI_Controller {
	private $data_view;
	function __construct() {
		parent::__construct ();
	}
	public function index() {
		$this->config->load ( 'vidol' );
		$this->load->library ( 'session' );
		$this->load->library ( 'GoogleAuthenticator' );
		//
		$secret = $this->config->item ( 'google_authenticator_key' );
		//
		$code = $this->input->post ( 'code' );
		if (! empty ( $code )) {
			$checkResult = $this->googleauthenticator->verifyCode ( $secret, $code, 30 ); // 2 = 2*30sec clock tolerance
			$this->session->set_userdata ( 'checkResult', $checkResult );
		}
		$checkResult = $this->session->userdata ( 'checkResult' );
		if ($checkResult) {
			$this->load->helper ( 'url' );
			$view_data = array ();
			$view_data ['doc_json_url'] = sprintf ( "%sv1/swaggerDoc", base_url () );
			$this->load->view ( 'Swagger/doc_2_2_6', $view_data );
		} else {
			$this->data_view ['system'] ['lang'] = 'zh-Hant-TW';
			$this->data_view ['system'] ['form_action'] = '#';
			$this->data_view ['system'] ['swagger_css'] = 'Swagger_2_2_6';
			$this->data_view ['meta'] ['title'] = '身分認證';
			// 輸出view
			$this->load->view ( 'Swagger/login', $this->data_view );
		}
	}
	public function logout() {
		$this->load->library ( 'session' );
		$this->load->helper ( 'url' );
		$this->session->unset_userdata ( 'checkResult' );
		redirect ( 'v1/doc' );
	}
}

/* End of file Doc.php */
/* Location: ./application/controllers/v1/Doc.php */
