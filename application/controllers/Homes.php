<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Homes extends CI_Controller {
	private $data_view;
	public function index() {
		try {
			// 套版
			$this->load->view ( 'Vidol/include/html5', $this->data_view );
		} catch ( Exception $e ) {
			show_error ( $e->getMessage () . ' --- ' . $e->getTraceAsString () );
		}
	}
}
